<?php

namespace App\Livewire\Instruments;

use App\Http\Controllers\InstrumentController;
use App\Models\Instrument;
use App\Models\Period;
use App\Models\User;
use Livewire\Component;

class Rekap extends Component
{
    public string $teacher_id = '';

    /** @var array<string, string> */
    public array $noteKekuatan = [];

    /** @var array<string, string> */
    public array $notePerbaikan = [];

    public string $catatanKekuatan = '';

    public string $prioritasRtl = '';

    public function render()
    {
        $period = Period::fromSession();

        $records = Instrument::with('teacher')->byPeriod($period)
            ->when($this->teacher_id !== '', fn ($q) => $q->where('teacher_id', $this->teacher_id))
            ->get();

        $byTahap = [];
        $byDimensi = [];
        foreach ($records as $record) {
            foreach ($record->items ?? [] as $item) {
                $tahap = trim((string) ($item['tahap'] ?? '')) ?: '-';
                $dimensi = trim((string) ($item['dimensi'] ?? '')) ?: '-';
                $skor = max(0, min(4, (int) ($item['skor'] ?? 0)));

                $byTahap[$tahap] ??= ['jumlah' => 0, 'diperoleh' => 0];
                $byTahap[$tahap]['jumlah']++;
                $byTahap[$tahap]['diperoleh'] += $skor;

                $byDimensi[$dimensi] ??= ['jumlah' => 0, 'diperoleh' => 0];
                $byDimensi[$dimensi]['jumlah']++;
                $byDimensi[$dimensi]['diperoleh'] += $skor;
            }
        }

        $build = fn (array $grouped) => collect($grouped)->map(function ($v, $nama) {
            $maks = $v['jumlah'] * 4;
            $nilai = $maks > 0 ? round(($v['diperoleh'] / $maks) * 100, 2) : 0;

            return [
                'nama' => $nama,
                'jumlah' => $v['jumlah'],
                'maks' => $maks,
                'diperoleh' => $v['diperoleh'],
                'nilai' => $nilai,
                'predikat' => InstrumentController::predikatRekap($nilai),
            ];
        })->values()->all();

        return view('livewire.instruments.rekap', [
            'komponen' => $build($byTahap),
            'dimensi' => $build($byDimensi),
            'teachers' => User::guru()->orderBy('name')->get(),
            'selectedTeacher' => $this->teacher_id !== '' ? User::find($this->teacher_id) : null,
            'jumlahRecord' => $records->count(),
        ])->layout('layouts.app', ['title' => 'Rekapitulasi Hasil Pemantauan']);
    }
}
