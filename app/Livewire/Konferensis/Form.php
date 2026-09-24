<?php

namespace App\Livewire\Konferensis;

use App\Http\Controllers\KonferensiController;
use App\Models\Period;
use App\Models\PreObservationKonferensi;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public ?PreObservationKonferensi $konferensi = null;

    public string $teacher_id = '';

    public string $subject_id = '';

    public string $class_name = '';

    public string $observation_date = '';

    public array $items = [];

    public array $photos = [];

    public array $existingPhotos = [];

    public function mount(?PreObservationKonferensi $konferensi = null): void
    {
        $this->observation_date = now()->format('Y-m-d');

        if ($konferensi?->exists) {
            $this->konferensi = $konferensi;
            $this->teacher_id = (string) ($konferensi->teacher_id ?? '');
            $this->subject_id = (string) ($konferensi->subject_id ?? '');
            $this->class_name = $konferensi->class_name ?? '';
            $this->observation_date = $konferensi->observation_date?->format('Y-m-d') ?? now()->format('Y-m-d');
            $this->items = array_map(fn ($it) => [
                'pertanyaan' => $it['pertanyaan'] ?? '',
                'catatan_guru' => $it['catatan_guru'] ?? '',
                'catatan_supervisor' => $it['catatan_supervisor'] ?? '',
                'kesepakatan' => $it['kesepakatan'] ?? '',
                'tindak_lanjut' => $it['tindak_lanjut'] ?? '',
            ], $konferensi->items ?? []);
            $this->existingPhotos = array_values((array) $konferensi->dokumentasi_foto);
        } else {
            $this->items = array_map(fn ($row) => [
                'pertanyaan' => $row['pertanyaan'] ?? '',
                'catatan_guru' => '',
                'catatan_supervisor' => '',
                'kesepakatan' => '',
                'tindak_lanjut' => '',
            ], KonferensiController::rows());
        }
    }

    public function removeExistingPhoto(int $index): void
    {
        $path = $this->existingPhotos[$index] ?? null;
        if ($path && $this->konferensi) {
            Storage::disk('public')->delete($path);
            $photos = array_values(array_diff($this->existingPhotos, [$path]));
            $this->konferensi->update(['dokumentasi_foto' => $photos]);
            $this->existingPhotos = $photos;
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
            'items.*.pertanyaan' => 'required|string|max:2000',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|max:5120',
        ]);

        $items = [];
        foreach (array_values($this->items) as $i => $item) {
            $items[] = [
                'no' => $i + 1,
                'pertanyaan' => $item['pertanyaan'] ?? '',
                'catatan_guru' => $item['catatan_guru'] ?? '',
                'catatan_supervisor' => $item['catatan_supervisor'] ?? '',
                'kesepakatan' => $item['kesepakatan'] ?? '',
                'tindak_lanjut' => $item['tindak_lanjut'] ?? '',
            ];
        }

        $stored = [];
        foreach ($this->photos as $photo) {
            $stored[] = $photo->store('konferensi-dokumentasi', 'public');
        }

        $data = [
            'teacher_id' => $this->teacher_id ?: null,
            'subject_id' => $this->subject_id ?: null,
            'class_name' => $this->class_name ?: null,
            'observation_date' => $this->observation_date,
            'items' => $items,
        ];

        if ($this->konferensi) {
            if (count($this->existingPhotos) + count($stored) > KonferensiController::MAX_PHOTOS) {
                $this->addError('photos', 'Maksimal '.KonferensiController::MAX_PHOTOS.' foto per record.');

                return;
            }
            $data['dokumentasi_foto'] = array_merge($this->existingPhotos, $stored);
            $this->konferensi->update($data);
            session()->flash('success', 'Konferensi diperbarui.');
        } else {
            $data['period_id'] = Period::fromSession()?->id;
            $data['supervisor_id'] = auth()->id();
            $data['dokumentasi_foto'] = $stored;
            PreObservationKonferensi::create($data);
            session()->flash('success', 'Konferensi disimpan.');
        }

        return $this->redirect(route('konferensis.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.konferensis.form', [
            'teachers' => \App\Models\User::guru()->get(),
            'subjects' => \App\Models\Subject::all(),
        ])->layout('layouts.app', ['title' => $this->konferensi ? 'Edit Konferensi' : 'Isi Konferensi']);
    }
}
