<?php

namespace App\Livewire\Periods;

use App\Models\Period;
use Livewire\Component;

class Form extends Component
{
    public ?Period $period = null;

    public string $tahun_ajaran = '';

    public string $semester = 'Ganjil';

    public function mount(?Period $period = null): void
    {
        if ($period?->exists) {
            $this->period = $period;
            $this->tahun_ajaran = $period->tahun_ajaran;
            $this->semester = $period->semester;
        }
    }

    public function save()
    {
        $this->validate([
            'tahun_ajaran' => 'required|string|regex:/^\d{4}\/\d{4}$/',
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        [$tahunAwal, $tahunAkhir] = explode('/', $this->tahun_ajaran);

        if ((int) $tahunAkhir !== (int) $tahunAwal + 1) {
            $this->addError('tahun_ajaran', 'Tahun ajaran tidak valid. Contoh: 2026/2027.');

            return;
        }

        $data = [
            'tahun_ajaran' => $this->tahun_ajaran,
            'semester' => $this->semester,
            'start_date' => $this->semester === 'Ganjil' ? $tahunAwal.'-07-01' : $tahunAkhir.'-01-01',
            'end_date' => $this->semester === 'Ganjil' ? $tahunAwal.'-12-31' : $tahunAkhir.'-06-30',
        ];

        if ($this->period) {
            $this->period->update($data);
            session()->flash('success', 'Periode diperbarui.');
        } else {
            $data['is_active'] = true;
            Period::create($data);
            session()->flash('success', 'Periode ditambahkan.');
        }

        return $this->redirect(route('periods.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.periods.form')->layout('layouts.app', ['title' => $this->period ? 'Edit Periode' : 'Tambah Periode']);
    }
}
