<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai', function (Blueprint $table) {
            $table->id('id_nilai');
            $table->foreignId('id_pengumpulan')->constrained('pengumpulan_tugas', 'id_pengumpulan')->onDelete('cascade');
            $table->integer('nilai');
            $table->text('komentar_guru')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};