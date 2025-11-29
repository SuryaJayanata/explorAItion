<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->string('tipe'); // materi_baru, tugas_baru, nilai_diberikan, komentar_baru, siswa_bergabung, tugas_dikumpulkan
            $table->string('judul');
            $table->text('pesan');
            $table->string('tautan')->nullable();
            $table->boolean('dibaca')->default(false);
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};