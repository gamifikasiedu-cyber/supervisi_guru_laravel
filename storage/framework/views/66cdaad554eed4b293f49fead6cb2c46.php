<?php
    $pastels = [
        'blue' => 'bg-blue-50 text-blue-500',
        'amber' => 'bg-amber-50 text-amber-500',
        'rose' => 'bg-rose-50 text-rose-500',
        'violet' => 'bg-violet-50 text-violet-500',
    ];
    $statusBadge = [
        'Scheduled' => 'bg-sky-100 text-sky-600',
        'Completed' => 'bg-emerald-100 text-emerald-600',
        'Cancelled' => 'bg-slate-100 text-slate-500',
    ];

    // ---- Area chart (Reports) ----
    $W = 620; $H = 215; $P = 32;
    $vals = array_column($chart, 'value');
    $max = max(max($vals), 1);
    $n = count($chart);
    $step = ($W - 2 * $P) / max($n - 1, 1);
    $pts = [];
    foreach ($chart as $i => $c) {
        $pts[] = [$P + $i * $step, $H - $P - (($c['value'] / $max) * ($H - 2 * $P))];
    }
    $d = 'M'.$pts[0][0].','.$pts[0][1];
    for ($i = 0; $i < $n - 1; $i++) {
        $p0 = $pts[max($i - 1, 0)]; $p1 = $pts[$i]; $p2 = $pts[$i + 1]; $p3 = $pts[min($i + 2, $n - 1)];
        $d .= ' C'.($p1[0] + ($p2[0] - $p0[0]) / 6).','.($p1[1] + ($p2[1] - $p0[1]) / 6)
            .' '.($p2[0] - ($p3[0] - $p1[0]) / 6).','.($p2[1] - ($p3[1] - $p1[1]) / 6)
            .' '.$p2[0].','.$p2[1];
    }
    $area = $d.' L'.$pts[$n - 1][0].','.($H - $P).' L'.$pts[0][0].','.($H - $P).' Z';
    $mi = array_search(max($vals), $vals);

    // ---- Donut (Analytics) ----
    $R = 62; $C = 2 * pi() * $R;
    $dTotal = max(array_sum(array_column(array_slice($donut, 0, 3), 'value')), 1);
    $offset = 0;
?>

