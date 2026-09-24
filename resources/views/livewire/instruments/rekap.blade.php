<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 md:p-5 no-print">
        <div class="flex justify-between items-center flex-wrap gap-2 mb-4">
            <h3 class="font-semibold text-slate-800">Rekapitulasi Hasil Pemantauan/Supervisi</h3>
            <div class="flex items-center gap-2">
                <select wire:model.live="teacher_id" class="text-sm rounded-lg border-slate-300 px-3 py-1.5 border">
                    <option value="">Semua Guru</option>
                    @foreach($teachers as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach
                </select>
                <button onclick="window.print()" class="text-sm px-3 py-1.5 rounded-lg bg-slate-800 text-white hover:bg-slate-900">🖨 Cetak {{ $selectedTeacher ? 'per Guru' : '' }}</button>
                <a href="{{ route('pemantauan.index') }}" class="text-sm text-indigo-600 hover:underline">← Pemantauan</a>
            </div>
        </div>

        @if($selectedTeacher)
            <p class="text-sm text-slate-500 mb-3">Guru: <strong class="text-slate-800">{{ $selectedTeacher->name }}</strong>{{ $selectedTeacher->nip ? ' · NIP '.$selectedTeacher->nip : '' }} · {{ $jumlahRecord }} record</p>
        @else
            <p class="text-sm text-slate-500 mb-3">Lingkup: <strong class="text-slate-800">Semua Guru</strong> · {{ $jumlahRecord }} record</p>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[1000px]">
                <thead>
                    <tr class="text-left text-xs uppercase text-slate-400 border-b border-slate-100">
                        <th class="px-4 py-2.5">Komponen</th><th class="px-4 py-2.5">Jumlah Indikator</th>
                        <th class="px-4 py-2.5">Skor Maks.</th><th class="px-4 py-2.5">Skor Diperoleh</th>
                        <th class="px-4 py-2.5">Nilai</th><th class="px-4 py-2.5">Predikat</th>
                        <th class="px-4 py-2.5 w-44">Kekuatan</th><th class="px-4 py-2.5 w-44">Prioritas Perbaikan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($komponen as $i => $r)
                        <tr>
                            <td class="px-4 py-2.5 font-medium text-slate-800">{{ $r['nama'] }}</td>
                            <td class="px-4 py-2.5">{{ $r['jumlah'] }}</td>
                            <td class="px-4 py-2.5">{{ $r['maks'] }}</td>
                            <td class="px-4 py-2.5">{{ $r['diperoleh'] }}</td>
                            <td class="px-4 py-2.5 font-semibold">{{ $r['nilai'] }}</td>
                            <td class="px-4 py-2.5">{{ $r['predikat'] }}</td>
                            <td class="px-4 py-2.5"><input wire:model="noteKekuatan.{{ $i }}" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border" placeholder="…"></td>
                            <td class="px-4 py-2.5"><input wire:model="notePerbaikan.{{ $i }}" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border" placeholder="…"></td>
                        </tr>
                    @endforeach
                    @if(! count($komponen))
                        <tr><td colspan="8" class="px-4 py-4 text-slate-400">Belum ada data.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>

        <h3 class="font-semibold text-slate-800 mt-6 mb-2">Rekap Dimensi Utama</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[700px]">
                <thead>
                    <tr class="text-left text-xs uppercase text-slate-400 border-b border-slate-100">
                        <th class="px-4 py-2.5">Dimensi</th><th class="px-4 py-2.5">Indikator</th>
                        <th class="px-4 py-2.5">Skor Maks.</th><th class="px-4 py-2.5">Skor Diperoleh</th><th class="px-4 py-2.5">Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dimensi as $r)
                        <tr>
                            <td class="px-4 py-2.5 font-medium text-slate-800">{{ $r['nama'] }}</td>
                            <td class="px-4 py-2.5">{{ $r['jumlah'] }}</td>
                            <td class="px-4 py-2.5">{{ $r['maks'] }}</td>
                            <td class="px-4 py-2.5">{{ $r['diperoleh'] }}</td>
                            <td class="px-4 py-2.5 font-semibold">{{ $r['nilai'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-4 text-slate-400">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
            <div>
                <h3 class="font-semibold text-slate-800 mb-2">Catatan Kekuatan/Praktik Baik</h3>
                <textarea wire:model="catatanKekuatan" rows="4" class="block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border" placeholder="Tuliskan kekuatan/praktik baik…"></textarea>
            </div>
            <div>
                <h3 class="font-semibold text-slate-800 mb-2">Prioritas Perbaikan dan Rencana Tindak Lanjut</h3>
                <textarea wire:model="prioritasRtl" rows="4" class="block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border" placeholder="Tuliskan prioritas perbaikan…"></textarea>
            </div>
        </div>
    </div>

    {{-- Dokumen resmi (hanya tampil saat cetak) --}}
    @php $printKepsek = \App\Models\User::kepalaSekolah()->first(); @endphp
    <div id="print-area">
        <div style="text-align:center; margin-bottom:4px;">
            <div style="font-size:18px; font-weight:bold;">{{ strtoupper(\App\Models\Setting::value('school_name') ?? 'SEKOLAH') }}</div>
            <div style="font-size:11px;">{{ \App\Models\Setting::value('address') ?? '' }}</div>
        </div>
        <hr style="border:none; border-top:3px double #000; margin:6px 0 12px;">
        <div style="text-align:center; margin-bottom:4px; font-size:14px; font-weight:bold; text-decoration:underline;">REKAPITULASI HASIL PEMANTAUAN/SUPERVISI</div>
        <div style="font-size:11px; margin-bottom:10px;">Guru: <strong>{{ $selectedTeacher->name ?? 'Semua Guru' }}</strong>{{ $selectedTeacher?->nip ? ' · NIP '.$selectedTeacher->nip : '' }}</div>

        <table border="1" cellspacing="0" cellpadding="5" style="width:100%; font-size:10.5px; border-collapse:collapse;">
            <thead>
                <tr style="background:#eee;">
                    <th>Komponen</th><th>Jumlah Indikator</th><th>Skor Maks.</th><th>Skor Diperoleh</th>
                    <th>Nilai</th><th>Predikat</th><th>Kekuatan</th><th>Prioritas Perbaikan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($komponen as $i => $r)
                    <tr>
                        <td><strong>{{ $r['nama'] }}</strong></td>
                        <td align="center">{{ $r['jumlah'] }}</td>
                        <td align="center">{{ $r['maks'] }}</td>
                        <td align="center">{{ $r['diperoleh'] }}</td>
                        <td align="center"><strong>{{ $r['nilai'] }}</strong></td>
                        <td align="center">{{ $r['predikat'] }}</td>
                        <td>{{ $noteKekuatan[$i] ?? '' }}</td>
                        <td>{{ $notePerbaikan[$i] ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="font-size:12px; font-weight:bold; margin:14px 0 6px;">Rekap Dimensi Utama</div>
        <table border="1" cellspacing="0" cellpadding="5" style="width:100%; font-size:10.5px; border-collapse:collapse;">
            <thead>
                <tr style="background:#eee;">
                    <th>Dimensi</th><th>Indikator</th><th>Skor Maks.</th><th>Skor Diperoleh</th><th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dimensi as $r)
                    <tr>
                        <td><strong>{{ $r['nama'] }}</strong></td>
                        <td align="center">{{ $r['jumlah'] }}</td>
                        <td align="center">{{ $r['maks'] }}</td>
                        <td align="center">{{ $r['diperoleh'] }}</td>
                        <td align="center"><strong>{{ $r['nilai'] }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="font-size:12px; font-weight:bold; margin:14px 0 4px;">Catatan Kekuatan/Praktik Baik</div>
        <div style="font-size:11px; min-height:60px;">{!! nl2br(e($catatanKekuatan)) ?: str_repeat('<div style="border-bottom:1px dotted #000; height:18px;"></div>', 4) !!}</div>

        <div style="font-size:12px; font-weight:bold; margin:14px 0 4px;">Prioritas Perbaikan dan Rencana Tindak Lanjut</div>
        <div style="font-size:11px; min-height:60px;">{!! nl2br(e($prioritasRtl)) ?: str_repeat('<div style="border-bottom:1px dotted #000; height:18px;"></div>', 4) !!}</div>

        <table style="width:100%; font-size:11px; margin-top:24px; text-align:center;">
            <tr>
                <td style="width:50%;">Mengetahui,<br>Kepala Sekolah<br><br><br><br><br><strong><u>{{ $printKepsek->name ?? '........................' }}</u></strong></td>
                <td style="width:50%;">{{ now()->translatedFormat('d F Y') }}<br>Supervisor<br><br><br><br><br><strong><u>{{ auth()->user()->name }}</u></strong></td>
            </tr>
        </table>
    </div>
</div>
