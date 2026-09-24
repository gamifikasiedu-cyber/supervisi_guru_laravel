<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements SkipsOnFailure, ToModel, WithHeadingRow
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        $row = $this->normalizeKeys($row);

        $name = trim((string) $this->cell($row, ['nama', 'name']));
        $email = trim((string) $this->cell($row, ['email']));

        if ($name === '' || $email === '') {
            return null;
        }

        $roles = $this->parseRoles($this->cell($row, ['peran', 'role', 'rol', 'role_utama', 'roles']));

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'nip' => $this->nip($this->cell($row, ['nip'])),
                'mata_pelajaran' => $this->stringOrNull($this->cell($row, ['mata_pelajaran', 'mapel', 'mata_pelajaran_dan_kerajinan'])),
                'password' => Hash::make($this->stringOrNull($this->cell($row, ['password', 'kata_sandi'])) ?? 'password'),
            ]
        );

        $user->setRoles($roles);

        return $user;
    }

    /**
     * Normalisasi kunci heading menjadi snake_case (mis. "Mata Pelajaran", "mata_pelajaran",
     * "mata-pelajaran" semuanya menjadi "mata_pelajaran").
     */
    private function normalizeKeys(array $row): array
    {
        $normalized = [];
        foreach ($row as $key => $value) {
            $normalized[preg_replace('/[_\-\s]+/', '_', strtolower((string) $key))] = $value;
        }

        return $normalized;
    }

    private function cell(array $row, array $keys): mixed
    {
        foreach ($keys as $key) {
            if (isset($row[$key]) && trim((string) $row[$key]) !== '') {
                return $row[$key];
            }
        }

        return null;
    }

    private function nip(mixed $value): ?string
    {
        return is_numeric($value)
            ? (string) (int) $value
            : Str::substr((string) $value, 0, 30);
    }

    private function stringOrNull(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    /**
     * Mengubah string peran (dipisah koma / spasi) menjadi array role valid.
     *
     * @return array<int, string>
     */
    private function parseRoles(mixed $value): array
    {
        if ($value === null || trim((string) $value) === '') {
            return [User::ROLE_GURU];
        }

        $roles = preg_split('/[,;\s]+/', (string) $value, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return array_values(array_unique(array_filter($roles, fn ($role) => array_key_exists($role, User::ROLES))));
    }
}
