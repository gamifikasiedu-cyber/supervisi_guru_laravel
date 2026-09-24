<?php

namespace App\Imports;

use App\Models\Subject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubjectImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $row = $this->normalizeKeys($row);

        $name = trim((string) $this->cell($row, ['nama_mata_pelajaran', 'mapel', 'name', 'nama']));

        if ($name === '') {
            return null;
        }

        return Subject::updateOrCreate(
            ['name' => $name],
            ['code' => $this->stringOrNull($this->cell($row, ['kode_mapel', 'kode', 'code']))]
        );
    }

    /**
     * Normalisasi kunci heading menjadi snake_case.
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

    private function stringOrNull(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
