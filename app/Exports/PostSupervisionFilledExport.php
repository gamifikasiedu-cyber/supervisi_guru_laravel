<?php

namespace App\Exports;

use App\Http\Controllers\PostSupervisionController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PostSupervisionFilledExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    /**
     * @param  array{title?: string, teacher?: string, subject?: string, class_name?: string, observation_date?: string, supervisor?: string, fields?: array, excel_keys?: ?array, items?: array, score?: mixed}  $payload
     */
    public function __construct(private array $payload) {}

    private function excelFields(): array
    {
        return PostSupervisionController::excelFields([
            'fields' => $this->payload['fields'] ?? [],
            'excel_keys' => $this->payload['excel_keys'] ?? null,
        ]);
    }

    public function headings(): array
    {
        return array_merge(
            ['No'],
            array_map(fn ($f) => $f['label'], $this->excelFields()),
        );
    }

    public function collection()
    {
        $rows = collect([
            [$this->payload['title'] ?? 'Pasca Supervisi'],
            ['Guru', $this->payload['teacher'] ?? '-'],
            ['Mata Pelajaran', $this->payload['subject'] ?? '-'],
            ['Kelas', $this->payload['class_name'] ?? '-'],
            ['Tanggal', $this->payload['observation_date'] ?? '-'],
            ['Supervisor', $this->payload['supervisor'] ?? '-'],
            [],
        ]);

        foreach (($this->payload['items'] ?? []) as $i => $item) {
            $row = [$i + 1];
            foreach ($this->excelFields() as $f) {
                $row[] = (string) ($item[$f['key']] ?? '');
            }
            $rows->push($row);
        }

        if (($this->payload['score'] ?? null) !== null) {
            $rows->push([]);
            $rows->push(['Nilai', $this->payload['score']]);
        }

        return $rows;
    }
}
