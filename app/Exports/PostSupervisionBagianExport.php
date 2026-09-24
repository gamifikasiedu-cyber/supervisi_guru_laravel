<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PostSupervisionBagianExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    /**
     * @param  array{label?: string, title?: string, fields?: array}  $cfg
     */
    public function __construct(private string $bagian, private array $cfg, private $records) {}

    public function headings(): array
    {
        return array_merge(
            ['Tanggal', 'Guru', 'Mapel', 'Kelas'],
            array_map(fn ($f) => $f['label'], $this->cfg['fields'] ?? []),
            ['Nilai']
        );
    }

    public function collection()
    {
        $rows = collect([
            [$this->cfg['title'] ?? $this->bagian],
            [],
        ]);

        foreach ($this->records as $r) {
            $row = [
                $r->observation_date,
                $r->teacher->name ?? '-',
                $r->subject->name ?? '-',
                $r->class_name ?? '-',
            ];
            foreach ($this->cfg['fields'] ?? [] as $f) {
                $row[] = $this->joinColumn($r->items ?? [], $f['key']);
            }
            $row[] = $r->score ?? '-';
            $rows->push($row);
        }

        return $rows;
    }

    private function joinColumn(array $items, string $key): string
    {
        return collect($items)
            ->map(fn ($it) => trim((string) ($it[$key] ?? '')))
            ->filter()
            ->implode(' | ');
    }
}
