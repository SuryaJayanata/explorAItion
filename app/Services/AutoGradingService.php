<?php

namespace App\Services;

use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Exception;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser as PdfParser;
use Illuminate\Support\Facades\Storage;

class AutoGradingService
{
    protected $pdfParser;
    protected $client;

    public function __construct()
    {
        $this->pdfParser = new PdfParser();
        $this->client = new Client([
            'base_uri' => 'https://generativelanguage.googleapis.com/',
            'timeout' => 60.0,
        ]);
    }

    public function gradeSubmission(PengumpulanTugas $pengumpulan)
    {
        try {
            $tugas = $pengumpulan->tugas;
            
            // Cek apakah tugas menggunakan auto grading
            if (!$tugas || !$tugas->usesAutoGrading()) {
                return [
                    'success' => false,
                    'error' => 'Auto grading tidak diaktifkan untuk tugas ini'
                ];
            }

            Log::info('Starting auto grading', [
                'pengumpulan_id' => $pengumpulan->id_pengumpulan,
                'tugas_id' => $tugas->id_tugas,
                'judul_tugas' => $tugas->judul
            ]);

            // Ekstrak teks dari jawaban siswa
            $jawabanSiswa = $this->extractTextFromFile($pengumpulan->file_jawaban);
            
            if (empty(trim($jawabanSiswa))) {
                throw new Exception('Tidak dapat mengekstrak teks dari jawaban siswa');
            }

            Log::info('Jawaban siswa extracted', [
                'length' => strlen($jawabanSiswa),
                'preview' => substr($jawabanSiswa, 0, 200)
            ]);

            // Ekstrak teks dari kunci jawaban
            $kunciJawaban = $this->getKunciJawaban($tugas);
            
            if (empty(trim($kunciJawaban))) {
                // Jika tidak ada kunci jawaban, gunakan grading berdasarkan pattern
                return $this->gradeWithPattern($tugas, $jawabanSiswa);
            }

            Log::info('Kunci jawaban extracted', [
                'length' => strlen($kunciJawaban)
            ]);

            // Coba berbagai model Gemini
            $gradingResult = $this->tryMultipleGeminiModels($tugas, $jawabanSiswa, $kunciJawaban);

            Log::info('Auto grading completed', [
                'pengumpulan_id' => $pengumpulan->id_pengumpulan,
                'nilai' => $gradingResult['nilai'] ?? null,
                'success' => $gradingResult['success'] ?? false
            ]);

            return $gradingResult;

        } catch (Exception $e) {
            Log::error('Auto grading failed', [
                'pengumpulan_id' => $pengumpulan->id_pengumpulan,
                'error' => $e->getMessage()
            ]);

            // Fallback ke pattern-based grading
            try {
                $tugas = $pengumpulan->tugas;
                $jawabanSiswa = $this->extractTextFromFile($pengumpulan->file_jawaban);
                return $this->gradeWithPattern($tugas, $jawabanSiswa);
            } catch (Exception $fallbackError) {
                return [
                    'success' => false,
                    'error' => 'Gagal melakukan auto grading: ' . $e->getMessage()
                ];
            }
        }
    }

    private function tryMultipleGeminiModels(Tugas $tugas, $jawabanSiswa, $kunciJawaban)
    {
        $models = [
            'gemini-1.5-pro-latest',
            'gemini-1.0-pro',
            'gemini-pro',
            'models/gemini-pro'
        ];

        foreach ($models as $model) {
            try {
                Log::info('Trying Gemini model', ['model' => $model]);
                $result = $this->performGradingWithModel($tugas, $jawabanSiswa, $kunciJawaban, $model);
                Log::info('Model successful', ['model' => $model]);
                return $result;
            } catch (Exception $e) {
                Log::warning('Model failed', [
                    'model' => $model,
                    'error' => $e->getMessage()
                ]);
                continue;
            }
        }

        throw new Exception('Semua model Gemini gagal');
    }

