<?php

namespace App\Exports;

use App\Http\Controllers\PostSupervisionController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PostSupervisionTemplateExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    public function __construct(
        private string $bagian,
        private array $cfg,
        private array $rows,
    ) {}

    public function headings(): array
    {
        return array_map(fn ($f) => $f['label'], PostSupervisionController::excelFields($this->cfg));
    }

    public function collection()
    {
        $fields = PostSupervisionController::excelFields($this->cfg);

        return collect($this->rows)->map(fn (array $row) => array_map(
            fn ($f) => (string) ($row[$f['key']] ?? ''),
            $fields
        ));
    }
}
