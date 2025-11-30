<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            // Cek dulu apakah kolom sudah ada sebelum menambahkan
            if (!Schema::hasColumn('tugas', 'kunci_jawaban_file')) {
                $table->string('kunci_jawaban_file')->nullable()->after('deadline');
            }
            
            if (!Schema::hasColumn('tugas', 'kunci_jawaban_text')) {
                $table->text('kunci_jawaban_text')->nullable()->after('kunci_jawaban_file');
            }
            
            if (!Schema::hasColumn('tugas', 'auto_grading')) {
                $table->boolean('auto_grading')->default(false)->after('kunci_jawaban_text');
            }
            
            if (!Schema::hasColumn('tugas', 'passing_grade')) {
                $table->decimal('passing_grade', 5, 2)->default(70.00)->after('auto_grading');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            // Hanya drop kolom jika mereka ada
            $columnsToDrop = [];
            
            if (Schema::hasColumn('tugas', 'kunci_jawaban_file')) {
                $columnsToDrop[] = 'kunci_jawaban_file';
            }
            
            if (Schema::hasColumn('tugas', 'kunci_jawaban_text')) {
                $columnsToDrop[] = 'kunci_jawaban_text';
            }
            
            if (Schema::hasColumn('tugas', 'auto_grading')) {
                $columnsToDrop[] = 'auto_grading';
            }
            
            if (Schema::hasColumn('tugas', 'passing_grade')) {
                $columnsToDrop[] = 'passing_grade';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};