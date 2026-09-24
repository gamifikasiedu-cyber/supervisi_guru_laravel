<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class PreObservationImport implements ToArray
{
    /**
     * Struktur pra observasi yang berhasil diparsing (tanpa skor).
     * Skor diisi langsung di tabel halaman setelah impor.
     *
     * @var array<int, array{komponen: string, indikator: string, keterkaitan: string}>
     */
    public array $items = [];

    /**
     * Pesan kesalahan per baris (format: "Baris X: ...").
     *
     * @var array<int, string>
     */
    public array $rowErrors = [];

    private bool $processed = false;

    /**
     * Struktur kolom (berbasis posisi):
     * 0 = Komponen Telaah, 1 = Aspek/Indikator RPP-RPM, 2 = Keterkaitan PM / Literasi-Numerasi.
     */
    public function array(array $rows)
    {
        // Hanya proses sheet pertama.
        if ($this->processed) {
            return;
        }
        $this->processed = true;

        $lastKomponen = '-';

        foreach ($rows as $index => $row) {
            // Baris pertama adalah header.
            if ($index === 0) {
                continue;
            }

            $line = $index + 1;
            $row = array_values((array) $row);

            $komponen = trim((string) ($row[0] ?? ''));
            $indikator = trim((string) ($row[1] ?? ''));
            $keterkaitan = trim((string) ($row[2] ?? ''));

            if ($komponen !== '') {
                $lastKomponen = $komponen;
            }

            // Lewati baris yang sepenuhnya kosong.
            if ($komponen === '' && $indikator === '' && $keterkaitan === '') {
                continue;
            }

            if ($indikator === '') {
                $this->rowErrors[] = "Baris {$line}: Aspek/Indikator wajib diisi.";

                continue;
            }

            $this->items[] = [
                'komponen' => $komponen !== '' ? $komponen : $lastKomponen,
                'indikator' => $indikator,
                'keterkaitan' => $keterkaitan,
            ];
        }
    }
}
