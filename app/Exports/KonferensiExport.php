<?php

namespace App\Exports;

use App\Models\PreObservationKonferensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KonferensiExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    public function __construct(private PreObservationKonferensi $record) {}

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
        return collect($this->record->items ?? [])->map(fn (array $item) => [
            $item['pertanyaan'] ?? '',
            $item['catatan_guru'] ?? '',
            $item['catatan_supervisor'] ?? '',
            $item['kesepakatan'] ?? '',
            $item['tindak_lanjut'] ?? '',
        ]);
    }
}
