<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class KonferensiImport implements ToArray
{
    /**
     * Baris yang berhasil diparsing (tanpa identitas).
     *
     * @var array<int, array{pertanyaan: string, catatan_guru: string, catatan_supervisor: string, kesepakatan: string, tindak_lanjut: string}>
     */
    public array $items = [];

    /**
     * Pesan kesalahan per baris.
     *
     * @var array<int, string>
     */
    public array $rowErrors = [];

    private bool $processed = false;

    /**
     * Struktur kolom (berbasis posisi):
     * 0 = Pertanyaan/Fokus Konferensi, 1 = Catatan Guru, 2 = Catatan Supervisor,
     * 3 = Kesepakatan, 4 = Tindak Lanjut.
     */
    public function array(array $rows)
    {
        // Hanya proses sheet pertama.
        if ($this->processed) {
            return;
        }
        $this->processed = true;

        foreach ($rows as $index => $row) {
            // Baris pertama adalah header.
            if ($index === 0) {
                continue;
            }

            $line = $index + 1;
            $row = array_values((array) $row);

            $item = [
                'pertanyaan' => trim((string) ($row[0] ?? '')),
                'catatan_guru' => trim((string) ($row[1] ?? '')),
                'catatan_supervisor' => trim((string) ($row[2] ?? '')),
                'kesepakatan' => trim((string) ($row[3] ?? '')),
                'tindak_lanjut' => trim((string) ($row[4] ?? '')),
            ];

            // Lewati baris yang sepenuhnya kosong.
            $allEmpty = true;
            foreach ($item as $v) {
                if ($v !== '') {
                    $allEmpty = false;
                    break;
                }
            }
            if ($allEmpty) {
                continue;
            }

            if ($item['pertanyaan'] === '') {
                $this->rowErrors[] = "Baris {$line}: Pertanyaan/Fokus wajib diisi.";

                continue;
            }

            $this->items[] = $item;
        }
    }
}
