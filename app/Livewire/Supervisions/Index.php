<?php

namespace App\Livewire\Supervisions;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.supervisions.index')->layout('layouts.app', ['title' => 'Jadwal Supervisi']);
    }
}
