<?php

namespace App\Http\Middleware;

use App\Models\Period;
use App\Models\Setting;
use App\Models\Supervision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'settings' => Schema::hasTable('settings')
                ? [
                    // Kecualikan blob JSON struktur instrumen (hanya dipakai halaman instrumen).
                    ...collect(Setting::allMap())->except(['instrument_default_rows'])->all(),
                    'logo_url' => Setting::value('logo')
                        ? Storage::url(Setting::value('logo'))
                        : null,
                ]
                : [],
            'active_period' => Schema::hasTable('periods')
                ? Period::fromSession() ?? Period::current()
                : null,
            'jadwal_notif' => function () use ($request) {
                $user = $request->user();
                if (! $user || ! Schema::hasTable('supervisions')) {
                    return ['items' => []];
                }

                $period = Schema::hasTable('periods') ? Period::fromSession() : null;

                $query = Supervision::with(['teacher:id,name', 'supervisor:id,name', 'subject:id,name'])
                    ->byPeriod($period)
                    ->whereDate('schedule_date', '>=', now()->toDateString())
                    ->whereNotIn('status', ['Completed', 'completed', 'Cancelled', 'cancelled']);

                // Guru murni hanya melihat jadwalnya sendiri.
                // Supervisor/admin/kepala sekolah/pengawas melihat seluruh
                // jadwal mendatang agar yang sesuai harinya selalu muncul.
                $isGuruOnly = $user->hasRole('guru')
                    && ! $user->hasRole('admin', 'supervisor', 'kepala_sekolah', 'pengawas');
                if ($isGuruOnly) {
                    $query->where('teacher_id', $user->id);
                }

                $items = $query->orderBy('schedule_date')->limit(8)->get()->map(fn ($s) => [
                    'id' => $s->id,
                    'tanggal' => $s->schedule_date?->format('Y-m-d'),
                    'guru' => $s->teacher?->name ?? '-',
                    'guru_id' => $s->teacher_id,
                    'supervisor' => $s->supervisor?->name ?? '-',
                    'supervisor_id' => $s->supervisor_id,
                    'mapel' => $s->subject?->name ?? '-',
                    'kelas' => $s->class_name ?? '-',
                    'status' => $s->status,
                ])->all();

                return ['items' => $items];
            },
        ];
    }
}
