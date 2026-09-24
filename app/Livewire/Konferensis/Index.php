<?php

namespace App\Livewire\Konferensis;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.konferensis.index')->layout('layouts.app', ['title' => 'Konferensi-Wawancara']);
    }
}
