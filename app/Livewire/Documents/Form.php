<?php

namespace App\Livewire\Documents;

use App\Models\Period;
use App\Models\TeachingDocument;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public ?TeachingDocument $document = null;

    public string $title = '';

    public string $description = '';

    public string $document_type = '';

    public string $subject_id = '';

    public $file;

    public function mount(?TeachingDocument $document = null): void
    {
        if ($document?->exists) {
            abort_unless(
                auth()->user()->isAdmin() || $document->user_id === auth()->id(),
                403
            );
            $this->document = $document;
            $this->title = $document->title;
            $this->description = $document->description ?? '';
            $this->document_type = $document->document_type;
            $this->subject_id = (string) ($document->subject_id ?? '');
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_type' => 'required|in:'.implode(',', TeachingDocument::TYPES),
            'subject_id' => 'nullable|exists:subjects,id',
            'file' => ($this->document ? 'nullable' : 'required').'|file|max:'.TeachingDocument::MAX_FILE_KB,
        ]);

        $data = [
            'title' => $this->title,
            'description' => $this->description ?: null,
            'document_type' => $this->document_type,
            'subject_id' => $this->subject_id ?: null,
        ];

        if ($this->file) {
            $ext = strtolower($this->file->getClientOriginalExtension());
            if (! in_array($ext, TeachingDocument::PACKAGE_EXTENSIONS, true)) {
                $this->addError('file', 'File harus berupa 1 paket RAR (.rar atau .zip) maksimal 100MB.');

                return;
            }
            $path = $this->file->store('teaching-documents', 'public');
            $data['file_path'] = $path;
            $data['file_name'] = $this->file->getClientOriginalName();
            $bytes = $this->file->getSize();
            $units = ['B', 'KB', 'MB', 'GB'];
            $pow = min((int) floor(($bytes ? log($bytes) : 0) / log(1024)), 3);
            $data['file_size'] = round($bytes / pow(1024, $pow), 2).' '.$units[$pow];
        }

        if ($this->document) {
            if (isset($data['file_path'])) {
                Storage::disk('public')->delete($this->document->file_path);
                $data['status'] = 'pending';
            }
            $this->document->update($data);
            session()->flash('success', 'Dokumen diperbarui.');
        } else {
            $data['period_id'] = Period::fromSession()?->id;
            $data['user_id'] = auth()->id();
            $data['status'] = 'pending';
            TeachingDocument::create($data);
            session()->flash('success', 'Dokumen berhasil diunggah.');
        }

        return $this->redirect(route('documents.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.documents.form', [
            'subjects' => \App\Models\Subject::all(),
            'types' => TeachingDocument::TYPES,
        ])->layout('layouts.app', ['title' => $this->document ? 'Edit Dokumen' : 'Unggah Dokumen']);
    }
}