<div>
    <!-- Stat cards -->
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 md:gap-5">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value, $icon, $theme]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-2xl shadow-sm p-3.5 sm:p-5 flex items-center gap-3 sm:gap-4 min-w-0">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center text-lg sm:text-xl shrink-0 <?php echo e($pastels[$theme] ?? $pastels['blue']); ?>"><?php echo e($icon); ?></div>
                <div class="min-w-0">
                    <div class="text-lg sm:text-[22px] sm:leading-7 font-extrabold truncate"><?php echo e($value); ?><span class="text-indigo-500">+</span></div>
                    <div class="text-[11px] sm:text-[13px] text-slate-400 truncate"><?php echo e($label); ?></div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Reports + Analytics -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 md:gap-5 mt-4 md:mt-5">
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm p-5">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-bold">Reports</h3>
                <span class="text-slate-300 tracking-widest text-sm">•••</span>
            </div>
            <svg viewBox="0 0 <?php echo e($W); ?> <?php echo e($H); ?>" class="w-full" style="height:230px">
                <defs>
                    <linearGradient id="lineGrad" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0" stop-color="#4f7cff"/><stop offset="1" stop-color="#a855f7"/>
                    </linearGradient>
                    <linearGradient id="fillGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0" stop-color="#818cf8" stop-opacity=".25"/><stop offset="1" stop-color="#818cf8" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [0, 0.5, 1]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $gy = $H - $P - ($g * ($H - 2 * $P)); ?>
                    <line x1="<?php echo e($P); ?>" y1="<?php echo e($gy); ?>" x2="<?php echo e($W - 8); ?>" y2="<?php echo e($gy); ?>" stroke="#eef1f6" stroke-width="1"/>
                    <text x="4" y="<?php echo e($gy + 4); ?>" font-size="10" fill="#b6bcc9"><?php echo e(round($max * $g)); ?></text>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <path d="<?php echo e($area); ?>" fill="url(#fillGrad)"/>
                <path d="<?php echo e($d); ?>" fill="none" stroke="url(#lineGrad)" stroke-width="2.5" stroke-linecap="round"/>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $pt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <circle cx="<?php echo e($pt[0]); ?>" cy="<?php echo e($pt[1]); ?>" r="4" fill="#fff" stroke="#a855f7" stroke-width="2"/>
                    <text x="<?php echo e($pt[0]); ?>" y="<?php echo e($H - 8); ?>" font-size="10" fill="#b6bcc9" text-anchor="middle"><?php echo e($chart[$i]['label']); ?></text>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php [$mx, $my] = $pts[$mi]; ?>
                <g>
                    <rect x="<?php echo e($mx - 34); ?>" y="<?php echo e(max($my - 52, 2)); ?>" width="68" height="38" rx="8" fill="#1e1b3a"/>
                    <text x="<?php echo e($mx); ?>" y="<?php echo e(max($my - 36, 18)); ?>" font-size="9" fill="#a5b4fc" text-anchor="middle">Supervisi</text>
                    <text x="<?php echo e($mx); ?>" y="<?php echo e(max($my - 22, 32)); ?>" font-size="13" font-weight="700" fill="#fff" text-anchor="middle"><?php echo e($vals[$mi]); ?></text>
                </g>
            </svg>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="flex items-center justify-between mb-1">
                <h3 class="font-bold">Analytics</h3>
                <span class="text-slate-300 tracking-widest text-sm">•••</span>
            </div>
            <svg viewBox="0 0 160 170" class="w-full" style="height:210px">
                <circle cx="80" cy="75" r="<?php echo e($R); ?>" fill="none" stroke="#eef1f6" stroke-width="17"/>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = array_slice($donut, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $len = ($seg['value'] / $dTotal) * $C; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seg['value'] > 0): ?>
                        <circle cx="80" cy="75" r="<?php echo e($R); ?>" fill="none" stroke="<?php echo e($seg['color']); ?>"
                            stroke-width="17" stroke-linecap="round"
                            stroke-dasharray="<?php echo e($len); ?> <?php echo e($C - $len); ?>"
                            stroke-dashoffset="<?php echo e(-$offset); ?>" transform="rotate(-90 80 75)"/>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php $offset += $len; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <text x="80" y="72" font-size="22" font-weight="800" text-anchor="middle" fill="#1e293b"><?php echo e($donut['pct']); ?>%</text>
                <text x="80" y="90" font-size="11" text-anchor="middle" fill="#94a3b8">Disetujui</text>
            </svg>
            <div class="flex flex-wrap justify-center gap-x-4 gap-y-1.5 mt-1 text-xs text-slate-500">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = array_slice($donut, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full" style="background:<?php echo e($seg['color']); ?>"></span>
                        <?php echo e($seg['label']); ?> (<?php echo e($seg['value']); ?>)
                    </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent + Top -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 md:gap-5 mt-4 md:mt-5">
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold">Recent Supervisi</h3>
                <a href="<?php echo e(route('supervisions.index')); ?>" class="text-slate-300 tracking-widest text-sm hover:text-indigo-500">•••</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-[13px] min-w-[560px]">
                    <thead>
                        <tr class="text-left text-slate-400 text-xs">
                            <th class="py-2 pr-3 font-medium">No ▾</th>
                            <th class="py-2 pr-3 font-medium">Guru ▾</th>
                            <th class="py-2 pr-3 font-medium">Mapel</th>
                            <th class="py-2 pr-3 font-medium">Kelas</th>
                            <th class="py-2 pr-3 font-medium">Status</th>
                            <th class="py-2 font-medium text-right">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="py-2.5 pr-3 text-slate-500">#SPV-<?php echo e($s->id); ?></td>
                                <td class="py-2.5 pr-3 font-semibold"><?php echo e($s->teacher->name ?? '-'); ?></td>
                                <td class="py-2.5 pr-3 text-slate-500"><?php echo e($s->subject->name ?? '-'); ?></td>
                                <td class="py-2.5 pr-3"><span class="px-2.5 py-1 rounded-md bg-sky-50 text-sky-600 text-xs font-medium"><?php echo e($s->class_name ?? '-'); ?></span></td>
                                <td class="py-2.5 pr-3"><span class="px-2.5 py-1 rounded-md text-xs font-medium <?php echo e($statusBadge[$s->status] ?? 'bg-slate-100 text-slate-500'); ?>"><?php echo e($s->status); ?></span></td>
                                <td class="py-2.5 text-right font-semibold"><?php echo e($s->schedule_date?->format('d/m/Y')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="6" class="py-6 text-center text-slate-400">Belum ada jadwal supervisi.</td></tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold">Guru Terbaik</h3>
                <span class="text-slate-300 tracking-widest text-sm">•••</span>
            </div>
            <div class="space-y-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $top; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-xl font-extrabold shrink-0"><?php echo e(strtoupper(substr($g['name'], 0, 1))); ?></div>
                        <div class="min-w-0">
                            <p class="font-semibold text-sm truncate"><?php echo e($g['name']); ?></p>
                            <p class="text-amber-400 text-xs tracking-tight">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($s = 1; $s <= 5; $s++): ?><span class="<?php echo e($s <= round($g['avg'] / 20) ? '' : 'text-slate-200'); ?>">★</span><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                            <p class="font-extrabold text-sm mt-0.5"><?php echo e($g['avg']); ?> <span class="font-normal text-slate-400 text-xs">· <?php echo e($g['count']); ?> observasi</span></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-slate-400 text-sm">Belum ada nilai.</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\project\supervisi_guru laravel\resources\views/livewire/dashboard.blade.php ENDPATH**/ ?>