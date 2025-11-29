<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tugas';
    public $incrementing = true;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'tugas';

    protected $fillable = [
        'judul',
        'deskripsi',
        'deadline',
        'id_kelas',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function pengumpulan()
    {
        return $this->hasMany(PengumpulanTugas::class, 'id_tugas', 'id_tugas');
    }

    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'id_target', 'id_tugas')
                    ->where('tipe', 'tugas');
    }
    public function isDeadlinePassed()
{
    return now()->greaterThan($this->deadline);
}

public function isDeadlineNear()
{
    return now()->addDays(1)->greaterThan($this->deadline) && !$this->isDeadlinePassed();
}
}