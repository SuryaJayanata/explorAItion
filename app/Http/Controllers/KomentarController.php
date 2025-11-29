<?php

namespace App\Http\Controllers;

use App\Models\Komentar;
use App\Models\Tugas;
use App\Models\Materi;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotifikasiService;

class KomentarController extends Controller
{
    // Store komentar untuk tugas
    public function store(Request $request, $kelasId, $tugasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $tugas = Tugas::findOrFail($tugasId);
        
        $isMember = $kelas->anggota()->where('id_user', Auth::id())->exists();
        $isGuru = $kelas->id_guru == Auth::id();
        
        if (!$isMember && !$isGuru) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan anggota kelas ini.'
            ], 403);
        }

        $request->validate([
            'isi' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:komentar,id_komentar',
        ]);

        $komentar = Komentar::create([
            'id_user' => Auth::id(),
            'tipe' => 'tugas',
            'id_target' => $tugasId,
            'isi' => $request->isi,
            'parent_id' => $request->parent_id,
        ]);

        $komentar->load('user', 'replies.user');

        // Format data untuk response
        $komentarData = [
            'id_komentar' => $komentar->id_komentar,
            'isi' => $komentar->isi,
            'created_at' => $komentar->created_at->format('d M Y H:i'),
            'created_at_human' => $komentar->created_at->diffForHumans(),
            'user' => [
                'id_user' => $komentar->user->id_user,
                'nama' => $komentar->user->nama,
                'avatar' => $komentar->user->avatar ? asset('storage/' . $komentar->user->avatar) : null,
                'is_guru' => $komentar->user->id_user == $tugas->kelas->id_guru,
                'initial' => strtoupper(substr($komentar->user->nama, 0, 1))
            ],
            'parent_id' => $komentar->parent_id,
            'has_replies' => $komentar->hasReplies(),
            'replies_count' => $komentar->replies->count()
        ];

        // Trigger notifikasi komentar baru
        NotifikasiService::notifyKomentarBaru($komentar, $tugas, 'tugas');

        return response()->json([
            'success' => true,
            'komentar' => $komentarData,
            'is_reply' => !is_null($request->parent_id),
            'parent_id' => $request->parent_id,
            'message' => 'Komentar berhasil ditambahkan!'
        ]);
    }

    // Store komentar untuk materi
    public function storeMateri(Request $request, $kelasId, $materiId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $materi = Materi::findOrFail($materiId);
        
        $isMember = $kelas->anggota()->where('id_user', Auth::id())->exists();
        $isGuru = $kelas->id_guru == Auth::id();
        
        if (!$isMember && !$isGuru) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan anggota kelas ini.'
            ], 403);
        }

        $request->validate([
            'isi' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:komentar,id_komentar',
        ]);

        $komentar = Komentar::create([
            'id_user' => Auth::id(),
            'tipe' => 'materi',
            'id_target' => $materiId,
            'isi' => $request->isi,
            'parent_id' => $request->parent_id,
        ]);

        $komentar->load('user', 'replies.user');

        // Format data untuk response
        $komentarData = [
            'id_komentar' => $komentar->id_komentar,
            'isi' => $komentar->isi,
            'created_at' => $komentar->created_at->format('d M Y H:i'),
            'created_at_human' => $komentar->created_at->diffForHumans(),
            'user' => [
                'id_user' => $komentar->user->id_user,
                'nama' => $komentar->user->nama,
                'avatar' => $komentar->user->avatar ? asset('storage/' . $komentar->user->avatar) : null,
                'is_guru' => $komentar->user->id_user == $materi->kelas->id_guru,
                'initial' => strtoupper(substr($komentar->user->nama, 0, 1))
            ],
            'parent_id' => $komentar->parent_id,
            'has_replies' => $komentar->hasReplies(),
            'replies_count' => $komentar->replies->count()
        ];

        // Trigger notifikasi komentar baru
        NotifikasiService::notifyKomentarBaru($komentar, $materi, 'materi');

        return response()->json([
            'success' => true,
            'komentar' => $komentarData,
            'is_reply' => !is_null($request->parent_id),
            'parent_id' => $request->parent_id,
            'message' => 'Komentar berhasil ditambahkan!'
        ]);
    }

    // Destroy komentar untuk tugas
    public function destroy($kelasId, $tugasId, $komentarId)
    {
        $komentar = Komentar::findOrFail($komentarId);
        
        if ($komentar->id_user !== Auth::id() && !Auth::user()->isGuru()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $komentar->delete();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus!'
        ]);
    }

    // Destroy komentar untuk materi
    public function destroyMateri($kelasId, $materiId, $komentarId)
    {
        $komentar = Komentar::findOrFail($komentarId);
        
        if ($komentar->id_user !== Auth::id() && !Auth::user()->isGuru()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $komentar->delete();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus!'
        ]);
    }

    // Get komentar untuk AJAX (opsional, untuk load lebih banyak komentar)
    public function getKomentar($tipe, $targetId)
    {
        $komentar = Komentar::where('tipe', $tipe)
            ->where('id_target', $targetId)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $formattedKomentar = $komentar->map(function($komen) {
            return [
                'id_komentar' => $komen->id_komentar,
                'isi' => $komen->isi,
                'created_at' => $komen->created_at->format('d M Y H:i'),
                'created_at_human' => $komen->created_at->diffForHumans(),
                'user' => [
                    'id_user' => $komen->user->id_user,
                    'nama' => $komen->user->nama,
                    'avatar' => $komen->user->avatar ? asset('storage/' . $komen->user->avatar) : null,
                    'is_guru' => $komen->user->isGuru(),
                    'initial' => strtoupper(substr($komen->user->nama, 0, 1))
                ],
                'parent_id' => $komen->parent_id,
                'has_replies' => $komen->hasReplies(),
                'replies_count' => $komen->replies->count(),
                'replies' => $komen->replies->map(function($reply) {
                    return [
                        'id_komentar' => $reply->id_komentar,
                        'isi' => $reply->isi,
                        'created_at' => $reply->created_at->format('d M Y H:i'),
                        'created_at_human' => $reply->created_at->diffForHumans(),
                        'user' => [
                            'id_user' => $reply->user->id_user,
                            'nama' => $reply->user->nama,
                            'avatar' => $reply->user->avatar ? asset('storage/' . $reply->user->avatar) : null,
                            'is_guru' => $reply->user->isGuru(),
                            'initial' => strtoupper(substr($reply->user->nama, 0, 1))
                        ],
                        'parent_id' => $reply->parent_id,
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'komentar' => $formattedKomentar
        ]);
    }
}