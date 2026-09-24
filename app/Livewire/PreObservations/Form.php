<?php

namespace App\Livewire\PreObservations;

use App\Exports\PreObservationFilledExport;
use App\Exports\PreObservationTemplateExport;
use App\Http\Controllers\PreObservationController;
use App\Imports\PreObservationImport;
use App\Models\Period;
use App\Models\PreObservation;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Form extends Component
{
    use WithFileUploads;

    public ?PreObservation $preObservation = null;

    public string $teacher_id = '';

    public string $subject_id = '';

    public string $class_name = '';

    public string $observation_date = '';

    public array $items = [];

    public $importFile;

    public function mount(?PreObservation $preObservation = null): void
    {
        $this->observation_date = now()->format('Y-m-d');

        if ($preObservation?->exists) {
            $this->preObservation = $preObservation;
            $this->teacher_id = (string) ($preObservation->teacher_id ?? '');
            $this->subject_id = (string) ($preObservation->subject_id ?? '');
            $this->class_name = $preObservation->class_name ?? '';
            $this->observation_date = $preObservation->observation_date?->format('Y-m-d') ?? now()->format('Y-m-d');
            $this->items = array_map(fn ($it) => [
                'komponen' => $it['komponen'] ?? '',
                'indikator' => $it['indikator'] ?? '',
                'keterkaitan' => $it['keterkaitan'] ?? '',
                'skor' => $it['skor'] ?? '',
                'bukti' => $it['bukti'] ?? '',
                'catatan' => $it['catatan'] ?? '',
                'rekomendasi' => $it['rekomendasi'] ?? '',
            ], $preObservation->items ?? []);
        } else {
            $this->items = array_map(fn ($row) => [
                'komponen' => $row['komponen'] ?? '',
                'indikator' => $row['indikator'] ?? '',
                'keterkaitan' => $row['keterkaitan'] ?? '',
                'skor' => '',
                'bukti' => '',
                'catatan' => '',
                'rekomendasi' => '',
            ], PreObservationController::rows());
        }
    }

    public function save()
    {
        $this->validate([
            'teacher_id' => 'nullable|exists:users,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'class_name' => 'nullable|string|max:255',
            'observation_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.skor' => 'required|integer|min:1|max:4',
        ]);

        $items = [];
        foreach (array_values($this->items) as $i => $item) {
            $items[] = [
                'no' => $i + 1,
                'komponen' => $item['komponen'] ?? '-',
                'indikator' => $item['indikator'] ?? '',
                'keterkaitan' => $item['keterkaitan'] ?? '',
                'skor' => max(0, min(4, (int) ($item['skor'] ?? 0))),
                'bukti' => $item['bukti'] ?? '',
                'catatan' => $item['catatan'] ?? '',
                'rekomendasi' => $item['rekomendasi'] ?? '',
            ];
        }

        $data = [
            'teacher_id' => $this->teacher_id ?: null,
            'subject_id' => $this->subject_id ?: null,
            'class_name' => $this->class_name ?: null,
            'observation_date' => $this->observation_date,
            'items' => $items,
        ];

        if ($this->preObservation) {
            $this->preObservation->update($data);
            session()->flash('success', 'Pra observasi diperbarui.');
        } else {
            $data['period_id'] = Period::fromSession()?->id;
            $data['supervisor_id'] = auth()->id();
            PreObservation::create($data);
            session()->flash('success', 'Pra observasi disimpan.');
        }

        return $this->redirect(route('pre-observations.index'), navigate: true);
    }

    public function importExcel()
    {
        $this->validate(['importFile' => 'required|file|max:10240']);

        $ext = strtolower($this->importFile->getClientOriginalExtension());
        if (! in_array($ext, ['xlsx', 'xls', 'csv'], true)) {
            $this->addError('importFile', 'Format file tidak valid. Gunakan .xlsx, .xls, atau .csv.');

            return;
        }

        $import = new PreObservationImport;
        Excel::import($import, $this->importFile->getRealPath());

        if (! empty($import->rowErrors)) {
            $this->addError('importFile', implode(' ', array_slice($import->rowErrors, 0, 5)));

            return;
        }

        if (empty($import->items)) {
            $this->addError('importFile', 'File tidak berisi baris indikator yang valid.');

            return;
        }

        $this->items = array_map(fn ($row) => [
            'komponen' => $row['komponen'] ?? '',
            'indikator' => $row['indikator'] ?? '',
            'keterkaitan' => $row['keterkaitan'] ?? '',
            'skor' => '',
            'bukti' => '',
            'catatan' => '',
            'rekomendasi' => '',
        ], $import->items);

        Setting::set(PreObservationController::DEFAULT_ROWS_KEY, json_encode($import->items, JSON_UNESCAPED_UNICODE));

        $this->reset('importFile');
        session()->flash('success', 'Berhasil memuat '.count($import->items).' baris ke tabel. Lengkapi skor lalu Simpan.');
    }

    public function exportTemplate()
    {
        $rows = $this->preObservation?->items
            ?? array_map(fn ($it) => [
                'komponen' => $it['komponen'] ?? '',
                'indikator' => $it['indikator'] ?? '',
                'keterkaitan' => $it['keterkaitan'] ?? '',
            ], $this->items);

        return Excel::download(new PreObservationTemplateExport(array_values($rows)), 'template-pra-observasi.xlsx');
    }

    public function exportExcel()
    {
        $teachers = \App\Models\User::guru()->get();
        $subjects = \App\Models\Subject::all();

        $total = 0;
        $count = 0;
        foreach ($this->items as $item) {
            $total += max(0, min(4, (int) ($item['skor'] ?? 0)));
            $count++;
        }
        $max = $count * 4;

        return Excel::download(new PreObservationFilledExport([
            'teacher' => $teachers->firstWhere('id', (int) $this->teacher_id)?->name ?? '-',
            'subject' => $subjects->firstWhere('id', (int) $this->subject_id)?->name ?? '-',
            'class_name' => $this->class_name ?: '-',
            'observation_date' => $this->observation_date,
            'supervisor' => auth()->user()->name,
            'items' => array_values($this->items),
            'total' => $total,
            'max' => $max,
            'score' => $max > 0 ? round(($total / $max) * 100, 2) : 0,
        ]), 'pra-observasi-'.now()->format('Ymd-His').'.xlsx');
    }

    public function render()
    {
        return view('livewire.pre-observations.form', [
            'teachers' => \App\Models\User::guru()->get(),
            'subjects' => \App\Models\Subject::all(),
        ])->layout('layouts.app', ['title' => $this->preObservation ? 'Edit Pra Observasi' : 'Isi Pra Observasi']);
    }
}
