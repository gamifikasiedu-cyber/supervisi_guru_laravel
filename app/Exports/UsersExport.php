<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'NIP',
            'Mata Pelajaran',
            'Peran',
            'Role Utama',
        ];
    }

    public function collection()
    {
        return User::query()
            ->get()
            ->map(function (User $user) {
                return [
                    'name' => $user->name,
                    'email' => $user->email,
                    'nip' => $user->nip,
                    'mata_pelajaran' => $user->mata_pelajaran,
                    'peran' => implode(',', $user->role_list ?? [$user->role]),
                    'role' => $user->role,
                ];
            });
    }
}
