<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_kelas';
    public $incrementing = true;

    protected $fillable = [
        'nama_kelas',
        'deskripsi',
        'kode_kelas',
        'id_guru',
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'id_guru', 'id_user');
    }

    public function anggota()
    {
        return $this->hasMany(AnggotaKelas::class, 'id_kelas', 'id_kelas');
    }

    public function siswa()
    {
        return $this->belongsToMany(User::class, 'anggota_kelas', 'id_kelas', 'id_user');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class, 'id_kelas', 'id_kelas');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'id_kelas', 'id_kelas');
    }
}