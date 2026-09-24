<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['supervisions', 'observations', 'teaching_documents'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('period_id')->nullable()->after('id')->constrained('periods')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        foreach (['supervisions', 'observations', 'teaching_documents'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropConstrainedForeignId('period_id');
            });
        }
    }
};