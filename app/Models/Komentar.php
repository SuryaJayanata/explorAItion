<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_komentar';
    public $incrementing = true;

    protected $table = 'komentar';

    protected $fillable = [
        'id_user',
        'tipe',
        'id_target',
        'isi',
        'parent_id', // Tambahkan ini
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke komentar parent
    public function parent()
    {
        return $this->belongsTo(Komentar::class, 'parent_id', 'id_komentar');
    }

    // Relasi ke replies
    public function replies()
    {
        return $this->hasMany(Komentar::class, 'parent_id', 'id_komentar')
                    ->with('user', 'replies.user') // Eager load nested replies
                    ->orderBy('created_at', 'asc');
    }

    // Relasi ke Materi
    public function materi()
    {
        return $this->belongsTo(Materi::class, 'id_target', 'id_materi')
                    ->where('tipe', 'materi');
    }

    // Relasi ke Tugas
    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'id_target', 'id_tugas')
                    ->where('tipe', 'tugas');
    }

    // Cek apakah komentar adalah reply
    public function isReply()
    {
        return !is_null($this->parent_id);
    }

    // Cek apakah komentar memiliki replies
    public function hasReplies()
    {
        return $this->replies->count() > 0;
    }
}