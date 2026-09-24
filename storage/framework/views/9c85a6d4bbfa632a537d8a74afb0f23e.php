<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 no-print">
        <div class="px-5 py-3 border-b border-slate-100 flex flex-wrap items-center gap-2">
            <span class="text-sm font-semibold text-slate-700 mr-auto">Isian Pra Observasi</span>
            <form wire:submit="importExcel" class="flex items-center gap-2">
                <input type="file" wire:model="importFile" accept=".xlsx,.xls,.csv" class="text-xs max-w-[180px]">
                <button class="text-xs px-3 py-1.5 rounded-lg bg-sky-600 text-white hover:bg-sky-700">⬆ Impor</button>
            </form>
            <button wire:click="exportTemplate" class="text-xs px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200">⬇ Template</button>
            <button wire:click="exportExcel" class="text-xs px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">⬇ Export Excel</button>
            <button onclick="window.print()" class="text-xs px-3 py-1.5 rounded-lg bg-slate-800 text-white hover:bg-slate-900">🖨 Cetak Resmi</button>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['importFile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-600 px-5 pt-2"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div wire:loading wire:target="importFile" class="text-xs text-indigo-600 px-5 pt-1">Mengunggah…</div>
        <form wire:submit="save">
            <div class="px-5 py-4 grid grid-cols-1 md:grid-cols-4 gap-3 border-b border-slate-100">
                <div>
                    <label class="text-sm font-medium">Guru</label>
                    <select wire:model="teacher_id" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-2.5 py-1.5 border">
                        <option value="">-- Pilih --</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($t->id); ?>"><?php echo e($t->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium">Mapel</label>
                    <select wire:model="subject_id" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-2.5 py-1.5 border">
                        <option value="">-- Pilih --</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($s->id); ?>"><?php echo e($s->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium">Kelas</label>
                    <input wire:model="class_name" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-2.5 py-1.5 border">
                </div>
                <div>
                    <label class="text-sm font-medium">Tanggal</label>
                    <input type="date" wire:model="observation_date" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-2.5 py-1.5 border">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[1200px]">
                    <thead>
                        <tr class="text-left text-xs uppercase text-slate-400 border-b border-slate-100">
                            <th class="px-4 py-2.5 w-10">No.</th>
                            <th class="px-4 py-2.5 w-44">Komponen Telaah</th>
                            <th class="px-4 py-2.5">Aspek/Indikator RPP-RPM</th>
                            <th class="px-4 py-2.5 w-44">Keterkaitan PM / Literasi-Numerasi</th>
                            <th class="px-4 py-2.5 w-24">Skor (1-4)</th>
                            <th class="px-4 py-2.5 w-40">Bukti pada RPP/RPM</th>
                            <th class="px-4 py-2.5 w-40">Catatan/Temuan Pra Observasi</th>
                            <th class="px-4 py-2.5 w-40">Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-4 py-2 text-slate-400"><?php echo e($i + 1); ?></td>
                                <td class="px-4 py-2 font-medium text-slate-700 text-xs"><?php echo e($it['komponen']); ?></td>
                                <td class="px-4 py-2 text-slate-600"><?php echo e($it['indikator']); ?></td>
                                <td class="px-4 py-2 text-slate-600 text-xs"><?php echo e($it['keterkaitan']); ?></td>
                                <td class="px-4 py-2">
                                    <select wire:model="items.<?php echo e($i); ?>.skor" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border">
                                        <option value="">-</option>
                                        <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>
                                    </select>
                                </td>
                                <td class="px-4 py-2"><input wire:model="items.<?php echo e($i); ?>.bukti" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border"></td>
                                <td class="px-4 py-2"><input wire:model="items.<?php echo e($i); ?>.catatan" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border"></td>
                                <td class="px-4 py-2"><input wire:model="items.<?php echo e($i); ?>.rekomendasi" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border"></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 flex gap-2">
                <button class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Simpan</button>
                <a href="<?php echo e(route('pre-observations.index')); ?>" class="px-4 py-2 rounded-lg bg-slate-100 text-sm text-slate-600 hover:bg-slate-200">Batal</a>
            </div>
        </form>
    </div>

    
    <?php
        $printTeacher = $teachers->firstWhere('id', (int) $teacher_id);
        $printSubject = $subjects->firstWhere('id', (int) $subject_id);
        $printTotal = 0; $printCount = 0;
        foreach ($items as $pit) { $printTotal += max(0, min(4, (int) ($pit['skor'] ?? 0))); $printCount++; }
        $printMax = $printCount * 4;
        $printScore = $printMax > 0 ? round(($printTotal / $printMax) * 100, 2) : 0;
        $printDate = $observation_date ? \Carbon\Carbon::parse($observation_date)->translatedFormat('d F Y') : '-';
    ?>
    <div id="print-area">
        <div style="text-align:center; margin-bottom:4px;">
            <div style="font-size:18px; font-weight:bold;"><?php echo e(strtoupper(\App\Models\Setting::value('school_name') ?? 'SEKOLAH')); ?></div>
            <div style="font-size:11px;"><?php echo e(\App\Models\Setting::value('address') ?? ''); ?><?php echo e(\App\Models\Setting::value('npsn') ? ' · NPSN: '.\App\Models\Setting::value('npsn') : ''); ?></div>
        </div>
        <hr style="border:none; border-top:3px double #000; margin:6px 0 12px;">
        <div style="text-align:center; margin-bottom:10px;">
            <div style="font-size:14px; font-weight:bold; text-decoration:underline;">INSTRUMEN PRA OBSERVASI / TELAAH RPP-RPM</div>
            <div style="font-size:11px;">Pembelajaran Mendalam Terintegrasi Penguatan Literasi dan Numerasi</div>
        </div>
        <table style="font-size:11px; margin-bottom:10px;">
            <tr><td style="width:130px;">Nama Guru</td><td style="width:10px;">:</td><td><strong><?php echo e($printTeacher->name ?? '-'); ?></strong><?php echo e($printTeacher?->nip ? ' · NIP '.$printTeacher->nip : ''); ?></td></tr>
            <tr><td>Mata Pelajaran</td><td>:</td><td><?php echo e($printSubject->name ?? '-'); ?></td></tr>
            <tr><td>Kelas</td><td>:</td><td><?php echo e($class_name ?: '-'); ?></td></tr>
            <tr><td>Tanggal</td><td>:</td><td><?php echo e($printDate); ?></td></tr>
            <tr><td>Supervisor</td><td>:</td><td><?php echo e(auth()->user()->name); ?></td></tr>
        </table>
        <table border="1" cellspacing="0" cellpadding="5" style="width:100%; font-size:10.5px; border-collapse:collapse;">
            <thead>
                <tr style="background:#eee;">
                    <th style="width:28px;">No.</th>
                    <th>Komponen Telaah</th>
                    <th>Aspek/Indikator RPP-RPM</th>
                    <th>Keterkaitan PM / Literasi-Numerasi</th>
                    <th style="width:45px;">Skor (1-4)</th>
                    <th>Bukti pada RPP/RPM</th>
                    <th>Catatan/Temuan Pra Observasi</th>
                    <th>Rekomendasi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td align="center"><?php echo e($i + 1); ?></td>
                        <td><strong><?php echo e($it['komponen']); ?></strong></td>
                        <td><?php echo e($it['indikator']); ?></td>
                        <td><?php echo e($it['keterkaitan']); ?></td>
                        <td align="center"><strong><?php echo e($it['skor'] ?: '-'); ?></strong></td>
                        <td><?php echo e($it['bukti'] ?? ''); ?></td>
                        <td><?php echo e($it['catatan'] ?? ''); ?></td>
                        <td><?php echo e($it['rekomendasi'] ?? ''); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
        <div style="font-size:11px; margin-top:8px;">Total Skor: <strong><?php echo e($printTotal); ?>/<?php echo e($printMax); ?></strong> &nbsp;·&nbsp; Nilai: <strong><?php echo e($printScore); ?></strong></div>
        <?php $printKepsek = \App\Models\User::kepalaSekolah()->first(); ?>
        <table style="width:100%; font-size:11px; margin-top:24px; text-align:center;">
            <tr>
                <td style="width:50%;">Mengetahui,<br>Kepala Sekolah<br><br><br><br><br><strong><u><?php echo e($printKepsek->name ?? '........................'); ?></u></strong><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($printKepsek?->nip) && $printKepsek->nip !== '-'): ?><br>NIP <?php echo e($printKepsek->nip); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></td>
                <td style="width:50%;"><?php echo e($printDate); ?><br>Observer/Penelaah<br><br><br><br><br><strong><u><?php echo e(auth()->user()->name); ?></u></strong></td>
            </tr>
        </table>
    </div>
</div>
<?php /**PATH D:\project\supervisi_guru laravel\resources\views\livewire\pre-observations\form.blade.php ENDPATH**/ ?>