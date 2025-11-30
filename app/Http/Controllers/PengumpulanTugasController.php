<?php

namespace App\Http\Controllers;

use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use App\Models\Kelas;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\NotifikasiService;
use App\Services\AutoGradingService;

class PengumpulanTugasController extends Controller
{
    protected $autoGradingService;

    public function __construct(AutoGradingService $autoGradingService)
    {
        $this->autoGradingService = $autoGradingService;
    }

    public function store(Request $request, $kelasId, $tugasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $tugas = Tugas::findOrFail($tugasId);
        
        // Cek apakah user adalah anggota kelas
        $isMember = $kelas->anggota()->where('id_user', Auth::id())->exists();
        if (!$isMember && Auth::id() !== $kelas->id_guru) {
            abort(403, 'Anda bukan anggota kelas ini.');
        }
    
        // Cek deadline
        if (Carbon::now()->greaterThan($tugas->deadline)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa mengumpulkan tugas karena sudah melewati deadline.'
                ], 422);
            }
            return redirect()->back()
                ->with('error', 'Tidak bisa mengumpulkan tugas karena sudah melewati deadline.');
        }
    
        $request->validate([
            'file_jawaban' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,txt,zip,rar,jpg,jpeg,png|max:10240',
        ], [
            'file_jawaban.required' => 'File jawaban harus diupload.',
            'file_jawaban.mimes' => 'Format file harus: pdf, doc, docx, ppt, pptx, txt, zip, rar, jpg, jpeg, png.',
            'file_jawaban.max' => 'Ukuran file tidak boleh lebih dari 10MB.'
        ]);
    
        // Cek apakah sudah mengumpulkan
        $existingPengumpulan = PengumpulanTugas::where('id_user', Auth::id())
            ->where('id_tugas', $tugasId)
            ->first();
    
        if ($existingPengumpulan) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah mengumpulkan tugas ini.'
                ], 422);
            }
            return redirect()->back()
                ->with('error', 'Anda sudah mengumpulkan tugas ini.');
        }
    
        // Upload file
        $filePath = $request->file('file_jawaban')->store('pengumpulan_tugas', 'public');
    
        $pengumpulan = PengumpulanTugas::create([
            'id_user' => Auth::id(),
            'id_tugas' => $tugasId,
            'file_jawaban' => $filePath,
        ]);
    
        // AUTO GRADING - Jika tugas menggunakan auto grading
        $gradingResult = null;
        if ($tugas->usesAutoGrading()) {
            try {
                $gradingResult = $this->autoGradingService->gradeSubmission($pengumpulan);
                
                if ($gradingResult['success']) {
                    // Simpan nilai otomatis
                    Nilai::create([
                        'id_pengumpulan' => $pengumpulan->id_pengumpulan,
                        'nilai' => $gradingResult['nilai'],
                        'komentar_guru' => "Auto Grading: " . ($gradingResult['feedback_umum'] ?? 'Tidak ada feedback') . 
                                          "\n\nRekomendasi: " . ($gradingResult['rekomendasi_perbaikan'] ?? 'Tidak ada rekomendasi') .
                                          "\n\nDetail Analisis: " . json_encode($gradingResult['analisis_per_soal'] ?? []),
                        'auto_graded' => true,
                        'analisis_detail' => $gradingResult['analisis_per_soal'] ?? [],
                    ]);
    
                    // Trigger notifikasi nilai diberikan
                    NotifikasiService::notifyNilaiDiberikan($pengumpulan);
                } else {
                    // Log error auto grading tapi lanjutkan proses
                    \Log::warning('Auto grading failed but continuing: ' . ($gradingResult['error'] ?? 'Unknown error'));
                }
            } catch (\Exception $e) {
                // Catch any exception in auto grading and continue
                \Log::error('Auto grading exception: ' . $e->getMessage());
                // Continue without auto grading
            }
        }
    
        // Trigger notifikasi tugas dikumpulkan
        NotifikasiService::notifyTugasDikumpulkan($pengumpulan);
    
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil dikumpulkan!' . 
                           ($tugas->usesAutoGrading() && ($gradingResult['success'] ?? false) ? ' Nilai telah di-generate otomatis.' : ''),
                'auto_graded' => $tugas->usesAutoGrading() && ($gradingResult['success'] ?? false)
            ]);
        }
    
        return redirect()->route('kelas.tugas.show', [$kelasId, $tugasId])
            ->with('success', 'Tugas berhasil dikumpulkan!' . 
                   ($tugas->usesAutoGrading() && ($gradingResult['success'] ?? false) ? ' Nilai telah di-generate otomatis.' : ''));
    }

    public function update(Request $request, $kelasId, $tugasId, $pengumpulanId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $tugas = Tugas::findOrFail($tugasId);
        $pengumpulan = PengumpulanTugas::findOrFail($pengumpulanId);
        
        // Authorization - hanya pemilik yang bisa update
        if ($pengumpulan->id_user !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Cek apakah sudah dinilai
        if ($pengumpulan->nilai) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa mengupdate tugas karena sudah dinilai.'
                ], 422);
            }
            return redirect()->back()
                ->with('error', 'Tidak bisa mengupdate tugas karena sudah dinilai.');
        }

        // Cek deadline
        if (Carbon::now()->greaterThan($tugas->deadline)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa mengupdate tugas karena sudah melewati deadline.'
                ], 422);
            }
            return redirect()->back()
                ->with('error', 'Tidak bisa mengupdate tugas karena sudah melewati deadline.');
        }

        $request->validate([
            'file_jawaban' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,txt,zip,rar,jpg,jpeg,png|max:10240',
        ]);

        // Hapus file lama
        if ($pengumpulan->file_jawaban) {
            Storage::disk('public')->delete($pengumpulan->file_jawaban);
        }

        // Upload file baru
        $filePath = $request->file('file_jawaban')->store('pengumpulan_tugas', 'public');

        $pengumpulan->update([
            'file_jawaban' => $filePath,
        ]);

        // AUTO GRADING - Jika tugas menggunakan auto grading dan belum ada nilai
        if ($tugas->usesAutoGrading() && !$pengumpulan->nilai) {
            $gradingResult = $this->autoGradingService->gradeSubmission($pengumpulan);
            
            if ($gradingResult['success']) {
                // Simpan nilai otomatis
                Nilai::create([
                    'id_pengumpulan' => $pengumpulan->id_pengumpulan,
                    'nilai' => $gradingResult['nilai'],
                    'komentar_guru' => "Auto Grading: " . ($gradingResult['feedback_umum'] ?? 'Tidak ada feedback') . 
                                      "\n\nRekomendasi: " . ($gradingResult['rekomendasi_perbaikan'] ?? 'Tidak ada rekomendasi') .
                                      "\n\nDetail Analisis: " . json_encode($gradingResult['analisis_per_soal'] ?? []),
                    'auto_graded' => true,
                    'analisis_detail' => $gradingResult['analisis_per_soal'] ?? [],
                ]);

                // Trigger notifikasi nilai diberikan
                NotifikasiService::notifyNilaiDiberikan($pengumpulan);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengumpulan tugas berhasil diperbarui!' . 
                           ($tugas->usesAutoGrading() && !$pengumpulan->nilai ? ' Nilai telah di-generate otomatis.' : '')
            ]);
        }

        return redirect()->route('kelas.tugas.show', [$kelasId, $tugasId])
            ->with('success', 'Pengumpulan tugas berhasil diperbarui!' . 
                   ($tugas->usesAutoGrading() && !$pengumpulan->nilai ? ' Nilai telah di-generate otomatis.' : ''));
    }

    public function destroy($kelasId, $tugasId, $pengumpulanId)
    {
        $pengumpulan = PengumpulanTugas::findOrFail($pengumpulanId);
        $tugas = Tugas::findOrFail($tugasId);
        
        // Authorization - hanya pemilik yang bisa hapus
        if ($pengumpulan->id_user !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Cek deadline
        if (Carbon::now()->greaterThan($tugas->deadline)) {
            return redirect()->back()
                ->with('error', 'Tidak bisa menghapus pengumpulan karena sudah melewati deadline.');
        }

        // Hapus file
        if ($pengumpulan->file_jawaban) {
            Storage::disk('public')->delete($pengumpulan->file_jawaban);
        }

        // Hapus nilai jika ada
        if ($pengumpulan->nilai) {
            $pengumpulan->nilai->delete();
        }

        $pengumpulan->delete();

        return redirect()->route('kelas.tugas.show', [$kelasId, $tugasId])
            ->with('success', 'Pengumpulan tugas berhasil dihapus!');
    }

    /**
     * Manual trigger auto grading untuk testing
     */
    public function triggerAutoGrading($kelasId, $tugasId, $pengumpulanId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $tugas = Tugas::findOrFail($tugasId);
        $pengumpulan = PengumpulanTugas::findOrFail($pengumpulanId);

        // Hanya guru yang bisa trigger manual
        if ($kelas->id_guru !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!$tugas->usesAutoGrading()) {
            return redirect()->back()
                ->with('error', 'Tugas ini tidak menggunakan auto grading.');
        }

        $gradingResult = $this->autoGradingService->gradeSubmission($pengumpulan);
        
        if ($gradingResult['success']) {
            // Hapus nilai lama jika ada
            if ($pengumpulan->nilai) {
                $pengumpulan->nilai->delete();
            }

            // Simpan nilai baru
            Nilai::create([
                'id_pengumpulan' => $pengumpulan->id_pengumpulan,
                'nilai' => $gradingResult['nilai'],
                'komentar_guru' => "Auto Grading: " . ($gradingResult['feedback_umum'] ?? 'Tidak ada feedback') . 
                                  "\n\nRekomendasi: " . ($gradingResult['rekomendasi_perbaikan'] ?? 'Tidak ada rekomendasi') .
                                  "\n\nDetail Analisis: " . json_encode($gradingResult['analisis_per_soal'] ?? []),
                'auto_graded' => true,
                'analisis_detail' => $gradingResult['analisis_per_soal'] ?? [],
            ]);

            // Trigger notifikasi nilai diberikan
            NotifikasiService::notifyNilaiDiberikan($pengumpulan);

            return redirect()->back()
                ->with('success', 'Auto grading berhasil! Nilai: ' . $gradingResult['nilai']);
        } else {
            return redirect()->back()
                ->with('error', 'Auto grading gagal: ' . ($gradingResult['error'] ?? 'Unknown error'));
        }
    }
}