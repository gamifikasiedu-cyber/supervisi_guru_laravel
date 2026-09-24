@php
    $pastels = [
        'blue' => 'bg-blue-50 text-blue-500',
        'amber' => 'bg-amber-50 text-amber-500',
        'rose' => 'bg-rose-50 text-rose-500',
        'violet' => 'bg-violet-50 text-violet-500',
    ];
    $statusBadge = [
        'Scheduled' => 'bg-sky-100 text-sky-600',
        'Completed' => 'bg-emerald-100 text-emerald-600',
        'Cancelled' => 'bg-slate-100 text-slate-500',
    ];

    // ---- Area chart (Reports) ----
    $W = 620; $H = 215; $P = 32;
    $vals = array_column($chart, 'value');
    $max = max(max($vals), 1);
    $n = count($chart);
    $step = ($W - 2 * $P) / max($n - 1, 1);
    $pts = [];
    foreach ($chart as $i => $c) {
        $pts[] = [$P + $i * $step, $H - $P - (($c['value'] / $max) * ($H - 2 * $P))];
    }
    $d = 'M'.$pts[0][0].','.$pts[0][1];
    for ($i = 0; $i < $n - 1; $i++) {
        $p0 = $pts[max($i - 1, 0)]; $p1 = $pts[$i]; $p2 = $pts[$i + 1]; $p3 = $pts[min($i + 2, $n - 1)];
        $d .= ' C'.($p1[0] + ($p2[0] - $p0[0]) / 6).','.($p1[1] + ($p2[1] - $p0[1]) / 6)
            .' '.($p2[0] - ($p3[0] - $p1[0]) / 6).','.($p2[1] - ($p3[1] - $p1[1]) / 6)
            .' '.$p2[0].','.$p2[1];
    }
    $area = $d.' L'.$pts[$n - 1][0].','.($H - $P).' L'.$pts[0][0].','.($H - $P).' Z';
    $mi = array_search(max($vals), $vals);

    // ---- Donut (Analytics) ----
    $R = 62; $C = 2 * pi() * $R;
    $dTotal = max(array_sum(array_column(array_slice($donut, 0, 3), 'value')), 1);
    $offset = 0;
@endphp

