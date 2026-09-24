<?php

namespace App\Exports;

use App\Http\Controllers\PostSupervisionController;
use App\Models\PostSupervision;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PostSupervisionExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    public function __construct(
        private array $cfg,
        private PostSupervision $record,
    ) {}

    public function headings(): array
    {
        return array_map(fn ($f) => $f['label'], PostSupervisionController::excelFields($this->cfg));
    }

    public function collection()
    {
        $fields = PostSupervisionController::excelFields($this->cfg);

        return collect($this->record->items ?? [])->map(fn (array $item) => array_map(
            fn ($f) => (string) ($item[$f['key']] ?? ''),
            $fields
        ));
    }
}
