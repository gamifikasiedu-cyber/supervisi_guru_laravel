<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PreObservationFilledExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    /**
     * @param  array{teacher?: string, subject?: string, class_name?: string, observation_date?: string, supervisor?: string, items?: array, total?: int, max?: int, score?: float}  $payload
     */
    public function __construct(private array $payload) {}

    public function headings(): array
    {
        return [
            'No',
            'Komponen Telaah',
            'Aspek/Indikator RPP-RPM',
            'Keterkaitan PM / Literasi-Numerasi',
            'Skor (1-4)',
            'Bukti pada RPP/RPM',
            'Catatan/Temuan Pra Observasi',
            'Rekomendasi',
        ];
    }

    public function collection()
    {
        $rows = collect([
            ['INSTRUMEN PRA OBSERVASI / TELAAH RPP-RPM'],
            ['Guru', $this->payload['teacher'] ?? '-'],
            ['Mata Pelajaran', $this->payload['subject'] ?? '-'],
            ['Kelas', $this->payload['class_name'] ?? '-'],
            ['Tanggal', $this->payload['observation_date'] ?? '-'],
            ['Supervisor', $this->payload['supervisor'] ?? '-'],
            [],
        ]);

        foreach (($this->payload['items'] ?? []) as $i => $item) {
            $rows->push([
                $i + 1,
                $item['komponen'] ?? '',
                $item['indikator'] ?? '',
                $item['keterkaitan'] ?? '',
                $item['skor'] ?? '',
                $item['bukti'] ?? '',
                $item['catatan'] ?? '',
                $item['rekomendasi'] ?? '',
            ]);
        }

        $rows->push([]);
        $rows->push(['Total Skor', ($this->payload['total'] ?? 0).'/'.($this->payload['max'] ?? 0)]);
        $rows->push(['Nilai', $this->payload['score'] ?? 0]);

        return $rows;
    }
}
