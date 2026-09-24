<?php

namespace App\Http\Controllers;

use App\Exports\InstrumentExport;
use App\Exports\InstrumentTemplateExport;
use App\Imports\InstrumentImport;
use App\Models\Instrument;
use App\Models\Period;
use App\Models\Setting;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class InstrumentController extends Controller
{
    /**
     * Kunci settings untuk struktur instrumen bawaan (default) kustom dari hasil impor.
     */
    public const DEFAULT_ROWS_KEY = 'instrument_default_rows';

    /**
     * Batas maksimal baris struktur kustom.
     */
    private const MAX_CUSTOM_ROWS = 200;

    public static function rows(): array
    {
        return self::customRows() ?? self::builtinRows();
    }

    /**
     * Struktur bawaan kustom dari hasil impor (null jika belum ada).
     */
    public static function customRows(): ?array
    {
        try {
            $raw = Setting::value(self::DEFAULT_ROWS_KEY);
        } catch (\Throwable) {
            return null;
        }

        if (! $raw) {
            return null;
        }

        $decoded = json_decode($raw, true);
        if (! is_array($decoded) || $decoded === []) {
            return null;
        }

        $rows = [];
        foreach ($decoded as $row) {
            if (! is_array($row)) {
                continue;
            }
            $indikator = trim((string) ($row['indikator'] ?? ''));
            if ($indikator === '') {
                continue;
            }
            $rows[] = [
                'tahap' => trim((string) ($row['tahap'] ?? '')) ?: '-',
                'dimensi' => trim((string) ($row['dimensi'] ?? '')),
                'indikator' => $indikator,
            ];
        }

        return $rows === [] ? null : $rows;
    }

    public static function builtinRows(): array
    {
        return [
            // A. Perencanaan Pembelajaran
            [
                'tahap' => 'A. Perencanaan Pembelajaran',
                'dimensi' => 'Berkesadaran',
                'indikator' => 'Tujuan pembelajaran dirumuskan dengan jelas, terukur, dan mencerminkan kebutuhan belajar peserta didik',
            ],
            [
                'tahap' => 'A. Perencanaan Pembelajaran',
                'dimensi' => 'Berkesadaran',
                'indikator' => 'Perencanaan memuat apersepsi dan kegiatan mengaitkan materi dengan pengetahuan awal peserta didik',
            ],
            [
                'tahap' => 'A. Perencanaan Pembelajaran',
                'dimensi' => 'Bermakna',
                'indikator' => 'Modul ajar/RPP memuat kegiatan yang menghubungkan materi dengan konteks kehidupan nyata',
            ],
            [
                'tahap' => 'A. Perencanaan Pembelajaran',
                'dimensi' => 'Bermakna',
                'indikator' => 'Pemilihan media dan sumber belajar sesuai karakteristik dan kebutuhan peserta didik',
            ],
            [
                'tahap' => 'A. Perencanaan Pembelajaran',
                'dimensi' => 'Menggembirakan',
                'indikator' => 'Aktivitas pembelajaran dirancang kolaboratif, eksploratif, dan berpusat pada peserta didik',
            ],
            [
                'tahap' => 'A. Perencanaan Pembelajaran',
                'dimensi' => 'Penguatan Literasi',
                'indikator' => 'Perencanaan terintegrasi penguatan literasi membaca dan menulis',
            ],
            [
                'tahap' => 'A. Perencanaan Pembelajaran',
                'dimensi' => 'Penguatan Numerasi',
                'indikator' => 'Perencanaan terintegrasi penguatan numerasi (bernar, pemecahan masalah)',
            ],
            [
                'tahap' => 'A. Perencanaan Pembelajaran',
                'dimensi' => 'Penguatan Literasi',
                'indikator' => 'Perencanaan memuat asesmen (diagnostik, formatif, sumatif) dan instrumen penilaian yang selaras',
            ],

            // B. Pelaksanaan Pembelajaran
            [
                'tahap' => 'B. Pelaksanaan Pembelajaran',
                'dimensi' => 'Berkesadaran',
                'indikator' => 'Guru melaksanakan apersepsi dan membantu peserta didik mengaitkan materi dengan kehidupan sehari-hari',
            ],
            [
                'tahap' => 'B. Pelaksanaan Pembelajaran',
                'dimensi' => 'Berkesadaran',
                'indikator' => 'Guru menerapkan penanaman nilai/karakter dalam proses pembelajaran (olah hati)',
            ],
            [
                'tahap' => 'B. Pelaksanaan Pembelajaran',
                'dimensi' => 'Bermakna',
                'indikator' => 'Guru memfasilitasi pembelajaran berpusat pada peserta didik (student-centered)',
            ],
            [
                'tahap' => 'B. Pelaksanaan Pembelajaran',
                'dimensi' => 'Bermakna',
                'indikator' => 'Guru menghubungkan materi dengan konteks nyata dan masalah autentik',
            ],
            [
                'tahap' => 'B. Pelaksanaan Pembelajaran',
                'dimensi' => 'Bermakna',
                'indikator' => 'Guru menggunakan media, teknologi, dan sumber belajar yang bervariasi',
            ],
            [
                'tahap' => 'B. Pelaksanaan Pembelajaran',
                'dimensi' => 'Menggembirakan',
                'indikator' => 'Guru melibatkan peserta didik dalam aktivitas kolaboratif dan partisipatif yang menyenangkan',
            ],
            [
                'tahap' => 'B. Pelaksanaan Pembelajaran',
                'dimensi' => 'Menggembirakan',
                'indikator' => 'Guru menumbuhkan kreativitas dan apresiasi estetika peserta didik (olah rasa)',
            ],
            [
                'tahap' => 'B. Pelaksanaan Pembelajaran',
                'dimensi' => 'Menggembirakan',
                'indikator' => 'Guru melibatkan peserta didik dalam aktivitas fisik/kinestetik yang mendukung pembelajaran (olah raga)',
            ],
            [
                'tahap' => 'B. Pelaksanaan Pembelajaran',
                'dimensi' => 'Penguatan Literasi',
                'indikator' => 'Guru melatih proses kognitif literasi: menemukan informasi, interpretasi, integrasi, evaluasi, refleksi',
            ],
            [
                'tahap' => 'B. Pelaksanaan Pembelajaran',
                'dimensi' => 'Penguatan Literasi',
                'indikator' => 'Guru mengintegrasikan kegiatan membaca/menyimak berbagai jenis teks dalam pembelajaran',
            ],
            [
                'tahap' => 'B. Pelaksanaan Pembelajaran',
                'dimensi' => 'Penguatan Numerasi',
                'indikator' => 'Guru mengintegrasikan penalaran numerasi (konsep, prosedur, fakta, alat matematika) dalam pembelajaran',
            ],
            [
                'tahap' => 'B. Pelaksanaan Pembelajaran',
                'dimensi' => 'Penguatan Numerasi',
                'indikator' => 'Guru melatih peserta didik memecahkan masalah sehari-hari berbantuan data, tabel, grafik, atau diagram',
            ],
            [
                'tahap' => 'B. Pelaksanaan Pembelajaran',
                'dimensi' => 'Berkesadaran',
                'indikator' => 'Guru menumbuhkan pemahaman kritis dan analitis peserta didik (olah pikir)',
            ],
            [
                'tahap' => 'B. Pelaksanaan Pembelajaran',
                'dimensi' => 'Bermakna',
                'indikator' => 'Guru memberikan umpan balik segera dan berkelanjutan kepada peserta didik selama pembelajaran',
            ],

            // C. Interaksi dan Pengelolaan Kelas
            [
                'tahap' => 'C. Interaksi & Pengelolaan Kelas',
                'dimensi' => '-',
                'indikator' => 'Guru mengelola kelas dan waktu pembelajaran secara efektif',
            ],
            [
                'tahap' => 'C. Interaksi & Pengelolaan Kelas',
                'dimensi' => '-',
                'indikator' => 'Guru berkomunikasi dengan bahasa yang santun, jelas, dan komunikatif',
            ],
            [
                'tahap' => 'C. Interaksi & Pengelolaan Kelas',
                'dimensi' => '-',
                'indikator' => 'Guru merespons pertanyaan dan kesulitan peserta didik secara positif',
            ],
            [
                'tahap' => 'C. Interaksi & Pengelolaan Kelas',
                'dimensi' => '-',
                'indikator' => 'Guru menciptakan suasana belajar yang aman, nyaman, dan menyenangkan',
            ],
            [
                'tahap' => 'C. Interaksi & Pengelolaan Kelas',
                'dimensi' => '-',
                'indikator' => 'Guru menumbuhkan sikap disiplin, tanggung jawab, dan gotong royong peserta didik',
            ],

            // D. Penilaian dan Tindak Lanjut
            [
                'tahap' => 'D. Penilaian & Tindak Lanjut',
                'dimensi' => '-',
                'indikator' => 'Penilaian dilaksanakan selaras dengan tujuan pembelajaran',
            ],
            [
                'tahap' => 'D. Penilaian & Tindak Lanjut',
                'dimensi' => '-',
                'indikator' => 'Guru memanfaatkan asesmen formatif untuk memperbaiki pembelajaran',
            ],
            [
                'tahap' => 'D. Penilaian & Tindak Lanjut',
                'dimensi' => '-',
                'indikator' => 'Guru memberikan umpan balik hasil penilaian kepada peserta didik secara berkelanjutan',
            ],
            [
                'tahap' => 'D. Penilaian & Tindak Lanjut',
                'dimensi' => '-',
                'indikator' => 'Guru melaksanakan refleksi dan menarik kesimpulan bersama peserta didik',
            ],
            [
                'tahap' => 'D. Penilaian & Tindak Lanjut',
                'dimensi' => '-',
                'indikator' => 'Guru merencanakan tindak lanjut (remedial/pengayaan) berdasarkan hasil asesmen',
            ],
        ];
    }

    public function index(Request $request)
    {
        $period = Period::fromSession();

        $records = Instrument::with(['teacher', 'subject', 'supervisor'])
            ->byPeriod($period)
            ->latest()
            ->get();

        $editing = null;
        if ($request->filled('edit')) {
            $editing = Instrument::with(['teacher', 'subject', 'supervisor'])
                ->findOrFail($request->integer('edit'));
            $this->guardPeriod($editing);
        }

        return Inertia::render('Instrumen/Index', [
            'rows' => self::rows(),
            'teachers' => User::guru()->get(),
            'subjects' => Subject::all(),
            'records' => $records,
            'editing' => $editing,
            'last_import' => session('imported_items'),
            'is_custom_default' => self::customRows() !== null,
        ]);
    }

    /**
     * Halaman Petunjuk Pengisian dan Rubrik Skor (tampilan statis).
     */
    public function petunjuk()
    {
        return Inertia::render('Instrumen/Petunjuk');
    }

    /**
     * Halaman Rekapitulasi Hasil Pemantauan/Supervisi periode berjalan.
     * Agregat seluruh item dari semua record: per Tahap/Komponen, per Dimensi,
     * dan per Guru (diambil dari data pengisian instrumen pemantauan).
     */
    public function rekapitulasi()
    {
        $period = Period::fromSession();

        $records = Instrument::with(['teacher', 'subject'])->byPeriod($period)->get();

        $byTahap = [];
        $byDimensi = [];
        $byGuru = [];
        foreach ($records as $record) {
            $diperoleh = 0;
            $jumlah = 0;
            foreach ($record->items ?? [] as $item) {
                $tahap = trim((string) ($item['tahap'] ?? '')) ?: '-';
                $dimensi = trim((string) ($item['dimensi'] ?? '')) ?: '-';
                $skor = max(0, min(4, (int) ($item['skor'] ?? 0)));

                $byTahap[$tahap] ??= ['jumlah' => 0, 'diperoleh' => 0];
                $byTahap[$tahap]['jumlah']++;
                $byTahap[$tahap]['diperoleh'] += $skor;

                $byDimensi[$dimensi] ??= ['jumlah' => 0, 'diperoleh' => 0];
                $byDimensi[$dimensi]['jumlah']++;
                $byDimensi[$dimensi]['diperoleh'] += $skor;

                $jumlah++;
                $diperoleh += $skor;
            }

            // Agregat per guru dari pengisian instrumen.
            $gid = $record->teacher_id ?? 0;
            $byGuru[$gid] ??= [
                'teacher_id' => $record->teacher_id,
                'nama' => $record->teacher?->name ?? '(Tanpa nama guru)',
                'nip' => $record->teacher?->nip ?? null,
                'mapel' => [],
                'kelas' => [],
                'observasi' => 0,
                'jumlah' => 0,
                'diperoleh' => 0,
            ];
            $byGuru[$gid]['observasi']++;
            $byGuru[$gid]['jumlah'] += $jumlah;
            $byGuru[$gid]['diperoleh'] += $diperoleh;
            if ($record->subject?->name) {
                $byGuru[$gid]['mapel'][$record->subject->name] = true;
            } elseif ($record->teacher?->mata_pelajaran) {
                $byGuru[$gid]['mapel'][$record->teacher->mata_pelajaran] = true;
            }
            if ($record->class_name) {
                $byGuru[$gid]['kelas'][$record->class_name] = true;
            }
        }

        $build = fn (array $grouped) => collect($grouped)->map(function ($v, $nama) {
            $maks = $v['jumlah'] * 4;
            $nilai = $maks > 0 ? round(($v['diperoleh'] / $maks) * 100, 2) : 0;

            return [
                'nama' => $nama,
                'jumlah' => $v['jumlah'],
                'maks' => $maks,
                'diperoleh' => $v['diperoleh'],
                'nilai' => $nilai,
                'predikat' => self::predikatRekap($nilai),
            ];
        })->values()->all();

        // Matriks nilai per guru × tahap: tiap guru yang sudah mengisi
        // instrumen pemantauan masuk sebagai kolom pada tabel Tahap/Komponen.
        // Matriks dimensi dihitung dengan cara yang sama untuk tabel Dimensi.
        $sel = [];
        $selDimensi = [];
        foreach ($records as $record) {
            $gid = $record->teacher_id ?? 0;
            foreach ($record->items ?? [] as $item) {
                $tahap = trim((string) ($item['tahap'] ?? '')) ?: '-';
                $skor = max(0, min(4, (int) ($item['skor'] ?? 0)));
                $sel[$gid][$tahap]['jumlah'] = ($sel[$gid][$tahap]['jumlah'] ?? 0) + 1;
                $sel[$gid][$tahap]['diperoleh'] = ($sel[$gid][$tahap]['diperoleh'] ?? 0) + $skor;

                $dim = trim((string) ($item['dimensi'] ?? '')) ?: '-';
                $selDimensi[$gid][$dim]['jumlah'] = ($selDimensi[$gid][$dim]['jumlah'] ?? 0) + 1;
                $selDimensi[$gid][$dim]['diperoleh'] = ($selDimensi[$gid][$dim]['diperoleh'] ?? 0) + $skor;
            }
        }
        $nilaiKomponen = collect($build($byTahap))->mapWithKeys(fn ($r) => [$r['nama'] => $r['nilai']]);
        $nilaiDimensi = collect($build($byDimensi))->mapWithKeys(fn ($r) => [$r['nama'] => $r['nilai']]);
        $matriks = [];
        foreach (array_keys($byTahap) as $tahap) {
            $rowSel = [];
            foreach ($byGuru as $gid => $gv) {
                $c = $sel[$gid][$tahap] ?? null;
                $rowSel[$gid] = ($c && $c['jumlah'] > 0)
                    ? round(($c['diperoleh'] / ($c['jumlah'] * 4)) * 100, 2)
                    : null;
            }
            $rata = (float) ($nilaiKomponen[$tahap] ?? 0);
            $matriks[] = [
                'nama' => $tahap,
                'sel' => $rowSel,
                'rata' => $rata,
                'predikat' => self::predikatRekap($rata),
            ];
        }
        $matriksDimensi = [];
        foreach (array_keys($byDimensi) as $dim) {
            $rowSel = [];
            foreach ($byGuru as $gid => $gv) {
                $c = $selDimensi[$gid][$dim] ?? null;
                $rowSel[$gid] = ($c && $c['jumlah'] > 0)
                    ? round(($c['diperoleh'] / ($c['jumlah'] * 4)) * 100, 2)
                    : null;
            }
            $rata = (float) ($nilaiDimensi[$dim] ?? 0);
            $matriksDimensi[] = [
                'nama' => $dim,
                'sel' => $rowSel,
                'rata' => $rata,
                'predikat' => self::predikatRekap($rata),
            ];
        }

        return Inertia::render('Instrumen/Rekapitulasi', [
            'komponen' => $build($byTahap),
            'dimensi' => $build($byDimensi),
            'matriks' => $matriks,
            'matriksDimensi' => $matriksDimensi,
            'perGuru' => collect($byGuru)->map(function ($v) {
                $maks = $v['jumlah'] * 4;
                $nilai = $maks > 0 ? round(($v['diperoleh'] / $maks) * 100, 2) : 0;

                return [
                    'teacher_id' => $v['teacher_id'],
                    'nama' => $v['nama'],
                    'nip' => $v['nip'],
                    'mapel' => implode(', ', array_keys($v['mapel'])) ?: '-',
                    'kelas' => implode(', ', array_keys($v['kelas'])) ?: '-',
                    'observasi' => $v['observasi'],
                    'jumlah' => $v['jumlah'],
                    'maks' => $maks,
                    'diperoleh' => $v['diperoleh'],
                    'nilai' => $nilai,
                    'predikat' => self::predikatRekap($nilai),
                ];
            })->sortBy('nama')->values()->all(),
            'jumlahRecord' => $records->count(),
        ]);
    }

    /**
     * Predikat rekap sesuai Interpretasi Nilai: 91–100 Sangat Baik,
     * 81–90 Baik, 71–80 Cukup, ≤70 Kurang.
     */
    public static function predikatRekap(float $nilai): string
    {
        if ($nilai >= 91) {
            return 'Sangat Baik';
        }
        if ($nilai >= 81) {
            return 'Baik';
        }
        if ($nilai >= 71) {
            return 'Cukup';
        }

        return 'Kurang';
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);

        $this->persist(
            $validated['teacher_id'] ?? null,
            $validated['subject_id'] ?? null,
            $validated['class_name'] ?? null,
            $validated['observation_date'],
            $validated['items'],
            $request->supervisor_id
        );

        return redirect()->route('instruments.index')->with('success', 'Instrumen berhasil disimpan.');
    }

    public function update(Request $request, Instrument $instrument)
    {
        $this->guardPeriod($instrument);

        $validated = $this->validatePayload($request);

        [$items, $totalSkor, $maxSkor, $score] = $this->buildItems($validated['items']);

        $instrument->update([
            'teacher_id' => $validated['teacher_id'] ?? null,
            'subject_id' => $validated['subject_id'] ?? null,
            'class_name' => $validated['class_name'] ?? null,
            'observation_date' => $validated['observation_date'],
            'items' => $items,
            'total_skor' => $totalSkor,
            'max_skor' => $maxSkor,
            'score' => $score,
        ]);

        return redirect()->route('instruments.index')->with('success', 'Instrumen berhasil diperbarui.');
    }

    public function destroy(Instrument $instrument)
    {
        $this->guardPeriod($instrument);

        $instrument->delete();

        return redirect()->route('instruments.index')->with('success', 'Instrumen berhasil dihapus.');
    }

    /**
     * Mengembalikan struktur bawaan ke 31 indikator standar
     * (menghapus struktur kustom dari hasil impor).
     */
    public function resetDefault()
    {
        Setting::where('key', self::DEFAULT_ROWS_KEY)->delete();

        return redirect()->route('instruments.index')->with('success', 'Struktur bawaan dikembalikan ke standar.');
    }

    /**
     * Mengunduh template Excel instrumen (header + baris indikator bawaan).
     */
    public function template()
    {
        return Excel::download(
            new InstrumentTemplateExport(self::rows()),
            'template-instrumen-supervisi.xlsx'
        );
    }

    /**
     * Mengekspor satu instrumen tersimpan ke Excel.
     * Hanya berisi struktur instrumen (tanpa skor dan identitas) agar
     * simetris dengan format impor.
     */
    public function export(Instrument $instrument)
    {
        $this->guardPeriod($instrument);

        return Excel::download(
            new InstrumentExport($instrument),
            'instrumen-'.$instrument->id.'-'.now()->format('Ymd-His').'.xlsx'
        );
    }

    /**
     * Mengimpor struktur instrumen dari file Excel (hanya Tahap/Aspek,
     * Dimensi, Indikator — tanpa skor dan identitas). Hasil impor dimuat
     * ke tabel halaman; skor diisi langsung di tabel lalu disimpan.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $extension = strtolower($request->file('file')->getClientOriginalExtension());
        if (! in_array($extension, ['xlsx', 'xls', 'csv'], true)) {
            return redirect()->route('instruments.index')->with('error', 'Format file tidak valid. Gunakan file .xlsx, .xls, atau .csv.');
        }

        try {
            $import = new InstrumentImport;
            Excel::import($import, $request->file('file'));

            if (! empty($import->rowErrors)) {
                throw new \RuntimeException(implode(' ', array_slice($import->rowErrors, 0, 5)));
            }

            if (empty($import->items)) {
                throw new \RuntimeException('File tidak berisi baris indikator yang valid.');
            }

            if (count($import->items) > self::MAX_CUSTOM_ROWS) {
                throw new \RuntimeException('Maksimal '.self::MAX_CUSTOM_ROWS.' baris indikator.');
            }

            // Hasil impor dijadikan struktur bawaan (default) + dimuat ke tabel halaman.
            Setting::set(self::DEFAULT_ROWS_KEY, json_encode($import->items, JSON_UNESCAPED_UNICODE));

            return redirect()->route('instruments.index')->with([
                'success' => 'Berhasil memuat '.count($import->items).' baris instrumen ke tabel dan menjadikannya struktur bawaan (default) — berlaku untuk tabel & template berikutnya.',
                'imported_items' => $import->items,
            ]);
        } catch (\Throwable $e) {
            return redirect()->route('instruments.index')->with('error', 'Gagal mengimpor instrumen: '.$e->getMessage());
        }
    }

    /**
     * Aturan validasi form instrumen (dipakai simpan & ubah).
     */
    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'teacher_id' => 'nullable|exists:users,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'class_name' => 'nullable|string|max:255',
            'observation_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.tahap' => 'required|string|max:255',
            'items.*.dimensi' => 'nullable|string|max:255',
            'items.*.indikator' => 'required|string|max:2000',
            'items.*.skor' => 'required|integer|min:1|max:4',
            'items.*.bukti' => 'nullable|string|max:2000',
            'items.*.catatan' => 'nullable|string|max:2000',
        ]);
    }

    /**
     * Membatasi aksi pada record di luar periode berjalan.
     */
    private function guardPeriod(Instrument $instrument): void
    {
        $period = Period::fromSession();
        if ($period && (int) $instrument->period_id !== (int) $period->id) {
            abort(404);
        }
    }

    /**
     * Menyusun baris tersimpan + total skor dari daftar baris tervalidasi.
     *
     * @return array{0: array, 1: int, 2: int, 3: float}
     */
    private function buildItems(array $submitted): array
    {
        $totalSkor = 0;
        $items = [];

        foreach (array_values($submitted) as $i => $item) {
            $skor = max(0, min(4, (int) ($item['skor'] ?? 0)));
            $totalSkor += $skor;

            $items[] = [
                'no' => $i + 1,
                'tahap' => $item['tahap'] ?? '-',
                'dimensi' => $item['dimensi'] ?? '',
                'indikator' => $item['indikator'] ?? '',
                'skor' => $skor,
                'bukti' => $item['bukti'] ?? '',
                'catatan' => $item['catatan'] ?? '',
            ];
        }

        $maxSkor = count($items) * 4;
        $score = $maxSkor > 0 ? round(($totalSkor / $maxSkor) * 100, 2) : 0;

        return [$items, $totalSkor, $maxSkor, $score];
    }

    /**
     * Menyimpan satu record instrumen dari daftar baris yang sudah tervalidasi.
     */
    private function persist(
        mixed $teacherId,
        mixed $subjectId,
        mixed $className,
        mixed $observationDate,
        array $submitted,
        mixed $supervisorId = null
    ): Instrument {
        [$items, $totalSkor, $maxSkor, $score] = $this->buildItems($submitted);

        return Instrument::create([
            'period_id' => Period::fromSession()?->id,
            'teacher_id' => $teacherId,
            'supervisor_id' => $supervisorId ?? auth()->id(),
            'subject_id' => $subjectId,
            'class_name' => $className,
            'observation_date' => $observationDate,
            'items' => $items,
            'total_skor' => $totalSkor,
            'max_skor' => $maxSkor,
            'score' => $score,
        ]);
    }
}