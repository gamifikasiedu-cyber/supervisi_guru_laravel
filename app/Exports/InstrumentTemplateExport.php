<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InstrumentTemplateExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    /**
     * @param  array<int, array{tahap?: string, dimensi?: string, indikator?: string}>  $rows
     */
    public function __construct(private array $rows) {}

    public function headings(): array
    {
        return [
            'Tahap/Aspek',
            'Dimensi',
            'Indikator Pemantauan/Supervisi',
        ];
    }

    public function collection()
    {
        return collect($this->rows)->map(fn (array $row) => [
            $row['tahap'] ?? '',
            $row['dimensi'] ?? '',
            $row['indikator'] ?? '',
        ]);
    }
}
