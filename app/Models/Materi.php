<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';
    protected $primaryKey = 'id_materi';
    public $incrementing = true;

    protected $fillable = [
        'judul',
        'deskripsi',
        'file',
        'summary',
        'flashcards', // Tambahkan ini
        'summary_generated_at',
        'id_kelas',
    ];

    protected $casts = [
        'summary_generated_at' => 'datetime',
        'flashcards' => 'array', // Cast ke array
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'id_target', 'id_materi')
                    ->where('tipe', 'materi');
    }

    public function hasSummary()
    {
        return !empty($this->summary);
    }

    public function getFormattedSummary()
    {
        if (!$this->hasSummary()) {
            return null;
        }

        return nl2br(e($this->summary));
    }

    // Cek apakah memiliki flashcards
    public function hasFlashcards()
    {
        return !empty($this->flashcards) && is_array($this->flashcards) && count($this->flashcards) > 0;
    }
}