<?php

namespace App\Livewire\Documents;

use App\Models\TeachingDocument;
use Livewire\Component;

class Review extends Component
{
    public TeachingDocument $document;

    public string $status = 'pending';

    public string $review_notes = '';

    public function mount(TeachingDocument $document): void
    {
        abort_unless(
            auth()->user()->isSupervisor() || auth()->user()->isPengawas() || auth()->user()->isKepalaSekolah() || auth()->user()->isAdmin(),
            403
        );
        $this->document = $document;
        $this->status = $document->status;
        $this->review_notes = $document->review_notes ?? '';
    }

    public function save()
    {
        $this->validate([
            'status' => 'required|in:pending,approved,rejected',
            'review_notes' => 'nullable|string',
        ]);

        $this->document->update([
            'status' => $this->status,
            'review_notes' => $this->review_notes ?: null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        session()->flash('success', 'Review berhasil disimpan.');

        return $this->redirect(route('documents.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.documents.review')->layout('layouts.app', ['title' => 'Review Dokumen']);
    }
}
