<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komentar', function (Blueprint $table) {
            $table->id('id_komentar');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->enum('tipe', ['materi', 'tugas']);
            $table->unsignedBigInteger('id_target');
            $table->text('isi');
            $table->unsignedBigInteger('parent_id')->nullable(); // Tambahkan ini
            $table->timestamps();
            
            // Foreign key untuk parent_id
            $table->foreign('parent_id')->references('id_komentar')->on('komentar')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komentar');
    }
};