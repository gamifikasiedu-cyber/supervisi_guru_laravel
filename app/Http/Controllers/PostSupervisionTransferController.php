<?php

namespace App\Http\Controllers;

use App\Imports\PostSupervisionImport;
use App\Models\Setting;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PostSupervisionTransferController extends Controller
{
    public function import(string $bagian, Request $request)
    {
        $cfg = PostSupervisionController::config($bagian);
        abort_if(! $cfg, 404);

        $request->validate(
            ['file' => 'required|file|max:10240'],
            [
                'file.required' => 'Pilih dulu file Excel yang akan diimpor.',
                'file.file' => 'File tidak valid.',
                'file.max' => 'Ukuran file maksimal 10MB.',
            ]
        );

        $ext = strtolower($request->file('file')->getClientOriginalExtension());
        if (! in_array($ext, ['xlsx', 'xls', 'csv'], true)) {
            return back()->withErrors(['importFile' => 'Format file tidak valid. Gunakan .xlsx, .xls, atau .csv.']);
        }

        $import = new PostSupervisionImport($cfg);
        Excel::import($import, $request->file('file'));

        if (! empty($import->rowErrors)) {
            return back()->withErrors(['importFile' => implode(' ', array_slice($import->rowErrors, 0, 5))]);
        }

        if (empty($import->items)) {
            return back()->withErrors(['importFile' => 'File tidak berisi baris yang valid.']);
        }

        if (count($import->items) > 200) {
            return back()->withErrors(['importFile' => 'Maksimal 200 baris.']);
        }

        Setting::set('pasca_supervisi_default_'.$bagian, json_encode($import->items, JSON_UNESCAPED_UNICODE));

        if (array_key_exists($bagian, PostSupervisionController::OBS_DEFAULT_KEYS)) {
            Setting::set(PostSupervisionController::OBS_DEFAULT_KEYS[$bagian], json_encode($import->items, JSON_UNESCAPED_UNICODE));
        }

        session()->put('pasca_import_'.$bagian, $import->items);

        return redirect()->route('post-supervisions.create', ['bagian' => $bagian])
            ->with('success', 'Berhasil memuat '.count($import->items).' baris ke tabel dan menjadikannya struktur bawaan — lengkapi lalu Simpan.');
    }
}
