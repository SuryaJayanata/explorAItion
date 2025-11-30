<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('appeals')) {
            Schema::create('appeals', function (Blueprint $table) {
                $table->id('id_appeal');
                $table->foreignId('id_pengumpulan')->constrained('pengumpulan_tugas', 'id_pengumpulan')->onDelete('cascade');
                $table->text('alasan_banding');
                $table->text('catatan_guru')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('appeals');
    }
};