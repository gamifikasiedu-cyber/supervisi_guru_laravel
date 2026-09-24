<?php

namespace App\Livewire\Instruments;

use App\Exports\InstrumentFilledExport;
use App\Exports\InstrumentTemplateExport;
use App\Http\Controllers\InstrumentController;
use App\Imports\InstrumentImport;
use App\Models\Instrument;
use App\Models\Period;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Form extends Component
{
    use WithFileUploads;

    public ?Instrument $instrument = null;

    public string $teacher_id = '';

    public string $subject_id = '';

    public string $class_name = '';

    public string $observation_date = '';

    public array $items = [];

    public $importFile;

    public function mount(?Instrument $instrument = null): void
    {
        $this->observation_date = now()->format('Y-m-d');

        if ($instrument?->exists) {
            $this->instrument = $instrument;
            $this->teacher_id = (string) ($instrument->teacher_id ?? '');
            $this->subject_id = (string) ($instrument->subject_id ?? '');
            $this->class_name = $instrument->class_name ?? '';
            $this->observation_date = $instrument->observation_date?->format('Y-m-d') ?? now()->format('Y-m-d');
            $this->items = array_map(fn ($it) => [
                'tahap' => $it['tahap'] ?? '',
                'dimensi' => $it['dimensi'] ?? '',
                'indikator' => $it['indikator'] ?? '',
                'skor' => $it['skor'] ?? '',
                'bukti' => $it['bukti'] ?? '',
                'catatan' => $it['catatan'] ?? '',
            ], $instrument->items ?? []);
        } else {
            $this->items = array_map(fn ($row) => [
                'tahap' => $row['tahap'] ?? '',
                'dimensi' => $row['dimensi'] ?? '',
                'indikator' => $row['indikator'] ?? '',
                'skor' => '',
                'bukti' => '',
                'catatan' => '',
            ], InstrumentController::rows());
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
            'items.*.bukti' => 'nullable|string|max:2000',
            'items.*.catatan' => 'nullable|string|max:2000',
        ]);

        $items = [];
        foreach (array_values($this->items) as $i => $item) {
            $items[] = [
                'no' => $i + 1,
                'tahap' => $item['tahap'] ?? '-',
                'dimensi' => $item['dimensi'] ?? '',
                'indikator' => $item['indikator'] ?? '',
                'skor' => max(0, min(4, (int) ($item['skor'] ?? 0))),
                'bukti' => $item['bukti'] ?? '',
                'catatan' => $item['catatan'] ?? '',
            ];
        }

        $data = [
            'teacher_id' => $this->teacher_id ?: null,
            'subject_id' => $this->subject_id ?: null,
            'class_name' => $this->class_name ?: null,
            'observation_date' => $this->observation_date,
            'items' => $items,
        ];

        if ($this->instrument) {
            $this->instrument->update($data);
            session()->flash('success', 'Pemantauan diperbarui.');
        } else {
            $data['period_id'] = Period::fromSession()?->id;
            $data['supervisor_id'] = auth()->id();
            Instrument::create($data);
            session()->flash('success', 'Pemantauan disimpan.');
        }

        return $this->redirect(route('pemantauan.index'), navigate: true);
    }

    public function importExcel()
    {
        $this->validate(['importFile' => 'required|file|max:10240']);

        $ext = strtolower($this->importFile->getClientOriginalExtension());
        if (! in_array($ext, ['xlsx', 'xls', 'csv'], true)) {
            $this->addError('importFile', 'Format file tidak valid. Gunakan .xlsx, .xls, atau .csv.');

            return;
        }

        $import = new InstrumentImport;
        Excel::import($import, $this->importFile->getRealPath());

        if (! empty($import->rowErrors)) {
            $this->addError('importFile', implode(' ', array_slice($import->rowErrors, 0, 5)));

            return;
        }

        if (empty($import->items)) {
            $this->addError('importFile', 'File tidak berisi baris indikator yang valid.');

            return;
        }

        if (count($import->items) > 200) {
            $this->addError('importFile', 'Maksimal 200 baris indikator.');

            return;
        }

        $this->items = array_map(fn ($row) => [
            'tahap' => $row['tahap'] ?? '',
            'dimensi' => $row['dimensi'] ?? '',
            'indikator' => $row['indikator'] ?? '',
            'skor' => '',
            'bukti' => '',
            'catatan' => '',
        ], $import->items);

        Setting::set(InstrumentController::DEFAULT_ROWS_KEY, json_encode($import->items, JSON_UNESCAPED_UNICODE));

        $this->reset('importFile');
        session()->flash('success', 'Berhasil memuat '.count($import->items).' baris ke tabel. Lengkapi skor lalu Simpan.');
    }

    public function exportTemplate()
    {
        $rows = $this->instrument?->items
            ?? array_map(fn ($it) => [
                'tahap' => $it['tahap'] ?? '',
                'dimensi' => $it['dimensi'] ?? '',
                'indikator' => $it['indikator'] ?? '',
            ], $this->items);

        return Excel::download(new InstrumentTemplateExport(array_values($rows)), 'template-instrumen-pemantauan.xlsx');
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

        return Excel::download(new InstrumentFilledExport([
            'teacher' => $teachers->firstWhere('id', (int) $this->teacher_id)?->name ?? '-',
            'subject' => $subjects->firstWhere('id', (int) $this->subject_id)?->name ?? '-',
            'class_name' => $this->class_name ?: '-',
            'observation_date' => $this->observation_date,
            'supervisor' => auth()->user()->name,
            'items' => array_values($this->items),
            'total' => $total,
            'max' => $max,
            'score' => $max > 0 ? round(($total / $max) * 100, 2) : 0,
        ]), 'instrumen-pemantauan-'.now()->format('Ymd-His').'.xlsx');
    }

    public function render()
    {
        return view('livewire.instruments.form', [
            'teachers' => \App\Models\User::guru()->get(),
            'subjects' => \App\Models\Subject::all(),
        ])->layout('layouts.app', ['title' => $this->instrument ? 'Edit Pemantauan' : 'Isi Pemantauan']);
    }
}
