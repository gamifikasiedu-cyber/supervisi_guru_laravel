<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instruments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->nullable()->constrained('periods')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->string('class_name')->nullable();
            $table->date('observation_date');

            // Setiap baris: {no, tahap, dimensi, indikator, skor, bukti, catatan}
            $table->json('items');

            $table->unsignedInteger('total_skor')->default(0);
            $table->unsignedInteger('max_skor')->default(0);
            $table->decimal('score', 5, 2)->default(0); // persentase

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instruments');
    }
};