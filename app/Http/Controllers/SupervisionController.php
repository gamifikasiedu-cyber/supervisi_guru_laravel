<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\Subject;
use App\Models\Supervision;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupervisionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $period = Period::fromSession();

        $supervisions = Supervision::with(['teacher', 'supervisor', 'subject', 'observations'])
            ->when($this->isGuruOnly($user), function ($query) use ($user) {
                // Guru murni hanya melihat jadwal supervisi dirinya sendiri.
                // Supervisor/admin/kepala sekolah/pengawas melihat seluruh
                // jadwal agar yang baru dibuat selalu tampil di tabel.
                $query->where('teacher_id', $user->id);
            })
            ->byPeriod($period)
            ->latest()
            ->get();

        return Inertia::render('Supervisions/Index', [
            'supervisions' => $supervisions,
        ]);
    }

    public function create()
    {
        return Inertia::render('Supervisions/Create', [
            'teachers' => User::guru()->get(),
            'subjects' => Subject::all(),
            'supervisors' => User::whereHas('userRoles', function ($query) {
                $query->whereIn('role', ['supervisor', 'kepala_sekolah', 'admin']);
            })->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_name' => 'required|string|max:255',
            'schedule_date' => 'required|date',
            'supervisor_id' => 'required|exists:users,id',
        ]);

        Supervision::create([
            'period_id' => Period::fromSession()?->id,
            'teacher_id' => $request->teacher_id,
            'supervisor_id' => $request->supervisor_id ?: auth()->id(),
            'subject_id' => $request->subject_id,
            'class_name' => $request->class_name,
            'schedule_date' => $request->schedule_date,
            'status' => 'Scheduled',
            'approval_status' => 'pending',
        ]);

        return redirect()->route('supervisions.index')->with('success', 'Jadwal supervisi berhasil dibuat.');
    }

    public function destroy(Supervision $supervision)
    {
        $supervision->delete();

        return redirect()->route('supervisions.index')->with('success', 'Jadwal supervisi berhasil dihapus.');
    }

    /**
     * Apakah user hanya berperan sebagai guru murni.
     */
    private function isGuruOnly($user): bool
    {
        return $user && $user->hasRole('guru')
            && ! $user->hasRole('admin', 'supervisor', 'kepala_sekolah', 'pengawas');
    }
}
