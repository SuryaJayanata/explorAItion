<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotifikasiService;

class NilaiController extends Controller
{
    public function store(Request $request, $kelasId, $tugasId, $pengumpulanId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $tugas = Tugas::findOrFail($tugasId);
        $pengumpulan = PengumpulanTugas::findOrFail($pengumpulanId);
        
        // Authorization - hanya guru kelas yang bisa beri nilai
        if ($kelas->id_guru !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'nilai' => 'required|integer|min:0|max:100',
            'komentar_guru' => 'nullable|string|max:500',
        ]);

        // Cek apakah sudah ada nilai
        $existingNilai = Nilai::where('id_pengumpulan', $pengumpulanId)->first();

        if ($existingNilai) {
            // Update nilai yang sudah ada
            $existingNilai->update([
                'nilai' => $request->nilai,
                'komentar_guru' => $request->komentar_guru,
            ]);
        } else {
            // Buat nilai baru
            Nilai::create([
                'id_pengumpulan' => $pengumpulanId,
                'nilai' => $request->nilai,
                'komentar_guru' => $request->komentar_guru,
            ]);
        }

        // Trigger notifikasi nilai diberikan
        NotifikasiService::notifyNilaiDiberikan($pengumpulan);

        // Return JSON response untuk AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Grade submitted successfully!'
            ]);
        }

        return redirect()->route('kelas.tugas.show', [$kelasId, $tugasId])
            ->with('success', 'Grade submitted successfully!');
    }
}