<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id('id_pengumpulan');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('id_tugas')->constrained('tugas', 'id_tugas')->onDelete('cascade');
            $table->string('file_jawaban');
            $table->timestamps();
            
            $table->unique(['id_user', 'id_tugas']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};