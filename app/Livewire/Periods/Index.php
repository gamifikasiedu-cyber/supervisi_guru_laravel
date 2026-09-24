<?php

namespace App\Livewire\Periods;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.periods.index')->layout('layouts.app', ['title' => 'Periode Akademik']);
    }
}
