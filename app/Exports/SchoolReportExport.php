<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SchoolReportExport implements FromArray, WithStyles, WithTitle, WithEvents, ShouldAutoSize
{
    public function __construct(
        private array $groups,
        private array $observations,
        private array $stats,
        private string $periode,
        private string $sekolah,
    ) {}

    public function title(): string
    {
        return 'Laporan Supervisi';
    }

    public function array(): array
    {
        $rows = [];

        $rows[] = ['LAPORAN HASIL SUPERVISI GURU'];
        $rows[] = ['Periode', $this->periode];
        $rows[] = [$this->sekolah];
        $rows[] = [];

        $rows[] = ['STATISTIK'];
        $rows[] = ['Total Guru', $this->stats['total_guru'] ?? 0];
        $rows[] = ['Total Supervisi', $this->stats['total_supervisi'] ?? 0];
        $rows[] = ['Total Observasi', $this->stats['total_observasi'] ?? 0];
        $rows[] = ['Observasi Disetujui', $this->stats['observasi_approved'] ?? 0];
        $rows[] = ['Observasi Menunggu', $this->stats['observasi_pending'] ?? 0];
        $rows[] = ['Rata-rata Sekolah', $this->stats['rata_rata_sekolah'] ?? 0];
        $rows[] = ['Total Dokumen', $this->stats['total_dokumen'] ?? 0];
        $rows[] = ['Dokumen Disetujui', $this->stats['dokumen_approved'] ?? 0];
        $rows[] = [];

        $rows[] = ['REKAP NILAI PER GURU'];
        $rows[] = [
            'No', 'Nama Guru', 'NIP', 'Mata Pelajaran', 'Jumlah Observasi',
            'Perencanaan', 'Penyampaian', 'Pengelolaan', 'Penilaian', 'Rata-rata',
        ];
        foreach ($this->groups as $i => $g) {
            $rows[] = [
                $i + 1,
                $g['name'],
                $g['nip'],
                $g['mata_pelajaran'],
                $g['total_observasi'],
                $g['rata_rata_planning'],
                $g['rata_rata_delivery'],
                $g['rata_rata_management'],
                $g['rata_rata_assessment'],
                $g['rata_rata_nilai'],
            ];
        }
        $rows[] = [];

        $rows[] = ['DETAIL HASIL OBSERVASI'];
        $rows[] = [
            'No', 'Tanggal', 'Guru', 'Supervisor', 'Mata Pelajaran', 'Kelas',
            'Perencanaan', 'Penyampaian', 'Pengelolaan', 'Penilaian', 'Total',
        ];
        foreach ($this->observations as $i => $o) {
            $rows[] = [
                $i + 1,
                $o['tanggal'],
                $o['guru'],
                $o['supervisor'],
                $o['mapel'],
                $o['kelas'],
                $o['perencanaan'],
                $o['penyampaian'],
                $o['pengelolaan'],
                $o['penilaian'],
                $o['total'],
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A2:A3')->getFont()->setItalic(true)->setSize(11);

                foreach (['A5', 'A10', 'A21'] as $cell) {
                    if ($sheet->getCell($cell)->getValue() !== null) {
                        $sheet->getStyle($cell)->getFont()->setBold(true)->setSize(12);
                    }
                }

                $sheet->mergeCells('A1:J1');

                $sheet->getColumnDimension('A')->setWidth(5);
            },
        ];
    }
}