<?php

namespace App\Livewire\Tables;

use App\Models\TeachingDocument;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class TeachingDocumentTable extends PowerGridComponent
{
    public string $tableName = 'teaching-documents-table';

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
        $query = TeachingDocument::query()->with(['user', 'subject', 'reviewer']);

        $canReview = $user && ($user->isSupervisor() || $user->isPengawas() || $user->isKepalaSekolah() || $user->isAdmin());
        if (! $canReview) {
            $query->where('user_id', $user?->id);
        }

        return $query;
    }

    public function relationSearch(): array
    {
        return [
            'user' => ['name'],
            'subject' => ['name'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('teacher_name', fn (TeachingDocument $m) => $m->user?->name)
            ->add('title')
            ->add('document_type')
            ->add('subject_name', fn (TeachingDocument $m) => $m->subject?->name)
            ->add('file_size')
            ->add('status')
            ->add('status_label', fn (TeachingDocument $m) => match ($m->status) {
                'pending' => 'Menunggu',
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
                default => $m->status,
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Guru', 'teacher_name')->sortable()->searchable(),
            Column::make('Judul', 'title')->sortable()->searchable(),
            Column::make('Jenis', 'document_type'),
            Column::make('Mapel', 'subject_name')->sortable(),
            Column::make('Ukuran', 'file_size'),
            Column::make('Status', 'status_label'),
            Column::action('Aksi'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('status', 'status')
                ->dataSource([
                    ['value' => 'pending', 'label' => 'Menunggu'],
                    ['value' => 'approved', 'label' => 'Disetujui'],
                    ['value' => 'rejected', 'label' => 'Ditolak'],
                ])
                ->optionValue('value')
                ->optionLabel('label'),
        ];
    }

    public function actionsFromView($row): string
    {
        $user = auth()->user();
        $canReview = $user && ($user->isSupervisor() || $user->isPengawas() || $user->isKepalaSekolah() || $user->isAdmin());

        $items = [
            ['url' => route('documents.download', $row->id), 'label' => 'Unduh', 'icon' => '⬇', 'method' => 'GET'],
        ];

        if ($canReview) {
            $items[] = ['url' => route('documents.review', $row->id), 'label' => 'Review', 'icon' => '✔', 'method' => 'GET'];
        }

        $items[] = ['url' => route('documents.edit', $row->id), 'label' => 'Edit', 'icon' => '✎', 'method' => 'GET'];
        $items[] = [
            'url' => route('records.destroy', ['type' => 'document', 'id' => $row->id]),
            'label' => 'Hapus', 'icon' => '🗑', 'method' => 'DELETE',
            'confirm' => 'Hapus dokumen ini?',
            'danger' => true,
        ];

        return view('components.row-menu', ['items' => $items])->render();
    }
}