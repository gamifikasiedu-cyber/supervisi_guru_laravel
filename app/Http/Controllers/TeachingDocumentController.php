<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\Subject;
use App\Models\TeachingDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TeachingDocumentController extends Controller
{
    public function index()
    {
        $period = Period::fromSession();

        $documents = TeachingDocument::with(['subject', 'reviewer'])
            ->where('user_id', auth()->id())
            ->byPeriod($period)
            ->latest()
            ->get();

        return Inertia::render('Guru/Documents', [
            'documents' => $documents,
            'subjects' => Subject::all(),
            'document_types' => TeachingDocument::TYPES,
        ]);
    }

    public function all()
    {
        $user = auth()->user();

        if (! $user->isSupervisor() && ! $user->isPengawas() && ! $user->isKepalaSekolah() && ! $user->isAdmin()) {
            abort(403);
        }

        $period = Period::fromSession();

        $documents = TeachingDocument::with(['user', 'subject', 'reviewer'])
            ->byPeriod($period)
            ->latest()
            ->get()
            ->map(function ($doc) {
                $doc->status_label = match ($doc->status) {
                    'pending' => 'Menunggu',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    default => 'Direview',
                };

                return $doc;
            });

        return Inertia::render('Supervisor/Documents', [
            'documents' => $documents,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_type' => 'required|in:'.implode(',', TeachingDocument::TYPES),
            'subject_id' => 'nullable|exists:subjects,id',
            'file' => 'required|file|max:'.TeachingDocument::MAX_FILE_KB,
        ]);

        // 1 paket RAR per unggahan (deteksi via ekstensi agar andal untuk arsip).
        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        if (! in_array($extension, TeachingDocument::PACKAGE_EXTENSIONS, true)) {
            return redirect()->route('documents.index')
                ->withErrors(['file' => 'File harus berupa 1 paket RAR (.rar atau .zip) maksimal 100MB.'])
                ->withInput();
        }

        $path = $file->store('teaching-documents', 'public');

        TeachingDocument::create([
            'period_id' => Period::fromSession()?->id,
            'user_id' => auth()->id(),
            'subject_id' => $request->subject_id,
            'title' => $request->title,
            'description' => $request->description,
            'document_type' => $request->document_type,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $this->formatBytes($file->getSize()),
            'status' => 'pending',
        ]);

        return redirect()->route('documents.index')->with('success', 'Dokumen perangkat ajar berhasil diunggah.');
    }

    public function destroy(TeachingDocument $document)
    {
        $this->authorizeDocument($document);

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }

    public function download(TeachingDocument $document)
    {
        $this->authorizeDocument($document);

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    private function authorizeDocument(TeachingDocument $document)
    {
        $user = auth()->user();

        if (! $user->isAdmin() && ! $user->isSupervisor() && ! $user->isPengawas() && ! $user->isKepalaSekolah() && $document->user_id !== $user->id) {
            abort(403);
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision).' '.$units[$pow];
    }
}
