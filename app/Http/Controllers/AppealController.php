<?php

namespace App\Http\Controllers;

use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Appeal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotifikasiService;

class AppealController extends Controller
{
    public function store(Request $request, $kelasId, $tugasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $tugas = Tugas::findOrFail($tugasId);
        
        // Cari pengumpulan user saat ini
        $pengumpulan = PengumpulanTugas::where('id_tugas', $tugasId)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        // Authorization
        if ($pengumpulan->id_user !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Cek apakah sudah ada nilai
        if (!$pengumpulan->nilai) {
            return redirect()->back()
                ->with('error', 'Tidak bisa mengajukan appeal karena tugas belum dinilai.');
        }

        // Cek apakah sudah ada appeal pending
        if ($pengumpulan->hasPendingAppeal()) {
            return redirect()->back()
                ->with('error', 'Anda sudah memiliki appeal yang sedang diproses untuk tugas ini.');
        }

        $request->validate([
            'alasan_banding' => 'required|string|max:1000',
        ]);

        // Buat appeal
        $appeal = Appeal::create([
            'id_pengumpulan' => $pengumpulan->id_pengumpulan,
            'alasan_banding' => $request->alasan_banding,
            'status' => 'pending',
        ]);

        // Notifikasi ke guru
        $this->notifyAppealDiajukan($pengumpulan, $request->alasan_banding);

        return redirect()->back()
            ->with('success', 'Appeal berhasil diajukan! Guru akan meninjau submission Anda.');
    }

    public function updateGrade(Request $request, $kelasId, $tugasId, $appealId)
    {
        try {
            \Log::info('=== UPDATE GRADE APPEAL START ===');
            \Log::info('Request data:', $request->all());
            \Log::info('Params:', ['kelasId' => $kelasId, 'tugasId' => $tugasId, 'appealId' => $appealId]);
    
            $kelas = Kelas::findOrFail($kelasId);
            $tugas = Tugas::findOrFail($tugasId);
            $appeal = Appeal::findOrFail($appealId);
    
            \Log::info('Data found:', [
                'kelas_guru' => $kelas->id_guru,
                'auth_user' => Auth::id(),
                'appeal_status' => $appeal->status
            ]);
    
            // Hanya guru kelas yang bisa update
            if ($kelas->id_guru !== Auth::id()) {
                \Log::warning('Unauthorized access attempt', [
                    'user_id' => Auth::id(),
                    'kelas_guru' => $kelas->id_guru
                ]);
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized action.'
                    ], 403);
                }
                abort(403, 'Unauthorized action.');
            }
    
            // Validasi request
            $request->validate([
                'nilai_baru' => 'required|integer|min:0|max:100',
                'catatan_guru' => 'required|string|max:500',
            ]);
    
            \Log::info('Validation passed');
    
            // Update nilai
            $nilai = $appeal->pengumpulan->nilai;
            
