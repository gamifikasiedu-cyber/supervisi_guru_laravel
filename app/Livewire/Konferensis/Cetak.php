<?php

namespace App\Livewire\Konferensis;

use App\Models\PreObservationKonferensi;
use Livewire\Component;

class Cetak extends Component
{
    public PreObservationKonferensi $konferensi;

    public function mount(PreObservationKonferensi $konferensi): void
    {
        $this->konferensi = $konferensi->load(['teacher', 'subject', 'supervisor']);
    }

    public function render()
    {
        return view('livewire.konferensis.cetak')->layout('layouts.app', ['title' => 'Cetak Konferensi']);
    }
}
