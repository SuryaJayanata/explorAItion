<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Exception;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser as PdfParser;
use Illuminate\Support\Facades\Storage;

class GeminiService
{
    protected $apiKey;
    protected $client;
    protected $pdfParser;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        $this->client = new Client([
            'base_uri' => 'https://generativelanguage.googleapis.com/',
            'timeout' => 60.0,
        ]);
        $this->pdfParser = new PdfParser();
    }

    public function summarizeFile($filePath, $includeFlashcards = false, $flashcardCount = 5, $maxLength = 1500)
    {
        try {
            Log::info('Starting file summarization', [
                'file_path' => $filePath,
                'include_flashcards' => $includeFlashcards,
                'flashcard_count' => $flashcardCount
            ]);

            $fullPath = storage_path('app/public/' . $filePath);
            
            if (!file_exists($fullPath)) {
                throw new Exception('File tidak ditemukan: ' . $fullPath);
            }

            // EKSTRAK TEKS REAL DARI FILE
            $text = $this->extractTextFromFile($fullPath);
            
            if (empty(trim($text))) {
                throw new Exception('Tidak dapat mengekstrak teks dari file atau file kosong');
            }

            Log::info('Real text extracted from file', [
                'text_length' => strlen($text),
                'first_500_chars' => substr($text, 0, 500)
            ]);

            $result = $this->summarizeText($text, $maxLength);
            
            // Generate flashcards jika diminta
            if ($includeFlashcards && $result['success']) {
                $flashcards = $this->generateFlashcards($result['summary'], $flashcardCount);
                $result['flashcards'] = $flashcards;
            }

            return $result;
            
        } catch (Exception $e) {
            Log::error('File summarization failed', [
                'file_path' => $filePath,
                'error' => $e->getMessage()
            ]);
            return [
                'success' => false,
                'error' => 'Gagal memproses file: ' . $e->getMessage()
            ];
        }
    }

    private function extractTextFromFile($filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        Log::info('Extracting text from file', [
            'file_path' => $filePath,
            'extension' => $extension
        ]);

        try {
            switch ($extension) {
                case 'pdf':
                    return $this->extractTextFromPDF($filePath);
                
                case 'txt':
                    $content = file_get_contents($filePath);
                    Log::info('TXT file content extracted', [
                        'length' => strlen($content),
                        'preview' => substr($content, 0, 300)
                    ]);
                    return $content;
                
                default:
                    Log::warning('Unsupported file format', ['extension' => $extension]);
                    return "File format .{$extension} tidak didukung untuk ekstraksi teks otomatis.";
            }
        } catch (Exception $e) {
            Log::error('Text extraction failed', [
                'file' => $filePath,
                'error' => $e->getMessage()
            ]);
            return "Gagal mengekstrak teks dari file: " . $e->getMessage();
        }
    }

    private function extractTextFromPDF($filePath)
    {
        try {
            Log::info('Parsing PDF file', ['file' => $filePath]);
            
            $pdf = $this->pdfParser->parseFile($filePath);
            $text = $pdf->getText();
            
            // Clean the extracted text
            $text = preg_replace('/\s+/', ' ', $text);
            $text = trim($text);
            
            Log::info('PDF text extraction successful', [
                'original_length' => strlen($text),
                'first_500_chars' => substr($text, 0, 500)
            ]);

            if (empty($text)) {
                throw new Exception('Teks kosong setelah ekstraksi - kemungkinan PDF terproteksi atau scan');
            }
            
            return $text;

        } catch (Exception $e) {
            Log::error('PDF parsing failed', [
                'file' => $filePath,
                'error' => $e->getMessage()
            ]);
            
            return "PDF tidak dapat diproses: " . $e->getMessage() . ". File mungkin terproteksi atau berupa scan gambar.";
        }
    }

    public function summarizeText($text, $maxLength = 1500)
    {
        try {
            Log::info('Starting text summarization', [
                'text_length' => strlen($text),
                'first_200_chars' => substr($text, 0, 200)
            ]);

            if (empty(trim($text))) {
                throw new Exception('Teks kosong tidak dapat diringkas');
            }

            // Clean text - potong jika terlalu panjang untuk efisiensi
            $text = $this->cleanText($text);
            $text = $this->truncateText($text, 30000);
            
            // Build prompt yang LEBIH SPESIFIK dan DETAILED
            $prompt = $this->buildSummaryPrompt($text);

            Log::info('Sending request to Gemini API');

            $response = $this->client->post("v1/models/gemini-pro:generateContent?key={$this->apiKey}", [
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.1,
                        'topK' => 20,
                        'topP' => 0.7,
                        'maxOutputTokens' => $maxLength,
                    ]
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            
            Log::info('Gemini API response received', ['has_candidates' => isset($data['candidates'])]);

            if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                Log::error('Invalid Gemini response format', $data);
                return $this->generateContentBasedSummary($text);
            }

            $summary = trim($data['candidates'][0]['content']['parts'][0]['text']);
            
            // Validasi apakah summary mengandung konten spesifik atau masih generic
            if ($this->isGenericSummary($summary)) {
                Log::warning('Summary masih generic, menggunakan content-based approach');
                return $this->generateContentBasedSummary($text);
            }
            
            // Bersihkan format markdown
            $summary = $this->cleanMarkdownFormat($summary);
            
            Log::info('Summary generated successfully', [
                'summary_length' => strlen($summary),
                'first_200_chars' => substr($summary, 0, 200)
            ]);

            return [
                'success' => true,
                'summary' => $summary,
                'length' => strlen($summary)
            ];
            
        } catch (RequestException $e) {
            Log::error('Gemini API Request failed', ['error' => $e->getMessage()]);
            return $this->generateContentBasedSummary($text);
            
        } catch (Exception $e) {
            Log::error('Summarization failed', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    private function buildSummaryPrompt($text)
    {
        return "ANALISIS TEKS MATERI PEMBELAJARAN BERIKUT DAN BUAT RINGKASAN YANG SPESIFIK:

**TEKS ASLI:**
{$text}

**INSTRUKSI DETAIL:**
1. BACA dan ANALISIS seluruh teks di atas dengan cermat
2. IDENTIFIKASI topik-topik SPESIFIK yang benar-benar dibahas dalam teks
3. EKSTRAK konsep-konsep NYATA yang dijelaskan, bukan generalisasi
4. GUNAKEN BAHASA INDONESIA yang jelas dan terstruktur
5. FOKUS pada konten yang ADA dalam teks, JANGAN menambahkan informasi dari luar
6. Untuk setiap bagian, berikan INFORMASI SPESIFIK dari teks

**FORMAT OUTPUT WAJIB:**

RINGKASAN MATERI
[Jelaskan secara spesifik materi apa yang dibahas, sebutkan judul/topik utama]

TOPIK-TOPIK YANG DIBAHAS
1. [Nama topik pertama - ambil dari teks asli]
   • [Penjelasan spesifik tentang topik ini dari teks]
   • [Konsep penting yang dijelaskan]

2. [Nama topik kedua - ambil dari teks asli] 
   • [Penjelasan spesifik tentang topik ini dari teks]
   • [Konsep penting yang dijelaskan]

3. [Lanjutkan untuk topik-topik lainnya...]

KONSEP PENTING
• [Konsep pertama yang spesifik dari teks]
• [Konsep kedua yang spesifik dari teks] 
• [Konsep ketiga yang spesifik dari teks]

HASIL PEMBELAJARAN
[Sebutkan secara spesifik kompetensi atau tujuan pembelajaran yang disebutkan dalam teks]

CATATAN: JANGAN gunakan kalimat general seperti 'materi ini membahas konsep-konsep penting' atau 'beberapa poin kunci yang diangkat'. HARUS spesifik berdasarkan isi teks!";
    }

    private function isGenericSummary($summary)
    {
        $genericPatterns = [
            '/materi ini membahas konsep-konsep penting/i',
            '/beberapa poin kunci yang diangkat/i',
            '/aspek-aspek fundamental/i',
            '/pemahaman yang komprehensif/i',
            '/topik yang disajikan/i',
            '/Berdasarkan materi, pembahasan mencakup:/i'
        ];
        
        foreach ($genericPatterns as $pattern) {
            if (preg_match($pattern, $summary)) {
                return true;
            }
        }
        
        return false;
    }

    private function generateContentBasedSummary($text)
    {
        Log::info('Generating CONTENT-BASED summary from real text');
        
        $contentAnalysis = $this->analyzeContent($text);
        
        $summary = "RINGKASAN MATERI\n";
        $summary .= $contentAnalysis['ringkasan_materi'] . "\n\n";
        
        $summary .= "TOPIK-TOPIK YANG DIBAHAS\n";
        foreach ($contentAnalysis['topik'] as $index => $topik) {
            $summary .= ($index + 1) . ". " . $topik['nama'] . "\n";
            foreach ($topik['poin'] as $poin) {
                $summary .= "   • " . $poin . "\n";
            }
            $summary .= "\n";
        }
        
        $summary .= "KONSEP PENTING\n";
        foreach ($contentAnalysis['konsep_penting'] as $konsep) {
            $summary .= "• " . $konsep . "\n";
        }
        
        $summary .= "\nHASIL PEMBELAJARAN\n";
        $summary .= $contentAnalysis['hasil_pembelajaran'];

        Log::info('Content-based summary generated', [
            'length' => strlen($summary),
            'topics_count' => count($contentAnalysis['topik']),
            'concepts_count' => count($contentAnalysis['konsep_penting'])
        ]);

        return [
            'success' => true,
            'summary' => $summary,
            'length' => strlen($summary),
            'content_based' => true
        ];
    }

    private function analyzeContent($text)
    {
        $analysis = [
            'ringkasan_materi' => '',
            'topik' => [],
            'konsep_penting' => [],
            'hasil_pembelajaran' => ''
        ];
        
        $lines = explode("\n", $text);
        $firstLines = array_slice($lines, 0, 10);
        
        foreach ($firstLines as $line) {
            $cleanLine = trim($line);
            if (strlen($cleanLine) > 20 && strlen($cleanLine) < 200) {
                if (preg_match('/(modul|materi|bab|kelas|xi|xi\s*|ipa|ipas)/i', $cleanLine)) {
                    $analysis['ringkasan_materi'] = $cleanLine;
                    break;
                }
            }
        }
        
        if (empty($analysis['ringkasan_materi'])) {
            $analysis['ringkasan_materi'] = "Materi pembelajaran berdasarkan konten teks yang tersedia";
        }
        
        $topics = $this->extractTopics($text);
        $analysis['topik'] = array_slice($topics, 0, 5);
        
        $analysis['konsep_penting'] = $this->extractKeyConcepts($text);
        
        $analysis['hasil_pembelajaran'] = $this->extractLearningOutcomes($text);
        
        return $analysis;
    }

    private function extractTopics($text)
    {
        $topics = [];
        
        $patterns = [
            '/(?:topik|materi|bab|pokok\s*bahasan)\s*[:\.]\s*([^\n\.]+)/i',
            '/(?:pengertian|definisi)\s+(?:[^\.]+)\.\s*([^\.]+)/i',
            '/(?:klasifikasi|jenis|macam)\s+([^\.]+)/i',
            '/(?:perubahan|proses)\s+([^\.]+)/i',
            '/(?:metode|teknik|cara)\s+([^\.]+)/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $text, $matches)) {
                foreach ($matches[1] as $match) {
                    $cleanTopic = trim($match);
                    if (strlen($cleanTopic) > 10 && strlen($cleanTopic) < 100) {
                        $topics[] = [
                            'nama' => $cleanTopic,
                            'poin' => $this->extractTopicPoints($cleanTopic, $text)
                        ];
                    }
                }
            }
        }
        
        if (empty($topics)) {
            $sentences = preg_split('/(?<=[.?!])\s+/', $text);
            foreach ($sentences as $sentence) {
                if (strlen($sentence) > 50 && strlen($sentence) < 200) {
                    $topics[] = [
                        'nama' => substr($sentence, 0, 50) . '...',
                        'poin' => [substr($sentence, 0, 100)]
                    ];
                }
            }
        }
        
        return array_slice($topics, 0, 5);
    }

    private function extractTopicPoints($topic, $text)
    {
        $points = [];
        
        $sentences = preg_split('/(?<=[.?!])\s+/', $text);
        $topicLower = strtolower($topic);
        
        foreach ($sentences as $sentence) {
            if (stripos($sentence, $topicLower) !== false && strlen($sentence) > 20) {
                $cleanSentence = trim($sentence);
                if (strlen($cleanSentence) < 150) {
                    $points[] = $cleanSentence;
                }
            }
            
            if (count($points) >= 3) break;
        }
        
        return array_slice($points, 0, 3);
    }

    private function extractKeyConcepts($text)
    {
        $concepts = [];
        
        $importantTerms = [
            'klasifikasi', 'perubahan', 'metode', 'pemisahan', 'campuran',
            'unsur', 'senyawa', 'fisika', 'kimia', 'sifat', 'contoh',
            'proses', 'teknik', 'percobaan', 'langkah', 'hasil'
        ];
        
        $sentences = preg_split('/(?<=[.?!])\s+/', $text);
        
        foreach ($sentences as $sentence) {
            foreach ($importantTerms as $term) {
                if (stripos($sentence, $term) !== false && strlen($sentence) > 30) {
                    $cleanConcept = trim($sentence);
                    if (strlen($cleanConcept) < 120) {
                        $concepts[] = $cleanConcept;
                        break;
                    }
                }
            }
            
            if (count($concepts) >= 8) break;
        }
        
        return array_slice(array_unique($concepts), 0, 6);
    }

    private function extractLearningOutcomes($text)
    {
        $patterns = [
            '/(?:kompetensi|tujuan|hasil\s*belajar)[^\.]+\.\s*([^\.]+)/i',
            '/(?:mampu|dapat|bisa)\s+([^\.]+)/i',
            '/(?:memahami|menjelaskan|mengidentifikasi)\s+([^\.]+)/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return trim($matches[1]);
            }
        }
        
        $sentences = preg_split('/(?<=[.?!])\s+/', $text);
        foreach ($sentences as $sentence) {
            if (strlen($sentence) > 40 && strlen($sentence) < 150) {
                return $sentence;
            }
        }
        
        return "Memahami konsep-konsep penting yang dibahas dalam materi";
    }

    private function generateFlashcards($summary, $count = 5)
    {
        try {
            Log::info('Generating intelligent flashcards from summary', [
                'summary_length' => strlen($summary),
                'requested_count' => $count
            ]);

            $flashcards = [];

            $content = $this->extractContentFromSummary($summary);
            
            Log::info('Content extracted from summary', [
                'topik_count' => count($content['topik'] ?? []),
                'konsep_penting_count' => count($content['konsep_penting'] ?? [])
            ]);

            $flashcards = $this->createContentBasedFlashcards($content, $count);

            $flashcards = $this->cleanFlashcardData($flashcards);

            Log::info('Content-based flashcards generated', ['count' => count($flashcards)]);
            return $flashcards;

        } catch (Exception $e) {
            Log::error('Intelligent flashcard generation failed', ['error' => $e->getMessage()]);
            $simpleFlashcards = $this->createSimpleFlashcards($summary, $count);
            return $this->cleanFlashcardData($simpleFlashcards);
        }
    }

    private function extractContentFromSummary($summary)
    {
        $content = [
            'topik' => [],
            'konsep_penting' => [],
            'hasil_pembelajaran' => ''
        ];
    
        $lines = explode("\n", $summary);
        $currentSection = '';
    
        foreach ($lines as $line) {
            $line = trim($line);
            
            if (empty($line)) continue;
    
            if (str_contains(strtolower($line), 'ringkasan materi')) {
                $currentSection = 'ringkasan';
                continue;
            } elseif (str_contains(strtolower($line), 'topik-topik yang dibahas')) {
                $currentSection = 'topik';
                continue;
            } elseif (str_contains(strtolower($line), 'konsep penting')) {
                $currentSection = 'konsep_penting';
                continue;
            } elseif (str_contains(strtolower($line), 'hasil pembelajaran')) {
                $currentSection = 'hasil_pembelajaran';
                continue;
            }
    
            if ($currentSection === 'topik' && preg_match('/^\d+\.\s*(.+)/', $line, $matches)) {
                $content['topik'][] = ['nama' => trim($matches[1]), 'poin' => []];
            } elseif ($currentSection === 'topik' && str_starts_with($line, '•')) {
                $lastTopic = end($content['topik']);
                if ($lastTopic) {
                    $content['topik'][key($content['topik'])]['poin'][] = trim(substr($line, 1));
                }
            } elseif ($currentSection === 'konsep_penting' && str_starts_with($line, '•')) {
                $content['konsep_penting'][] = trim(substr($line, 1));
            } elseif ($currentSection === 'hasil_pembelajaran' && !empty($line)) {
                $content['hasil_pembelajaran'] .= $line . ' ';
            }
        }
    
        $content['hasil_pembelajaran'] = trim($content['hasil_pembelajaran']);
        return $content;
    }
    
    private function createContentBasedFlashcards($content, $count)
    {
        $flashcards = [];
        $usedQuestions = [];
    
        if (!empty($content['topik']) && count($flashcards) < $count) {
            foreach ($content['topik'] as $index => $topik) {
                if (count($flashcards) >= $count) break;
                
                $flashcards[] = [
                    'pertanyaan' => "Apa yang dimaksud dengan " . $topik['nama'] . "?",
                    'jawaban' => !empty($topik['poin']) ? implode("\n", $topik['poin']) : "Konsep yang dibahas dalam materi",
                    'id' => count($flashcards) + 1
                ];
            }
            $usedQuestions[] = 'topik';
        }
    
        if (!empty($content['konsep_penting']) && count($flashcards) < $count) {
            foreach ($content['konsep_penting'] as $index => $konsep) {
                if (count($flashcards) >= $count) break;
                
                $flashcards[] = [
                    'pertanyaan' => "Jelaskan konsep: " . $this->createQuestionFromConcept($konsep),
                    'jawaban' => $konsep,
                    'id' => count($flashcards) + 1
                ];
            }
            $usedQuestions[] = 'konsep';
        }
    
        if (!empty($content['hasil_pembelajaran']) && count($flashcards) < $count) {
            $flashcards[] = [
                'pertanyaan' => "Apa tujuan pembelajaran materi ini?",
                'jawaban' => $content['hasil_pembelajaran'],
                'id' => count($flashcards) + 1
            ];
            $usedQuestions[] = 'tujuan';
        }
    
        return $flashcards;
    }
    
    private function createQuestionFromConcept($concept)
    {
        $firstSentence = preg_split('/[.!?]/', $concept)[0];
        return substr($firstSentence, 0, 50) . (strlen($firstSentence) > 50 ? '...' : '');
    }

    private function cleanFlashcardData($flashcards)
    {
        return array_map(function($flashcard) {
            return [
                'id' => $flashcard['id'] ?? null,
                'pertanyaan' => $this->cleanText($flashcard['pertanyaan'] ?? ''),
                'jawaban' => $this->cleanText($flashcard['jawaban'] ?? ''),
            ];
        }, $flashcards);
    }

    private function createSimpleFlashcards($summary, $count)
    {
        Log::info('Creating simple flashcards as fallback');
        
        $flashcards = [];
        $sentences = preg_split('/(?<=[.?!])\s+/', $summary, $count + 2);
        
        $simpleQuestions = [
            "Apa inti dari materi yang dipelajari?",
            "Poin penting apa yang disampaikan?",
            "Konsep apa yang perlu dipahami?",
            "Apa tujuan pembelajaran materi ini?",
            "Bagaimana kesimpulan dari pembahasan?"
        ];
    
        for ($i = 0; $i < min($count, count($simpleQuestions)); $i++) {
            $answer = $sentences[$i] ?? $summary;
            $flashcards[] = [
                'pertanyaan' => $simpleQuestions[$i],
                'jawaban' => $this->cleanFlashcardAnswer($answer),
                'id' => $i + 1
            ];
        }
    
        return $flashcards;
    }
    
    private function cleanFlashcardAnswer($text)
    {
        $text = preg_replace('/[^\x20-\x7E\xA0-\xFF\p{L}\p{N}\p{P}\s]/u', '', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        return $text;
    }

    private function cleanMarkdownFormat($text)
    {
        $text = preg_replace('/^#+\s*/m', '', $text);
        $text = preg_replace('/^[\-\*\+]\s*/m', '', $text);
        $text = preg_replace('/\*\*(.*?)\*\*/', '$1', $text);
        $text = preg_replace('/\*(.*?)\*/', '$1', $text);
        $text = preg_replace('/\[(.*?)\]\(.*?\)/', '$1', $text);
        $text = preg_replace('/\n\s*\n\s*\n/', "\n\n", $text);
        $text = preg_replace('/^\s+/m', '', $text);
        
        return trim($text);
    }

    private function truncateText($text, $maxLength)
    {
        if (strlen($text) <= $maxLength) {
            return $text;
        }
        
        $text = substr($text, 0, $maxLength);
        $lastSpace = strrpos($text, ' ');
        
        if ($lastSpace !== false) {
            $text = substr($text, 0, $lastSpace);
        }
        
        return $text . "... [teks dipotong untuk efisiensi]";
    }

    private function cleanText($text)
    {
        if (!is_string($text)) {
            return '';
        }
        
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        $text = preg_replace('/[^\x{0000}-\x{FFFF}]/u', '', $text);
        $text = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = preg_replace('/[^\x20-\x7E\xA0-\xFF\p{L}\p{N}\p{P}]/u', ' ', $text);
        
        return trim($text);
    }
}