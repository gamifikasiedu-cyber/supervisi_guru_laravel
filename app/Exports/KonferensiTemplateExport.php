<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KonferensiTemplateExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    /**
     * @param  array<int, array<string, string>>  $rows
     */
    public function __construct(private array $rows) {}

    public function headings(): array
    {
        return [
            'Pertanyaan/Fokus Konferensi',
            'Catatan Guru',
            'Catatan Supervisor',
            'Kesepakatan',
            'Tindak Lanjut',
        ];
    }

    public function collection()
    {
        return collect($this->rows)->map(fn (array $row) => [
            $row['pertanyaan'] ?? '',
            $row['catatan_guru'] ?? '',
            $row['catatan_supervisor'] ?? '',
            $row['kesepakatan'] ?? '',
            $row['tindak_lanjut'] ?? '',
        ]);
    }
}
