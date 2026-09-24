<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 no-print">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <div>
                <h3 class="font-semibold text-slate-800">Cetak Resmi Konferensi</h3>
                <p class="text-sm text-slate-500">{{ $konferensi->teacher->name ?? '-' }} · {{ $konferensi->observation_date?->format('d/m/Y') }}</p>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="text-sm px-4 py-2 rounded-lg bg-slate-800 text-white hover:bg-slate-900">🖨 Cetak</button>
                <a href="{{ route('konferensis.index') }}" class="text-sm px-4 py-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200">← Kembali</a>
            </div>
        </div>
    </div>

    @php
        $printDate = $konferensi->observation_date ? \Carbon\Carbon::parse($konferensi->observation_date)->translatedFormat('d F Y') : '-';
        $printKepsek = \App\Models\User::kepalaSekolah()->first();
    @endphp
    <div id="print-area">
        <div style="text-align:center; margin-bottom:4px;">
            <div style="font-size:18px; font-weight:bold;">{{ strtoupper(\App\Models\Setting::value('school_name') ?? 'SEKOLAH') }}</div>
            <div style="font-size:11px;">{{ \App\Models\Setting::value('address') ?? '' }}{{ \App\Models\Setting::value('npsn') ? ' · NPSN: '.\App\Models\Setting::value('npsn') : '' }}</div>
        </div>
        <hr style="border:none; border-top:3px double #000; margin:6px 0 12px;">
        <div style="text-align:center; margin-bottom:10px;">
            <div style="font-size:14px; font-weight:bold; text-decoration:underline;">BERITA ACARA KONFERENSI-WAWANCARA PRA OBSERVASI</div>
            <div style="font-size:11px;">Supervisi Akademik Guru</div>
        </div>
        <table style="font-size:11px; margin-bottom:10px;">
            <tr><td style="width:130px;">Nama Guru</td><td style="width:10px;">:</td><td><strong>{{ $konferensi->teacher->name ?? '-' }}</strong>{{ $konferensi->teacher?->nip ? ' · NIP '.$konferensi->teacher->nip : '' }}</td></tr>
            <tr><td>Mata Pelajaran</td><td>:</td><td>{{ $konferensi->subject->name ?? '-' }}</td></tr>
            <tr><td>Kelas</td><td>:</td><td>{{ $konferensi->class_name ?? '-' }}</td></tr>
            <tr><td>Tanggal</td><td>:</td><td>{{ $printDate }}</td></tr>
            <tr><td>Supervisor</td><td>:</td><td>{{ $konferensi->supervisor->name ?? auth()->user()->name }}</td></tr>
        </table>
        <table border="1" cellspacing="0" cellpadding="5" style="width:100%; font-size:10.5px; border-collapse:collapse;">
            <thead>
                <tr style="background:#eee;">
                    <th style="width:28px;">No.</th>
                    <th>Pertanyaan / Fokus</th>
                    <th>Catatan Guru</th>
                    <th>Catatan Supervisor</th>
                    <th>Kesepakatan</th>
                    <th>Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                @foreach(($konferensi->items ?? []) as $i => $it)
                    <tr>
                        <td align="center">{{ $i + 1 }}</td>
                        <td>{{ $it['pertanyaan'] ?? '' }}</td>
                        <td>{{ $it['catatan_guru'] ?? '' }}</td>
                        <td>{{ $it['catatan_supervisor'] ?? '' }}</td>
                        <td>{{ $it['kesepakatan'] ?? '' }}</td>
                        <td>{{ $it['tindak_lanjut'] ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table style="width:100%; font-size:11px; margin-top:24px; text-align:center;">
            <tr>
                <td style="width:50%;">Mengetahui,<br>Kepala Sekolah<br><br><br><br><br><strong><u>{{ $printKepsek->name ?? '........................' }}</u></strong>@if(! empty($printKepsek?->nip) && $printKepsek->nip !== '-')<br>NIP {{ $printKepsek->nip }}@endif</td>
                <td style="width:50%;">{{ $printDate }}<br>Observer/Penelaah<br><br><br><br><br><strong><u>{{ $konferensi->supervisor->name ?? auth()->user()->name }}</u></strong></td>
            </tr>
        </table>
    </div>
</div>
