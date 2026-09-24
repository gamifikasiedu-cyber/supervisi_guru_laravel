<?php

namespace App\Livewire\PostSupervisions;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.post-supervisions.index')->layout('layouts.app', ['title' => 'Pasca Supervisi']);
    }
}
