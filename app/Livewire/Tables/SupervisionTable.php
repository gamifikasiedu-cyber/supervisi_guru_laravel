<?php

namespace App\Livewire\Tables;

use App\Models\Supervision;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class SupervisionTable extends PowerGridComponent
{
    public string $tableName = 'supervisions-table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        $user = auth()->user();
        $query = Supervision::query()->with(['teacher', 'supervisor', 'subject']);

        if ($user && $user->hasRole('guru') && ! $user->hasRole('admin', 'supervisor', 'kepala_sekolah', 'pengawas')) {
            $query->where('teacher_id', $user->id);
        }

        return $query;
    }

    public function relationSearch(): array
    {
        return [
            'teacher' => ['name'],
            'supervisor' => ['name'],
            'subject' => ['name'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('schedule_date_formatted', fn (Supervision $m) => $m->schedule_date?->format('d/m/Y'))
            ->add('teacher_name', fn (Supervision $m) => $m->teacher?->name)
            ->add('subject_name', fn (Supervision $m) => $m->subject?->name)
            ->add('class_name')
            ->add('supervisor_name', fn (Supervision $m) => $m->supervisor?->name)
            ->add('status');
    }

    public function columns(): array
    {
        return [
            Column::make('Tanggal', 'schedule_date_formatted', 'schedule_date')->sortable(),
            Column::make('Guru', 'teacher_name')->sortable()->searchable(),
            Column::make('Mapel', 'subject_name')->sortable(),
            Column::make('Kelas', 'class_name')->searchable(),
            Column::make('Supervisor', 'supervisor_name')->sortable(),
            Column::make('Status', 'status'),
            Column::action('Aksi'),
        ];
    }

    public function actionsFromView($row): string
    {
        return view('components.row-menu', [
            'items' => [
                ['url' => route('supervisions.edit', $row->id), 'label' => 'Edit', 'icon' => '✎', 'method' => 'GET'],
                [
                    'url' => route('records.destroy', ['type' => 'supervision', 'id' => $row->id]),
                    'label' => 'Hapus', 'icon' => '🗑', 'method' => 'DELETE',
                    'confirm' => 'Hapus jadwal ini?',
                    'danger' => true,
                ],
            ],
        ])->render();
    }
}