<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class InstrumentImport implements ToArray
{
    /**
     * Struktur instrumen yang berhasil diparsing (tanpa skor).
     * Skor diisi langsung di tabel halaman setelah impor.
     *
     * @var array<int, array{tahap: string, dimensi: string, indikator: string}>
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
     * 0 = Tahap/Aspek, 1 = Dimensi, 2 = Indikator Pemantauan/Supervisi.
     */
    public function array(array $rows)
    {
        // Hanya proses sheet pertama.
        if ($this->processed) {
            return;
        }
        $this->processed = true;

        $lastTahap = '-';

        foreach ($rows as $index => $row) {
            // Baris pertama adalah header.
            if ($index === 0) {
                continue;
            }

            $line = $index + 1;
            $row = array_values((array) $row);

            $tahap = trim((string) ($row[0] ?? ''));
            $dimensi = trim((string) ($row[1] ?? ''));
            $indikator = trim((string) ($row[2] ?? ''));

            if ($tahap !== '') {
                $lastTahap = $tahap;
            }

            // Lewati baris yang sepenuhnya kosong.
            if ($tahap === '' && $dimensi === '' && $indikator === '') {
                continue;
            }

            if ($indikator === '') {
                $this->rowErrors[] = "Baris {$line}: indikator wajib diisi.";

                continue;
            }

            $this->items[] = [
                'tahap' => $tahap !== '' ? $tahap : $lastTahap,
                'dimensi' => $dimensi,
                'indikator' => $indikator,
            ];
        }
    }
}
