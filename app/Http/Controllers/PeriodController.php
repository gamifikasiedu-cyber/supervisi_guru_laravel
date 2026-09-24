<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|regex:/^\d{4}\/\d{4}$/',
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        [$tahunAwal, $tahunAkhir] = explode('/', $request->tahun_ajaran);

        if ((int) $tahunAkhir !== (int) $tahunAwal + 1) {
            return back()->with('error', 'Tahun ajaran tidak valid. Contoh: 2026/2027.');
        }

        $startDate = $request->semester === 'Ganjil'
            ? $tahunAwal.'-07-01'
            : $tahunAkhir.'-01-01';
        $endDate = $request->semester === 'Ganjil'
            ? $tahunAwal.'-12-31'
            : $tahunAkhir.'-06-30';

        Period::create([
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_active' => true,
        ]);

        return back()->with('success', 'Periode berhasil ditambahkan.');
    }

    public function destroy(Period $period)
    {
        $period->delete();

        if (session('active_period_id') === $period->id) {
            session()->forget('active_period_id');
        }

        return back()->with('success', 'Periode berhasil dihapus.');
    }
}
