<?php

namespace App\Exports;

use App\Models\PreObservation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PreObservationExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    public function __construct(private PreObservation $record) {}

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
        return collect($this->record->items ?? [])->map(fn (array $item) => [
            $item['komponen'] ?? $item['aspek'] ?? '',
            $item['indikator'] ?? '',
            $item['keterkaitan'] ?? '',
        ]);
    }
}
