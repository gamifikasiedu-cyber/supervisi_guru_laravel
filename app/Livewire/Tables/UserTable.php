<?php

namespace App\Livewire\Tables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class UserTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'users-table';

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
        return User::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('email')
            ->add('role_label', fn (User $user) => $user->role_label)
            ->add('nip')
            ->add('mata_pelajaran');
    }

    public function columns(): array
    {
        return [
            Column::make('Nama', 'name')->sortable()->searchable(),
            Column::make('Email', 'email')->sortable()->searchable(),
            Column::make('Peran', 'role_label'),
            Column::make('NIP', 'nip')->searchable(),
            Column::make('Mapel', 'mata_pelajaran')->searchable(),
            Column::action('Aksi'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('role', 'role')
                ->dataSource(collect(User::ROLES)->map(fn ($label, $value) => ['value' => $value, 'label' => $label])->values())
                ->optionValue('value')
                ->optionLabel('label'),
        ];
    }

    public function actionsFromView($row): string
    {
        $items = [
            ['url' => route('users.edit', $row->id), 'label' => 'Edit', 'icon' => '✎', 'method' => 'GET'],
        ];

        if ($row->id !== auth()->id()) {
            $items[] = [
                'url' => route('records.destroy', ['type' => 'user', 'id' => $row->id]),
                'label' => 'Hapus', 'icon' => '🗑', 'method' => 'DELETE',
                'confirm' => 'Hapus pengguna ini? Data tidak dapat dikembalikan.',
                'danger' => true,
            ];
        }

        return view('components.row-menu', ['items' => $items])->render();
    }
}