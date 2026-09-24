<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 no-print">
        @if($bagian !== 'rekap')
        <div class="px-5 py-3 border-b border-slate-100 flex flex-wrap items-center gap-2">
            <span class="text-sm font-semibold text-slate-700 mr-auto">Isian Pasca Supervisi</span>
            <form method="POST" action="{{ route('post-supervisions.import', $bagian) }}" enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="text-xs max-w-[180px]">
                <button class="text-xs px-3 py-1.5 rounded-lg bg-sky-600 text-white hover:bg-sky-700">⬆ Impor</button>
            </form>
            <button wire:click="exportTemplate" class="text-xs px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200">⬇ Template</button>
            @if($bagian !== 'rekap' && ($is_custom_default ?? false))
                <button wire:click="resetDefault" onclick="return confirm('Kembalikan struktur ke standar?')" class="text-xs px-3 py-1.5 rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200">Reset Standar</button>
            @endif
            <button wire:click="exportExcel" class="text-xs px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">⬇ Export Excel</button>
            <button onclick="window.print()" class="text-xs px-3 py-1.5 rounded-lg bg-slate-800 text-white hover:bg-slate-900">🖨 Cetak Resmi</button>
        </div>
        @endif
        @error('importFile') <p class="text-xs text-rose-600 px-5 pt-2">{{ $message }}</p> @enderror
            <div class="px-5 pt-4">
                <label class="text-sm font-medium">Bagian</label>
                @if($postSupervision)
                    <div class="mt-1 inline-block text-sm px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 font-medium">{{ $options[$bagian] ?? $bagian }}</div>
                @else
                    <div class="flex flex-wrap gap-1.5 mt-1.5">
                        @foreach($options as $slug => $label)
                            <a href="{{ route('post-supervisions.create', ['bagian' => $slug]) }}"
                                class="text-xs px-3 py-1.5 rounded-lg font-medium transition {{ $bagian === $slug ? 'bg-indigo-600 text-white shadow' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">{{ $label }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
            @if($bagian === 'rekap' && $rekap)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 md:p-5 no-print mt-4">
                <div class="flex justify-between items-center flex-wrap gap-2 mb-3">
                    <h3 class="font-semibold text-slate-800">Rekap Hasil Pasca Supervisi</h3>
                    <div class="flex items-center gap-2">
                        @php $guruOnly = auth()->user()->hasRole('guru') && ! auth()->user()->hasRole('admin', 'supervisor', 'kepala_sekolah', 'pengawas'); @endphp
                        @if(! $guruOnly)
                            <form method="GET" action="{{ route('post-supervisions.create') }}" class="inline">
                                <input type="hidden" name="bagian" value="rekap">
                                <select name="guru" onchange="this.form.submit()" class="text-sm rounded-lg border-slate-300 px-3 py-1.5 border">
                                    <option value="">Semua Guru</option>
                                    @foreach($teachers as $t)<option value="{{ $t->id }}" @selected($rekapTeacher === (string) $t->id)>{{ $t->name }}</option>@endforeach
                                </select>
                            </form>
                        @else
                            <span class="text-sm text-slate-500">Rekap saya</span>
                        @endif
                        <button type="button" onclick="window.print()" class="text-sm px-3 py-1.5 rounded-lg bg-slate-800 text-white hover:bg-slate-900">🖨 Cetak</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[900px]">
                        <thead>
                            <tr class="text-left text-xs uppercase text-slate-400 border-b border-slate-100">
                                <th class="px-4 py-2.5">Guru</th><th class="px-4 py-2.5">Pra-Obs</th><th class="px-4 py-2.5">Pemantauan</th>
                                <th class="px-4 py-2.5">Formatif</th><th class="px-4 py-2.5">Sumatif</th><th class="px-4 py-2.5">Obs 3M</th>
                                <th class="px-4 py-2.5">Obs BBM</th><th class="px-4 py-2.5">Total</th><th class="px-4 py-2.5">Kategori</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($rekap['summary']['rows'] as $r)
                                <tr>
                                    <td class="px-4 py-2.5 font-medium text-slate-800">{{ $r['name'] }}</td>
                                    <td class="px-4 py-2.5">{{ $r['pra'] ?? '-' }}</td>
                                    <td class="px-4 py-2.5">{{ $r['ins'] ?? '-' }}</td>
                                    <td class="px-4 py-2.5">{{ $r['for'] ?? '-' }}</td>
                                    <td class="px-4 py-2.5">{{ $r['sum'] ?? '-' }}</td>
                                    <td class="px-4 py-2.5">{{ $r['m3'] ?? '-' }}</td>
                                    <td class="px-4 py-2.5">{{ $r['bbm'] ?? '-' }}</td>
                                    <td class="px-4 py-2.5 font-semibold">{{ $r['total'] ?? '-' }}</td>
                                    <td class="px-4 py-2.5">{{ $r['kategori'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="px-4 py-4 text-slate-400">Belum ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5">
                    @foreach(['pra_observasi' => 'Pra Observasi', 'instrumen' => 'Pemantauan', 'formatif' => 'Asesmen Formatif', 'sumatif' => 'Asesmen Sumatif'] as $key => $label)
                        <div>
                            <h4 class="font-semibold text-slate-700 text-sm mb-1.5">Riwayat {{ $label }}</h4>
                            <table class="w-full text-[13px]">
                                <thead>
                                    <tr class="text-left text-xs text-slate-400 border-b border-slate-100">
                                        <th class="py-1.5 pr-2">Tanggal</th><th class="py-1.5 pr-2">Guru</th><th class="py-1.5 pr-2">Mapel</th><th class="py-1.5 text-right">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($rekap['riwayat'][$key] ?? [] as $d)
                                        <tr>
                                            <td class="py-1.5 pr-2 text-slate-500">{{ $d['tanggal'] }}</td>
                                            <td class="py-1.5 pr-2">{{ $d['guru'] }}</td>
                                            <td class="py-1.5 pr-2 text-slate-500">{{ $d['mapel'] }}</td>
                                            <td class="py-1.5 text-right font-semibold">{{ $d['nilai'] ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="py-2 text-slate-400">Belum ada data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
            @else
            <form wire:submit="save">
            <div class="px-5 py-4 grid grid-cols-1 md:grid-cols-4 gap-3 border-b border-slate-100">
                <div>
                    <label class="text-sm font-medium">Guru</label>
                    <select wire:model="teacher_id" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-2.5 py-1.5 border">
                        <option value="">-- Pilih --</option>
                        @foreach($teachers as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium">Mapel</label>
                    <select wire:model="subject_id" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-2.5 py-1.5 border">
                        <option value="">-- Pilih --</option>
                        @foreach($subjects as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium">Kelas</label>
                    <input wire:model="class_name" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-2.5 py-1.5 border">
                </div>
                <div>
                    <label class="text-sm font-medium">Tanggal</label>
                    <input type="date" wire:model="observation_date" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-2.5 py-1.5 border">
                    @error('observation_date') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[900px]">
                    <thead>
                        <tr class="text-left text-xs uppercase text-slate-400 border-b border-slate-100">
                            <th class="px-4 py-2.5 w-10">No</th>
                            @foreach($fields as $f)<th class="px-4 py-2.5">{{ $f['label'] }}</th>@endforeach
                            <th class="px-4 py-2.5 w-12"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($items as $i => $it)
                            <tr>
                                <td class="px-4 py-2 text-slate-400">{{ $i + 1 }}</td>
                                @foreach($fields as $f)
                                    <td class="px-4 py-2 min-w-[140px]">
                                        @if($f['type'] === 'skor')
                                            <select wire:model="items.{{ $i }}.{{ $f['key'] }}" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border">
                                                <option value="">-</option>
                                                <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>
                                            </select>
                                        @elseif($f['type'] === 'select')
                                            <select wire:model="items.{{ $i }}.{{ $f['key'] }}" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border">
                                                <option value="">--</option>
                                                @foreach($f['options'] ?? [] as $o)<option value="{{ $o }}">{{ $o }}</option>@endforeach
                                            </select>
                                        @elseif($f['type'] === 'textarea')
                                            <textarea wire:model="items.{{ $i }}.{{ $f['key'] }}" rows="2" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border"></textarea>
                                        @else
                                            <input wire:model="items.{{ $i }}.{{ $f['key'] }}" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border">
                                        @endif
                                        @error('items.'.$i.'.'.$f['key']) <p class="text-xs text-rose-600 mt-0.5">{{ $message }}</p> @enderror
                                    </td>
                                @endforeach
                                <td class="px-4 py-2"><button type="button" wire:click="removeRow({{ $i }})" class="text-rose-500">✕</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($errors->any())
                <div class="mx-5 mb-1 px-4 py-2.5 rounded-lg bg-rose-50 border border-rose-200 text-xs text-rose-700">
                    <strong>Belum bisa disimpan, periksa isian:</strong>
                    <ul class="list-disc ml-4 mt-1">
                        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                    </ul>
                </div>
            @endif
            <div class="px-5 py-4 flex gap-2">
                <button type="button" wire:click="addRow" class="px-4 py-2 rounded-lg bg-slate-100 text-sm text-slate-600 hover:bg-slate-200">+ Baris</button>
                <button class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Simpan</button>
                <a href="{{ route('post-supervisions.index') }}" class="px-4 py-2 rounded-lg bg-slate-100 text-sm text-slate-600 hover:bg-slate-200">Batal</a>
            </div>
        </form>
            @endif
    </div>

    @if(! $postSupervision && $bagian !== 'rekap')
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mt-4 no-print">
        <div class="px-5 pt-4 pb-3 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-semibold text-slate-800">Riwayat {{ $bagianLabel }} (10 terbaru)</h3>
            <a href="{{ route('post-supervisions.index') }}" class="text-sm text-indigo-600 hover:underline">Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[700px]">
                <thead>
                    <tr class="text-left text-xs uppercase text-slate-400 border-b border-slate-100">
                        <th class="px-5 py-2.5">Tanggal</th><th class="px-5 py-2.5">Guru</th>
                        <th class="px-5 py-2.5">Mapel</th><th class="px-5 py-2.5">Kelas</th>
                        <th class="px-5 py-2.5">Nilai</th><th class="px-5 py-2.5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($history as $h)
                        <tr>
                            <td class="px-5 py-2.5 text-slate-500">{{ $h->observation_date }}</td>
                            <td class="px-5 py-2.5 font-medium text-slate-800">{{ $h->teacher->name ?? '-' }}</td>
                            <td class="px-5 py-2.5 text-slate-500">{{ $h->subject->name ?? '-' }}</td>
                            <td class="px-5 py-2.5 text-slate-500">{{ $h->class_name ?? '-' }}</td>
                            <td class="px-5 py-2.5 font-semibold">{{ $h->score ?? '-' }}</td>
                            <td class="px-5 py-2.5 whitespace-nowrap">
                                <a href="{{ route('post-supervisions.edit', $h) }}" class="text-indigo-600 hover:underline mr-3">Edit</a>
                                <form method="POST" action="{{ route('records.destroy', ['type' => 'post-supervision', 'id' => $h->id]) }}" class="inline" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-rose-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-4 text-slate-400 text-sm">Belum ada data {{ $bagianLabel }}. Isi form di atas lalu Simpan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Dokumen resmi (hanya tampil saat cetak) --}}
    @if($bagian !== 'rekap')
    @php
        $printCfg = \App\Http\Controllers\PostSupervisionController::config($bagian) ?? ['title' => 'Pasca Supervisi', 'fields' => [], 'hasSkor' => false];
        $printTeacher = $teachers->firstWhere('id', (int) $teacher_id);
        $printSubject = $subjects->firstWhere('id', (int) $subject_id);
        $printTotal = 0; $printCount = 0;
        if (! empty($printCfg['hasSkor'])) { foreach ($items as $pit) { $printTotal += max(0, min(4, (int) ($pit['skor'] ?? 0))); $printCount++; } }
        $printMax = $printCount * 4;
        $printScore = $printMax > 0 ? round(($printTotal / $printMax) * 100, 2) : null;
        $printDate = $observation_date ? \Carbon\Carbon::parse($observation_date)->translatedFormat('d F Y') : '-';
        $printKepsek = \App\Models\User::kepalaSekolah()->first();
    @endphp
    <div id="print-area">
        <div style="text-align:center; margin-bottom:4px;">
            <div style="font-size:18px; font-weight:bold;">{{ strtoupper(\App\Models\Setting::value('school_name') ?? 'SEKOLAH') }}</div>
            <div style="font-size:11px;">{{ \App\Models\Setting::value('address') ?? '' }}{{ \App\Models\Setting::value('npsn') ? ' · NPSN: '.\App\Models\Setting::value('npsn') : '' }}</div>
        </div>
        <hr style="border:none; border-top:3px double #000; margin:6px 0 12px;">
        <div style="text-align:center; margin-bottom:10px;">
            <div style="font-size:14px; font-weight:bold; text-decoration:underline;">{{ strtoupper($printCfg['title']) }}</div>
            <div style="font-size:11px;">{{ $printCfg['subtitle'] ?? '' }}</div>
        </div>
        <table style="font-size:11px; margin-bottom:10px;">
            <tr><td style="width:130px;">Nama Guru</td><td style="width:10px;">:</td><td><strong>{{ $printTeacher->name ?? '-' }}</strong>{{ $printTeacher?->nip ? ' · NIP '.$printTeacher->nip : '' }}</td></tr>
            <tr><td>Mata Pelajaran</td><td>:</td><td>{{ $printSubject->name ?? '-' }}</td></tr>
            <tr><td>Kelas</td><td>:</td><td>{{ $class_name ?: '-' }}</td></tr>
            <tr><td>Tanggal</td><td>:</td><td>{{ $printDate }}</td></tr>
            <tr><td>Supervisor</td><td>:</td><td>{{ auth()->user()->name }}</td></tr>
        </table>
        <table border="1" cellspacing="0" cellpadding="5" style="width:100%; font-size:10.5px; border-collapse:collapse;">
            <thead>
                <tr style="background:#eee;">
                    <th style="width:28px;">No.</th>
                    @foreach($printCfg['fields'] as $f)<th>{{ $f['label'] }}</th>@endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($items as $i => $it)
                    <tr>
                        <td align="center">{{ $i + 1 }}</td>
                        @foreach($printCfg['fields'] as $f)<td>{{ $it[$f['key']] ?? '' }}</td>@endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if($printScore !== null)
            <div style="font-size:11px; margin-top:8px;">Total Skor: <strong>{{ $printTotal }}/{{ $printMax }}</strong> &nbsp;·&nbsp; Nilai: <strong>{{ $printScore }}</strong></div>
        @endif
        <table style="width:100%; font-size:11px; margin-top:24px; text-align:center;">
            <tr>
                <td style="width:50%;">Mengetahui,<br>Kepala Sekolah<br><br><br><br><br><strong><u>{{ $printKepsek->name ?? '........................' }}</u></strong>@if(! empty($printKepsek?->nip) && $printKepsek->nip !== '-')<br>NIP {{ $printKepsek->nip }}@endif</td>
                <td style="width:50%;">{{ $printDate }}<br>Supervisor<br><br><br><br><br><strong><u>{{ auth()->user()->name }}</u></strong></td>
            </tr>
        </table>
    </div>
    @else
    <div id="print-area">
        <div style="text-align:center; margin-bottom:4px;">
            <div style="font-size:18px; font-weight:bold;">{{ strtoupper(\App\Models\Setting::value('school_name') ?? 'SEKOLAH') }}</div>
            <div style="font-size:11px;">{{ \App\Models\Setting::value('address') ?? '' }}</div>
        </div>
        <hr style="border:none; border-top:3px double #000; margin:6px 0 12px;">
        <div style="text-align:center; margin-bottom:10px;">
            <div style="font-size:14px; font-weight:bold; text-decoration:underline;">REKAP HASIL PASCA SUPERVISI</div>
            <div style="font-size:11px;">{{ $rekapTeacher !== '' ? 'Guru: '.(optional($teachers->firstWhere('id', (int) $rekapTeacher))->name) : 'Semua Guru' }}</div>
        </div>
        <table border="1" cellspacing="0" cellpadding="5" style="width:100%; font-size:10px; border-collapse:collapse;">
            <thead>
                <tr style="background:#eee;">
                    <th>Guru</th><th>Pra-Obs</th><th>Pemantauan</th><th>Formatif</th><th>Sumatif</th>
                    <th>Obs 3M</th><th>Obs BBM</th><th>Total</th><th>Kategori</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekap['summary']['rows'] as $r)
                    <tr>
                        <td><strong>{{ $r['name'] }}</strong></td>
                        <td align="center">{{ $r['pra'] ?? '-' }}</td>
                        <td align="center">{{ $r['ins'] ?? '-' }}</td>
                        <td align="center">{{ $r['for'] ?? '-' }}</td>
                        <td align="center">{{ $r['sum'] ?? '-' }}</td>
                        <td align="center">{{ $r['m3'] ?? '-' }}</td>
                        <td align="center">{{ $r['bbm'] ?? '-' }}</td>
                        <td align="center"><strong>{{ $r['total'] ?? '-' }}</strong></td>
                        <td align="center">{{ $r['kategori'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table style="width:100%; font-size:11px; margin-top:24px; text-align:center;">
            <tr>
                <td style="width:50%;">Mengetahui,<br>Kepala Sekolah<br><br><br><br><br><strong><u>{{ ($rk = \App\Models\User::kepalaSekolah()->first()) ? $rk->name : '........................' }}</u></strong></td>
                <td style="width:50%;">{{ now()->translatedFormat('d F Y') }}<br>Supervisor<br><br><br><br><br><strong><u>{{ auth()->user()->name }}</u></strong></td>
            </tr>
        </table>
    </div>
    @endif
</div>
