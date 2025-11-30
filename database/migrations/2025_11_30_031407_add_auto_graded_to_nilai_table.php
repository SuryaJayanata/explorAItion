<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            if (!Schema::hasColumn('nilai', 'auto_graded')) {
                $table->boolean('auto_graded')->default(false)->after('komentar_guru');
            }
            
            if (!Schema::hasColumn('nilai', 'analisis_detail')) {
                $table->json('analisis_detail')->nullable()->after('auto_graded');
            }
        });
    }

    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            $columnsToDrop = [];
            
            if (Schema::hasColumn('nilai', 'auto_graded')) {
                $columnsToDrop[] = 'auto_graded';
            }
            
            if (Schema::hasColumn('nilai', 'analisis_detail')) {
                $columnsToDrop[] = 'analisis_detail';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};