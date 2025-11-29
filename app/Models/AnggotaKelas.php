<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaKelas extends Model
{
    use HasFactory;

    protected $table = 'anggota_kelas';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'id_user',
        'id_kelas',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }
}