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
                'first_100_chars' => substr($text, 0, 100)
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
                    Log::info('TXT file content extracted', ['length' => strlen($content)]);
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
                'first_200_chars' => substr($text, 0, 200)
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
            
            // Fallback: coba baca sebagai binary atau return error message
            return "PDF tidak dapat diproses: " . $e->getMessage() . ". File mungkin terproteksi atau berupa scan gambar.";
        }
    }

    public function summarizeText($text, $maxLength = 1500)
    {
        try {
            Log::info('Starting text summarization', [
                'text_length' => strlen($text),
                'first_100_chars' => substr($text, 0, 100)
            ]);

            if (empty(trim($text))) {
                throw new Exception('Teks kosong tidak dapat diringkas');
            }

            // Clean text - potong jika terlalu panjang untuk efisiensi
            $text = $this->cleanText($text);
            $text = $this->truncateText($text, 30000); // Max 30k characters untuk Gemini
            
            // Build prompt yang lebih spesifik
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
                        'temperature' => 0.2,
                        'topK' => 40,
                        'topP' => 0.8,
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
                
                // Fallback: Generate summary manual berdasarkan teks asli
                return $this->generateFallbackSummary($text);
            }

            $summary = trim($data['candidates'][0]['content']['parts'][0]['text']);
            
            // Bersihkan format markdown
            $summary = $this->cleanMarkdownFormat($summary);
            
            Log::info('Summary generated successfully', [
                'summary_length' => strlen($summary),
                'first_100_chars' => substr($summary, 0, 100)
            ]);

            return [
                'success' => true,
                'summary' => $summary,
                'length' => strlen($summary)
            ];
            
        } catch (RequestException $e) {
            Log::error('Gemini API Request failed', ['error' => $e->getMessage()]);
            
            // Fallback jika API error
            return $this->generateFallbackSummary($text);
            
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
        return "Buatlah ringkasan yang AKURAT dan RELEVAN dari teks materi pembelajaran berikut. 

**TEKS ASLI MATERI:**
{$text}

**INSTRUKSI:**
1. Buat ringkasan yang SESUAI dengan konten teks di atas
2. Fokus pada poin-poin penting yang benar-benar ada dalam teks
3. Jangan menambahkan informasi yang tidak ada dalam teks asli
4. Gunakan Bahasa Indonesia yang baik dan benar
5. Struktur yang jelas dan mudah dipahami
6. Maksimal 400 kata
7. JANGAN gunakan format markdown (#, -, *)

**FORMAT OUTPUT:**
Ringkasan Materi

Poin-Poin Utama
[jelaskan poin-poin utama yang ada dalam teks]

Konsep Penting
[uraikan konsep-konsep penting yang dibahas]

Kesimpulan
[berikan kesimpulan yang relevan dengan materi]";
    }

    private function generateFallbackSummary($text)
    {
        Log::info('Generating fallback summary from real text');
        
        // Ambil beberapa kalimat pertama dari teks asli sebagai fallback
        $sentences = preg_split('/(?<=[.?!])\s+/', $text, 6);
        $firstSentences = array_slice($sentences, 0, 5);
        $keyContent = implode(' ', $firstSentences);
        
        // Identifikasi topik dari teks
        $words = str_word_count($text, 1);
        $commonWords = array_slice($words, 0, 20);
        $topic = implode(' ', array_slice($commonWords, 0, 5));
        
        $summary = "Ringkasan Materi\n\n";
        $summary .= "Poin-Poin Utama\n";
        $summary .= "Berdasarkan materi, pembahasan mencakup: " . $topic . ". ";
        $summary .= "Materi ini membahas konsep-konsep penting yang relevan dengan topik yang disajikan.\n\n";
        
        $summary .= "Konsep Penting\n";
        $summary .= "Beberapa poin kunci yang diangkat dalam materi antara lain aspek-aspek fundamental ";
        $summary .= "yang perlu dipahami untuk menguasai topik ini secara komprehensif.\n\n";
        
        $summary .= "Kesimpulan\n";
        $summary .= "Materi ini memberikan dasar pemahaman yang penting. " . $keyContent;

        Log::info('Fallback summary generated', ['length' => strlen($summary)]);

        return [
            'success' => true,
            'summary' => $summary,
            'length' => strlen($summary),
            'fallback' => true
        ];
    }

    private function generateFlashcards($summary, $count = 5)
    {
        try {
            Log::info('Generating intelligent flashcards from summary', [
                'summary_length' => strlen($summary),
                'requested_count' => $count
            ]);
    
            $flashcards = [];
    
            // Parse summary untuk ekstrak konten yang meaningful
            $content = $this->extractContentFromSummary($summary);
            
            Log::info('Content extracted from summary', [
                'poin_utama' => $content['poin_utama'] ?? 'Not found',
                'konsep_penting' => $content['konsep_penting'] ?? 'Not found',
                'kesimpulan' => $content['kesimpulan'] ?? 'Not found'
            ]);
    
            // Generate flashcards berdasarkan konten yang diekstrak
            $flashcards = $this->createContentBasedFlashcards($content, $count);
    
            Log::info('Content-based flashcards generated', ['count' => count($flashcards)]);
            return $flashcards;
    
        } catch (Exception $e) {
            Log::error('Intelligent flashcard generation failed', ['error' => $e->getMessage()]);
            return $this->createSimpleFlashcards($summary, $count);
        }
    }
    
    private function extractContentFromSummary($summary)
    {
        $content = [
            'poin_utama' => [],
            'konsep_penting' => [],
            'kesimpulan' => ''
        ];
    
        // Split summary by lines
        $lines = explode("\n", $summary);
        $currentSection = '';
    
        foreach ($lines as $line) {
            $line = trim($line);
            
            if (empty($line)) continue;
    
            // Deteksi section headers
            if (str_contains(strtolower($line), 'poin-poin utama') || 
                str_contains(strtolower($line), 'poin utama')) {
                $currentSection = 'poin_utama';
                continue;
            } elseif (str_contains(strtolower($line), 'konsep penting') || 
                     str_contains(strtolower($line), 'konsep kunci')) {
                $currentSection = 'konsep_penting';
                continue;
            } elseif (str_contains(strtolower($line), 'kesimpulan')) {
                $currentSection = 'kesimpulan';
                continue;
            } elseif (str_contains(strtolower($line), 'ringkasan materi')) {
                $currentSection = '';
                continue;
            }
    
            // Tambahkan konten ke section yang sesuai
            if ($currentSection && strlen($line) > 10) { // Minimal 10 karakter
                if ($currentSection === 'kesimpulan') {
                    $content[$currentSection] .= $line . ' ';
                } else {
                    $content[$currentSection][] = $line;
                }
            }
        }
    
        // Clean up
        $content['kesimpulan'] = trim($content['kesimpulan']);
    
        return $content;
    }
    
    private function createContentBasedFlashcards($content, $count)
    {
        $flashcards = [];
        $usedQuestions = [];
    
        // Flashcard 1: Topik Utama (dari Poin Utama)
        if (!empty($content['poin_utama']) && count($flashcards) < $count) {
            $firstMainPoint = $content['poin_utama'][0] ?? '';
            if (strlen($firstMainPoint) > 20) {
                $flashcards[] = [
                    'pertanyaan' => "Apa topik utama yang dibahas dalam materi ini?",
                    'jawaban' => $this->extractMainTopic($firstMainPoint),
                    'id' => count($flashcards) + 1
                ];
                $usedQuestions[] = 'topik_utama';
            }
        }
    
        // Flashcard 2: Poin-Poin Penting
        if (!empty($content['poin_utama']) && count($flashcards) < $count) {
            $mainPoints = array_slice($content['poin_utama'], 0, 3); // Ambil max 3 poin
            $answer = "Poin-poin utama dalam materi ini meliputi:\n";
            foreach ($mainPoints as $index => $point) {
                $cleanPoint = $this->cleanFlashcardAnswer($point);
                if (strlen($cleanPoint) > 10) {
                    $answer .= "• " . $cleanPoint . "\n";
                }
            }
            
            if (strlen($answer) > 50) {
                $flashcards[] = [
                    'pertanyaan' => "Sebutkan poin-poin penting yang dibahas dalam materi!",
                    'jawaban' => trim($answer),
                    'id' => count($flashcards) + 1
                ];
                $usedQuestions[] = 'poin_penting';
            }
        }
    
        // Flashcard 3: Konsep Kunci
        if (!empty($content['konsep_penting']) && count($flashcards) < $count) {
            $keyConcepts = array_slice($content['konsep_penting'], 0, 2);
            $answer = "Konsep-konsep kunci yang perlu dipahami:\n";
            foreach ($keyConcepts as $concept) {
                $cleanConcept = $this->cleanFlashcardAnswer($concept);
                if (strlen($cleanConcept) > 10) {
                    $answer .= "• " . $cleanConcept . "\n";
                }
            }
            
            if (strlen($answer) > 50) {
                $flashcards[] = [
                    'pertanyaan' => "Apa saja konsep kunci yang dijelaskan dalam materi?",
                    'jawaban' => trim($answer),
                    'id' => count($flashcards) + 1
                ];
                $usedQuestions[] = 'konsep_kunci';
            }
        }
    
        // Flashcard 4: Kesimpulan
        if (!empty($content['kesimpulan']) && strlen($content['kesimpulan']) > 20 && count($flashcards) < $count) {
            $flashcards[] = [
                'pertanyaan' => "Apa kesimpulan utama dari materi ini?",
                'jawaban' => $this->cleanFlashcardAnswer($content['kesimpulan']),
                'id' => count($flashcards) + 1
            ];
            $usedQuestions[] = 'kesimpulan';
        }
    
        // Flashcard 5: Tujuan Pembelajaran
        if (!empty($content['poin_utama']) && count($flashcards) < $count) {
            $firstPoint = $content['poin_utama'][0] ?? '';
            if (strlen($firstPoint) > 30) {
                $flashcards[] = [
                    'pertanyaan' => "Apa tujuan dari mempelajari materi ini?",
                    'jawaban' => "Materi ini bertujuan untuk memahami " . $this->extractLearningObjective($firstPoint),
                    'id' => count($flashcards) + 1
                ];
                $usedQuestions[] = 'tujuan';
            }
        }
    
        // Jika masih kurang, tambahkan flashcards generik berdasarkan konten
        $remaining = $count - count($flashcards);
        if ($remaining > 0) {
            $genericFlashcards = $this->createGenericFlashcards($content, $remaining, $usedQuestions);
            $flashcards = array_merge($flashcards, $genericFlashcards);
        }
    
        return $flashcards;
    }
    
    private function extractMainTopic($text)
    {
        // Bersihkan teks dan ambil kalimat pertama yang meaningful
        $cleanText = $this->cleanFlashcardAnswer($text);
        $sentences = preg_split('/(?<=[.?!])\s+/', $cleanText, 2);
        
        return $sentences[0] ?? "Topik utama materi pembelajaran";
    }
    
    private function extractLearningObjective($text)
    {
        $cleanText = $this->cleanFlashcardAnswer($text);
        
        // Coba ekstrak tujuan pembelajaran dari teks
        if (str_contains(strtolower($cleanText), 'tujuan') || 
            str_contains(strtolower($cleanText), 'untuk') ||
            str_contains(strtolower($cleanText), 'agar')) {
            return $cleanText;
        }
        
        return "konsep-konsep fundamental yang dibahas dalam materi";
    }
    
    private function createGenericFlashcards($content, $count, $usedQuestions)
    {
        $flashcards = [];
        $availableQuestions = [
            'definisi' => "Apa definisi dari konsep utama yang dibahas?",
            'manfaat' => "Apa manfaat mempelajari materi ini?",
            'contoh' => "Beri contoh penerapan konsep yang dijelaskan!",
            'hubungan' => "Bagaimana hubungan antara berbagai konsep yang dibahas?",
            'implementasi' => "Bagaimana cara mengimplementasikan konsep ini?",
            'perbedaan' => "Apa perbedaan antara konsep-konsep yang dijelaskan?",
            'pentingnya' => "Mengapa materi ini penting untuk dipelajari?",
            'aplikasi' => "Di bidang apa saja konsep ini dapat diaplikasikan?"
        ];
    
        $questionKeys = array_diff(array_keys($availableQuestions), $usedQuestions);
        shuffle($questionKeys);
    
        foreach (array_slice($questionKeys, 0, $count) as $questionKey) {
            $answer = $this->generateGenericAnswer($questionKey, $content);
            $flashcards[] = [
                'pertanyaan' => $availableQuestions[$questionKey],
                'jawaban' => $answer,
                'id' => count($flashcards) + 1
            ];
        }
    
        return $flashcards;
    }
    
    private function generateGenericAnswer($questionType, $content)
    {
        switch ($questionType) {
            case 'definisi':
                $firstConcept = $content['konsep_penting'][0] ?? $content['poin_utama'][0] ?? '';
                return $firstConcept ?: "Konsep utama yang dibahas dalam materi ini.";
                
            case 'manfaat':
                return "Memahami materi ini membantu dalam " . 
                       ($content['kesimpulan'] ?: "penguasaan konsep-konsep penting yang dibahas.");
                
            case 'contoh':
                $mainTopic = $content['poin_utama'][0] ?? '';
                return "Contoh penerapannya dapat dilihat dalam " . 
                       ($mainTopic ?: "berbagai situasi yang relevan dengan topik.");
                
            case 'hubungan':
                return "Konsep-konsep tersebut saling terkait dan membentuk pemahaman yang komprehensif.";
                
            case 'implementasi':
                return "Dapat diimplementasikan melalui penerapan langkah-langkah praktis yang dijelaskan.";
                
            case 'perbedaan':
                return "Masing-masing konsep memiliki karakteristik dan penerapan yang berbeda.";
                
            case 'pentingnya':
                return "Materi ini penting karena memberikan dasar pemahaman yang fundamental.";
                
            case 'aplikasi':
                return "Konsep ini dapat diaplikasikan dalam berbagai bidang terkait.";
                
            default:
                return "Jawaban berdasarkan konten materi yang telah dipelajari.";
        }
    }
    
    private function cleanFlashcardAnswer($text)
    {
        // Hapus karakter khusus dan format yang tidak perlu
        $text = preg_replace('/[^\x20-\x7E\xA0-\xFF\p{L}\p{N}\p{P}\s]/u', '', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        // Hapus "Ringkasan Materi" jika ada di awal
        if (strpos(strtolower($text), 'ringkasan materi') === 0) {
            $text = substr($text, strlen('Ringkasan Materi'));
        }
        
        return trim($text);
    }
    
    // Fallback method untuk summary yang terlalu pendek
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
    private function cleanMarkdownFormat($text)
    {
        // Hapus markdown headers
        $text = preg_replace('/^#+\s*/m', '', $text);
        
        // Hapus markdown list items
        $text = preg_replace('/^[\-\*\+]\s*/m', '', $text);
        
        // Hapus markdown bold/italic
        $text = preg_replace('/\*\*(.*?)\*\*/', '$1', $text);
        $text = preg_replace('/\*(.*?)\*/', '$1', $text);
        
        // Hapus markdown links
        $text = preg_replace('/\[(.*?)\]\(.*?\)/', '$1', $text);
        
        // Hapus extra whitespace
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
        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        
        // Remove special characters but keep basic punctuation and Indonesian characters
        $text = preg_replace('/[^\x20-\x7E\xA0-\xFF\p{L}\p{N}\p{P}]/u', ' ', $text);
        
        return trim($text);
    }
}