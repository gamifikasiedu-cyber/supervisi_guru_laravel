<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use App\Models\Period;
use App\Models\PostSupervision;
use App\Models\PreObservation;
use App\Models\PreObservationKonferensi;
use App\Models\Subject;
use App\Models\Supervision;
use App\Models\TeachingDocument;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class RecordController extends Controller
{
    private const MAP = [
        'user' => User::class,
        'subject' => Subject::class,
        'period' => Period::class,
        'supervision' => Supervision::class,
        'document' => TeachingDocument::class,
        'instrument' => Instrument::class,
        'pre-observation' => PreObservation::class,
        'konferensi' => PreObservationKonferensi::class,
        'post-supervision' => PostSupervision::class,
    ];

    public function destroy(string $type, int $id)
    {
        abort_unless(isset(self::MAP[$type]), 404);

        $record = self::MAP[$type]::findOrFail($id);
        $me = auth()->user();

        match ($type) {
            'user' => $this->ensure($me->isAdmin() && $record->id !== $me->id),
            'subject', 'period' => $this->ensure($me->isAdmin()),
            'document' => $this->ensure(
                $me->isAdmin() || $me->isSupervisor() || $me->isPengawas() || $me->isKepalaSekolah() || $record->user_id === $me->id
            ),
            default => $this->ensure($me !== null),
        };

        if ($type === 'document') {
            Storage::disk('public')->delete($record->file_path);
        }

        if ($type === 'konferensi') {
            foreach ((array) $record->dokumentasi_foto as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        if ($type === 'period' && (int) session('active_period_id') === (int) $record->id) {
            session()->forget('active_period_id');
        }

        $record->delete();

        return back()->with('success', 'Data berhasil dihapus.');
    }

    private function ensure(bool $allowed): void
    {
        abort_unless($allowed, 403);
    }
}
