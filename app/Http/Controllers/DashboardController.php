<?php

namespace App\Http\Controllers;

use App\Exports\SchoolReportExport;
use App\Models\Observation;
use App\Models\Period;
use App\Models\Setting;
use App\Models\Subject;
use App\Models\Supervision;
use App\Models\TeachingDocument;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $period = Period::fromSession();

        switch ($user->role) {
            case 'admin':
                return $this->adminDashboard($period);
            case 'guru':
                return $this->guruDashboard($period);
            case 'supervisor':
                return $this->supervisorDashboard($period);
            case 'kepala_sekolah':
                return $this->kepalaSekolahDashboard($period);
            case 'pengawas':
                return $this->pengawasDashboard($period);
            default:
                return $this->adminDashboard($period);
        }
    }

    private function adminDashboard(?Period $period)
    {
        return Inertia::render('Admin/Index', [
            'stats' => [
                'guru' => User::guru()->count(),
                'supervisor' => User::supervisor()->count(),
                'kepala_sekolah' => User::kepalaSekolah()->count(),
                'pengawas' => User::pengawas()->count(),
                'total_pengguna' => User::count(),
                'total_mapel' => Subject::count(),
                'total_dokumen' => TeachingDocument::byPeriod($period)->count(),
                'total_supervisi' => Supervision::byPeriod($period)->count(),
                'total_observasi' => Observation::byPeriod($period)->count(),
                'dokumen_pending' => TeachingDocument::byPeriod($period)->where('status', 'pending')->count(),
            ],
            'users' => User::latest()->take(8)->get(),
            'subjects' => Subject::all(),
        ]);
    }

    private function guruDashboard(?Period $period)
    {
        $user = auth()->user();

        return Inertia::render('Guru/Index', [
            'stats' => [
                'total_dokumen' => TeachingDocument::where('user_id', $user->id)->byPeriod($period)->count(),
                'dokumen_pending' => TeachingDocument::where('user_id', $user->id)->byPeriod($period)->where('status', 'pending')->count(),
                'dokumen_approved' => TeachingDocument::where('user_id', $user->id)->byPeriod($period)->where('status', 'approved')->count(),
                'total_supervisi' => Supervision::where('teacher_id', $user->id)->byPeriod($period)->count(),
            ],
            'documents' => TeachingDocument::with(['subject', 'reviewer'])
                ->where('user_id', $user->id)
                ->byPeriod($period)
                ->latest()
                ->get(),
            'supervisions' => Supervision::with(['subject', 'supervisor'])
                ->where('teacher_id', $user->id)
                ->byPeriod($period)
                ->latest()
                ->take(5)
                ->get(),
            'subjects' => Subject::all(),
            'document_types' => TeachingDocument::TYPES,
        ]);
    }

    private function supervisorDashboard(?Period $period)
    {
        $user = auth()->user();

        return Inertia::render('Supervisor/Index', [
            'stats' => [
                'total_dokumen_direview' => TeachingDocument::where('reviewed_by', $user->id)->byPeriod($period)->count(),
                'dokumen_pending' => TeachingDocument::byPeriod($period)->where('status', 'pending')->count(),
                'dokumen_approved' => TeachingDocument::byPeriod($period)->where('status', 'approved')->count(),
                'total_observasi' => Observation::where('supervisor_id', $user->id)->byPeriod($period)->count(),
                'supervisi_aktif' => Supervision::where('supervisor_id', $user->id)
                    ->where('status', 'Scheduled')
                    ->byPeriod($period)
                    ->count(),
            ],
            'pending_documents' => TeachingDocument::with(['user', 'subject'])
                ->where('status', 'pending')
                ->byPeriod($period)
                ->latest()
                ->get(),
            'my_observations' => Observation::with(['teacher', 'subject', 'supervision'])
                ->where('supervisor_id', $user->id)
                ->byPeriod($period)
                ->latest()
                ->take(5)
                ->get(),
            'teachers' => User::guru()->get(),
            'subjects' => Subject::all(),
            'supervisions' => Supervision::with(['teacher', 'subject'])
                ->where('supervisor_id', $user->id)
                ->where('status', 'Scheduled')
                ->byPeriod($period)
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }

    private function kepalaSekolahDashboard(?Period $period)
    {
        return Inertia::render('KepalaSekolah/Index', [
            'stats' => [
                'total_guru' => User::guru()->count(),
                'total_supervisi' => Supervision::byPeriod($period)->count(),
                'total_observasi' => Observation::byPeriod($period)->count(),
                'approval_pending' => Observation::byPeriod($period)->where('status', 'submitted')->count(),
                'approval_approved' => Observation::byPeriod($period)->where('status', 'approved')->count(),
                'rata_rata_nilai' => round(
                    Observation::byPeriod($period)->where('status', '!=', 'draft')->avg('total_score') ?? 0,
                    2
                ),
            ],
            'pending_observations' => Observation::with(['teacher', 'supervisor', 'subject'])
                ->where('status', 'submitted')
                ->byPeriod($period)
                ->latest()
                ->get(),
            'recent_observations' => Observation::with(['teacher', 'supervisor', 'subject'])
                ->byPeriod($period)
                ->latest()
                ->take(5)
                ->get(),
            'pending_documents' => TeachingDocument::with(['user', 'subject'])
                ->where('status', 'pending')
                ->byPeriod($period)
                ->latest()
                ->get(),
            'guru_recap' => User::guru()
                ->withCount('observationsReceived')
                ->with('observationsReceived', function ($query) use ($period) {
                    $query->where('status', 'approved')->byPeriod($period);
                })
                ->get()
                ->map(function ($guru) {
                    $observations = $guru->observationsReceived;

                    return [
                        'id' => $guru->id,
                        'name' => $guru->name,
                        'nip' => $guru->nip,
                        'mata_pelajaran' => $guru->mata_pelajaran,
                        'total_observasi' => $observations->count(),
                        'rata_rata_nilai' => $observations->count() > 0
                            ? round($observations->avg('total_score'), 2)
                            : 0,
                    ];
                }),
            'chart_data' => $this->schoolChartData($period),
            'laporan' => $this->schoolReportData($period),
        ]);
    }

    private function pengawasDashboard(?Period $period)
    {
        return Inertia::render('Pengawas/Index', [
            'stats' => [
                'total_guru' => User::guru()->count(),
                'total_supervisi_selesai' => Supervision::byPeriod($period)->where('status', 'Completed')->count(),
                'total_observasi' => Observation::byPeriod($period)->count(),
                'observasi_approved' => Observation::byPeriod($period)->where('status', 'approved')->count(),
                'rata_rata_sekolah' => round(
                    Observation::byPeriod($period)->where('status', 'approved')->avg('total_score') ?? 0,
                    2
                ),
                'kepatuhan_dokumen' => round(
                    (TeachingDocument::byPeriod($period)->where('status', 'approved')->count()
                        / max(TeachingDocument::byPeriod($period)->count(), 1)) * 100,
                    0
                ),
            ],
            'recent_observations' => Observation::with(['teacher', 'supervisor', 'subject'])
                ->byPeriod($period)
                ->latest()
                ->take(10)
                ->get(),
            'approved_observations' => Observation::with(['teacher', 'supervisor', 'subject'])
                ->where('status', 'approved')
                ->byPeriod($period)
                ->latest()
                ->get(),
            'pending_documents' => TeachingDocument::with(['user', 'subject'])
                ->where('status', 'pending')
                ->byPeriod($period)
                ->latest()
                ->get(),
            'guru_recap' => User::guru()
                ->with('observationsReceived', function ($query) use ($period) {
                    $query->where('status', 'approved')->byPeriod($period);
                })
                ->get()
                ->map(function ($guru) {
                    $observations = $guru->observationsReceived;

                    return [
                        'id' => $guru->id,
                        'name' => $guru->name,
                        'nip' => $guru->nip,
                        'mata_pelajaran' => $guru->mata_pelajaran,
                        'total_observasi' => $observations->count(),
                        'rata_rata_nilai' => $observations->count() > 0
                            ? round($observations->avg('total_score'), 2)
                            : 0,
                        'status' => $observations->count() > 0 ? 'Sesuai Standar' : 'Belum Dinilai',
                    ];
                }),
            'chart_data' => $this->schoolChartData($period),
            'laporan' => $this->schoolReportData($period),
        ]);
    }

    private function schoolChartData(?Period $period)
    {
        return User::guru()
            ->with('observationsReceived', function ($query) use ($period) {
                $query->where('status', 'approved')->byPeriod($period);
            })
            ->orderBy('name')
            ->get()
            ->map(function ($guru) {
                $observations = $guru->observationsReceived;

                return [
                    'nama' => $guru->name,
                    'nilai' => $observations->count() > 0
                        ? round($observations->avg('total_score'), 2)
                        : 0,
                ];
            })
            ->values();
    }

    private function schoolReportData(?Period $period)
    {
        $observations = Observation::with(['teacher', 'supervisor', 'subject'])
            ->where('status', 'approved')
            ->byPeriod($period)
            ->orderByDesc('observation_date')
            ->get();

        $observationsNonDraft = Observation::byPeriod($period)->where('status', '!=', 'draft');

        return [
            'periode' => $period
                ? $period->tahun_ajaran.' - '.$period->semester
                : now()->format('Y'),
            'ssekolah' => $period
                ? substr($period->tahun_ajaran, 0, 4)
                : now()->format('Y'),
            'statistik' => [
                'total_guru' => User::guru()->count(),
                'total_supervisi' => Supervision::byPeriod($period)->count(),
                'total_observasi' => Observation::byPeriod($period)->count(),
                'observasi_approved' => Observation::byPeriod($period)->where('status', 'approved')->count(),
                'observasi_pending' => Observation::byPeriod($period)->where('status', 'submitted')->count(),
                'rata_rata_sekolah' => round(
                    $observationsNonDraft->clone()->avg('total_score') ?? 0,
                    2
                ),
                'total_dokumen' => TeachingDocument::byPeriod($period)->count(),
                'dokumen_approved' => TeachingDocument::byPeriod($period)->where('status', 'approved')->count(),
            ],
            'guru_recap' => User::guru()
                ->with('observationsReceived', function ($query) use ($period) {
                    $query->where('status', 'approved')->byPeriod($period);
                })
                ->orderBy('name')
                ->get()
                ->map(function ($guru) {
                    $observations = $guru->observationsReceived;

                    return [
                        'name' => $guru->name,
                        'nip' => $guru->nip,
                        'mata_pelajaran' => $guru->mata_pelajaran,
                        'total_observasi' => $observations->count(),
                        'rata_rata_planning' => $observations->count() > 0 ? round($observations->avg('score_planning'), 2) : 0,
                        'rata_rata_delivery' => $observations->count() > 0 ? round($observations->avg('score_delivery'), 2) : 0,
                        'rata_rata_management' => $observations->count() > 0 ? round($observations->avg('score_management'), 2) : 0,
                        'rata_rata_assessment' => $observations->count() > 0 ? round($observations->avg('score_assessment'), 2) : 0,
                        'rata_rata_nilai' => $observations->count() > 0
                            ? round($observations->avg('total_score'), 2)
                            : 0,
                    ];
                })
                ->values(),
            'detail_observasi' => $observations->map(function ($obs) {
                return [
                    'tanggal' => $obs->observation_date->format('d/m/Y'),
                    'guru' => $obs->teacher->name ?? '-',
                    'supervisor' => $obs->supervisor->name ?? '-',
                    'mapel' => $obs->subject->name ?? '-',
                    'kelas' => $obs->class_name,
                    'perencanaan' => (float) $obs->score_planning,
                    'penyampaian' => (float) $obs->score_delivery,
                    'pengelolaan' => (float) $obs->score_management,
                    'penilaian' => (float) $obs->score_assessment,
                    'total' => (float) $obs->total_score,
                ];
            })->values(),
        ];
    }

    public function exportExcel(Request $request)
    {
        $period = Period::fromSession();
        $data   = $this->schoolReportData($period);

        $sekolah = Setting::value('school_name', '');
        $periode = $data['periode'];

        return Excel::download(
            new SchoolReportExport(
                $data['guru_recap'],
                $data['detail_observasi'],
                $data['statistik'],
                $periode,
                $sekolah,
            ),
            "laporan-supervisi-{$periode}.xlsx",
        );
    }

    public function exportPdf(Request $request)
    {
        $period = Period::fromSession();
        $data   = $this->schoolReportData($period);

        $sekolah = Setting::value('school_name', '');

        $pdf = Pdf::loadView('reports.school-report', [
            'periode'         => $data['periode'],
            'sstahun'         => $data['ssekolah'],
            'sekolah'         => $sekolah,
            'statistik'       => $data['statistik'],
            'guru_recap'      => $data['guru_recap'],
            'detail_observasi'=> $data['detail_observasi'],
        ]);

        $periodeSlug = str_replace('/', '-', $data['periode']);

        return $pdf->download("laporan-supervisi-{$periodeSlug}.pdf");
    }
}
