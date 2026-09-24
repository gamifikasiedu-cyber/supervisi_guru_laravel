<?php

namespace App\Livewire\PreObservations;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.pre-observations.index')->layout('layouts.app', ['title' => 'Pra Observasi']);
    }
}
