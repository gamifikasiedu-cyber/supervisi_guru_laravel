<?php

namespace App\Http\Controllers;

use App\Exports\PreObservationExport;
use App\Exports\PreObservationTemplateExport;
use App\Imports\PreObservationImport;
use App\Models\Period;
use App\Models\PreObservation;
use App\Models\Setting;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class PreObservationController extends Controller
{
    /**
     * Kunci settings untuk struktur pra observasi bawaan (default) kustom dari hasil impor.
     */
    public const DEFAULT_ROWS_KEY = 'pre_observation_default_rows';

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
                'komponen' => trim((string) ($row['komponen'] ?? $row['aspek'] ?? '')) ?: '-',
                'indikator' => $indikator,
                'keterkaitan' => trim((string) ($row['keterkaitan'] ?? '')),
            ];
        }

        return $rows === [] ? null : $rows;
    }

    /**
     * Struktur bawaan INSTRUMEN PRA OBSERVASI / TELAAH RPP-RPM
     * Pembelajaran Mendalam Terintegrasi Penguatan Literasi dan Numerasi
     * (sesuai format resmi: A. Identitas/CP/Tujuan, B. Perancangan
     * Pembelajaran Mendalam, C. Tahap Memahami, ...).
     *
     * Struktur dapat diganti melalui impor Excel di halaman.
     */
    public static function builtinRows(): array
    {
        return [
            // A. Identitas, CP, dan Tujuan Pembelajaran
            ['komponen' => 'A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN', 'indikator' => 'Identitas pembelajaran dan fase/kelas, alokasi waktu, dan konteks pembelajaran ditulis jelas dan konsisten.', 'keterkaitan' => 'Kesiapan pembelajaran dan konteks bermakna'],
            ['komponen' => 'A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN', 'indikator' => 'CP/kompetensi yang menjadi acuan pembelajaran tercantum dan relevan dengan materi.', 'keterkaitan' => 'Keselarasan CP-TP'],
            ['komponen' => 'A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN', 'indikator' => 'Tujuan pembelajaran dirumuskan spesifik, terukur, relevan, dan berorientasi pada kemampuan murid.', 'keterkaitan' => 'Tujuan sebagai arah pengalaman belajar mendalam'],
            ['komponen' => 'A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN', 'indikator' => 'Tujuan pembelajaran memuat tuntutan literasi yang relevan: menemukan informasi, menginterpretasi, mengintegrasikan, dan mengevaluasi.', 'keterkaitan' => 'Literasi'],
            ['komponen' => 'A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN', 'indikator' => 'Tujuan pembelajaran memuat tuntutan numerasi yang relevan: memahami, menerapkan, menalar/memecahkan masalah, atau mengambil keputusan.', 'keterkaitan' => 'Numerasi'],

            // B. Perancangan Pembelajaran Mendalam
            ['komponen' => 'B. PERANCANGAN PEMBELAJARAN MENDALAM', 'indikator' => 'RPP/RPM menunjukkan prinsip Berkesadaran: tujuan, kriteria keberhasilan, kesiapan belajar, pilihan/agensi murid, dan kesadaran terhadap proses belajar.', 'keterkaitan' => 'BBM – Berkesadaran'],
            ['komponen' => 'B. PERANCANGAN PEMBELAJARAN MENDALAM', 'indikator' => 'RPP/RPM menunjukkan prinsip Bermakna: materi/aktivitas dikaitkan dengan pengalaman, kehidupan nyata, lingkungan, budaya, atau dunia kerja.', 'keterkaitan' => 'BBM – Bermakna'],
            ['komponen' => 'B. PERANCANGAN PEMBELAJARAN MENDALAM', 'indikator' => 'RPP/RPM menunjukkan prinsip Menggembirakan: aktivitas menantang, interaktif, aman, positif, dan memotivasi murid.', 'keterkaitan' => 'BBM – Menggembirakan'],
            ['komponen' => 'B. PERANCANGAN PEMBELAJARAN MENDALAM', 'indikator' => 'Alur kegiatan dirancang melalui pengalaman belajar Memahami → Mengaplikasi → Merefleksi.', 'keterkaitan' => '3M'],
            ['komponen' => 'B. PERANCANGAN PEMBELAJARAN MENDALAM', 'indikator' => 'Aktivitas murid lebih dominan daripada aktivitas guru dan memberi ruang berpikir, bertanya, berdiskusi, mencoba, serta berkarya.', 'keterkaitan' => 'Pembelajaran berpusat pada murid'],

            // C. Tahap Memahami
            ['komponen' => 'C. TAHAP MEMAHAMI', 'indikator' => 'Kegiatan awal/inti memberi kesempatan murid mengakses berbagai sumber informasi dan membangun pemahaman konsep.', 'keterkaitan' => '3M – Memahami + Literasi'],
            ['komponen' => 'C. TAHAP MEMAHAMI', 'indikator' => 'Tersedia aktivitas membaca/menelaah teks, gambar, infografik, grafik, tabel, data, video, atau sumber digital yang relevan.', 'keterkaitan' => 'Literasi'],
            ['komponen' => 'C. TAHAP MEMAHAMI', 'indikator' => 'Pertanyaan/tugas mendorong murid menemukan informasi penting, mengolah, dan menyimpulkan secara kritis.', 'keterkaitan' => 'Literasi kritis'],
        ];
    }

    public function index(Request $request)
    {
        $period = Period::fromSession();

        $records = PreObservation::with(['teacher', 'subject', 'supervisor'])
            ->byPeriod($period)
            ->latest()
            ->get();

        $editing = null;
        if ($request->filled('edit')) {
            $editing = PreObservation::with(['teacher', 'subject', 'supervisor'])
                ->findOrFail($request->integer('edit'));
            $this->guardPeriod($editing);
        }

        return Inertia::render('PraObservasi/Index', [
            'rows' => self::rows(),
            'teachers' => User::guru()->get(),
            'subjects' => Subject::all(),
            'records' => $records,
            'editing' => $editing,
            'last_import' => session('imported_items'),
            'is_custom_default' => self::customRows() !== null,
        ]);
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
        );

        return redirect()->route('pre-observations.index')->with('success', 'Pra observasi berhasil disimpan.');
    }

    public function update(Request $request, PreObservation $preObservation)
    {
        $this->guardPeriod($preObservation);

        $validated = $this->validatePayload($request);

        [$items, $totalSkor, $maxSkor, $score] = $this->buildItems($validated['items']);

        $preObservation->update([
            'teacher_id' => $validated['teacher_id'] ?? null,
            'subject_id' => $validated['subject_id'] ?? null,
            'class_name' => $validated['class_name'] ?? null,
            'observation_date' => $validated['observation_date'],
            'items' => $items,
            'total_skor' => $totalSkor,
            'max_skor' => $maxSkor,
            'score' => $score,
        ]);

        return redirect()->route('pre-observations.index')->with('success', 'Pra observasi berhasil diperbarui.');
    }

    public function destroy(PreObservation $preObservation)
    {
        $this->guardPeriod($preObservation);

        $preObservation->delete();

        return redirect()->route('pre-observations.index')->with('success', 'Pra observasi berhasil dihapus.');
    }

    /**
     * Mengembalikan struktur bawaan ke standar
     * (menghapus struktur kustom dari hasil impor).
     */
    public function resetDefault()
    {
        Setting::where('key', self::DEFAULT_ROWS_KEY)->delete();

        return redirect()->route('pre-observations.index')->with('success', 'Struktur bawaan dikembalikan ke standar.');
    }

    /**
     * Mengunduh template Excel pra observasi (header + baris indikator bawaan).
     */
    public function template()
    {
        return Excel::download(
            new PreObservationTemplateExport(self::rows()),
            'template-pra-observasi.xlsx'
        );
    }

    /**
     * Mengekspor satu pra observasi tersimpan ke Excel.
     * Hanya berisi struktur (tanpa skor dan identitas) agar
     * simetris dengan format impor.
     */
    public function export(PreObservation $preObservation)
    {
        $this->guardPeriod($preObservation);

        return Excel::download(
            new PreObservationExport($preObservation),
            'pra-observasi-'.$preObservation->id.'-'.now()->format('Ymd-His').'.xlsx'
        );
    }

    /**
     * Mengimpor struktur pra observasi dari file Excel (hanya Komponen,
     * Aspek/Indikator, Keterkaitan — tanpa skor dan identitas). Hasil impor dimuat
     * ke tabel halaman; skor diisi langsung di tabel lalu disimpan.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $extension = strtolower($request->file('file')->getClientOriginalExtension());
        if (! in_array($extension, ['xlsx', 'xls', 'csv'], true)) {
            return redirect()->route('pre-observations.index')->with('error', 'Format file tidak valid. Gunakan file .xlsx, .xls, atau .csv.');
        }

        try {
            $import = new PreObservationImport;
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

            return redirect()->route('pre-observations.index')->with([
                'success' => 'Berhasil memuat '.count($import->items).' baris pra observasi ke tabel dan menjadikannya struktur bawaan (default) — berlaku untuk tabel & template berikutnya.',
                'imported_items' => $import->items,
            ]);
        } catch (\Throwable $e) {
            return redirect()->route('pre-observations.index')->with('error', 'Gagal mengimpor pra observasi: '.$e->getMessage());
        }
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'teacher_id' => 'nullable|exists:users,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'class_name' => 'nullable|string|max:255',
            'observation_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.komponen' => 'required|string|max:255',
            'items.*.indikator' => 'required|string|max:2000',
            'items.*.keterkaitan' => 'nullable|string|max:1000',
            'items.*.skor' => 'required|integer|min:1|max:4',
            'items.*.bukti' => 'nullable|string|max:2000',
            'items.*.catatan' => 'nullable|string|max:2000',
            'items.*.rekomendasi' => 'nullable|string|max:2000',
        ]);
    }

    private function guardPeriod(PreObservation $preObservation): void
    {
        $period = Period::fromSession();
        if ($period && (int) $preObservation->period_id !== (int) $period->id) {
            abort(404);
        }
    }

    /**
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
                'komponen' => $item['komponen'] ?? $item['aspek'] ?? '-',
                'indikator' => $item['indikator'] ?? '',
                'keterkaitan' => $item['keterkaitan'] ?? '',
                'skor' => $skor,
                'bukti' => $item['bukti'] ?? '',
                'catatan' => $item['catatan'] ?? '',
                'rekomendasi' => $item['rekomendasi'] ?? '',
            ];
        }

        $maxSkor = count($items) * 4;
        $score = $maxSkor > 0 ? round(($totalSkor / $maxSkor) * 100, 2) : 0;

        return [$items, $totalSkor, $maxSkor, $score];
    }

    private function persist(
        mixed $teacherId,
        mixed $subjectId,
        mixed $className,
        mixed $observationDate,
        array $submitted,
    ): PreObservation {
        [$items, $totalSkor, $maxSkor, $score] = $this->buildItems($submitted);

        return PreObservation::create([
            'period_id' => Period::fromSession()?->id,
            'teacher_id' => $teacherId,
            'supervisor_id' => auth()->id(),
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
