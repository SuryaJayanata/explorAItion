<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotifikasiService;

class TugasController extends Controller
{
    public function create($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $this->authorize('create', [Tugas::class, $kelas]);
        
        return view('tugas.create', compact('kelas'));
    }

    public function store(Request $request, $kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $this->authorize('create', [Tugas::class, $kelas]);
        
        $request->validate([
            'judul' => 'required|string|max:150',
            'deskripsi' => 'required|string',
            'deadline' => 'required|date',
        ]);

        $tugas = Tugas::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'deadline' => $request->deadline,
            'id_kelas' => $kelasId,
        ]);

        // Trigger notifikasi tugas baru
        NotifikasiService::notifyTugasBaru($kelas, $tugas);

        return redirect()->route('kelas.show', $kelasId)
            ->with('success', 'Assignment created successfully!');
    }

    public function show($kelasId, $tugasId)
    {
        $kelas = Kelas::with(['anggota.user'])->findOrFail($kelasId);
        $tugas = Tugas::with([
            'kelas', 
            'komentar.user', 
            'komentar.replies.user',
            'pengumpulan.user',
            'pengumpulan.nilai'
        ])->findOrFail($tugasId);
        
        $this->authorize('view', $tugas);
        
        $semuaSiswa = $kelas->anggota->where('user.id_user', '!=', $kelas->id_guru);
        
        $pengumpulanSaya = null;
        if (Auth::user()->isSiswa()) {
            $pengumpulanSaya = $tugas->pengumpulan->where('id_user', Auth::id())->first();
        }
        
        return view('tugas.show', compact('kelas', 'tugas', 'pengumpulanSaya', 'semuaSiswa'));
    }

    public function edit($kelasId, $tugasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $tugas = Tugas::findOrFail($tugasId);
        $this->authorize('update', $tugas);
        
        return view('tugas.edit', compact('kelas', 'tugas'));
    }

    public function update(Request $request, $kelasId, $tugasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $tugas = Tugas::findOrFail($tugasId);
        $this->authorize('update', $tugas);
        
        $request->validate([
            'judul' => 'required|string|max:150',
            'deskripsi' => 'required|string',
            'deadline' => 'required|date',
        ]);

        $tugas->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'deadline' => $request->deadline,
        ]);

        return redirect()->route('kelas.tugas.show', [$kelasId, $tugasId])
            ->with('success', 'Assignment updated successfully!');
    }

    public function destroy($kelasId, $tugasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $tugas = Tugas::findOrFail($tugasId);
        $this->authorize('delete', $tugas);
        
        $tugas->delete();

        return redirect()->route('kelas.show', $kelasId)
            ->with('success', 'Assignment deleted successfully!');
    }

    public function indexAll()
    {
        $user = Auth::user();
        
        if ($user->isGuru()) {
            // Guru melihat semua tugas yang mereka buat di semua kelas
            $assignments = Tugas::whereHas('kelas', function($query) use ($user) {
                $query->where('id_guru', $user->id_user);
            })->with('kelas')->latest()->paginate(12);
        } else {
            // Siswa melihat semua tugas dari kelas yang mereka ikuti
            $assignments = Tugas::whereHas('kelas.anggota', function($query) use ($user) {
                $query->where('id_user', $user->id_user);
            })->with('kelas')->latest()->paginate(12);
        }
        
        return view('assignments.index', compact('assignments'));
    }

    public function showAll($tugasId)
    {
        $tugas = Tugas::with([
            'kelas', 
            'komentar.user', 
            'komentar.replies.user',
            'pengumpulan.user',
            'pengumpulan.nilai'
        ])->findOrFail($tugasId);
        
        $this->authorize('view', $tugas);
        
        $pengumpulanSaya = null;
        if (Auth::user()->isSiswa()) {
            $pengumpulanSaya = $tugas->pengumpulan->where('id_user', Auth::id())->first();
        }
        
        return view('assignments.show', compact('tugas', 'pengumpulanSaya'));
    }

    // AJAX Search Assignments
    public function search(Request $request)
    {
        $query = $request->get('query');
        $user = Auth::user();
        
        // Debug: Check if user is authenticated and query is received
        \Log::info('Assignment Search request:', [
            'user_id' => $user->id,
            'user_type' => $user->isGuru() ? 'guru' : 'siswa',
            'query' => $query
        ]);

        $assignmentsQuery = Tugas::query();

        if ($user->isGuru()) {
            // Guru melihat semua tugas yang mereka buat
            $assignmentsQuery->whereHas('kelas', function($q) use ($user) {
                $q->where('id_guru', $user->id_user);
            });
        } else {
            // Siswa melihat tugas dari kelas yang mereka ikuti
            $assignmentsQuery->whereHas('kelas.anggota', function($q) use ($user) {
                $q->where('id_user', $user->id_user);
            });
        }

        // Apply search filter jika ada query
        if ($query && strlen($query) >= 2) {
            $assignmentsQuery->where(function($q) use ($query) {
                $q->where('judul', 'like', "%{$query}%")
                  ->orWhere('deskripsi', 'like', "%{$query}%")
                  ->orWhereHas('kelas', function($q2) use ($query) {
                      $q2->where('nama_kelas', 'like', "%{$query}%");
                  });
            });
        }

        $assignments = $assignmentsQuery->with('kelas')
            ->latest()
            ->paginate(12);

        // Debug: Check results
        \Log::info('Assignment Search results:', [
            'total' => $assignments->total(),
            'count' => $assignments->count(),
            'query' => $query
        ]);

        if ($request->ajax()) {
            $view = view('assignments.partials.assignments-grid', compact('assignments'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $view,
                'has_more' => $assignments->hasMorePages(),
                'total' => $assignments->total(),
                'count' => $assignments->count(),
                'query' => $query
            ]);
        }

        return view('assignments.index', compact('assignments'));
    }

    // Load More Assignments
    public function loadMore(Request $request)
    {
        $user = Auth::user();
        $page = $request->get('page', 2);
        $query = $request->get('query', '');
        
        $assignmentsQuery = Tugas::query();

        if ($user->isGuru()) {
            $assignmentsQuery->whereHas('kelas', function($q) use ($user) {
                $q->where('id_guru', $user->id_user);
            });
        } else {
            $assignmentsQuery->whereHas('kelas.anggota', function($q) use ($user) {
                $q->where('id_user', $user->id_user);
            });
        }

        // Apply search filter jika ada query
        if ($query && strlen($query) >= 2) {
            $assignmentsQuery->where(function($q) use ($query) {
                $q->where('judul', 'like', "%{$query}%")
                  ->orWhere('deskripsi', 'like', "%{$query}%")
                  ->orWhereHas('kelas', function($q2) use ($query) {
                      $q2->where('nama_kelas', 'like', "%{$query}%");
                  });
            });
        }

        $assignments = $assignmentsQuery->with('kelas')
            ->latest()
            ->paginate(12, ['*'], 'page', $page);

        if ($assignments->count() > 0) {
            $view = view('assignments.partials.assignments-grid', compact('assignments'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $view,
                'has_more' => $assignments->hasMorePages(),
                'count' => $assignments->count()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No more assignments found'
        ]);
    }
}