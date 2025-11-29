<?php

namespace App\Policies;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KelasPolicy
{
    public function view(User $user, Kelas $kelas)
    {
        // Guru dapat melihat kelas yang mereka buat
        if ($user->isGuru() && $kelas->id_guru === $user->id_user) {
            return true;
        }
        
        // Siswa dapat melihat kelas yang mereka ikuti
        if ($user->isSiswa() && $kelas->siswa->contains('id_user', $user->id_user)) {
            return true;
        }
        
        return false;
    }

    public function create(User $user)
    {
        return $user->isGuru();
    }

    public function update(User $user, Kelas $kelas)
    {
        return $user->isGuru() && $kelas->id_guru === $user->id_user;
    }

    public function delete(User $user, Kelas $kelas)
    {
        return $user->isGuru() && $kelas->id_guru === $user->id_user;
    }
}