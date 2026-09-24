<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 no-print">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <div>
                <h3 class="font-semibold text-slate-800"><?php echo e($cfg['title']); ?></h3>
                <p class="text-sm text-slate-500"><?php echo e($records->count()); ?> record · Periode berjalan</p>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="text-sm px-4 py-2 rounded-lg bg-slate-800 text-white hover:bg-slate-900">🖨 Cetak</button>
                <a href="<?php echo e(route('post-supervisions.index')); ?>" class="text-sm px-4 py-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200">← Kembali</a>
            </div>
        </div>
    </div>

    <div id="print-area">
        <div style="text-align:center; margin-bottom:4px;">
            <div style="font-size:18px; font-weight:bold;"><?php echo e(strtoupper(\App\Models\Setting::value('school_name') ?? 'SEKOLAH')); ?></div>
            <div style="font-size:11px;"><?php echo e(\App\Models\Setting::value('address') ?? ''); ?><?php echo e(\App\Models\Setting::value('npsn') ? ' · NPSN: '.\App\Models\Setting::value('npsn') : ''); ?></div>
        </div>
        <hr style="border:none; border-top:3px double #000; margin:6px 0 12px;">
        <div style="text-align:center; margin-bottom:10px;">
            <div style="font-size:14px; font-weight:bold; text-decoration:underline;"><?php echo e(strtoupper($cfg['title'])); ?></div>
            <div style="font-size:11px;"><?php echo e($cfg['subtitle'] ?? ''); ?></div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="font-size:11px; margin:10px 0 4px;">
                <strong><?php echo e($r->teacher->name ?? '-'); ?></strong>
                · <?php echo e($r->subject->name ?? '-'); ?> · <?php echo e($r->class_name ?? '-'); ?>

                · <?php echo e($r->observation_date ? \Carbon\Carbon::parse($r->observation_date)->translatedFormat('d F Y') : '-'); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($r->score !== null): ?> · Nilai: <strong><?php echo e($r->score); ?></strong><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <table border="1" cellspacing="0" cellpadding="5" style="width:100%; font-size:10.5px; border-collapse:collapse;">
                <thead>
                    <tr style="background:#eee;">
                        <th style="width:28px;">No.</th>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $cfg['fields']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><th><?php echo e($f['label']); ?></th><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ($r->items ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td align="center"><?php echo e($i + 1); ?></td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $cfg['fields']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><td><?php echo e($it[$f['key']] ?? ''); ?></td><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p style="font-size:11px;">Belum ada data pada bagian ini.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <table style="width:100%; font-size:11px; margin-top:24px; text-align:center;">
            <tr>
                <td style="width:50%;">Mengetahui,<br>Kepala Sekolah<br><br><br><br><br><strong><u><?php echo e($kepsek->name ?? '........................'); ?></u></strong><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($kepsek?->nip) && $kepsek->nip !== '-'): ?><br>NIP <?php echo e($kepsek->nip); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></td>
                <td style="width:50%;"><?php echo e(now()->translatedFormat('d F Y')); ?><br>Supervisor<br><br><br><br><br><strong><u><?php echo e(auth()->user()->name); ?></u></strong></td>
            </tr>
        </table>
    </div>
</div>
<?php /**PATH D:\project\supervisi_guru laravel\resources\views\livewire\post-supervisions\cetak.blade.php ENDPATH**/ ?>