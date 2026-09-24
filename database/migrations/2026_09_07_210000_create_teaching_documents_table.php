<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Guru yang mengunggah
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete(); // Mata pelajaran terkait
            $table->string('title'); // Judul dokumen (contoh: RPP Matematika Kelas XII)
            $table->text('description')->nullable(); // Deskripsi singkat
            $table->string('document_type')->default('RPP'); // RPP, Silabus, KKM, Prosem, Lainnya
            $table->string('file_path'); // Path file yang diunggah
            $table->string('file_name'); // Nama asli file
            $table->string('file_size')->nullable(); // Ukuran file
            $table->enum('status', ['pending', 'reviewed', 'approved', 'rejected'])->default('pending');
            $table->text('review_notes')->nullable(); // Catatan dari supervisor
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete(); // Supervisor yang mereview
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_documents');
    }
};
