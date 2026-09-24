<?php

namespace App\Http\Controllers;

use App\Models\TeachingDocument;
use Illuminate\Http\Request;

class ObservationController extends Controller
{
    /**
     * Halaman Observasi lama (/observations) telah dihapus.
     * Controller ini dipertahankan hanya untuk review dokumen perangkat ajar.
     */
    public function reviewDocument(Request $request, TeachingDocument $document)
    {
        $request->validate([
            'review_notes' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $document->update([
            'status' => $request->status,
            'review_notes' => $request->review_notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Review dokumen berhasil disimpan.');
    }
}
