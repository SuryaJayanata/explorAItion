<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\GeminiService;
use App\Services\NotifikasiService;

class MateriController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function create($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $this->authorize('create', [Materi::class, $kelas]);
        
        return view('materi.create', compact('kelas'));
    }

    public function store(Request $request, $kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $this->authorize('create', [Materi::class, $kelas]);
        
        $request->validate([
            'judul' => 'required|string|max:150',
            'deskripsi' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,txt,zip,rar,xls,xlsx|max:10240',
        ], [
            'file.max' => 'Ukuran file tidak boleh lebih dari 10MB.',
            'file.mimes' => 'Format file harus: pdf, doc, docx, ppt, pptx, txt, zip, rar, xls, xlsx.'
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            try {
                $filePath = $request->file('file')->store('materi', 'public');
            } catch (\Exception $e) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mengupload file: ' . $e->getMessage()
                    ], 422);
                }
                return redirect()->back()
                    ->with('error', 'Gagal mengupload file: ' . $e->getMessage())
                    ->withInput();
            }
        }

        $materi = Materi::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file' => $filePath,
            'id_kelas' => $kelasId,
        ]);

        // Trigger notifikasi materi baru
        NotifikasiService::notifyMateriBaru($kelas, $materi);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Materi berhasil ditambahkan!',
                'redirect_url' => route('kelas.show', $kelasId)
            ]);
        }

        return redirect()->route('kelas.show', $kelasId)
            ->with('success', 'Materi berhasil ditambahkan!');
    }

    public function show($kelasId, $materiId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $materi = Materi::with([
            'kelas', 
            'komentar.user',
            'komentar.replies.user'
        ])->findOrFail($materiId);
        
        $this->authorize('view', $materi);
        
        return view('materi.show', compact('kelas', 'materi'));
    }

    public function edit($kelasId, $materiId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $materi = Materi::findOrFail($materiId);
        $this->authorize('update', $materi);
        
        return view('materi.edit', compact('kelas', 'materi'));
    }

    public function update(Request $request, $kelasId, $materiId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $materi = Materi::findOrFail($materiId);
        $this->authorize('update', $materi);
        
        $request->validate([
            'judul' => 'required|string|max:150',
            'deskripsi' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,txt|max:2048',
        ]);

        $filePath = $materi->file;
        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($materi->file) {
                Storage::disk('public')->delete($materi->file);
            }
            $filePath = $request->file('file')->store('materi', 'public');
        }

        $materi->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file' => $filePath,
        ]);

        return redirect()->route('kelas.materi.show', [$kelasId, $materiId])
            ->with('success', 'Materi berhasil diperbarui!');
    }

    public function destroy($kelasId, $materiId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $materi = Materi::findOrFail($materiId);
        $this->authorize('delete', $materi);
        
        // Hapus file jika ada
        if ($materi->file) {
            Storage::disk('public')->delete($materi->file);
        }
        
        $materi->delete();

        return redirect()->route('kelas.show', $kelasId)
            ->with('success', 'Materi berhasil dihapus!');
    }

    public function indexAll()
    {
        $user = Auth::user();
        
        if ($user->isGuru()) {
            // Guru melihat semua materi yang mereka buat di semua kelas
            $materials = Materi::whereHas('kelas', function($query) use ($user) {
                $query->where('id_guru', $user->id_user);
            })->with('kelas')->latest()->paginate(12);
        } else {
            // Siswa melihat semua materi dari kelas yang mereka ikuti
            $materials = Materi::whereHas('kelas.anggota', function($query) use ($user) {
                $query->where('id_user', $user->id_user);
            })->with('kelas')->latest()->paginate(12);
        }
        
        return view('materials.index', compact('materials'));
    }

    public function showAll($materiId)
    {
        $materi = Materi::with([
            'kelas', 
            'komentar.user', 
            'komentar.replies.user'
        ])->findOrFail($materiId);
        
        $this->authorize('view', $materi);
        
        return view('materials.show', compact('materi'));
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $user = Auth::user();
        
        $materialsQuery = Materi::query();

        if ($user->isGuru()) {
            // Guru melihat semua materi yang mereka buat
            $materialsQuery->whereHas('kelas', function($q) use ($user) {
                $q->where('id_guru', $user->id_user);
            });
        } else {
            // Siswa melihat materi dari kelas yang mereka ikuti
            $materialsQuery->whereHas('kelas.anggota', function($q) use ($user) {
                $q->where('id_user', $user->id_user);
            });
        }

        // Apply search filter jika ada query
        if ($query && strlen($query) >= 2) {
            $materialsQuery->where(function($q) use ($query) {
                $q->where('judul', 'like', "%{$query}%")
                  ->orWhere('deskripsi', 'like', "%{$query}%")
                  ->orWhereHas('kelas', function($q2) use ($query) {
                      $q2->where('nama_kelas', 'like', "%{$query}%");
                  });
            });
        }

        $materials = $materialsQuery->with('kelas')
            ->latest()
            ->paginate(12);

        if ($request->ajax()) {
            $view = view('materials.partials.materials-grid', compact('materials'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $view,
                'has_more' => $materials->hasMorePages(),
                'total' => $materials->total(),
                'count' => $materials->count(),
                'query' => $query
            ]);
        }

        return view('materials.index', compact('materials'));
    }

    public function loadMore(Request $request)
    {
        $user = Auth::user();
        $page = $request->get('page', 2);
        
        $materials = Materi::whereHas('kelas.anggota', function($q) use ($user) {
                $q->where('id_user', $user->id_user);
            })
            ->with('kelas')
            ->latest()
            ->paginate(12, ['*'], 'page', $page);

        if ($materials->count() > 0) {
            $view = view('materials.partials.materials-grid', compact('materials'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $view,
                'has_more' => $materials->hasMorePages()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No more materials found'
        ]);
    }

    public function generateSummary(Request $request, $kelasId, $materiId)
    {
        \Log::info('Generate Summary called', [
            'kelasId' => $kelasId, 
            'materiId' => $materiId,
            'include_flashcards' => $request->input('include_flashcards'),
            'flashcard_count' => $request->input('flashcard_count')
        ]);
        
        $kelas = Kelas::findOrFail($kelasId);
        $materi = Materi::findOrFail($materiId);
        
        $this->authorize('view', $materi);
    
        // Cek apakah materi memiliki file
        if (!$materi->file) {
            return response()->json([
                'success' => false,
                'message' => 'Materi tidak memiliki file untuk diringkas.'
            ], 422);
        }
    
        try {
            $includeFlashcards = $request->input('include_flashcards', false);
            $flashcardCount = $request->input('flashcard_count', 5);
    
            // Generate summary menggunakan Gemini Service
            $result = $this->geminiService->summarizeFile($materi->file, $includeFlashcards, $flashcardCount);
            
            \Log::info('Summary generation result', [
                'success' => $result['success'],
                'has_flashcards' => !empty($result['flashcards'])
            ]);
            
            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['error']
                ], 500);
            }
    
            // Simpan summary dan flashcards ke database
            $updateData = [
                'summary' => $result['summary'],
                'summary_generated_at' => now(),
            ];
    
            if ($includeFlashcards && !empty($result['flashcards'])) {
                $updateData['flashcards'] = $result['flashcards'];
            }
    
            $materi->update($updateData);
    
            $message = 'Ringkasan berhasil dibuat menggunakan AI!';
            if ($includeFlashcards && !empty($result['flashcards'])) {
                $message .= ' ' . count($result['flashcards']) . ' flashcards juga telah dibuat.';
            }
    
            return response()->json([
                'success' => true,
                'summary' => $materi->getFormattedSummary(),
                'message' => $message,
                'flashcards_count' => $includeFlashcards ? count($result['flashcards'] ?? []) : 0
            ]);
    
        } catch (\Exception $e) {
            \Log::error('Summary generation error', [
                'materi_id' => $materi->id_materi,
                'error' => $e->getMessage()
            ]);
    
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadSummary($kelasId, $materiId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $materi = Materi::findOrFail($materiId);
        
        $this->authorize('view', $materi);

        if (!$materi->hasSummary()) {
            return redirect()->back()
                ->with('error', 'Tidak ada ringkasan untuk materi ini.');
        }

        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('materi.pdf-summary', [
                'materi' => $materi,
                'kelas' => $kelas
            ]);

            $filename = "Ringkasan_" . str_replace(' ', '_', $materi->judul) . "_" . date('Y-m-d') . '.pdf';
            
            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('PDF download failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Gagal membuat file PDF: ' . $e->getMessage());
        }
    }
}