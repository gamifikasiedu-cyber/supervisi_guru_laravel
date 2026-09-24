<?php

namespace App\Livewire\Instruments;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.instruments.index')->layout('layouts.app', ['title' => 'Pemantauan']);
    }
}