    private function performGradingWithModel(Tugas $tugas, $jawabanSiswa, $kunciJawaban, $model)
    {
        try {
            $prompt = $this->buildGradingPrompt($tugas, $jawabanSiswa, $kunciJawaban);

            $apiKey = env('GEMINI_API_KEY');
            
            if (!$apiKey) {
                throw new Exception('GEMINI_API_KEY tidak ditemukan');
            }

            // Coba endpoint yang berbeda
            $endpoints = [
                "v1beta/models/{$model}:generateContent",
                "v1/models/{$model}:generateContent"
            ];

            foreach ($endpoints as $endpoint) {
                try {
                    Log::info('Trying endpoint', ['endpoint' => $endpoint]);
                    
                    $response = $this->client->post("{$endpoint}?key={$apiKey}", [
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
                                'maxOutputTokens' => 1500,
                            ]
                        ],
                        'headers' => [
                            'Content-Type' => 'application/json',
                        ],
                        'timeout' => 30
                    ]);

                    $data = json_decode($response->getBody(), true);

                    if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                        throw new Exception('Format response tidak valid');
                    }

                    $resultText = trim($data['candidates'][0]['content']['parts'][0]['text']);
                    
                    return $this->parseGradingResult($resultText);

                } catch (RequestException $e) {
                    $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 'Unknown';
                    if ($statusCode == 404) {
                        continue; // Coba endpoint berikutnya
                    }
                    throw $e;
                }
            }

            throw new Exception('Semua endpoint gagal');

        } catch (Exception $e) {
            throw new Exception('Gagal grading dengan model ' . $model . ': ' . $e->getMessage());
        }
    }

    private function buildGradingPrompt(Tugas $tugas, $jawabanSiswa, $kunciJawaban)
    {
        // Potong teks jika terlalu panjang
        $jawabanSiswa = $this->truncateText($jawabanSiswa, 8000);
        $kunciJawaban = $this->truncateText($kunciJawaban, 4000);

        return "TUGAS: Analisis dan Beri Nilai Jawaban Siswa

**INFORMASI TUGAS:**
- Judul: {$tugas->judul}
- Deskripsi: {$tugas->deskripsi}

**KUNCI JAWABAN:**
{$kunciJawaban}

**JAWABAN SISWA:**
{$jawabanSiswa}

**INSTRUKSI:**
1. Bandingkan jawaban siswa dengan kunci jawaban
2. Beri nilai 0-100 berdasarkan ketepatan dan kelengkapan
3. Berikan feedback yang membantu
4. Analisis per bagian soal

**FORMAT OUTPUT (HARUS JSON):**
{
    \"nilai\": 75,
    \"feedback_umum\": \"Feedback umum disini...\",
    \"analisis_per_soal\": [
        {
            \"soal\": \"Deskripsi soal\",
            \"kebenaran\": \"benar/salah/sebagian\",
            \"skor\": 15,
            \"feedback\": \"Feedback spesifik...\"
        }
    ],
    \"rekomendasi_perbaikan\": \"Rekomendasi disini...\"
}

