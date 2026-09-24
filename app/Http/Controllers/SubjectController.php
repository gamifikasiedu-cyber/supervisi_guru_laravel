<?php

namespace App\Http\Controllers;

use App\Exports\SubjectExport;
use App\Imports\SubjectImport;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class SubjectController extends Controller
{
    public function index()
    {
        return Inertia::render('Subjects/Index', [
            'subjects' => Subject::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Subjects/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);

        Subject::create($request->all());

        return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Subject $subject)
    {
        return Inertia::render('Subjects/Edit', [
            'subject' => $subject,
        ]);
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);

        $subject->update($request->all());

        return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }

    // Mengunduh data seluruh mata pelajaran ke file Excel
    public function export()
    {
        return Excel::download(new SubjectExport, 'data-mata-pelajaran-'.now()->format('Ymd-His').'.xlsx');
    }

    // Mengimpor data mata pelajaran dari file Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $extension = strtolower($request->file('file')->getClientOriginalExtension());
        if (! in_array($extension, ['xlsx', 'xls', 'csv'], true)) {
            return redirect()->route('subjects.index')->with('error', 'Format file tidak valid. Gunakan file .xlsx, .xls, atau .csv.');
        }

        try {
            Excel::import(new SubjectImport, $request->file('file'));

            return redirect()->route('subjects.index')->with('success', 'Data mata pelajaran berhasil diimpor.');
        } catch (\Throwable $e) {
            return redirect()->route('subjects.index')->with('error', 'Gagal mengimpor data mata pelajaran: '.$e->getMessage());
        }
    }

    // Menghapus semua mata pelajaran
    public function deleteAll()
    {
        Subject::query()->delete();

        return redirect()->route('subjects.index')->with('success', 'Semua mata pelajaran berhasil dihapus.');
    }
}
