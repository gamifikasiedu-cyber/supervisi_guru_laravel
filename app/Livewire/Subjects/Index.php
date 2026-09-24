<?php

namespace App\Livewire\Subjects;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.subjects.index')->layout('layouts.app', ['title' => 'Mata Pelajaran']);
    }
}
