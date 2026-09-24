<?php

namespace App\Livewire\Tables;

use App\Models\PreObservationKonferensi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class KonferensiTable extends PowerGridComponent
{
    public string $tableName = 'konferensis-table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return PreObservationKonferensi::query()->with(['teacher', 'subject']);
    }

    public function relationSearch(): array
    {
        return ['teacher' => ['name'], 'subject' => ['name']];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('date_formatted', fn (PreObservationKonferensi $m) => $m->observation_date?->format('d/m/Y'))
            ->add('teacher_name', fn (PreObservationKonferensi $m) => $m->teacher?->name)
            ->add('subject_name', fn (PreObservationKonferensi $m) => $m->subject?->name)
            ->add('class_name')
            ->add('foto_count', fn (PreObservationKonferensi $m) => count((array) $m->dokumentasi_foto).' foto');
    }

    public function columns(): array
    {
        return [
            Column::make('Tanggal', 'date_formatted', 'observation_date')->sortable(),
            Column::make('Guru', 'teacher_name')->sortable()->searchable(),
            Column::make('Mapel', 'subject_name')->sortable(),
            Column::make('Kelas', 'class_name')->searchable(),
            Column::make('Foto', 'foto_count'),
            Column::action('Aksi'),
        ];
    }

    public function actionsFromView($row): string
    {
        $fotoCount = count((array) $row->dokumentasi_foto);

        return view('components.row-menu', [
            'items' => [
                ['url' => route('konferensis.photos', $row->id), 'label' => "Foto ({$fotoCount})", 'icon' => '🖼', 'method' => 'GET'],
                ['url' => route('konferensis.cetak', $row->id), 'label' => 'Cetak', 'icon' => '🖨', 'method' => 'GET'],
                ['url' => route('konferensis.edit', $row->id), 'label' => 'Edit', 'icon' => '✎', 'method' => 'GET'],
                [
                    'url' => route('records.destroy', ['type' => 'konferensi', 'id' => $row->id]),
                    'label' => 'Hapus', 'icon' => '🗑', 'method' => 'DELETE',
                    'confirm' => 'Hapus record ini?',
                    'danger' => true,
                ],
            ],
        ])->render();
    }
}