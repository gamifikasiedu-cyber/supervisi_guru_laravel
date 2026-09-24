<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('observations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervision_id')->nullable()->constrained('supervisions')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete(); // Guru yang diamati
            $table->foreignId('supervisor_id')->constrained('users')->cascadeOnDelete(); // Supervisor yang mengisi nilai
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->string('class_name'); // Kelas yang diamati
            $table->date('observation_date'); // Tanggal observasi

            // Skor Observasi
            $table->decimal('score_planning', 5, 2)->nullable(); // Skor perencanaan (1-4, Pembelajaran Mendalam)
            $table->decimal('score_delivery', 5, 2)->nullable(); // Skor pelaksanaan (1-4, Pembelajaran Mendalam)
            $table->decimal('score_management', 5, 2)->nullable(); // Skor pengelolaan kelas (1-4, Pembelajaran Mendalam)
            $table->decimal('score_assessment', 5, 2)->nullable(); // Skor penilaian (1-4, Pembelajaran Mendalam)
            $table->decimal('total_score', 5, 2)->nullable(); // Rata-rata total

            // Catatan
            $table->text('observation_notes')->nullable(); // Catatan observasi
            $table->text('feedback')->nullable(); // Feedback untuk guru
            $table->text('recommendations')->nullable(); // Rekomendasi perbaikan

            // Status
            $table->enum('status', ['draft', 'submitted', 'approved'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete(); // Kepala Sekolah yang approve
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observations');
    }
};
