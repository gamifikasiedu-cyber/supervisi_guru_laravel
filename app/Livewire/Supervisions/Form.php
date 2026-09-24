<?php

namespace App\Livewire\Supervisions;

use App\Models\Period;
use App\Models\Supervision;
use Livewire\Component;

class Form extends Component
{
    public ?Supervision $supervision = null;

    public string $teacher_id = '';

    public string $supervisor_id = '';

    public string $subject_id = '';

    public string $class_name = '';

    public string $schedule_date = '';

    public string $status = 'Scheduled';

    public string $notes = '';

    public function mount(?Supervision $supervision = null): void
    {
        if ($supervision?->exists) {
            $this->supervision = $supervision;
            $this->teacher_id = (string) $supervision->teacher_id;
            $this->supervisor_id = (string) $supervision->supervisor_id;
            $this->subject_id = (string) $supervision->subject_id;
            $this->class_name = $supervision->class_name;
            $this->schedule_date = $supervision->schedule_date?->format('Y-m-d');
            $this->status = $supervision->status;
            $this->notes = $supervision->notes ?? '';
        }
    }

    public function save()
    {
        $this->validate([
            'teacher_id' => 'required|exists:users,id',
            'supervisor_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_name' => 'required|string|max:255',
            'schedule_date' => 'required|date',
            'status' => 'required|in:Scheduled,Completed,Cancelled',
        ]);

        $data = [
            'teacher_id' => $this->teacher_id,
            'supervisor_id' => $this->supervisor_id,
            'subject_id' => $this->subject_id,
            'class_name' => $this->class_name,
            'schedule_date' => $this->schedule_date,
            'status' => $this->status,
            'notes' => $this->notes ?: null,
        ];

        if ($this->supervision) {
            $this->supervision->update($data);
            session()->flash('success', 'Jadwal diperbarui.');
        } else {
            $data['period_id'] = Period::fromSession()?->id;
            $data['approval_status'] = 'pending';
            Supervision::create($data);
            session()->flash('success', 'Jadwal supervisi dibuat.');
        }

        return $this->redirect(route('supervisions.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.supervisions.form', [
            'teachers' => \App\Models\User::guru()->get(),
            'subjects' => \App\Models\Subject::all(),
            'supervisors' => \App\Models\User::whereHas('userRoles', fn ($q) => $q->whereIn('role', ['supervisor', 'kepala_sekolah', 'admin']))->get(),
        ])->layout('layouts.app', ['title' => $this->supervision ? 'Edit Jadwal' : 'Buat Jadwal']);
    }
}
