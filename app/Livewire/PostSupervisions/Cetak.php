<?php

namespace App\Livewire\PostSupervisions;

use App\Http\Controllers\PostSupervisionController;
use App\Models\Period;
use App\Models\PostSupervision;
use Livewire\Component;

class Cetak extends Component
{
    public string $bagian = '';

    public function mount(): void
    {
        $bagian = (string) request('bagian', '');
        abort_unless($bagian && PostSupervisionController::config($bagian), 404);
        $this->bagian = $bagian;
    }

    public function render()
    {
        $cfg = PostSupervisionController::config($this->bagian);

        $records = PostSupervision::with(['teacher', 'subject'])
            ->byPeriod(Period::fromSession())
            ->byBagian($this->bagian)
            ->latest()
            ->get();

        return view('livewire.post-supervisions.cetak', [
            'cfg' => $cfg,
            'records' => $records,
            'kepsek' => \App\Models\User::kepalaSekolah()->first(),
        ])->layout('layouts.app', ['title' => 'Cetak '.$cfg['label']]);
    }
}
