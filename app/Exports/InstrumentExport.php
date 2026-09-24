<?php

namespace App\Exports;

use App\Models\Instrument;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InstrumentExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    public function __construct(private Instrument $record) {}

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
        return collect($this->record->items ?? [])->map(fn (array $item) => [
            $item['tahap'] ?? '',
            $item['dimensi'] ?? '',
            $item['indikator'] ?? '',
        ]);
    }
}
