<?php

namespace App\Http\Controllers;

use App\Exports\PostSupervisionExport;
use App\Exports\PostSupervisionTemplateExport;
use App\Http\Controllers\InstrumentController;
use App\Imports\PostSupervisionImport;
use App\Models\Instrument;
use App\Models\Period;
use App\Models\PostSupervision;
use App\Models\PreObservation;
use App\Models\Setting;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class PostSupervisionController extends Controller
{
    /**
     * Konfigurasi 8 bagian Pasca-Supervisi.
     *
     * fields: key, label, type (text|textarea|skor|select), required, options, width
     */
    public const BAGIAN = [
        'asesmen-formatif' => [
            'label' => 'Asesmen Formatif',
            'title' => 'ASESMEN FORMATIF – INFORMASI UNTUK PERBAIKAN PROSES PEMBELAJARAN',
            'subtitle' => 'Informasi untuk perbaikan proses pembelajaran',
            'groupBy' => null,
            'hasSkor' => true,
            // Kolom Excel (template, impor, ekspor) hanya Indikator;
            // skor/bukti/tindakan diisi langsung di tabel form.
            'excel_keys' => ['indikator'],
            'fields' => [
                ['key' => 'indikator', 'label' => 'Indikator', 'type' => 'textarea', 'required' => true, 'width' => ''],
                ['key' => 'skor', 'label' => 'Skor (1-4)', 'type' => 'skor', 'required' => true, 'width' => 'w-24'],
                ['key' => 'bukti', 'label' => 'Bukti/Informasi', 'type' => 'textarea', 'required' => false, 'width' => 'w-56'],
                ['key' => 'tindakan', 'label' => 'Tindakan Perbaikan Pembelajaran', 'type' => 'textarea', 'required' => false, 'width' => 'w-56'],
            ],
            'standard' => [
                ['indikator' => 'Pemahaman konsep peserta didik terhadap materi'],
                ['indikator' => 'Keterampilan proses / unjuk kerja peserta didik'],
                ['indikator' => 'Partisipasi, sikap, dan kolaborasi peserta didik'],
                ['indikator' => 'Penguatan literasi dan numerasi dalam proses'],
            ],
        ],
        'asesmen-sumatif' => [
            'label' => 'Asesmen Sumatif',
            'title' => 'ASESMEN SUMATIF – BUKTI CAPAIAN DAN NILAI SETIAP TP',
            'subtitle' => 'Bukti capaian dan nilai setiap TP',
            'groupBy' => null,
            'hasSkor' => true,
            // Kolom Excel (template, impor, ekspor) hanya Indikator;
            // skor/bukti/catatan diisi langsung di tabel form.
            'excel_keys' => ['indikator'],
            'fields' => [
                ['key' => 'indikator', 'label' => 'Indikator', 'type' => 'textarea', 'required' => true, 'width' => ''],
                ['key' => 'skor', 'label' => 'Skor (1-4)', 'type' => 'skor', 'required' => true, 'width' => 'w-24'],
                ['key' => 'bukti', 'label' => 'Bukti', 'type' => 'textarea', 'required' => false, 'width' => 'w-56'],
                ['key' => 'catatan', 'label' => 'Catatan', 'type' => 'textarea', 'required' => false, 'width' => 'w-56'],
            ],
            'standard' => [
                ['indikator' => 'TP 1 — capaian tujuan pembelajaran 1'],
                ['indikator' => 'TP 2 — capaian tujuan pembelajaran 2'],
                ['indikator' => 'TP 3 — capaian tujuan pembelajaran 3'],
            ],
        ],
        'refleksi-dialog' => [
            'label' => 'Refleksi Dialog',
            'title' => 'REFLEKSI DAN DIALOG PASCA-SUPERVISI',
            'subtitle' => 'Catatan refleksi dan dialog supervisor dengan guru',
            'groupBy' => 'tahap',
            'hasSkor' => false,
            'fields' => [
                ['key' => 'tahap', 'label' => 'Tahap', 'type' => 'text', 'required' => true, 'width' => 'w-44'],
                ['key' => 'pertanyaan', 'label' => 'Pertanyaan Pemandu', 'type' => 'textarea', 'required' => true, 'width' => ''],
                ['key' => 'catatan', 'label' => 'Catatan Hasil Dialog', 'type' => 'textarea', 'required' => false, 'width' => 'w-72'],
            ],
            'standard' => [
                ['tahap' => 'Refleksi', 'pertanyaan' => 'Apa yang sudah berjalan baik dalam pembelajaran?'],
                ['tahap' => 'Refleksi', 'pertanyaan' => 'Apa kendala yang dihadapi selama pembelajaran?'],
                ['tahap' => 'Dialog', 'pertanyaan' => 'Apa rencana perbaikan yang disepakati?'],
                ['tahap' => 'Dialog', 'pertanyaan' => 'Dukungan apa yang dibutuhkan guru?'],
            ],
        ],
        'temuan-rtl' => [
            'label' => 'Temuan RTL',
            'title' => 'TEMUAN, AKAR MASALAH DAN RENCANA TINDAK LANJUT',
            'subtitle' => 'Temuan, akar masalah, dan rencana tindak lanjut',
            'groupBy' => null,
            'hasSkor' => false,
            'fields' => [
                ['key' => 'fokus', 'label' => 'Fokus/Temuan', 'type' => 'textarea', 'required' => true, 'width' => ''],
                ['key' => 'bukti', 'label' => 'Bukti', 'type' => 'textarea', 'required' => false, 'width' => 'w-44'],
                ['key' => 'akar', 'label' => 'Akar Masalah', 'type' => 'textarea', 'required' => false, 'width' => 'w-44'],
                ['key' => 'prioritas', 'label' => 'Prioritas', 'type' => 'select', 'required' => false, 'options' => ['Tinggi', 'Sedang', 'Rendah'], 'width' => 'w-28'],
                ['key' => 'tindakan', 'label' => 'Tindakan/RTL', 'type' => 'textarea', 'required' => false, 'width' => 'w-44'],
                ['key' => 'target', 'label' => 'Target', 'type' => 'text', 'required' => false, 'width' => 'w-36'],
                ['key' => 'waktu', 'label' => 'Waktu', 'type' => 'text', 'required' => false, 'width' => 'w-32'],
                ['key' => 'penanggung', 'label' => 'Penanggung Jawab', 'type' => 'text', 'required' => false, 'width' => 'w-36'],
                ['key' => 'bukti_keberhasilan', 'label' => 'Bukti Keberhasilan', 'type' => 'textarea', 'required' => false, 'width' => 'w-44'],
            ],
            'standard' => [
                ['fokus' => 'Contoh: literasi belum terintegrasi eksplisit di RPP'],
                ['fokus' => 'Contoh: rubrik penilaian belum rinci per indikator'],
            ],
        ],
        'monitoring' => [
            'label' => 'Monitoring',
            'title' => 'MONITORING TINDAK LANJUT',
            'subtitle' => 'Pemantauan pelaksanaan tindak lanjut',
            'groupBy' => null,
            'hasSkor' => false,
            'fields' => [
                ['key' => 'indikator', 'label' => 'Indikator Perubahan', 'type' => 'textarea', 'required' => true, 'width' => ''],
                ['key' => 'awal', 'label' => 'Kondisi Awal', 'type' => 'textarea', 'required' => false, 'width' => 'w-44'],
                ['key' => 'target', 'label' => 'Target', 'type' => 'textarea', 'required' => false, 'width' => 'w-44'],
                ['key' => 'hasil', 'label' => 'Hasil Monitoring', 'type' => 'textarea', 'required' => false, 'width' => 'w-44'],
                ['key' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => false, 'options' => ['Belum Ditindaklanjuti', 'Dalam Proses', 'Selesai'], 'width' => 'w-40'],
                ['key' => 'bukti', 'label' => 'Bukti', 'type' => 'textarea', 'required' => false, 'width' => 'w-44'],
            ],
            'standard' => [
                ['indikator' => 'Contoh: kegiatan literasi terintegrasi di RPP dan KBM'],
                ['indikator' => 'Contoh: rubrik penilaian dirinci per indikator'],
            ],
        ],
        'observasi-3m' => [
            'label' => 'Observasi_3M',
            'title' => 'OBSERVASI 3M – MEMAHAMI, MENGAPLIKASI, MEREFLEKSI',
            'subtitle' => 'Observasi pengalaman belajar 3M: Memahami, Mengaplikasi, Merefleksi',
            'groupBy' => null,
            'hasSkor' => true,
            // Kolom Excel (template, impor, ekspor) hanya 2 kolom ini.
            'excel_keys' => ['pengalaman_belajar', 'indikator'],
            'fields' => [
                ['key' => 'pengalaman_belajar', 'label' => 'Pengalaman Belajar', 'type' => 'select', 'required' => true, 'options' => ['Memahami', 'Mengaplikasi', 'Merefleksi'], 'width' => 'w-44'],
                ['key' => 'indikator', 'label' => 'Indikator', 'type' => 'textarea', 'required' => true, 'width' => ''],
                ['key' => 'skor', 'label' => 'Skor (1-4)', 'type' => 'skor', 'required' => true, 'width' => 'w-24'],
                ['key' => 'bukti', 'label' => 'Bukti/Temuan', 'type' => 'textarea', 'required' => false, 'width' => 'w-56'],
            ],
            'standard' => [
                ['pengalaman_belajar' => 'Memahami', 'indikator' => 'Peserta didik memahami konsep/materi yang dipelajari'],
                ['pengalaman_belajar' => 'Mengaplikasi', 'indikator' => 'Peserta didik menerapkan konsep dalam tugas, latihan, dan pemecahan masalah'],
                ['pengalaman_belajar' => 'Merefleksi', 'indikator' => 'Peserta didik dan guru merefleksi proses dan hasil pembelajaran'],
            ],
        ],
        'observasi-bbm' => [
            'label' => 'Observasi_BBM',
            'title' => 'OBSERVASI BBM – BERKESADARAN, BERMAKNA, MENGGEMBIRAKAN',
            'subtitle' => 'Observasi prinsip Pembelajaran Mendalam: Berkesadaran, Bermakna, Menggembirakan',
            'groupBy' => null,
            'hasSkor' => true,
            // Kolom Excel (template, impor, ekspor) hanya 2 kolom ini.
            'excel_keys' => ['prinsip_bbm', 'indikator'],
            'fields' => [
                ['key' => 'prinsip_bbm', 'label' => 'Prinsip BBM', 'type' => 'select', 'required' => true, 'options' => ['Berkesadaran', 'Bermakna', 'Menggembirakan'], 'width' => 'w-44'],
                ['key' => 'indikator', 'label' => 'Indikator', 'type' => 'textarea', 'required' => true, 'width' => ''],
                ['key' => 'skor', 'label' => 'Skor (1-4)', 'type' => 'skor', 'required' => true, 'width' => 'w-24'],
                ['key' => 'bukti', 'label' => 'Bukti/Temuan', 'type' => 'textarea', 'required' => false, 'width' => 'w-56'],
            ],
            'standard' => [
                ['prinsip_bbm' => 'Berkesadaran', 'indikator' => 'Pembelajaran menumbuhkan kesadaran, karakter, dan olah pikir peserta didik'],
                ['prinsip_bbm' => 'Bermakna', 'indikator' => 'Materi dikaitkan dengan konteks nyata dan kehidupan peserta didik'],
                ['prinsip_bbm' => 'Menggembirakan', 'indikator' => 'Suasana belajar menyenangkan, aktif, kolaboratif, dan partisipatif'],
            ],
        ],
        'rekap' => [
            'label' => 'Rekap',
            'title' => 'REKAP HASIL PASCA-SUPERVISI AKADEMIK',
            'subtitle' => 'Rekapitulasi hasil pasca-supervisi akademik',
            'groupBy' => null,
            'hasSkor' => false,
            'fields' => [
                ['key' => 'komponen', 'label' => 'Komponen', 'type' => 'text', 'required' => true, 'width' => ''],
                ['key' => 'rata', 'label' => 'Rata-rata', 'type' => 'text', 'required' => false, 'width' => 'w-32'],
                ['key' => 'kategori', 'label' => 'Kategori', 'type' => 'select', 'required' => false, 'options' => ['Sangat Baik', 'Baik', 'Cukup', 'Kurang'], 'width' => 'w-40'],
            ],
            'standard' => [
                ['komponen' => 'Perencanaan (RPP/RPM)'],
                ['komponen' => 'Pelaksanaan (Pemantauan)'],
                ['komponen' => 'Asesmen Formatif'],
                ['komponen' => 'Asesmen Sumatif'],
                ['komponen' => 'Tindak Lanjut (RTL & Monitoring)'],
            ],
        ],
    ];

    /**
     * Pilihan jenis observasi khusus bagian Asesmen Formatif.
     * 3M = Memahami, Mengaplikasi, Merefleksi;
     * BBM = Berkesadaran, Bermakna, Menggembirakan.
     */
    public const JENIS_OBSERVASI = ['Observasi_3M', 'Observasi_BBM'];

    private const MAX_CUSTOM_ROWS = 200;

    /**
     * Kunci settings untuk struktur bawaan kustom dari hasil impor
     * (Observasi_3M dan Observasi_BBM).
     */
    public const OBS_DEFAULT_KEYS = [
        'observasi-3m' => 'observasi_3m_default_rows',
        'observasi-bbm' => 'observasi_bbm_default_rows',
    ];

    public static function config(string $bagian): ?array
    {
        return self::BAGIAN[$bagian] ?? null;
    }

    /**
     * Kolom yang dipakai untuk template/impor/ekspor Excel.
     * Bawaan: seluruh fields; dapat dibatasi via 'excel_keys' pada config bagian.
     */
    public static function excelFields(array $cfg): array
    {
        $keys = $cfg['excel_keys'] ?? array_column($cfg['fields'], 'key');

        return array_values(array_filter(
            $cfg['fields'],
            fn ($f) => in_array($f['key'], $keys, true)
        ));
    }
    public static function nav(): array
    {
        $nav = [];
        foreach (self::BAGIAN as $slug => $meta) {
            $nav[] = ['slug' => $slug, 'label' => $meta['label']];
        }

        return $nav;
    }

    public function redirect(): RedirectResponse
    {
        return redirect()->route('pasca-supervisi.show', ['bagian' => 'asesmen-formatif']);
    }

    public function show(string $bagian, Request $request): Response|RedirectResponse
    {
        $cfg = self::config($bagian);
        if (! $cfg) {
            return redirect()->route('pasca-supervisi.show', ['bagian' => 'asesmen-formatif']);
        }

        // Guru murni hanya boleh mengakses Monitoring & Rekap
        $user = $request->user();
        if ($user && $user->hasRole('guru') && ! $user->hasRole('admin', 'supervisor', 'kepala_sekolah', 'pengawas') && ! in_array($bagian, ['monitoring', 'rekap'], true)) {
            return redirect()->route('pasca-supervisi.show', ['bagian' => 'monitoring']);
        }

        $period = Period::fromSession();

        $records = PostSupervision::with(['teacher', 'subject', 'supervisor'])
            ->byPeriod($period)
            ->byBagian($bagian)
            ->latest()
            ->get();

        $editing = null;
        if ($request->filled('edit')) {
            $editing = PostSupervision::with(['teacher', 'subject', 'supervisor'])
                ->byBagian($bagian)
                ->findOrFail($request->integer('edit'));
            $this->guardPeriod($editing);
        }

        $payload = [
            'bagian' => $bagian,
            'meta' => [
                'label' => $cfg['label'],
                'title' => $cfg['title'],
                'subtitle' => $cfg['subtitle'],
                'groupBy' => $cfg['groupBy'],
                'hasSkor' => $cfg['hasSkor'],
                'fields' => $cfg['fields'],
                'excel_labels' => array_map(fn ($f) => $f['label'], self::excelFields($cfg)),
            ],
            'nav' => ($user && $user->hasRole('guru') && ! $user->hasRole('admin', 'supervisor', 'kepala_sekolah', 'pengawas'))
                ? array_values(array_filter(self::nav(), fn ($n) => in_array($n['slug'], ['monitoring', 'rekap'], true)))
                : self::nav(),
            'rows' => $this->blankRows($bagian),
            'teachers' => User::guru()->get(),
            'subjects' => Subject::all(),
            'records' => $records,
            'editing' => $editing,
            'last_import' => session('imported_items'),
            'is_custom_default' => self::customRowsFor($bagian) !== null,
        ];

        // Rekap otomatis: agregat dari 4 riwayat penilaian + total rata-rata
        if ($bagian === 'rekap') {
            $payload['rekap_summary'] = $this->rekapSummary($period, $user);
            $payload['rekap_riwayat'] = $this->rekapRiwayat($period, $user);
        }

        return Inertia::render('PascaSupervisi/Index', $payload);
    }

    public function store(string $bagian, Request $request)
    {
        $cfg = self::config($bagian);
        abort_if(! $cfg, 404);

        $validated = $this->validatePayload($request, $cfg, $bagian);

        $this->persist($bagian, $cfg, $validated);

        return redirect()->route('pasca-supervisi.show', ['bagian' => $bagian])->with('success', $cfg['label'].' berhasil disimpan.');
    }

    public function update(string $bagian, Request $request, PostSupervision $postSupervision)
    {
        $cfg = self::config($bagian);
        abort_if(! $cfg, 404);
        abort_if($postSupervision->bagian !== $bagian, 404);
        $this->guardPeriod($postSupervision);

        $validated = $this->validatePayload($request, $cfg, $bagian);
        $built = $this->buildItems($cfg, $validated['items']);

        $postSupervision->update([
            'teacher_id' => $validated['teacher_id'] ?? null,
            'subject_id' => $validated['subject_id'] ?? null,
            'class_name' => $validated['class_name'] ?? null,
            'observation_date' => $validated['observation_date'],
            'jenis_observasi' => $bagian === 'asesmen-formatif' ? ($validated['jenis_observasi'] ?? null) : null,
            'items' => $built['items'],
            'total_skor' => $built['total'],
            'max_skor' => $built['max'],
            'score' => $built['score'],
        ]);

        return redirect()->route('pasca-supervisi.show', ['bagian' => $bagian])->with('success', $cfg['label'].' berhasil diperbarui.');
    }

    public function destroy(string $bagian, PostSupervision $postSupervision)
    {
        abort_if($postSupervision->bagian !== $bagian, 404);
        $this->guardPeriod($postSupervision);

        $postSupervision->delete();

        return redirect()->route('pasca-supervisi.show', ['bagian' => $bagian])->with('success', 'Data berhasil dihapus.');
    }

    public function template(string $bagian)
    {
        $cfg = self::config($bagian);
        abort_if(! $cfg, 404);

        return Excel::download(
            new PostSupervisionTemplateExport($bagian, $cfg, $this->blankRows($bagian)),
            'template-'.$bagian.'.xlsx'
        );
    }

    public function export(string $bagian, PostSupervision $postSupervision)
    {
        $cfg = self::config($bagian);
        abort_if(! $cfg, 404);
        abort_if($postSupervision->bagian !== $bagian, 404);
        $this->guardPeriod($postSupervision);

        return Excel::download(
            new PostSupervisionExport($cfg, $postSupervision),
            $bagian.'-'.$postSupervision->id.'-'.now()->format('Ymd-His').'.xlsx'
        );
    }

    public function import(string $bagian, Request $request)
    {
        $cfg = self::config($bagian);
        abort_if(! $cfg, 404);

        $request->validate(['file' => 'required|file|max:10240']);

        $extension = strtolower($request->file('file')->getClientOriginalExtension());
        if (! in_array($extension, ['xlsx', 'xls', 'csv'], true)) {
            return redirect()->route('pasca-supervisi.show', ['bagian' => $bagian])->with('error', 'Format file tidak valid. Gunakan file .xlsx, .xls, atau .csv.');
        }

        try {
            $import = new PostSupervisionImport($cfg);
            Excel::import($import, $request->file('file'));

            if (! empty($import->rowErrors)) {
                throw new \RuntimeException(implode(' ', array_slice($import->rowErrors, 0, 5)));
            }

            if (empty($import->items)) {
                throw new \RuntimeException('File tidak berisi baris yang valid.');
            }

            if (count($import->items) > self::MAX_CUSTOM_ROWS) {
                throw new \RuntimeException('Maksimal '.self::MAX_CUSTOM_ROWS.' baris.');
            }

            // Khusus Observasi_3M/BBM: hasil impor dijadikan struktur bawaan
            // agar tetap tampil setelah halaman di-refresh.
            if (array_key_exists($bagian, self::OBS_DEFAULT_KEYS)) {
                Setting::set(self::OBS_DEFAULT_KEYS[$bagian], json_encode($import->items, JSON_UNESCAPED_UNICODE));
            }

            return redirect()->route('pasca-supervisi.show', ['bagian' => $bagian])->with([
                'success' => 'Berhasil memuat '.count($import->items).' baris ke tabel'.(array_key_exists($bagian, self::OBS_DEFAULT_KEYS) ? ' dan menjadikannya struktur bawaan — tetap tampil setelah refresh' : '').' — periksa, lengkapi, lalu klik Simpan.',
                'imported_items' => $import->items,
            ]);
        } catch (\Throwable $e) {
            return redirect()->route('pasca-supervisi.show', ['bagian' => $bagian])->with('error', 'Gagal mengimpor: '.$e->getMessage());
        }
    }

    /**
     * Mengembalikan struktur bawaan observasi ke standar
     * (menghapus struktur kustom dari hasil impor).
     */
    public function resetDefault(string $bagian)
    {
        abort_if(! array_key_exists($bagian, self::OBS_DEFAULT_KEYS), 404);
        Setting::where('key', self::OBS_DEFAULT_KEYS[$bagian])->delete();

        return redirect()->route('pasca-supervisi.show', ['bagian' => $bagian])->with('success', 'Struktur bawaan dikembalikan ke standar.');
    }

    /**
     * Baris awal standar (kolom isian dikosongkan agar diisi di tabel).
     * Khusus Observasi_3M/BBM: memakai struktur kustom dari hasil impor bila ada,
     * sehingga tidak kembali ke awal saat halaman di-refresh.
     */
    private function blankRows(string $bagian): array
    {
        if (($custom = self::customRowsFor($bagian)) !== null) {
            return $custom;
        }

        $cfg = self::config($bagian);
        $rows = [];
        foreach ($cfg['standard'] as $std) {
            $row = [];
            foreach ($cfg['fields'] as $f) {
                $row[$f['key']] = $std[$f['key']] ?? '';
            }
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Struktur bawaan kustom dari hasil impor (null jika belum ada / bukan bagian observasi).
     */
    public static function customRowsFor(string $bagian): ?array
    {
        if (! array_key_exists($bagian, self::OBS_DEFAULT_KEYS)) {
            return null;
        }

        try {
            $raw = Setting::value(self::OBS_DEFAULT_KEYS[$bagian]);
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
            // Simpan kolom apa adanya (kolom Excel bagian tsb.); kolom lain dikosongkan di form.
            $rows[] = array_map(fn ($v) => is_string($v) ? trim($v) : $v, $row);
        }

        return $rows === [] ? null : $rows;
    }

    private function validatePayload(Request $request, array $cfg, string $bagian): array
    {
        $rules = [
            'teacher_id' => 'nullable|exists:users,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'class_name' => 'nullable|string|max:255',
            'observation_date' => 'required|date',
            'items' => 'required|array|min:1',
        ];

        if ($bagian === 'asesmen-formatif') {
            $rules['jenis_observasi'] = 'nullable|string|in:'.implode(',', self::JENIS_OBSERVASI);
        }

        foreach ($cfg['fields'] as $f) {
            $key = 'items.*.'.$f['key'];
            if ($f['type'] === 'skor') {
                $rules[$key] = $f['required'] ? 'required|integer|min:1|max:4' : 'nullable|integer|min:1|max:4';
            } elseif ($f['type'] === 'select') {
                $in = implode(',', $f['options'] ?? []);
                $rules[$key] = $f['required'] ? "required|string|in:{$in}" : "nullable|string|in:{$in}";
            } else {
                $max = $f['key'] === 'tahap' ? 255 : 2000;
                $rules[$key] = $f['required'] ? "required|string|max:{$max}" : "nullable|string|max:{$max}";
            }
        }

        return $request->validate($rules);
    }

    private function guardPeriod(PostSupervision $record): void
    {
        $period = Period::fromSession();
        if ($period && (int) $record->period_id !== (int) $period->id) {
            abort(404);
        }
    }

    /**
     * @return array{items: array, total: ?int, max: ?int, score: ?float}
     */
    private function buildItems(array $cfg, array $submitted): array
    {
        $items = [];
        $total = 0;
        $countSkor = 0;

        foreach (array_values($submitted) as $i => $item) {
            $row = ['no' => $i + 1];
            foreach ($cfg['fields'] as $f) {
                $k = $f['key'];
                $row[$k] = $item[$k] ?? '';
            }
            if ($cfg['hasSkor']) {
                $skor = max(0, min(4, (int) ($item['skor'] ?? 0)));
                $row['skor'] = $skor;
                $total += $skor;
                $countSkor++;
            }
            $items[] = $row;
        }

        if (! $cfg['hasSkor']) {
            return ['items' => $items, 'total' => null, 'max' => null, 'score' => null];
        }

        $max = $countSkor * 4;
        $score = $max > 0 ? round(($total / $max) * 100, 2) : 0;

        return ['items' => $items, 'total' => $total, 'max' => $max, 'score' => $score];
    }

    private function persist(string $bagian, array $cfg, array $validated): PostSupervision
    {
        $built = $this->buildItems($cfg, $validated['items']);

        return PostSupervision::create([
            'period_id' => Period::fromSession()?->id,
            'teacher_id' => $validated['teacher_id'] ?? null,
            'supervisor_id' => auth()->id(),
            'subject_id' => $validated['subject_id'] ?? null,
            'class_name' => $validated['class_name'] ?? null,
            'observation_date' => $validated['observation_date'],
            'bagian' => $bagian,
            'jenis_observasi' => $bagian === 'asesmen-formatif' ? ($validated['jenis_observasi'] ?? null) : null,
            'items' => $built['items'],
            'total_skor' => $built['total'],
            'max_skor' => $built['max'],
            'score' => $built['score'],
        ]);
    }

    /**
     * Apakah user hanya berperan sebagai guru murni.
     */
    private function isGuruOnly($user): bool
    {
        return $user && $user->hasRole('guru')
            && ! $user->hasRole('admin', 'supervisor', 'kepala_sekolah', 'pengawas');
    }

    /**
     * Rekap otomatis per guru dari 6 sumber penilaian:
     * pra-observasi, instrumen, asesmen formatif, asesmen sumatif,
     * observasi 3M, observasi BBM.
     * Nilai tiap komponen = rata-rata score (0-100) pada periode berjalan.
     * Total = rata-rata dari komponen yang ada datanya.
     */
    private function rekapSummary(?Period $period, $user): array
    {
        $avgByTeacher = function ($records) {
            $group = [];
            foreach ($records as $r) {
                if (! $r->teacher_id || $r->score === null) {
                    continue;
                }
                $tid = (int) $r->teacher_id;
                $group[$tid] ??= ['total' => 0, 'count' => 0];
                $group[$tid]['total'] += (float) $r->score;
                $group[$tid]['count']++;
            }
            $avg = [];
            foreach ($group as $tid => $v) {
                $avg[$tid] = $v['count'] > 0 ? round($v['total'] / $v['count'], 2) : null;
            }

            return [$avg, $group];
        };

        $praQuery = PreObservation::byPeriod($period);
        $insQuery = Instrument::byPeriod($period);
        $forQuery = PostSupervision::byPeriod($period)->byBagian('asesmen-formatif');
        $sumQuery = PostSupervision::byPeriod($period)->byBagian('asesmen-sumatif');
        $tigaMQuery = PostSupervision::byPeriod($period)->byBagian('observasi-3m');
        $bbmQuery = PostSupervision::byPeriod($period)->byBagian('observasi-bbm');

        if ($this->isGuruOnly($user)) {
            $praQuery->where('teacher_id', $user->id);
            $insQuery->where('teacher_id', $user->id);
            $forQuery->where('teacher_id', $user->id);
            $sumQuery->where('teacher_id', $user->id);
            $tigaMQuery->where('teacher_id', $user->id);
            $bbmQuery->where('teacher_id', $user->id);
        }

        [$praAvg, $praGroup] = $avgByTeacher($praQuery->get(['teacher_id', 'score']));
        [$insAvg, $insGroup] = $avgByTeacher($insQuery->get(['teacher_id', 'score']));
        [$forAvg, $forGroup] = $avgByTeacher($forQuery->get(['teacher_id', 'score']));
        [$sumAvg, $sumGroup] = $avgByTeacher($sumQuery->get(['teacher_id', 'score']));
        [$tigaMAvg, $tigaMGroup] = $avgByTeacher($tigaMQuery->get(['teacher_id', 'score']));
        [$bbmAvg, $bbmGroup] = $avgByTeacher($bbmQuery->get(['teacher_id', 'score']));

        $teachers = $this->isGuruOnly($user)
            ? User::where('id', $user->id)->get()
            : User::guru()->orderBy('name')->get();

        $rows = [];
        foreach ($teachers as $guru) {
            $tid = (int) $guru->id;
            $nPra = $praAvg[$tid] ?? null;
            $nIns = $insAvg[$tid] ?? null;
            $nFor = $forAvg[$tid] ?? null;
            $nSum = $sumAvg[$tid] ?? null;
            $nTigaM = $tigaMAvg[$tid] ?? null;
            $nBbm = $bbmAvg[$tid] ?? null;

            $available = array_values(array_filter([$nPra, $nIns, $nFor, $nSum, $nTigaM, $nBbm], fn ($v) => $v !== null));
            $total = count($available) > 0 ? round(array_sum($available) / count($available), 2) : null;

            $rows[] = [
                'teacher_id' => $guru->id,
                'name' => $guru->name,
                'nip' => $guru->nip,
                'mata_pelajaran' => $guru->mata_pelajaran,
                'pra_observasi' => $nPra,
                'pra_count' => $praGroup[$tid]['count'] ?? 0,
                'instrumen' => $nIns,
                'instrumen_count' => $insGroup[$tid]['count'] ?? 0,
                'formatif' => $nFor,
                'formatif_count' => $forGroup[$tid]['count'] ?? 0,
                'sumatif' => $nSum,
                'sumatif_count' => $sumGroup[$tid]['count'] ?? 0,
                'observasi_3m' => $nTigaM,
                'observasi_3m_count' => $tigaMGroup[$tid]['count'] ?? 0,
                'observasi_bbm' => $nBbm,
                'observasi_bbm_count' => $bbmGroup[$tid]['count'] ?? 0,
                'total_rata' => $total,
                'kategori' => $total === null ? '-' : InstrumentController::predikatRekap((float) $total),
            ];
        }

        // Baris total rata-rata keseluruhan (rata-rata kolom)
        $colAvg = function (string $key) use ($rows) {
            $vals = array_filter(array_column($rows, $key), fn ($v) => $v !== null);
            return count($vals) > 0 ? round(array_sum($vals) / count($vals), 2) : null;
        };

        return [
            'rows' => $rows,
            'averages' => [
                'pra_observasi' => $colAvg('pra_observasi'),
                'instrumen' => $colAvg('instrumen'),
                'formatif' => $colAvg('formatif'),
                'sumatif' => $colAvg('sumatif'),
                'observasi_3m' => $colAvg('observasi_3m'),
                'observasi_bbm' => $colAvg('observasi_bbm'),
                'total_rata' => $colAvg('total_rata'),
            ],
        ];
    }

    /**
     * 4 riwayat penilaian mentah untuk ditampilkan di bawah rekap.
     */
    private function rekapRiwayat(?Period $period, $user): array
    {
        $map = function ($rec) {
            return [
                'id' => $rec->id,
                'tanggal' => $rec->observation_date ? $rec->observation_date->format('d/m/Y') : '-',
                'guru' => $rec->teacher->name ?? '-',
                'mapel' => $rec->subject->name ?? '-',
                'kelas' => $rec->class_name ?? '-',
                'skor' => $rec->total_skor !== null ? $rec->total_skor.'/'.$rec->max_skor : '-',
                'nilai' => $rec->score !== null ? (float) $rec->score : null,
            ];
        };

        $praQuery = PreObservation::with(['teacher', 'subject'])->byPeriod($period)->latest();
        $insQuery = Instrument::with(['teacher', 'subject'])->byPeriod($period)->latest();
        $forQuery = PostSupervision::with(['teacher', 'subject'])->byPeriod($period)->byBagian('asesmen-formatif')->latest();
        $sumQuery = PostSupervision::with(['teacher', 'subject'])->byPeriod($period)->byBagian('asesmen-sumatif')->latest();
        $refQuery = PostSupervision::with(['teacher', 'subject'])->byPeriod($period)->byBagian('refleksi-dialog')->latest();

        if ($this->isGuruOnly($user)) {
            $praQuery->where('teacher_id', $user->id);
            $insQuery->where('teacher_id', $user->id);
            $forQuery->where('teacher_id', $user->id);
            $sumQuery->where('teacher_id', $user->id);
            $refQuery->where('teacher_id', $user->id);
        }

        $mapRefleksi = function ($rec) {
            return [
                'id' => $rec->id,
                'tanggal' => $rec->observation_date ? $rec->observation_date->format('d/m/Y') : '-',
                'teacher_id' => $rec->teacher_id,
                'guru' => $rec->teacher->name ?? '-',
                'mapel' => $rec->subject->name ?? '-',
                'kelas' => $rec->class_name ?? '-',
                'items' => collect($rec->items ?? [])->map(fn ($it) => [
                    'tahap' => $it['tahap'] ?? '-',
                    'pertanyaan' => $it['pertanyaan'] ?? '-',
                    'catatan' => $it['catatan'] ?? '-',
                ])->values()->all(),
            ];
        };

        return [
            'pra_observasi' => $praQuery->get()->map($map)->values()->all(),
            'instrumen' => $insQuery->get()->map($map)->values()->all(),
            'formatif' => $forQuery->get()->map($map)->values()->all(),
            'sumatif' => $sumQuery->get()->map($map)->values()->all(),
            'refleksi_dialog' => $refQuery->get()->map($mapRefleksi)->values()->all(),
        ];
    }
}
