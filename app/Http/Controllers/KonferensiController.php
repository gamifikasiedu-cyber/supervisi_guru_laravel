<?php

namespace App\Http\Controllers;

use App\Exports\KonferensiExport;
use App\Exports\KonferensiTemplateExport;
use App\Imports\KonferensiImport;
use App\Models\Period;
use App\Models\PreObservationKonferensi;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class KonferensiController extends Controller
{
    public const MAX_PHOTOS = 5;
    /**
     * Pertanyaan/fokus bawaan Konferensi-Wawancara Pra Observasi.
     */
    public static function rows(): array
    {
        return [
            ['pertanyaan' => 'Kesiapan perangkat pembelajaran (RPP/RPM, media, LKPD, instrumen asesmen)'],
            ['pertanyaan' => 'Tujuan pembelajaran dan rencana kegiatan yang akan diobservasi'],
            ['pertanyaan' => 'Strategi penguatan literasi dan numerasi yang direncanakan'],
            ['pertanyaan' => 'Kendala yang diperkirakan dan bantuan yang dibutuhkan guru'],
            ['pertanyaan' => 'Kesepakatan fokus observasi, jadwal, dan instrumen yang dipakai'],
        ];
    }

    public function index(Request $request)
    {
        $period = Period::fromSession();

        $records = PreObservationKonferensi::with(['teacher', 'subject', 'supervisor'])
            ->byPeriod($period)
            ->latest()
            ->get()
            ->each(fn ($rec) => $rec->appendDokumentasiUrls());

        $editing = null;
        if ($request->filled('edit')) {
            $editing = PreObservationKonferensi::with(['teacher', 'subject', 'supervisor'])
                ->findOrFail($request->integer('edit'));
            $this->guardPeriod($editing);
            $editing->appendDokumentasiUrls();
        }

        return Inertia::render('PraObservasi/Konferensi', [
            'rows' => self::rows(),
            'teachers' => User::guru()->get(),
            'subjects' => Subject::all(),
            'records' => $records,
            'editing' => $editing,
            'last_import' => session('imported_items'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);

        $record = $this->persist($validated);
        $this->storeUploadedPhotos($request, $record);

        return redirect()->route('konferensi.index')->with('success', 'Konferensi-wawancara berhasil disimpan.');
    }

    public function update(Request $request, PreObservationKonferensi $konferensi)
    {
        $this->guardPeriod($konferensi);

        $validated = $this->validatePayload($request);

        // Sinkron foto lama yang dipertahankan + foto baru.
        $keep = array_values(array_filter((array) $request->input('keep_photos', [])));
        $existing = array_values(array_filter((array) ($konferensi->dokumentasi_foto ?? [])));
        // Hanya pertahankan path yang memang milik record ini (cegah manipulasi).
        $kept = array_values(array_intersect($keep, $existing));
        foreach (array_diff($existing, $kept) as $removed) {
            Storage::disk('public')->delete($removed);
        }

        $konferensi->update([
            'teacher_id' => $validated['teacher_id'] ?? null,
            'subject_id' => $validated['subject_id'] ?? null,
            'class_name' => $validated['class_name'] ?? null,
            'observation_date' => $validated['observation_date'],
            'items' => $this->buildItems($validated['items']),
            'dokumentasi_foto' => $kept,
        ]);

        $this->storeUploadedPhotos($request, $konferensi->fresh());

        return redirect()->route('konferensi.index')->with('success', 'Konferensi-wawancara berhasil diperbarui.');
    }

    public function destroy(PreObservationKonferensi $konferensi)
    {
        $this->guardPeriod($konferensi);

        $this->deleteAllPhotos($konferensi);
        $konferensi->delete();

        return redirect()->route('konferensi.index')->with('success', 'Konferensi-wawancara berhasil dihapus.');
    }

    /**
     * Upload susulan foto dokumentasi per guru (dari riwayat).
     */
    public function uploadFoto(Request $request, PreObservationKonferensi $konferensi)
    {
        $this->guardPeriod($konferensi);

        $request->validate([
            'dokumentasi' => 'required|array|min:1|max:'.self::MAX_PHOTOS,
            'dokumentasi.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'dokumentasi.required' => 'Pilih minimal 1 foto.',
            'dokumentasi.*.image' => 'File harus berupa gambar.',
            'dokumentasi.*.max' => 'Ukuran tiap foto maksimal 5MB.',
        ]);

        $this->storeUploadedPhotos($request, $konferensi, true);

        return back()->with('success', 'Foto dokumentasi berhasil diunggah.');
    }

    /**
     * Hapus satu foto dokumentasi.
     */
    public function destroyFoto(Request $request, PreObservationKonferensi $konferensi)
    {
        $this->guardPeriod($konferensi);

        $request->validate(['path' => 'required|string']);

        $existing = array_values(array_filter((array) ($konferensi->dokumentasi_foto ?? [])));
        if (! in_array($request->input('path'), $existing, true)) {
            return back()->with('error', 'Foto tidak ditemukan.');
        }

        Storage::disk('public')->delete($request->input('path'));
        $konferensi->update([
            'dokumentasi_foto' => array_values(array_diff($existing, [$request->input('path')])),
        ]);

        return back()->with('success', 'Foto dokumentasi berhasil dihapus.');
    }

    public function template()
    {
        return Excel::download(
            new KonferensiTemplateExport(self::rows()),
            'template-konferensi-wawancara.xlsx'
        );
    }

    public function export(PreObservationKonferensi $konferensi)
    {
        $this->guardPeriod($konferensi);

        return Excel::download(
            new KonferensiExport($konferensi),
            'konferensi-'.$konferensi->id.'-'.now()->format('Ymd-His').'.xlsx'
        );
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|max:10240']);

        $extension = strtolower($request->file('file')->getClientOriginalExtension());
        if (! in_array($extension, ['xlsx', 'xls', 'csv'], true)) {
            return redirect()->route('konferensi.index')->with('error', 'Format file tidak valid. Gunakan file .xlsx, .xls, atau .csv.');
        }

        try {
            $import = new KonferensiImport;
            Excel::import($import, $request->file('file'));

            if (! empty($import->rowErrors)) {
                throw new \RuntimeException(implode(' ', array_slice($import->rowErrors, 0, 5)));
            }

            if (empty($import->items)) {
                throw new \RuntimeException('File tidak berisi baris yang valid.');
            }

            if (count($import->items) > 200) {
                throw new \RuntimeException('Maksimal 200 baris.');
            }

            return redirect()->route('konferensi.index')->with([
                'success' => 'Berhasil memuat '.count($import->items).' baris ke tabel — periksa, lengkapi, lalu klik Simpan.',
                'imported_items' => $import->items,
            ]);
        } catch (\Throwable $e) {
            return redirect()->route('konferensi.index')->with('error', 'Gagal mengimpor: '.$e->getMessage());
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
            'items.*.pertanyaan' => 'required|string|max:2000',
            'items.*.catatan_guru' => 'nullable|string|max:2000',
            'items.*.catatan_supervisor' => 'nullable|string|max:2000',
            'items.*.kesepakatan' => 'nullable|string|max:2000',
            'items.*.tindak_lanjut' => 'nullable|string|max:2000',
            'dokumentasi' => 'nullable|array|max:'.self::MAX_PHOTOS,
            'dokumentasi.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'keep_photos' => 'nullable|array',
            'keep_photos.*' => 'string|max:500',
        ], [
            'dokumentasi.*.image' => 'Foto dokumentasi harus berupa gambar (jpg/jpeg/png/webp).',
            'dokumentasi.*.max' => 'Ukuran tiap foto maksimal 5MB.',
        ]);
    }

    private function guardPeriod(PreObservationKonferensi $record): void
    {
        $period = Period::fromSession();
        if ($period && (int) $record->period_id !== (int) $period->id) {
            abort(404);
        }
    }

    private function buildItems(array $submitted): array
    {
        $items = [];
        foreach (array_values($submitted) as $i => $item) {
            $items[] = [
                'no' => $i + 1,
                'pertanyaan' => $item['pertanyaan'] ?? '',
                'catatan_guru' => $item['catatan_guru'] ?? '',
                'catatan_supervisor' => $item['catatan_supervisor'] ?? '',
                'kesepakatan' => $item['kesepakatan'] ?? '',
                'tindak_lanjut' => $item['tindak_lanjut'] ?? '',
            ];
        }

        return $items;
    }

    private function persist(array $validated): PreObservationKonferensi
    {
        return PreObservationKonferensi::create([
            'period_id' => Period::fromSession()?->id,
            'teacher_id' => $validated['teacher_id'] ?? null,
            'supervisor_id' => auth()->id(),
            'subject_id' => $validated['subject_id'] ?? null,
            'class_name' => $validated['class_name'] ?? null,
            'observation_date' => $validated['observation_date'],
            'items' => $this->buildItems($validated['items']),
            'dokumentasi_foto' => [],
        ]);
    }

    /**
     * Simpan file foto yang diunggah (maks. MAX_PHOTOS per record).
     */
    private function storeUploadedPhotos(Request $request, PreObservationKonferensi $record, bool $failOnFull = false): void
    {
        if (! $request->hasFile('dokumentasi')) {
            return;
        }

        $existing = array_values(array_filter((array) ($record->dokumentasi_foto ?? [])));
        $files = array_values((array) $request->file('dokumentasi'));

        if (count($existing) + count($files) > self::MAX_PHOTOS) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'dokumentasi' => 'Maksimal '.self::MAX_PHOTOS.' foto dokumentasi per guru.',
            ]);
        }

        foreach ($files as $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }
            $existing[] = $file->store('konferensi-dokumentasi', 'public');
        }

        $record->update(['dokumentasi_foto' => array_values($existing)]);
    }

    private function deleteAllPhotos(PreObservationKonferensi $record): void
    {
        foreach ((array) ($record->dokumentasi_foto ?? []) as $path) {
            Storage::disk('public')->delete($path);
        }
    }
}
