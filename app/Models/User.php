<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_user';
    public $incrementing = true;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function kelasDiajar()
    {
        return $this->hasMany(Kelas::class, 'id_guru', 'id_user');
    }

    public function anggotaKelas()
    {
        return $this->hasMany(AnggotaKelas::class, 'id_user', 'id_user');
    }

    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'id_user', 'id_user');
    }

    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class, 'id_user', 'id_user');
    }

    public function isGuru()
    {
        return $this->role === 'guru';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function notifications()
    {
        return $this->hasMany(Notifikasi::class, 'id_user', 'id_user');
    }
}