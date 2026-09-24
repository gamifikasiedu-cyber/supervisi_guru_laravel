<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('post_supervisions', function (Blueprint $table) {
            // Jenis observasi khusus Asesmen Formatif: Observasi_3M / Observasi_BBM.
            $table->string('jenis_observasi', 50)->nullable()->after('bagian');
        });
    }

    public function down(): void
    {
        Schema::table('post_supervisions', function (Blueprint $table) {
            $table->dropColumn('jenis_observasi');
        });
    }
};
