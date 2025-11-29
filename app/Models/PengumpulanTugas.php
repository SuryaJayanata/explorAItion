<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengumpulanTugas extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pengumpulan';
    public $incrementing = true;

    protected $table = 'pengumpulan_tugas';

    protected $fillable = [
        'id_user',
        'id_tugas',
        'file_jawaban',
    ];

    // Tambahkan accessor untuk cek apakah sudah dinilai
    protected $appends = ['sudah_dinilai'];

    public function getSudahDinilaiAttribute()
    {
        return !is_null($this->nilai);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'id_tugas', 'id_tugas');
    }

    public function nilai()
    {
        return $this->hasOne(Nilai::class, 'id_pengumpulan', 'id_pengumpulan');
    }
}