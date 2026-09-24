<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\Setting;
use App\Services\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        return Inertia::render('Settings/Index', [
            'settings' => [
                'school_name' => Setting::value('school_name'),
                'address' => Setting::value('address'),
                'npsn' => Setting::value('npsn'),
                'tahun_ajaran' => Setting::value('tahun_ajaran'),
                'semester' => Setting::value('semester'),
                'logo_url' => Setting::value('logo') ? Storage::url(Setting::value('logo')) : null,
            ],
            'periods' => Period::forLogin()->get(),
            'backups' => $this->backupList(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'school_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'npsn' => 'nullable|string|max:20',
            'tahun_ajaran' => 'nullable|string|max:50',
            'semester' => 'nullable|in:Ganjil,Genap',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        foreach (['school_name', 'address', 'npsn', 'tahun_ajaran', 'semester'] as $field) {
            Setting::set($field, $request->input($field));
        }

        if ($request->hasFile('logo')) {
            Setting::set('logo', $request->file('logo')->store('logos', 'public'));
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function backup()
    {
        try {
            $filename = 'backup-'.now()->format('Ymd-His').'.json';
            Storage::disk('local')->put('backups/'.$filename, app(BackupService::class)->dump());

            return back()->with('success', 'Backup berhasil dibuat: '.$filename);
        } catch (\Throwable $e) {
            return back()->with('error', 'Backup gagal: '.$e->getMessage());
        }
    }

    public function download(string $backup)
    {
        abort_unless($this->validFilename($backup), 404);

        if (! Storage::disk('local')->exists('backups/'.$backup)) {
            abort(404);
        }

        return Storage::disk('local')->download('backups/'.$backup);
    }

    public function restore(Request $request, ?string $backup = null)
    {
        $filePath = null;

        if ($backup) {
            abort_unless($this->validFilename($backup), 422);
            $filePath = Storage::disk('local')->path('backups/'.$backup);

            if (! file_exists($filePath)) {
                return back()->with('error', 'File backup tidak ditemukan.');
            }
        } else {
            $request->validate(['file' => 'required|file|mimes:json,application/json|max:10240']);
            $filePath = $request->file('file')->getRealPath();
        }

        try {
            $data = json_decode((string) file_get_contents($filePath), true);

            if (! is_array($data) || ! isset($data['tables'])) {
                return back()->with('error', 'File backup tidak valid.');
            }

            app(BackupService::class)->restore($data['tables']);

            return back()->with('success', 'Data berhasil dipulihkan dari backup.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Restore gagal: '.$e->getMessage());
        }
    }

    public function destroy(string $backup)
    {
        abort_unless($this->validFilename($backup), 404);

        Storage::disk('local')->delete('backups/'.$backup);

        return back()->with('success', 'Backup berhasil dihapus.');
    }

    protected function validFilename(string $backup): bool
    {
        return (bool) preg_match('/^backup-\d{8}-\d{6}\.json$/', $backup);
    }

    protected function backupList(): array
    {
        return collect(Storage::disk('local')->files('backups'))
            ->filter(fn ($file) => $this->validFilename(basename($file)))
            ->map(fn ($file) => [
                'name' => basename($file),
                'size' => Storage::disk('local')->size($file),
                'created_at' => Storage::disk('local')->lastModified($file),
            ])
            ->sortByDesc('created_at')
            ->values()
            ->all();
    }
}
