<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\NotifikasiService;

class KelasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isGuru()) {
            $kelas = $user->kelasDiajar()->withCount('anggota')->get();
        } else {
            $kelas = $user->anggotaKelas()->with('kelas')->get()->pluck('kelas');
        }
        
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'deskripsi' => $request->deskripsi,
            'kode_kelas' => Str::random(8),
            'id_guru' => Auth::id(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Kelas berhasil dibuat! Kode Kelas: ' . $kelas->kode_kelas);
    }

    public function show($id_kelas)
    {
        // Eager load all necessary relationships
        $kelas = Kelas::with([
            'guru',
            'anggota.user', 
            'materi',
            'tugas'
        ])->findOrFail($id_kelas);
        
        // Get class members separately for the members section
        $anggota = AnggotaKelas::with('user')
            ->where('id_kelas', $id_kelas)
            ->get();
        
        // Check if user is member of this class
        $isMember = $anggota->contains('id_user', auth()->id());
        
        if (!$isMember && auth()->user()->id_user !== $kelas->id_guru) {
            abort(403, 'Anda bukan anggota kelas ini.');
        }
        
        return view('kelas.show', compact('kelas', 'anggota'));
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        // $this->authorize('update', $kelas);
        
        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        // $this->authorize('update', $kelas);
        
        $request->validate([
            'nama_kelas' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('kelas.show', $kelas->id_kelas)
            ->with('success', 'Kelas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        // $this->authorize('delete', $kelas);
        
        $kelas->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Kelas berhasil dihapus!');
    }

    public function join(Request $request)
    {
        $request->validate([
            'kode_kelas' => 'required|string|exists:kelas,kode_kelas',
        ]);

        $kelas = Kelas::where('kode_kelas', $request->kode_kelas)->first();
        
        // Cek apakah user sudah bergabung dengan kelas
        $isAlreadyMember = AnggotaKelas::where('id_user', Auth::id())
            ->where('id_kelas', $kelas->id_kelas)
            ->exists();
            
        if ($isAlreadyMember) {
            return redirect()->back()->with('error', 'Anda sudah bergabung dengan kelas ini.');
        }
        
        AnggotaKelas::create([
            'id_user' => Auth::id(),
            'id_kelas' => $kelas->id_kelas,
        ]);

        // Trigger notifikasi siswa bergabung
        NotifikasiService::notifySiswaBergabung($kelas, Auth::user());

        return redirect()->route('kelas.show', $kelas->id_kelas)
            ->with('success', 'Berhasil bergabung dengan kelas!');
    }

    // Method untuk leave class
    public function leave($id)
    {
        $kelas = Kelas::findOrFail($id);
        
        // Hapus anggota kelas
        AnggotaKelas::where('id_user', Auth::id())
            ->where('id_kelas', $id)
            ->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Anda telah keluar dari kelas ' . $kelas->nama_kelas);
    }
}