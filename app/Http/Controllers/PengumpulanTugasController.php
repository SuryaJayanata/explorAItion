<?php

namespace App\Http\Controllers;

use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\NotifikasiService;

class PengumpulanTugasController extends Controller
{
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

        // Trigger notifikasi tugas dikumpulkan
        NotifikasiService::notifyTugasDikumpulkan($pengumpulan);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil dikumpulkan!'
            ]);
        }

        return redirect()->route('kelas.tugas.show', [$kelasId, $tugasId])
            ->with('success', 'Tugas berhasil dikumpulkan!');
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

        // Cek apakah sudah dinilai - TAMBAHKAN VALIDASI INI
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

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengumpulan tugas berhasil diperbarui!'
            ]);
        }

        return redirect()->route('kelas.tugas.show', [$kelasId, $tugasId])
            ->with('success', 'Pengumpulan tugas berhasil diperbarui!');
    }

    public function destroy($kelasId, $tugasId, $pengumpulanId)
    {
        $pengumpulan = PengumpulanTugas::findOrFail($pengumpulanId);
        $tugas = Tugas::findOrFail($tugasId);
        
        // Authorization - hanya pemilik yang bisa hapus
        if ($pengumpulan->id_user !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Cek deadline - TAMBAHKAN INI
        if (Carbon::now()->greaterThan($tugas->deadline)) {
            return redirect()->back()
                ->with('error', 'Tidak bisa menghapus pengumpulan karena sudah melewati deadline.');
        }

        // Hapus file
        if ($pengumpulan->file_jawaban) {
            Storage::disk('public')->delete($pengumpulan->file_jawaban);
        }

        $pengumpulan->delete();

        return redirect()->route('kelas.tugas.show', [$kelasId, $tugasId])
            ->with('success', 'Pengumpulan tugas berhasil dihapus!');
    }
}