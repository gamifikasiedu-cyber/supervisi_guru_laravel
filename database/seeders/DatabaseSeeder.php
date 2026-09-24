<?php

namespace Database\Seeders;

use App\Models\Period;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $defaultSettings = [
            'school_name' => 'SMA Negeri 1 Contoh',
            'address' => 'Jl. Pendidikan No. 1, Kecamatan, Kabupaten, Provinsi',
            'npsn' => '20123456',
            'tahun_ajaran' => now()->year.'/'.(now()->year + 1),
            'semester' => 'Ganjil',
        ];

        foreach ($defaultSettings as $key => $value) {
            Setting::set($key, $value);
        }

        $currentYear = (int) now()->format('Y');
        $periods = [
            [
                'tahun_ajaran' => $currentYear.'/'.($currentYear + 1),
                'semester' => 'Ganjil',
                'start_date' => $currentYear.'-07-01',
                'end_date' => $currentYear.'-12-31',
                'is_active' => true,
            ],
            [
                'tahun_ajaran' => $currentYear.'/'.($currentYear + 1),
                'semester' => 'Genap',
                'start_date' => ($currentYear + 1).'-01-01',
                'end_date' => ($currentYear + 1).'-06-30',
                'is_active' => true,
            ],
        ];

        foreach ($periods as $period) {
            Period::updateOrCreate(
                ['tahun_ajaran' => $period['tahun_ajaran'], 'semester' => $period['semester']],
                $period
            );
        }

        $users = [
            [
                'name' => 'Admin Kurikulum',
                'email' => 'admin@supervisi.test',
                'role' => User::ROLE_ADMIN,
                'nip' => '197501011998031001',
                'mata_pelajaran' => null,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Kepala Sekolah',
                'email' => 'kepsek@supervisi.test',
                'role' => User::ROLE_KEPALA_SEKOLAH,
                'nip' => '196806151990021002',
                'mata_pelajaran' => null,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Guru Matematika',
                'email' => 'guru@supervisi.test',
                'role' => User::ROLE_GURU,
                'nip' => '198504122010031003',
                'mata_pelajaran' => 'Matematika',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            $created = User::updateOrCreate(['email' => $user['email']], $user);
            $created->setRoles([$user['role']]);
        }
    }
}
