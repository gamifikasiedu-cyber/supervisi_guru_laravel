<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pre_observation_konferensis', function (Blueprint $table) {
            // Daftar path foto dokumentasi wawancara (JSON array), maks. beberapa foto per guru.
            $table->json('dokumentasi_foto')->nullable()->after('items');
        });
    }

    public function down(): void
    {
        Schema::table('pre_observation_konferensis', function (Blueprint $table) {
            $table->dropColumn('dokumentasi_foto');
        });
    }
};
