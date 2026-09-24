<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Supervisi - {{ $periode ?? '' }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        h1 { font-size: 18px; margin-bottom: 0; }
        h2 { font-size: 14px; margin-top: 20px; }
        .meta { margin: 4px 0 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #444; padding: 4px 6px; text-align: left; }
        th { background: #eee; }
        .stats td:first-child { font-weight: bold; width: 55%; }
    </style>
</head>
<body>
    <h1>Laporan Hasil Supervisi Akademik</h1>
    <div class="meta">
        <div>Sekolah: {{ $sekolah ?? '-' }}</div>
        <div>Periode: {{ $periode ?? '-' }} &nbsp;|&nbsp; Tahun: {{ $sstahun ?? '-' }}</div>
    </div>

    <h2>Statistik</h2>
    <table class="stats">
        @foreach(($statistik ?? []) as $key => $value)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
            </tr>
        @endforeach
    </table>

    <h2>Rekap Per Guru</h2>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIP</th>
                <th>Mapel</th>
                <th>Jml Observasi</th>
                <th>Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            @forelse(($guru_recap ?? []) as $g)
                <tr>
                    <td>{{ $g['name'] ?? '-' }}</td>
                    <td>{{ $g['nip'] ?? '-' }}</td>
                    <td>{{ $g['mata_pelajaran'] ?? '-' }}</td>
                    <td>{{ $g['total_observasi'] ?? 0 }}</td>
                    <td>{{ $g['rata_rata_nilai'] ?? 0 }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Detail Observasi</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Guru</th>
                <th>Supervisor</th>
                <th>Mapel</th>
                <th>Kelas</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse(($detail_observasi ?? []) as $d)
                <tr>
                    <td>{{ $d['tanggal'] ?? '-' }}</td>
                    <td>{{ $d['guru'] ?? '-' }}</td>
                    <td>{{ $d['supervisor'] ?? '-' }}</td>
                    <td>{{ $d['mapel'] ?? '-' }}</td>
                    <td>{{ $d['kelas'] ?? '-' }}</td>
                    <td>{{ $d['total'] ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
