<?php

namespace App\Policies;

use App\Models\Tugas;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Auth\Access\Response;

class TugasPolicy
{
    public function view(User $user, Tugas $tugas)
    {
        // Guru dapat melihat tugas yang mereka buat
        if ($user->isGuru() && $tugas->kelas->id_guru === $user->id_user) {
            return true;
        }
        
        // Siswa dapat melihat tugas di kelas yang mereka ikuti
        if ($user->isSiswa() && $tugas->kelas->siswa->contains('id_user', $user->id_user)) {
            return true;
        }
        
        return false;
    }

    public function create(User $user, Kelas $kelas)
    {
        return $user->isGuru() && $kelas->id_guru === $user->id_user;
    }

    public function update(User $user, Tugas $tugas)
    {
        return $user->isGuru() && $tugas->kelas->id_guru === $user->id_user;
    }

    public function delete(User $user, Tugas $tugas)
    {
        return $user->isGuru() && $tugas->kelas->id_guru === $user->id_user;
    }
}