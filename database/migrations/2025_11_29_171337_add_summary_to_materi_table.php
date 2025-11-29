<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->text('summary')->nullable()->after('file');
            $table->timestamp('summary_generated_at')->nullable()->after('summary');
        });
    }

    public function down(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->dropColumn(['summary', 'summary_generated_at']);
        });
    }
};