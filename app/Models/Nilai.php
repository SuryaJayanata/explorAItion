<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_nilai';
    public $incrementing = true;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'nilai';

    protected $fillable = [
        'id_pengumpulan',
        'nilai',
        'komentar_guru',
    ];

    public function pengumpulan()
    {
        return $this->belongsTo(PengumpulanTugas::class, 'id_pengumpulan', 'id_pengumpulan');
    }
}