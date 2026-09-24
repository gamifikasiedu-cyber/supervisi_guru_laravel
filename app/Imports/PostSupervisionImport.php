<?php

namespace App\Imports;

use App\Http\Controllers\PostSupervisionController;
use Maatwebsite\Excel\Concerns\ToArray;

class PostSupervisionImport implements ToArray
{
    /**
     * Baris yang berhasil diparsing (keys sesuai fields config).
     *
     * @var array<int, array<string, string>>
     */
    public array $items = [];

    /**
     * Pesan kesalahan per baris.
     *
     * @var array<int, string>
     */
    public array $rowErrors = [];

    private bool $processed = false;

    public function __construct(private array $cfg) {}

    public function array(array $rows)
    {
        if ($this->processed) {
            return;
        }
        $this->processed = true;

        $fields = PostSupervisionController::excelFields($this->cfg);
        $groupKey = $this->cfg['groupBy'] ?? null;
        $lastGroup = '-';

        // Kunci field pertama yang required bertipe text/textarea (untuk validasi baris).
        $firstRequired = null;
        foreach ($fields as $f) {
            if (! empty($f['required']) && in_array($f['type'], ['text', 'textarea'], true)) {
                $firstRequired = $f['key'];
                break;
            }
        }

        foreach ($rows as $index => $row) {
            // Baris pertama adalah header.
            if ($index === 0) {
                continue;
            }

            $line = $index + 1;
            $row = array_values((array) $row);

            $item = [];
            foreach ($fields as $col => $f) {
                $item[$f['key']] = trim((string) ($row[$col] ?? ''));
            }

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

            // Warisi nilai grup (mis. Tahap) bila kosong.
            if ($groupKey && ($item[$groupKey] ?? '') !== '') {
                $lastGroup = $item[$groupKey];
            } elseif ($groupKey) {
                $item[$groupKey] = $lastGroup;
            }

            if ($firstRequired && ($item[$firstRequired] ?? '') === '') {
                $this->rowErrors[] = "Baris {$line}: kolom wajib diisi masih kosong.";

                continue;
            }

            // Validasi skor bila ada.
            if (array_key_exists('skor', $item) && $item['skor'] !== '' && ! in_array($item['skor'], ['1', '2', '3', '4'], true)) {
                $this->rowErrors[] = "Baris {$line}: skor harus 1–4.";

                continue;
            }

            $this->items[] = $item;
        }
    }
}
