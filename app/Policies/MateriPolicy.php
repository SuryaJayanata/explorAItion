<?php

namespace App\Policies;

use App\Models\Materi;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Auth\Access\Response;

class MateriPolicy
{
    public function view(User $user, Materi $materi)
    {
        // Guru dapat melihat materi yang mereka buat
        if ($user->isGuru() && $materi->kelas->id_guru === $user->id_user) {
            return true;
        }
        
        // Siswa dapat melihat materi di kelas yang mereka ikuti
        if ($user->isSiswa() && $materi->kelas->siswa->contains('id_user', $user->id_user)) {
            return true;
        }
        
        return false;
    }

    public function create(User $user, Kelas $kelas)
    {
        return $user->isGuru() && $kelas->id_guru === $user->id_user;
    }

    public function update(User $user, Materi $materi)
    {
        return $user->isGuru() && $materi->kelas->id_guru === $user->id_user;
    }

    public function delete(User $user, Materi $materi)
    {
        return $user->isGuru() && $materi->kelas->id_guru === $user->id_user;
    }
}