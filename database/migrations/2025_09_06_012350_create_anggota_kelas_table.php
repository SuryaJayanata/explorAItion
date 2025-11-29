<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('id_kelas')->constrained('kelas', 'id_kelas')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['id_user', 'id_kelas']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota_kelas');
    }
};