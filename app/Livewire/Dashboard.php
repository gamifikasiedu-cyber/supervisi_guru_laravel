<?php

namespace App\Livewire;

use App\Models\Observation;
use App\Models\Period;
use App\Models\Subject;
use App\Models\Supervision;
use App\Models\TeachingDocument;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $period = Period::fromSession();
        $role = $user->role ?? 'guru';
        $isGuruOnly = $role === 'guru' && ! $user->hasRole('admin', 'supervisor', 'kepala_sekolah', 'pengawas');

        $data = match ($role) {
            'admin' => [
                'stats' => [
                    ['Total Pengguna', User::count(), '👥', 'blue'],
                    ['Guru', User::guru()->count(), '🧑‍🏫', 'amber'],
                    ['Mata Pelajaran', Subject::count(), '📚', 'rose'],
                    ['Dokumen Pending', TeachingDocument::byPeriod($period)->where('status', 'pending')->count(), '⏳', 'violet'],
                ],
            ],
            'guru' => [
                'stats' => [
                    ['Dokumen Saya', TeachingDocument::where('user_id', $user->id)->byPeriod($period)->count(), '📄', 'blue'],
                    ['Menunggu Review', TeachingDocument::where('user_id', $user->id)->byPeriod($period)->where('status', 'pending')->count(), '⏳', 'amber'],
                    ['Disetujui', TeachingDocument::where('user_id', $user->id)->byPeriod($period)->where('status', 'approved')->count(), '✅', 'rose'],
                    ['Jadwal Supervisi', Supervision::where('teacher_id', $user->id)->byPeriod($period)->count(), '📅', 'violet'],
                ],
            ],
            'supervisor' => [
                'stats' => [
                    ['Perlu Review', TeachingDocument::byPeriod($period)->where('status', 'pending')->count(), '⏳', 'blue'],
                    ['Observasi Saya', Observation::where('supervisor_id', $user->id)->byPeriod($period)->count(), '🔍', 'amber'],
                    ['Supervisi Aktif', Supervision::where('supervisor_id', $user->id)->where('status', 'Scheduled')->byPeriod($period)->count(), '📅', 'rose'],
                    ['Total Supervisi', Supervision::byPeriod($period)->count(), '📋', 'violet'],
                ],
            ],
            'kepala_sekolah' => [
                'stats' => [
                    ['Total Guru', User::guru()->count(), '🧑‍🏫', 'blue'],
                    ['Menunggu Persetujuan', Observation::byPeriod($period)->where('status', 'submitted')->count(), '⏳', 'amber'],
                    ['Disetujui', Observation::byPeriod($period)->where('status', 'approved')->count(), '✅', 'rose'],
                    ['Rata-rata Nilai', round(Observation::byPeriod($period)->where('status', '!=', 'draft')->avg('total_score') ?? 0, 2), '⭐', 'violet'],
                ],
            ],
            default => [
                'stats' => [
                    ['Total Guru', User::guru()->count(), '🧑‍🏫', 'blue'],
                    ['Supervisi Selesai', Supervision::byPeriod($period)->where('status', 'Completed')->count(), '✅', 'amber'],
                    ['Observasi Disetujui', Observation::byPeriod($period)->where('status', 'approved')->count(), '📝', 'rose'],
                    ['Rata-rata Sekolah', round(Observation::byPeriod($period)->where('status', 'approved')->avg('total_score') ?? 0, 2), '⭐', 'violet'],
                ],
            ],
        };

        // Grafik laporan: jumlah supervisi 8 bulan terakhir
        $months = [];
        for ($i = 7; $i >= 0; $i--) {
            $months[] = now()->copy()->subMonths($i);
        }
        $supervisions = Supervision::query()
            ->when($isGuruOnly, fn ($q) => $q->where('teacher_id', $user->id))
            ->where('schedule_date', '>=', $months[0]->copy()->startOfMonth()->toDateString())
            ->get(['schedule_date']);
        $chart = array_map(function (Carbon $m) use ($supervisions) {
            $key = $m->format('Y-m');

            return [
                'label' => $m->translatedFormat('M'),
                'value' => $supervisions->filter(fn ($s) => substr((string) $s->schedule_date, 0, 7) === $key)->count(),
            ];
        }, $months);

        // Donut: distribusi status dokumen
        $docQuery = TeachingDocument::query()
            ->when($isGuruOnly, fn ($q) => $q->where('user_id', $user->id))
            ->byPeriod($period);
        $approved = (clone $docQuery)->where('status', 'approved')->count();
        $pending = (clone $docQuery)->where('status', 'pending')->count();
        $rejected = (clone $docQuery)->where('status', 'rejected')->count();
        $donutTotal = max($approved + $pending + $rejected, 1);

        // Supervisi terbaru
        $recent = Supervision::with(['teacher', 'subject'])
            ->when($isGuruOnly, fn ($q) => $q->where('teacher_id', $user->id))
            ->byPeriod($period)
            ->latest()
            ->take(5)
            ->get();

        // Guru terbaik (rata-rata observasi disetujui)
        $top = User::guru()
            ->with(['observationsReceived' => fn ($q) => $q->where('status', 'approved')->byPeriod($period)])
            ->get()
            ->map(fn ($g) => [
                'name' => $g->name,
                'mapel' => $g->mata_pelajaran ?? '-',
                'avg' => $g->observationsReceived->count() ? round($g->observationsReceived->avg('total_score'), 1) : 0,
                'count' => $g->observationsReceived->count(),
            ])
            ->sortByDesc('avg')
            ->take(3)
            ->values();

        $titles = ['admin' => 'Dashboard', 'guru' => 'Dashboard', 'supervisor' => 'Dashboard', 'kepala_sekolah' => 'Dashboard', 'pengawas' => 'Dashboard'];

        return view('livewire.dashboard', array_merge($data, [
            'role' => $role,
            'chart' => $chart,
            'donut' => [
                ['label' => 'Disetujui', 'value' => $approved, 'color' => '#4f7cff'],
                ['label' => 'Menunggu', 'value' => $pending, 'color' => '#f5b942'],
                ['label' => 'Ditolak', 'value' => $rejected, 'color' => '#ff6b4a'],
                'pct' => round($approved / $donutTotal * 100),
            ],
            'recent' => $recent,
            'top' => $top,
        ]))->layout('layouts.app', ['title' => $titles[$role] ?? 'Dashboard']);
    }
}
