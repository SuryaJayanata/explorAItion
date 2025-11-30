<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appeal extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_appeal';
    public $incrementing = true;

    protected $table = 'appeals';

    protected $fillable = [
        'id_pengumpulan',
        'alasan_banding',
        'catatan_guru',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function pengumpulan()
    {
        return $this->belongsTo(PengumpulanTugas::class, 'id_pengumpulan', 'id_pengumpulan');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}