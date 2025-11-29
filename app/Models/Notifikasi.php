<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_notifikasi';
    public $incrementing = true;

    protected $table = 'notifications';

    protected $fillable = [
        'id_user',
        'tipe',
        'judul',
        'pesan',
        'tautan',
        'dibaca',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'dibaca' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Scope untuk notifikasi yang belum dibaca
    public function scopeUnread($query)
    {
        return $query->where('dibaca', false);
    }

    // Scope untuk notifikasi terbaru
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}