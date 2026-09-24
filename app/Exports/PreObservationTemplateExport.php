<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PreObservationTemplateExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    /**
     * @param  array<int, array{komponen?: string, indikator?: string, keterkaitan?: string}>  $rows
     */
    public function __construct(private array $rows) {}

    public function headings(): array
    {
        return [
            'Komponen Telaah',
            'Aspek/Indikator RPP-RPM',
            'Keterkaitan PM / Literasi-Numerasi',
        ];
    }

    public function collection()
    {
        return collect($this->rows)->map(fn (array $row) => [
            $row['komponen'] ?? $row['aspek'] ?? '',
            $row['indikator'] ?? '',
            $row['keterkaitan'] ?? '',
        ]);
    }
}
