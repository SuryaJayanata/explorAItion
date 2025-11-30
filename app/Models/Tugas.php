<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tugas';
    public $incrementing = true;

    protected $table = 'tugas';

    protected $fillable = [
        'judul',
        'deskripsi',
        'deadline',
        'id_kelas',
        'kunci_jawaban_file',
        'kunci_jawaban_text',
        'auto_grading',
        'passing_grade',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'auto_grading' => 'boolean',
        'passing_grade' => 'decimal:2',
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

    // Method untuk cek apakah tugas menggunakan auto grading
    public function usesAutoGrading()
    {
        return $this->auto_grading && ($this->kunci_jawaban_file || $this->kunci_jawaban_text);
    }
}