<?php

namespace App\Livewire\Tables;

use App\Http\Controllers\PostSupervisionController;
use App\Models\PostSupervision;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class PostSupervisionTable extends PowerGridComponent
{
    public string $tableName = 'post-supervisions-table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return PostSupervision::query()->with(['teacher', 'subject']);
    }

    public function relationSearch(): array
    {
        return ['teacher' => ['name'], 'subject' => ['name']];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('date_formatted', fn (PostSupervision $m) => $m->observation_date?->format('d/m/Y'))
            ->add('teacher_name', fn (PostSupervision $m) => $m->teacher?->name)
            ->add('subject_name', fn (PostSupervision $m) => $m->subject?->name)
            ->add('bagian_label', fn (PostSupervision $m) => PostSupervisionController::BAGIAN[$m->bagian]['label'] ?? $m->bagian)
            ->add('score');
    }

    public function columns(): array
    {
        return [
            Column::make('Tanggal', 'date_formatted', 'observation_date')->sortable(),
            Column::make('Guru', 'teacher_name')->sortable()->searchable(),
            Column::make('Bagian', 'bagian_label'),
            Column::make('Mapel', 'subject_name')->sortable(),
            Column::make('Nilai', 'score')->sortable(),
            Column::action('Aksi'),
        ];
    }

    public function filters(): array
    {
        $options = [];
        foreach (PostSupervisionController::BAGIAN as $slug => $meta) {
            $options[] = ['value' => $slug, 'label' => $meta['label']];
        }

        return [
            Filter::select('bagian', 'bagian')
                ->dataSource($options)
                ->optionValue('value')
                ->optionLabel('label'),
        ];
    }

    public function actionsFromView($row): string
    {
        return view('components.row-menu', [
            'items' => [
                ['url' => route('post-supervisions.edit', $row->id), 'label' => 'Edit', 'icon' => '✎', 'method' => 'GET'],
                [
                    'url' => route('records.destroy', ['type' => 'post-supervision', 'id' => $row->id]),
                    'label' => 'Hapus', 'icon' => '🗑', 'method' => 'DELETE',
                    'confirm' => 'Hapus record ini?',
                    'danger' => true,
                ],
            ],
        ])->render();
    }
}