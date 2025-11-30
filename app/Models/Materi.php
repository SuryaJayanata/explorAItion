<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $casts = [
        'flashcards' => 'array',
        'summary_generated_at' => 'datetime', // tambahkan ini
    ];
    
    // Tambahkan method untuk handle summary_generated_at
    public function setSummaryAttribute($value)
    {
        $this->attributes['summary'] = $value;
        
        // Set summary_generated_at ketika summary di-set
        if (!empty($value) && trim($value) !== '') {
            $this->attributes['summary_generated_at'] = now();
        }
    }
    use HasFactory;

    protected $primaryKey = 'id_materi';
    public $incrementing = true;

    protected $table = 'materi';

    protected $fillable = [
        'judul',
        'deskripsi',
        'file_materi',
        'id_kelas',
        'summary',
        'flashcards',
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

    // Method untuk clean flashcards data sebelum save
    public function setFlashcardsAttribute($value)
    {
        if (is_array($value)) {
            // Clean setiap item dalam array
            $cleanedValue = array_map(function($item) {
                if (is_array($item)) {
                    return array_map([$this, 'cleanUtf8'], $item);
                }
                return $this->cleanUtf8($item);
            }, $value);
            
            $this->attributes['flashcards'] = json_encode($cleanedValue, JSON_UNESCAPED_UNICODE);
        } else {
            $this->attributes['flashcards'] = $value;
        }
    }

    // Method untuk clean UTF-8 characters
    private function cleanUtf8($string)
    {
        if (is_string($string)) {
            // Remove invalid UTF-8 characters
            $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
            // Remove any remaining invalid characters
            $string = preg_replace('/[^\x{0000}-\x{FFFF}]/u', '', $string);
            return $string;
        }
        return $string;
    }

    // Method untuk get flashcards dengan default empty array
    public function getFlashcardsAttribute($value)
    {
        if (empty($value)) {
            return [];
        }
        
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    // ========== TAMBAHKAN METHOD BERIKUT ==========

    /**
     * Check if materi has summary
     */
    public function hasSummary()
    {
        return !empty($this->summary) && trim($this->summary) !== '';
    }

    /**
     * Check if materi has flashcards
     */
    public function hasFlashcards()
    {
        return !empty($this->flashcards) && is_array($this->flashcards) && count($this->flashcards) > 0;
    }

    /**
     * Get summary preview (first 200 characters)
     */
    public function getSummaryPreview()
    {
        if (!$this->hasSummary()) {
            return null;
        }
        
        return strlen($this->summary) > 200 
            ? substr($this->summary, 0, 200) . '...' 
            : $this->summary;
    }

    /**
     * Get formatted summary with line breaks
     */
    public function getFormattedSummary()
    {
        if (!$this->hasSummary()) {
            return null;
        }
        
        return nl2br(e($this->summary));
    }

    /**
     * Get flashcards count
     */
    public function getFlashcardsCount()
    {
        if (!$this->hasFlashcards()) {
            return 0;
        }
        
        return count($this->flashcards);
    }

    /**
     * Check if user can generate summary (only teacher)
     */
    public function canGenerateSummary($user)
    {
        return $user->isGuru() && $this->kelas->id_guru == $user->id_user;
    }

    /**
     * Check if materi has file
     */
    public function hasFile()
    {
        return !empty($this->file_materi);
    }

    /**
     * Get file extension
     */
    public function getFileExtension()
    {
        if (!$this->hasFile()) {
            return null;
        }
        
        return pathinfo($this->file_materi, PATHINFO_EXTENSION);
    }

    /**
     * Check if file is PDF
     */
    public function isPdf()
    {
        return $this->getFileExtension() === 'pdf';
    }

    /**
     * Check if file is text
     */
    public function isText()
    {
        return in_array($this->getFileExtension(), ['txt', 'doc', 'docx']);
    }
}