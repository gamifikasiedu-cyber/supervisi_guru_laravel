<?php

namespace App\Livewire\Subjects;

use App\Models\Subject;
use Livewire\Component;

class Form extends Component
{
    public ?Subject $subject = null;

    public string $name = '';

    public string $code = '';

    public function mount(?Subject $subject = null): void
    {
        if ($subject?->exists) {
            $this->subject = $subject;
            $this->name = $subject->name;
            $this->code = $subject->code ?? '';
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);

        $data = ['name' => $this->name, 'code' => $this->code ?: null];

        if ($this->subject) {
            $this->subject->update($data);
            session()->flash('success', 'Mata pelajaran diperbarui.');
        } else {
            Subject::create($data);
            session()->flash('success', 'Mata pelajaran ditambahkan.');
        }

        return $this->redirect(route('subjects.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.subjects.form')->layout('layouts.app', ['title' => $this->subject ? 'Edit Mapel' : 'Tambah Mapel']);
    }
}
