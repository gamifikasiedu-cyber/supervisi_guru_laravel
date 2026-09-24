<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role', 30);
            $table->timestamps();

            $table->unique(['user_id', 'role']);
        });

        // Backfill: pastikan setiap user memiliki role saat ini sebagai pivot
        $users = DB::table('users')->select('id', 'role')->get();
        foreach ($users as $user) {
            if (!empty($user->role)) {
                DB::table('user_roles')->insert([
                    'user_id' => $user->id,
                    'role' => $user->role,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};