<?php

namespace App\Livewire\Tables;

use App\Models\Period;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class PeriodTable extends PowerGridComponent
{
    public string $tableName = 'periods-table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Period::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('tahun_ajaran')
            ->add('semester')
            ->add('start_date_formatted', fn (Period $p) => $p->start_date?->format('d/m/Y'))
            ->add('end_date_formatted', fn (Period $p) => $p->end_date?->format('d/m/Y'))
            ->add('is_active');
    }

    public function columns(): array
    {
        return [
            Column::make('Tahun Ajaran', 'tahun_ajaran')->sortable()->searchable(),
            Column::make('Semester', 'semester'),
            Column::make('Mulai', 'start_date_formatted', 'start_date')->sortable(),
            Column::make('Selesai', 'end_date_formatted', 'end_date')->sortable(),
            Column::make('Aktif', 'is_active')->toggleable(),
            Column::action('Aksi'),
        ];
    }

    public function actionsFromView($row): string
    {
        $isActive = (int) session('active_period_id') === (int) $row->id;

        return view('components.row-menu', [
            'items' => [
                ['url' => route('periods.activate', $row->id), 'label' => $isActive ? '✓ Periode aktif' : 'Aktifkan', 'icon' => '✓', 'method' => 'POST'],
                ['url' => route('periods.edit', $row->id), 'label' => 'Edit', 'icon' => '✎', 'method' => 'GET'],
                [
                    'url' => route('records.destroy', ['type' => 'period', 'id' => $row->id]),
                    'label' => 'Hapus', 'icon' => '🗑', 'method' => 'DELETE',
                    'confirm' => 'Hapus periode ini?',
                    'danger' => true,
                ],
            ],
        ])->render();
    }
}