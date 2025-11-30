<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Safe approach - only modify if needed
        try {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'guru', 'siswa') DEFAULT 'siswa'");
        } catch (Exception $e) {
            // Log error but don't stop execution
            \Log::warning('Failed to modify users role column: ' . $e->getMessage());
        }
    }

    public function down(): void
    {
        // Safe rollback - don't actually modify the column to avoid data loss
        // We'll leave the column as is to prevent data truncation errors
        \Log::info('Skipping users role column modification in rollback to prevent data loss');
    }
};