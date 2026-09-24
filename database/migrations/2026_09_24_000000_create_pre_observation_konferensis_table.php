<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pre_observation_konferensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->nullable()->constrained('periods')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->string('class_name')->nullable();
            $table->date('observation_date');

            // Setiap baris: {no, pertanyaan, catatan_guru, catatan_supervisor, kesepakatan, tindak_lanjut}
            $table->json('items');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_observation_konferensis');
    }
};
