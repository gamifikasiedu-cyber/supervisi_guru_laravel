<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Supervisi - <?php echo e($periode ?? ''); ?></title>
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
        <div>Sekolah: <?php echo e($sekolah ?? '-'); ?></div>
        <div>Periode: <?php echo e($periode ?? '-'); ?> &nbsp;|&nbsp; Tahun: <?php echo e($sstahun ?? '-'); ?></div>
    </div>

    <h2>Statistik</h2>
    <table class="stats">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ($statistik ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($key); ?></td>
                <td><?php echo e(is_array($value) ? json_encode($value) : $value); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = ($guru_recap ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($g['name'] ?? '-'); ?></td>
                    <td><?php echo e($g['nip'] ?? '-'); ?></td>
                    <td><?php echo e($g['mata_pelajaran'] ?? '-'); ?></td>
                    <td><?php echo e($g['total_observasi'] ?? 0); ?></td>
                    <td><?php echo e($g['rata_rata_nilai'] ?? 0); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5">Belum ada data.</td></tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = ($detail_observasi ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($d['tanggal'] ?? '-'); ?></td>
                    <td><?php echo e($d['guru'] ?? '-'); ?></td>
                    <td><?php echo e($d['supervisor'] ?? '-'); ?></td>
                    <td><?php echo e($d['mapel'] ?? '-'); ?></td>
                    <td><?php echo e($d['kelas'] ?? '-'); ?></td>
                    <td><?php echo e($d['total'] ?? '-'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6">Belum ada data.</td></tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH D:\project\supervisi_guru laravel\resources\views\reports\school-report.blade.php ENDPATH**/ ?>