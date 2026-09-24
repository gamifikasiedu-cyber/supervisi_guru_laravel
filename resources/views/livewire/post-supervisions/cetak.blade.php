<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 no-print">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <div>
                <h3 class="font-semibold text-slate-800">{{ $cfg['title'] }}</h3>
                <p class="text-sm text-slate-500">{{ $records->count() }} record · Periode berjalan</p>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="text-sm px-4 py-2 rounded-lg bg-slate-800 text-white hover:bg-slate-900">🖨 Cetak</button>
                <a href="{{ route('post-supervisions.index') }}" class="text-sm px-4 py-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200">← Kembali</a>
            </div>
        </div>
    </div>

    <div id="print-area">
        <div style="text-align:center; margin-bottom:4px;">
            <div style="font-size:18px; font-weight:bold;">{{ strtoupper(\App\Models\Setting::value('school_name') ?? 'SEKOLAH') }}</div>
            <div style="font-size:11px;">{{ \App\Models\Setting::value('address') ?? '' }}{{ \App\Models\Setting::value('npsn') ? ' · NPSN: '.\App\Models\Setting::value('npsn') : '' }}</div>
        </div>
        <hr style="border:none; border-top:3px double #000; margin:6px 0 12px;">
        <div style="text-align:center; margin-bottom:10px;">
            <div style="font-size:14px; font-weight:bold; text-decoration:underline;">{{ strtoupper($cfg['title']) }}</div>
            <div style="font-size:11px;">{{ $cfg['subtitle'] ?? '' }}</div>
        </div>

        @forelse($records as $r)
            <div style="font-size:11px; margin:10px 0 4px;">
                <strong>{{ $r->teacher->name ?? '-' }}</strong>
                · {{ $r->subject->name ?? '-' }} · {{ $r->class_name ?? '-' }}
                · {{ $r->observation_date ? \Carbon\Carbon::parse($r->observation_date)->translatedFormat('d F Y') : '-' }}
                @if($r->score !== null) · Nilai: <strong>{{ $r->score }}</strong>@endif
            </div>
            <table border="1" cellspacing="0" cellpadding="5" style="width:100%; font-size:10.5px; border-collapse:collapse;">
                <thead>
                    <tr style="background:#eee;">
                        <th style="width:28px;">No.</th>
                        @foreach($cfg['fields'] as $f)<th>{{ $f['label'] }}</th>@endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach(($r->items ?? []) as $i => $it)
                        <tr>
                            <td align="center">{{ $i + 1 }}</td>
                            @foreach($cfg['fields'] as $f)<td>{{ $it[$f['key']] ?? '' }}</td>@endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @empty
            <p style="font-size:11px;">Belum ada data pada bagian ini.</p>
        @endforelse

        <table style="width:100%; font-size:11px; margin-top:24px; text-align:center;">
            <tr>
                <td style="width:50%;">Mengetahui,<br>Kepala Sekolah<br><br><br><br><br><strong><u>{{ $kepsek->name ?? '........................' }}</u></strong>@if(! empty($kepsek?->nip) && $kepsek->nip !== '-')<br>NIP {{ $kepsek->nip }}@endif</td>
                <td style="width:50%;">{{ now()->translatedFormat('d F Y') }}<br>Supervisor<br><br><br><br><br><strong><u>{{ auth()->user()->name }}</u></strong></td>
            </tr>
        </table>
    </div>
</div>
