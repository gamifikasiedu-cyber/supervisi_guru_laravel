<?php

namespace App\Livewire\Tables;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class SubjectTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'subjects-table';

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
        return Subject::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('code');
    }

    public function columns(): array
    {
        return [
            Column::make('Nama', 'name')->sortable()->searchable(),
            Column::make('Kode', 'code')->sortable()->searchable(),
            Column::action('Aksi'),
        ];
    }

    public function actionsFromView($row): string
    {
        return view('components.row-menu', [
            'items' => [
                ['url' => route('subjects.edit', $row->id), 'label' => 'Edit', 'icon' => '✎', 'method' => 'GET'],
                [
                    'url' => route('records.destroy', ['type' => 'subject', 'id' => $row->id]),
                    'label' => 'Hapus', 'icon' => '🗑', 'method' => 'DELETE',
                    'confirm' => 'Hapus mata pelajaran ini?',
                    'danger' => true,
                ],
            ],
        ])->render();
    }
}