<div>
    <!-- Stat cards -->
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 md:gap-5">
        @foreach($stats as [$label, $value, $icon, $theme])
            <div class="bg-white rounded-2xl shadow-sm p-3.5 sm:p-5 flex items-center gap-3 sm:gap-4 min-w-0">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center text-lg sm:text-xl shrink-0 {{ $pastels[$theme] ?? $pastels['blue'] }}">{{ $icon }}</div>
                <div class="min-w-0">
                    <div class="text-lg sm:text-[22px] sm:leading-7 font-extrabold truncate">{{ $value }}<span class="text-indigo-500">+</span></div>
                    <div class="text-[11px] sm:text-[13px] text-slate-400 truncate">{{ $label }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Reports + Analytics -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 md:gap-5 mt-4 md:mt-5">
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm p-5">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-bold">Reports</h3>
                <span class="text-slate-300 tracking-widest text-sm">•••</span>
            </div>
            <svg viewBox="0 0 {{ $W }} {{ $H }}" class="w-full" style="height:230px">
                <defs>
                    <linearGradient id="lineGrad" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0" stop-color="#4f7cff"/><stop offset="1" stop-color="#a855f7"/>
                    </linearGradient>
                    <linearGradient id="fillGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0" stop-color="#818cf8" stop-opacity=".25"/><stop offset="1" stop-color="#818cf8" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                @foreach([0, 0.5, 1] as $g)
                    @php $gy = $H - $P - ($g * ($H - 2 * $P)); @endphp
                    <line x1="{{ $P }}" y1="{{ $gy }}" x2="{{ $W - 8 }}" y2="{{ $gy }}" stroke="#eef1f6" stroke-width="1"/>
                    <text x="4" y="{{ $gy + 4 }}" font-size="10" fill="#b6bcc9">{{ round($max * $g) }}</text>
                @endforeach
                <path d="{{ $area }}" fill="url(#fillGrad)"/>
                <path d="{{ $d }}" fill="none" stroke="url(#lineGrad)" stroke-width="2.5" stroke-linecap="round"/>
                @foreach($pts as $i => $pt)
                    <circle cx="{{ $pt[0] }}" cy="{{ $pt[1] }}" r="4" fill="#fff" stroke="#a855f7" stroke-width="2"/>
                    <text x="{{ $pt[0] }}" y="{{ $H - 8 }}" font-size="10" fill="#b6bcc9" text-anchor="middle">{{ $chart[$i]['label'] }}</text>
                @endforeach
                @php [$mx, $my] = $pts[$mi]; @endphp
                <g>
                    <rect x="{{ $mx - 34 }}" y="{{ max($my - 52, 2) }}" width="68" height="38" rx="8" fill="#1e1b3a"/>
                    <text x="{{ $mx }}" y="{{ max($my - 36, 18) }}" font-size="9" fill="#a5b4fc" text-anchor="middle">Supervisi</text>
                    <text x="{{ $mx }}" y="{{ max($my - 22, 32) }}" font-size="13" font-weight="700" fill="#fff" text-anchor="middle">{{ $vals[$mi] }}</text>
                </g>
            </svg>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="flex items-center justify-between mb-1">
                <h3 class="font-bold">Analytics</h3>
                <span class="text-slate-300 tracking-widest text-sm">•••</span>
            </div>
            <svg viewBox="0 0 160 170" class="w-full" style="height:210px">
                <circle cx="80" cy="75" r="{{ $R }}" fill="none" stroke="#eef1f6" stroke-width="17"/>
                @foreach(array_slice($donut, 0, 3) as $seg)
                    @php $len = ($seg['value'] / $dTotal) * $C; @endphp
                    @if($seg['value'] > 0)
                        <circle cx="80" cy="75" r="{{ $R }}" fill="none" stroke="{{ $seg['color'] }}"
                            stroke-width="17" stroke-linecap="round"
                            stroke-dasharray="{{ $len }} {{ $C - $len }}"
                            stroke-dashoffset="{{ -$offset }}" transform="rotate(-90 80 75)"/>
                    @endif
                    @php $offset += $len; @endphp
                @endforeach
                <text x="80" y="72" font-size="22" font-weight="800" text-anchor="middle" fill="#1e293b">{{ $donut['pct'] }}%</text>
                <text x="80" y="90" font-size="11" text-anchor="middle" fill="#94a3b8">Disetujui</text>
            </svg>
            <div class="flex flex-wrap justify-center gap-x-4 gap-y-1.5 mt-1 text-xs text-slate-500">
                @foreach(array_slice($donut, 0, 3) as $seg)
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full" style="background:{{ $seg['color'] }}"></span>
                        {{ $seg['label'] }} ({{ $seg['value'] }})
                    </span>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent + Top -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 md:gap-5 mt-4 md:mt-5">
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold">Recent Supervisi</h3>
                <a href="{{ route('supervisions.index') }}" class="text-slate-300 tracking-widest text-sm hover:text-indigo-500">•••</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-[13px] min-w-[560px]">
                    <thead>
                        <tr class="text-left text-slate-400 text-xs">
                            <th class="py-2 pr-3 font-medium">No ▾</th>
                            <th class="py-2 pr-3 font-medium">Guru ▾</th>
                            <th class="py-2 pr-3 font-medium">Mapel</th>
                            <th class="py-2 pr-3 font-medium">Kelas</th>
                            <th class="py-2 pr-3 font-medium">Status</th>
                            <th class="py-2 font-medium text-right">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recent as $s)
                            <tr>
                                <td class="py-2.5 pr-3 text-slate-500">#SPV-{{ $s->id }}</td>
                                <td class="py-2.5 pr-3 font-semibold">{{ $s->teacher->name ?? '-' }}</td>
                                <td class="py-2.5 pr-3 text-slate-500">{{ $s->subject->name ?? '-' }}</td>
                                <td class="py-2.5 pr-3"><span class="px-2.5 py-1 rounded-md bg-sky-50 text-sky-600 text-xs font-medium">{{ $s->class_name ?? '-' }}</span></td>
                                <td class="py-2.5 pr-3"><span class="px-2.5 py-1 rounded-md text-xs font-medium {{ $statusBadge[$s->status] ?? 'bg-slate-100 text-slate-500' }}">{{ $s->status }}</span></td>
                                <td class="py-2.5 text-right font-semibold">{{ $s->schedule_date?->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-6 text-center text-slate-400">Belum ada jadwal supervisi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold">Guru Terbaik</h3>
                <span class="text-slate-300 tracking-widest text-sm">•••</span>
            </div>
            <div class="space-y-4">
                @forelse($top as $g)
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-xl font-extrabold shrink-0">{{ strtoupper(substr($g['name'], 0, 1)) }}</div>
                        <div class="min-w-0">
                            <p class="font-semibold text-sm truncate">{{ $g['name'] }}</p>
                            <p class="text-amber-400 text-xs tracking-tight">
                                @for($s = 1; $s <= 5; $s++)<span class="{{ $s <= round($g['avg'] / 20) ? '' : 'text-slate-200' }}">★</span>@endfor
                            </p>
                            <p class="font-extrabold text-sm mt-0.5">{{ $g['avg'] }} <span class="font-normal text-slate-400 text-xs">· {{ $g['count'] }} observasi</span></p>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 text-sm">Belum ada nilai.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
