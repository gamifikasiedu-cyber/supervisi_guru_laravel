<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_supervisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->nullable()->constrained('periods')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->string('class_name')->nullable();
            $table->date('observation_date');
            $table->string('bagian', 50);

            // Setiap baris: keys dinamis per bagian (lihat PostSupervisionController::BAGIAN)
            $table->json('items');

            $table->unsignedInteger('total_skor')->nullable();
            $table->unsignedInteger('max_skor')->nullable();
            $table->decimal('score', 5, 2)->nullable(); // persentase (hanya bagian berskor)

            $table->timestamps();

            $table->index(['bagian', 'period_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_supervisions');
    }
};