            if (!$nilai) {
                \Log::error('Nilai not found for pengumpulan:', [
                    'pengumpulan_id' => $appeal->id_pengumpulan
                ]);
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Nilai tidak ditemukan untuk pengumpulan ini.'
                    ], 404);
                }
                return redirect()->back()->with('error', 'Nilai tidak ditemukan untuk pengumpulan ini.');
            }
    
            \Log::info('Updating nilai', [
                'nilai_lama' => $nilai->nilai,
                'nilai_baru' => $request->nilai_baru
            ]);
    
            // Update nilai
            $nilai->update([
                'nilai' => $request->nilai_baru,
                'komentar_guru' => $request->catatan_guru . "\n\n[Grade revised by teacher after appeal]",
                'auto_graded' => false, // Tandai sudah direvisi guru
            ]);
    
            \Log::info('Nilai updated successfully');
    
            // Update status appeal
            $appeal->update([
                'status' => 'approved',
                'catatan_guru' => $request->catatan_guru,
            ]);
    
            \Log::info('Appeal status updated to approved');
    
            // Notifikasi ke siswa
            $this->notifyAppealDisetujui($appeal);
    
            \Log::info('=== UPDATE GRADE APPEAL END - SUCCESS ===');
    
            // Return JSON response untuk AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Nilai berhasil diperbarui! Siswa telah diberitahu.'
                ]);
            }
    
            return redirect()->back()
                ->with('success', 'Nilai berhasil diperbarui! Siswa telah diberitahu.');
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in updateGrade:', ['errors' => $e->errors()]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            \Log::error('Error in AppealController@updateGrade:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
    
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function rejectAppeal(Request $request, $kelasId, $tugasId, $appealId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $tugas = Tugas::findOrFail($tugasId);
        $appeal = Appeal::findOrFail($appealId);

        // Hanya guru kelas yang bisa reject
        if ($kelas->id_guru !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        // Update status appeal
        $appeal->update([
            'status' => 'rejected',
            'catatan_guru' => $request->alasan_penolakan,
        ]);

        // Notifikasi ke siswa
        $this->notifyAppealDitolak($appeal);

        return redirect()->back()
            ->with('success', 'Appeal telah ditolak. Siswa telah diberitahu.');
    }

    public function getPendingAppeals()
    {
        $user = Auth::user();
        
        if ($user->isGuru()) {
            $appeals = Appeal::whereHas('pengumpulan.tugas.kelas', function($query) use ($user) {
                $query->where('id_guru', $user->id_user);
            })
            ->with(['pengumpulan.user', 'pengumpulan.tugas', 'pengumpulan.nilai'])
            ->where('status', 'pending')
            ->latest()
            ->get();
            
            return response()->json($appeals);
        }
        
        return response()->json([]);
    }

    // Helper methods untuk notifikasi
    private function notifyAppealDiajukan($pengumpulan, $alasan)
    {
        // Gunakan NotifikasiService yang sudah ada
        \App\Services\NotifikasiService::createNotifikasi(
            $pengumpulan->tugas->kelas->id_guru,
            'appeal_diajukan',
            'Appeal Diajukan',
            "{$pengumpulan->user->nama} mengajukan appeal untuk tugas '{$pengumpulan->tugas->judul}'",
            route('kelas.tugas.show', [$pengumpulan->tugas->kelas->id_kelas, $pengumpulan->tugas->id_tugas]),
            [
                'kelas_id' => $pengumpulan->tugas->kelas->id_kelas,
                'tugas_id' => $pengumpulan->tugas->id_tugas,
                'siswa_id' => $pengumpulan->id_user,
                'siswa_nama' => $pengumpulan->user->nama,
                'alasan_banding' => $alasan
            ]
        );
    }

    private function notifyAppealDisetujui($appeal)
    {
        \App\Services\NotifikasiService::createNotifikasi(
            $appeal->pengumpulan->id_user,
            'appeal_disetujui',
            'Appeal Disetujui',
            "Appeal Anda untuk tugas '{$appeal->pengumpulan->tugas->judul}' telah disetujui. Nilai diperbarui: {$appeal->pengumpulan->nilai->nilai}/100",
            route('kelas.tugas.show', [$appeal->pengumpulan->tugas->kelas->id_kelas, $appeal->pengumpulan->tugas->id_tugas]),
            [
                'kelas_id' => $appeal->pengumpulan->tugas->kelas->id_kelas,
                'tugas_id' => $appeal->pengumpulan->tugas->id_tugas,
                'nilai_baru' => $appeal->pengumpulan->nilai->nilai,
                'catatan_guru' => $appeal->catatan_guru
            ]
        );
    }

    private function notifyAppealDitolak($appeal)
    {
        \App\Services\NotifikasiService::createNotifikasi(
            $appeal->pengumpulan->id_user,
            'appeal_ditolak',
            'Appeal Ditolak',
            "Appeal Anda untuk tugas '{$appeal->pengumpulan->tugas->judul}' telah ditolak.",
            route('kelas.tugas.show', [$appeal->pengumpulan->tugas->kelas->id_kelas, $appeal->pengumpulan->tugas->id_tugas]),
            [
                'kelas_id' => $appeal->pengumpulan->tugas->kelas->id_kelas,
                'tugas_id' => $appeal->pengumpulan->tugas->id_tugas,
                'alasan_penolakan' => $appeal->catatan_guru
            ]
        );
    }
}