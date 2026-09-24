<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('supervisions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete(); // Guru yang disupervisi
        $table->foreignId('supervisor_id')->constrained('users')->cascadeOnDelete(); // Pengawas / Penilai
        $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete(); // Mata Pelajaran
        $table->string('class_name'); // Kelas (Contoh: XII TKJ 1)
        $table->date('schedule_date'); // Tanggal pelaksanaan
        $table->enum('status', ['Scheduled', 'Completed', 'Cancelled'])->default('Scheduled');
        $table->text('notes')->nullable(); // Catatan hasil supervisi
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisions');
    }
};
