<?php

namespace App\Livewire\Tables;

use App\Models\Instrument;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class InstrumentTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'instruments-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable('export')->striped()->type('xlsx', 'csv'),
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Instrument::query()->with(['teacher', 'subject']);
    }

    public function relationSearch(): array
    {
        return ['teacher' => ['name'], 'subject' => ['name']];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('date_formatted', fn (Instrument $m) => $m->observation_date?->format('d/m/Y'))
            ->add('teacher_name', fn (Instrument $m) => $m->teacher?->name)
            ->add('subject_name', fn (Instrument $m) => $m->subject?->name)
            ->add('class_name')
            ->add('skor', fn (Instrument $m) => $m->total_skor.'/'.$m->max_skor)
            ->add('score');
    }

    public function columns(): array
    {
        return [
            Column::make('Tanggal', 'date_formatted', 'observation_date')->sortable(),
            Column::make('Guru', 'teacher_name')->sortable()->searchable(),
            Column::make('Mapel', 'subject_name')->sortable(),
            Column::make('Kelas', 'class_name')->searchable(),
            Column::make('Skor', 'skor'),
            Column::make('Nilai', 'score')->sortable(),
            Column::action('Aksi'),
        ];
    }

    public function actionsFromView($row): string
    {
        return view('components.row-menu', [
            'items' => [
                ['url' => route('pemantauan.edit', $row->id), 'label' => 'Edit', 'icon' => '✎', 'method' => 'GET'],
                [
                    'url' => route('records.destroy', ['type' => 'instrument', 'id' => $row->id]),
                    'label' => 'Hapus', 'icon' => '🗑', 'method' => 'DELETE',
                    'confirm' => 'Hapus record ini?',
                    'danger' => true,
                ],
            ],
        ])->render();
    }
}