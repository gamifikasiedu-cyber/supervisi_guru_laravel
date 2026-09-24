<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 no-print">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <div>
                <h3 class="font-semibold text-slate-800">Cetak Resmi Konferensi</h3>
                <p class="text-sm text-slate-500"><?php echo e($konferensi->teacher->name ?? '-'); ?> · <?php echo e($konferensi->observation_date?->format('d/m/Y')); ?></p>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="text-sm px-4 py-2 rounded-lg bg-slate-800 text-white hover:bg-slate-900">🖨 Cetak</button>
                <a href="<?php echo e(route('konferensis.index')); ?>" class="text-sm px-4 py-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200">← Kembali</a>
            </div>
        </div>
    </div>

    <?php
        $printDate = $konferensi->observation_date ? \Carbon\Carbon::parse($konferensi->observation_date)->translatedFormat('d F Y') : '-';
        $printKepsek = \App\Models\User::kepalaSekolah()->first();
    ?>
    <div id="print-area">
        <div style="text-align:center; margin-bottom:4px;">
            <div style="font-size:18px; font-weight:bold;"><?php echo e(strtoupper(\App\Models\Setting::value('school_name') ?? 'SEKOLAH')); ?></div>
            <div style="font-size:11px;"><?php echo e(\App\Models\Setting::value('address') ?? ''); ?><?php echo e(\App\Models\Setting::value('npsn') ? ' · NPSN: '.\App\Models\Setting::value('npsn') : ''); ?></div>
        </div>
        <hr style="border:none; border-top:3px double #000; margin:6px 0 12px;">
        <div style="text-align:center; margin-bottom:10px;">
            <div style="font-size:14px; font-weight:bold; text-decoration:underline;">BERITA ACARA KONFERENSI-WAWANCARA PRA OBSERVASI</div>
            <div style="font-size:11px;">Supervisi Akademik Guru</div>
        </div>
        <table style="font-size:11px; margin-bottom:10px;">
            <tr><td style="width:130px;">Nama Guru</td><td style="width:10px;">:</td><td><strong><?php echo e($konferensi->teacher->name ?? '-'); ?></strong><?php echo e($konferensi->teacher?->nip ? ' · NIP '.$konferensi->teacher->nip : ''); ?></td></tr>
            <tr><td>Mata Pelajaran</td><td>:</td><td><?php echo e($konferensi->subject->name ?? '-'); ?></td></tr>
            <tr><td>Kelas</td><td>:</td><td><?php echo e($konferensi->class_name ?? '-'); ?></td></tr>
            <tr><td>Tanggal</td><td>:</td><td><?php echo e($printDate); ?></td></tr>
            <tr><td>Supervisor</td><td>:</td><td><?php echo e($konferensi->supervisor->name ?? auth()->user()->name); ?></td></tr>
        </table>
        <table border="1" cellspacing="0" cellpadding="5" style="width:100%; font-size:10.5px; border-collapse:collapse;">
            <thead>
                <tr style="background:#eee;">
                    <th style="width:28px;">No.</th>
                    <th>Pertanyaan / Fokus</th>
                    <th>Catatan Guru</th>
                    <th>Catatan Supervisor</th>
                    <th>Kesepakatan</th>
                    <th>Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ($konferensi->items ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td align="center"><?php echo e($i + 1); ?></td>
                        <td><?php echo e($it['pertanyaan'] ?? ''); ?></td>
                        <td><?php echo e($it['catatan_guru'] ?? ''); ?></td>
                        <td><?php echo e($it['catatan_supervisor'] ?? ''); ?></td>
                        <td><?php echo e($it['kesepakatan'] ?? ''); ?></td>
                        <td><?php echo e($it['tindak_lanjut'] ?? ''); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
        <table style="width:100%; font-size:11px; margin-top:24px; text-align:center;">
            <tr>
                <td style="width:50%;">Mengetahui,<br>Kepala Sekolah<br><br><br><br><br><strong><u><?php echo e($printKepsek->name ?? '........................'); ?></u></strong><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($printKepsek?->nip) && $printKepsek->nip !== '-'): ?><br>NIP <?php echo e($printKepsek->nip); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></td>
                <td style="width:50%;"><?php echo e($printDate); ?><br>Observer/Penelaah<br><br><br><br><br><strong><u><?php echo e($konferensi->supervisor->name ?? auth()->user()->name); ?></u></strong></td>
            </tr>
        </table>
    </div>
</div>
<?php /**PATH D:\project\supervisi_guru laravel\resources\views\livewire\konferensis\cetak.blade.php ENDPATH**/ ?>