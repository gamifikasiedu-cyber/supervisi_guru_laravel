<?php

namespace App\Exports;

use App\Models\Subject;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubjectExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    public function headings(): array
    {
        return [
            'Kode Mapel',
            'Nama Mata Pelajaran',
        ];
    }

    public function collection()
    {
        return Subject::query()
            ->get()
            ->map(fn (Subject $subject) => [
                'code' => $subject->code,
                'name' => $subject->name,
            ]);
    }
}