**CATATAN:**
- Nilai harus angka antara 0-100
- Berikan feedback yang konstruktif
- Pertimbangkan pemahaman konsep, bukan hanya hafalan";
    }

    private function gradeWithPattern(Tugas $tugas, $jawabanSiswa)
    {
        Log::info('Using pattern-based grading as fallback');
        
        // Rule-based grading untuk tugas matematika
        $patterns = [
            'determinan' => [
                'keywords' => ['determinan', '5', '= 5', '=5', '(2*4) - (3*1)', '8 - 3'],
                'score' => 20,
                'feedback' => 'Jawaban determinan matriks A benar'
            ],
            'sistem_persamaan' => [
                'keywords' => ['2x + 3y = 8', 'x - y = 1', 'x = 2.2', 'y = 1.2', 'solusi:'],
                'score' => 20,
                'feedback' => 'Sistem persamaan diselesaikan'
            ],
            'invers_matriks' => [
                'keywords' => ['invers', '-2 1', '1.5 -0.5', '-0.5'],
                'score' => 20,
                'feedback' => 'Invers matriks dihitung'
            ],
            'rank_matriks' => [
                'keywords' => ['rank', '2'],
                'score' => 20,
                'feedback' => 'Rank matriks disebutkan'
            ],
            'eliminasi_gauss' => [
                'keywords' => ['eliminasi gauss', 'x=1', 'y=2', 'z=4'],
                'score' => 20,
                'feedback' => 'Eliminasi Gauss digunakan'
            ]
        ];

        $totalScore = 0;
        $analisisPerSoal = [];
        $lowercaseJawaban = strtolower($jawabanSiswa);

        foreach ($patterns as $soal => $rule) {
            $score = 0;
            $matched = [];

            foreach ($rule['keywords'] as $keyword) {
                if (strpos($lowercaseJawaban, strtolower($keyword)) !== false) {
                    $matched[] = $keyword;
                    $score = $rule['score'];
                    break;
                }
            }

            $analisisPerSoal[] = [
                'soal' => $soal,
                'kebenaran' => $score > 0 ? 'benar' : 'salah',
                'skor' => $score,
                'feedback' => $score > 0 ? $rule['feedback'] : 'Jawaban tidak ditemukan'
            ];

            $totalScore += $score;
        }

        // Berikan bonus untuk jawaban yang lengkap
        $answered = count(array_filter($analisisPerSoal, fn($item) => $item['skor'] > 0));
        if ($answered >= 3) {
            $totalScore = min(100, $totalScore + 10);
        }

        return [
            'success' => true,
            'nilai' => $totalScore,
            'feedback_umum' => "Dinilai otomatis berdasarkan pattern matching. {$answered} dari 5 soal terjawab.",
            'analisis_per_soal' => $analisisPerSoal,
            'rekomendasi_perbaikan' => "Periksa jawaban yang belum lengkap.",
            'pattern_based' => true
        ];
    }

    private function extractTextFromFile($filePath)
    {
        $fullPath = storage_path('app/public/' . $filePath);
        
        if (!file_exists($fullPath)) {
            throw new Exception('File jawaban tidak ditemukan: ' . $fullPath);
        }

        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        switch ($extension) {
            case 'pdf':
                return $this->extractTextFromPDF($fullPath);
            
            case 'txt':
                $content = file_get_contents($fullPath);
                return $content ?: '';
            
            default:
                throw new Exception("Format file .{$extension} tidak didukung");
        }
    }

    private function extractTextFromPDF($filePath)
    {
        try {
            $pdf = $this->pdfParser->parseFile($filePath);
            $text = $pdf->getText();
            $text = preg_replace('/\s+/', ' ', $text);
            return trim($text);
        } catch (Exception $e) {
            throw new Exception('Gagal mengekstrak teks dari PDF: ' . $e->getMessage());
        }
    }

    private function getKunciJawaban(Tugas $tugas)
    {
        if ($tugas->kunci_jawaban_file) {
            $fullPath = storage_path('app/public/' . $tugas->kunci_jawaban_file);
            
            if (file_exists($fullPath)) {
                $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
                
                if ($extension === 'pdf') {
                    return $this->extractTextFromPDF($fullPath);
                } elseif ($extension === 'txt') {
                    return file_get_contents($fullPath) ?: '';
                }
            }
        }

        return $tugas->kunci_jawaban_text ?? '';
    }

    private function parseGradingResult($resultText)
    {
        // Cari JSON dalam response
        preg_match('/\{(?:[^{}]|(?R))*\}/s', $resultText, $matches);
        
        if (empty($matches)) {
            return $this->fallbackGrading($resultText);
        }

        try {
            $resultData = json_decode($matches[0], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON decode error');
            }

            $nilai = isset($resultData['nilai']) ? max(0, min(100, (int)$resultData['nilai'])) : 50;

            return [
                'success' => true,
                'nilai' => $nilai,
                'feedback_umum' => $resultData['feedback_umum'] ?? 'Tidak ada feedback',
                'analisis_per_soal' => $resultData['analisis_per_soal'] ?? [],
                'rekomendasi_perbaikan' => $resultData['rekomendasi_perbaikan'] ?? '',
                'gemini_used' => true
            ];

        } catch (Exception $e) {
            return $this->fallbackGrading($resultText);
        }
    }

    private function fallbackGrading($resultText)
    {
        // Fallback sederhana
        preg_match('/\b(\d{1,3})\b/', $resultText, $matches);
        $nilai = $matches[1] ?? 60;
        
        return [
            'success' => true,
            'nilai' => min(100, max(0, (int)$nilai)),
            'feedback_umum' => 'Grading otomatis: ' . substr($resultText, 0, 100),
            'analisis_per_soal' => [],
            'rekomendasi_perbaikan' => 'Periksa secara manual untuk detail',
            'fallback_used' => true
        ];
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
        
        return $text . "... [text dipotong]";
    }
}