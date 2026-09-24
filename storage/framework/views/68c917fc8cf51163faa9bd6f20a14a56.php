<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 no-print">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bagian !== 'rekap'): ?>
        <div class="px-5 py-3 border-b border-slate-100 flex flex-wrap items-center gap-2">
            <span class="text-sm font-semibold text-slate-700 mr-auto">Isian Pasca Supervisi</span>
            <form method="POST" action="<?php echo e(route('post-supervisions.import', $bagian)); ?>" enctype="multipart/form-data" class="flex items-center gap-2">
                <?php echo csrf_field(); ?>
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="text-xs max-w-[180px]">
                <button class="text-xs px-3 py-1.5 rounded-lg bg-sky-600 text-white hover:bg-sky-700">⬆ Impor</button>
            </form>
            <button wire:click="exportTemplate" class="text-xs px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200">⬇ Template</button>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bagian !== 'rekap' && ($is_custom_default ?? false)): ?>
                <button wire:click="resetDefault" onclick="return confirm('Kembalikan struktur ke standar?')" class="text-xs px-3 py-1.5 rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200">Reset Standar</button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <button wire:click="exportExcel" class="text-xs px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">⬇ Export Excel</button>
            <button onclick="window.print()" class="text-xs px-3 py-1.5 rounded-lg bg-slate-800 text-white hover:bg-slate-900">🖨 Cetak Resmi</button>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['importFile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-600 px-5 pt-2"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="px-5 pt-4">
                <label class="text-sm font-medium">Bagian</label>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($postSupervision): ?>
                    <div class="mt-1 inline-block text-sm px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 font-medium"><?php echo e($options[$bagian] ?? $bagian); ?></div>
                <?php else: ?>
                    <div class="flex flex-wrap gap-1.5 mt-1.5">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slug => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('post-supervisions.create', ['bagian' => $slug])); ?>"
                                class="text-xs px-3 py-1.5 rounded-lg font-medium transition <?php echo e($bagian === $slug ? 'bg-indigo-600 text-white shadow' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'); ?>"><?php echo e($label); ?></a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bagian === 'rekap' && $rekap): ?>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 md:p-5 no-print mt-4">
                <div class="flex justify-between items-center flex-wrap gap-2 mb-3">
                    <h3 class="font-semibold text-slate-800">Rekap Hasil Pasca Supervisi</h3>
                    <div class="flex items-center gap-2">
                        <?php $guruOnly = auth()->user()->hasRole('guru') && ! auth()->user()->hasRole('admin', 'supervisor', 'kepala_sekolah', 'pengawas'); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $guruOnly): ?>
                            <form method="GET" action="<?php echo e(route('post-supervisions.create')); ?>" class="inline">
                                <input type="hidden" name="bagian" value="rekap">
                                <select name="guru" onchange="this.form.submit()" class="text-sm rounded-lg border-slate-300 px-3 py-1.5 border">
                                    <option value="">Semua Guru</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($t->id); ?>" <?php if($rekapTeacher === (string) $t->id): echo 'selected'; endif; ?>><?php echo e($t->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </form>
                        <?php else: ?>
                            <span class="text-sm text-slate-500">Rekap saya</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <button type="button" onclick="window.print()" class="text-sm px-3 py-1.5 rounded-lg bg-slate-800 text-white hover:bg-slate-900">🖨 Cetak</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[900px]">
                        <thead>
                            <tr class="text-left text-xs uppercase text-slate-400 border-b border-slate-100">
                                <th class="px-4 py-2.5">Guru</th><th class="px-4 py-2.5">Pra-Obs</th><th class="px-4 py-2.5">Pemantauan</th>
                                <th class="px-4 py-2.5">Formatif</th><th class="px-4 py-2.5">Sumatif</th><th class="px-4 py-2.5">Obs 3M</th>
                                <th class="px-4 py-2.5">Obs BBM</th><th class="px-4 py-2.5">Total</th><th class="px-4 py-2.5">Kategori</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $rekap['summary']['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="px-4 py-2.5 font-medium text-slate-800"><?php echo e($r['name']); ?></td>
                                    <td class="px-4 py-2.5"><?php echo e($r['pra'] ?? '-'); ?></td>
                                    <td class="px-4 py-2.5"><?php echo e($r['ins'] ?? '-'); ?></td>
                                    <td class="px-4 py-2.5"><?php echo e($r['for'] ?? '-'); ?></td>
                                    <td class="px-4 py-2.5"><?php echo e($r['sum'] ?? '-'); ?></td>
                                    <td class="px-4 py-2.5"><?php echo e($r['m3'] ?? '-'); ?></td>
                                    <td class="px-4 py-2.5"><?php echo e($r['bbm'] ?? '-'); ?></td>
                                    <td class="px-4 py-2.5 font-semibold"><?php echo e($r['total'] ?? '-'); ?></td>
                                    <td class="px-4 py-2.5"><?php echo e($r['kategori']); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="9" class="px-4 py-4 text-slate-400">Belum ada data.</td></tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['pra_observasi' => 'Pra Observasi', 'instrumen' => 'Pemantauan', 'formatif' => 'Asesmen Formatif', 'sumatif' => 'Asesmen Sumatif']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <h4 class="font-semibold text-slate-700 text-sm mb-1.5">Riwayat <?php echo e($label); ?></h4>
                            <table class="w-full text-[13px]">
                                <thead>
                                    <tr class="text-left text-xs text-slate-400 border-b border-slate-100">
                                        <th class="py-1.5 pr-2">Tanggal</th><th class="py-1.5 pr-2">Guru</th><th class="py-1.5 pr-2">Mapel</th><th class="py-1.5 text-right">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $rekap['riwayat'][$key] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="py-1.5 pr-2 text-slate-500"><?php echo e($d['tanggal']); ?></td>
                                            <td class="py-1.5 pr-2"><?php echo e($d['guru']); ?></td>
                                            <td class="py-1.5 pr-2 text-slate-500"><?php echo e($d['mapel']); ?></td>
                                            <td class="py-1.5 text-right font-semibold"><?php echo e($d['nilai'] ?? '-'); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr><td colspan="4" class="py-2 text-slate-400">Belum ada data.</td></tr>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php else: ?>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['observation_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[900px]">
                    <thead>
                        <tr class="text-left text-xs uppercase text-slate-400 border-b border-slate-100">
                            <th class="px-4 py-2.5 w-10">No</th>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><th class="px-4 py-2.5"><?php echo e($f['label']); ?></th><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <th class="px-4 py-2.5 w-12"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-4 py-2 text-slate-400"><?php echo e($i + 1); ?></td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td class="px-4 py-2 min-w-[140px]">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($f['type'] === 'skor'): ?>
                                            <select wire:model="items.<?php echo e($i); ?>.<?php echo e($f['key']); ?>" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border">
                                                <option value="">-</option>
                                                <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>
                                            </select>
                                        <?php elseif($f['type'] === 'select'): ?>
                                            <select wire:model="items.<?php echo e($i); ?>.<?php echo e($f['key']); ?>" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border">
                                                <option value="">--</option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $f['options'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($o); ?>"><?php echo e($o); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </select>
                                        <?php elseif($f['type'] === 'textarea'): ?>
                                            <textarea wire:model="items.<?php echo e($i); ?>.<?php echo e($f['key']); ?>" rows="2" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border"></textarea>
                                        <?php else: ?>
                                            <input wire:model="items.<?php echo e($i); ?>.<?php echo e($f['key']); ?>" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['items.'.$i.'.'.$f['key']];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-600 mt-0.5"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <td class="px-4 py-2"><button type="button" wire:click="removeRow(<?php echo e($i); ?>)" class="text-rose-500">✕</button></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="mx-5 mb-1 px-4 py-2.5 rounded-lg bg-rose-50 border border-rose-200 text-xs text-rose-700">
                    <strong>Belum bisa disimpan, periksa isian:</strong>
                    <ul class="list-disc ml-4 mt-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($err); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="px-5 py-4 flex gap-2">
                <button type="button" wire:click="addRow" class="px-4 py-2 rounded-lg bg-slate-100 text-sm text-slate-600 hover:bg-slate-200">+ Baris</button>
                <button class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Simpan</button>
                <a href="<?php echo e(route('post-supervisions.index')); ?>" class="px-4 py-2 rounded-lg bg-slate-100 text-sm text-slate-600 hover:bg-slate-200">Batal</a>
            </div>
        </form>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $postSupervision && $bagian !== 'rekap'): ?>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mt-4 no-print">
        <div class="px-5 pt-4 pb-3 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-semibold text-slate-800">Riwayat <?php echo e($bagianLabel); ?> (10 terbaru)</h3>
            <a href="<?php echo e(route('post-supervisions.index')); ?>" class="text-sm text-indigo-600 hover:underline">Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[700px]">
                <thead>
                    <tr class="text-left text-xs uppercase text-slate-400 border-b border-slate-100">
                        <th class="px-5 py-2.5">Tanggal</th><th class="px-5 py-2.5">Guru</th>
                        <th class="px-5 py-2.5">Mapel</th><th class="px-5 py-2.5">Kelas</th>
                        <th class="px-5 py-2.5">Nilai</th><th class="px-5 py-2.5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-5 py-2.5 text-slate-500"><?php echo e($h->observation_date); ?></td>
                            <td class="px-5 py-2.5 font-medium text-slate-800"><?php echo e($h->teacher->name ?? '-'); ?></td>
                            <td class="px-5 py-2.5 text-slate-500"><?php echo e($h->subject->name ?? '-'); ?></td>
                            <td class="px-5 py-2.5 text-slate-500"><?php echo e($h->class_name ?? '-'); ?></td>
                            <td class="px-5 py-2.5 font-semibold"><?php echo e($h->score ?? '-'); ?></td>
                            <td class="px-5 py-2.5 whitespace-nowrap">
                                <a href="<?php echo e(route('post-supervisions.edit', $h)); ?>" class="text-indigo-600 hover:underline mr-3">Edit</a>
                                <form method="POST" action="<?php echo e(route('records.destroy', ['type' => 'post-supervision', 'id' => $h->id])); ?>" class="inline" onsubmit="return confirm('Hapus data ini?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="text-rose-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="px-5 py-4 text-slate-400 text-sm">Belum ada data <?php echo e($bagianLabel); ?>. Isi form di atas lalu Simpan.</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bagian !== 'rekap'): ?>
    <?php
        $printCfg = \App\Http\Controllers\PostSupervisionController::config($bagian) ?? ['title' => 'Pasca Supervisi', 'fields' => [], 'hasSkor' => false];
        $printTeacher = $teachers->firstWhere('id', (int) $teacher_id);
        $printSubject = $subjects->firstWhere('id', (int) $subject_id);
        $printTotal = 0; $printCount = 0;
        if (! empty($printCfg['hasSkor'])) { foreach ($items as $pit) { $printTotal += max(0, min(4, (int) ($pit['skor'] ?? 0))); $printCount++; } }
        $printMax = $printCount * 4;
        $printScore = $printMax > 0 ? round(($printTotal / $printMax) * 100, 2) : null;
        $printDate = $observation_date ? \Carbon\Carbon::parse($observation_date)->translatedFormat('d F Y') : '-';
        $printKepsek = \App\Models\User::kepalaSekolah()->first();
    ?>
    <div id="print-area">
        <div style="text-align:center; margin-bottom:4px;">
            <div style="font-size:18px; font-weight:bold;"><?php echo e(strtoupper(\App\Models\Setting::value('school_name') ?? 'SEKOLAH')); ?></div>
            <div style="font-size:11px;"><?php echo e(\App\Models\Setting::value('address') ?? ''); ?><?php echo e(\App\Models\Setting::value('npsn') ? ' · NPSN: '.\App\Models\Setting::value('npsn') : ''); ?></div>
        </div>
        <hr style="border:none; border-top:3px double #000; margin:6px 0 12px;">
        <div style="text-align:center; margin-bottom:10px;">
            <div style="font-size:14px; font-weight:bold; text-decoration:underline;"><?php echo e(strtoupper($printCfg['title'])); ?></div>
            <div style="font-size:11px;"><?php echo e($printCfg['subtitle'] ?? ''); ?></div>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $printCfg['fields']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><th><?php echo e($f['label']); ?></th><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td align="center"><?php echo e($i + 1); ?></td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $printCfg['fields']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><td><?php echo e($it[$f['key']] ?? ''); ?></td><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($printScore !== null): ?>
            <div style="font-size:11px; margin-top:8px;">Total Skor: <strong><?php echo e($printTotal); ?>/<?php echo e($printMax); ?></strong> &nbsp;·&nbsp; Nilai: <strong><?php echo e($printScore); ?></strong></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <table style="width:100%; font-size:11px; margin-top:24px; text-align:center;">
            <tr>
                <td style="width:50%;">Mengetahui,<br>Kepala Sekolah<br><br><br><br><br><strong><u><?php echo e($printKepsek->name ?? '........................'); ?></u></strong><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($printKepsek?->nip) && $printKepsek->nip !== '-'): ?><br>NIP <?php echo e($printKepsek->nip); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></td>
                <td style="width:50%;"><?php echo e($printDate); ?><br>Supervisor<br><br><br><br><br><strong><u><?php echo e(auth()->user()->name); ?></u></strong></td>
            </tr>
        </table>
    </div>
    <?php else: ?>
    <div id="print-area">
        <div style="text-align:center; margin-bottom:4px;">
            <div style="font-size:18px; font-weight:bold;"><?php echo e(strtoupper(\App\Models\Setting::value('school_name') ?? 'SEKOLAH')); ?></div>
            <div style="font-size:11px;"><?php echo e(\App\Models\Setting::value('address') ?? ''); ?></div>
        </div>
        <hr style="border:none; border-top:3px double #000; margin:6px 0 12px;">
        <div style="text-align:center; margin-bottom:10px;">
            <div style="font-size:14px; font-weight:bold; text-decoration:underline;">REKAP HASIL PASCA SUPERVISI</div>
            <div style="font-size:11px;"><?php echo e($rekapTeacher !== '' ? 'Guru: '.(optional($teachers->firstWhere('id', (int) $rekapTeacher))->name) : 'Semua Guru'); ?></div>
        </div>
        <table border="1" cellspacing="0" cellpadding="5" style="width:100%; font-size:10px; border-collapse:collapse;">
            <thead>
                <tr style="background:#eee;">
                    <th>Guru</th><th>Pra-Obs</th><th>Pemantauan</th><th>Formatif</th><th>Sumatif</th>
                    <th>Obs 3M</th><th>Obs BBM</th><th>Total</th><th>Kategori</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $rekap['summary']['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><strong><?php echo e($r['name']); ?></strong></td>
                        <td align="center"><?php echo e($r['pra'] ?? '-'); ?></td>
                        <td align="center"><?php echo e($r['ins'] ?? '-'); ?></td>
                        <td align="center"><?php echo e($r['for'] ?? '-'); ?></td>
                        <td align="center"><?php echo e($r['sum'] ?? '-'); ?></td>
                        <td align="center"><?php echo e($r['m3'] ?? '-'); ?></td>
                        <td align="center"><?php echo e($r['bbm'] ?? '-'); ?></td>
                        <td align="center"><strong><?php echo e($r['total'] ?? '-'); ?></strong></td>
                        <td align="center"><?php echo e($r['kategori']); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
        <table style="width:100%; font-size:11px; margin-top:24px; text-align:center;">
            <tr>
                <td style="width:50%;">Mengetahui,<br>Kepala Sekolah<br><br><br><br><br><strong><u><?php echo e(($rk = \App\Models\User::kepalaSekolah()->first()) ? $rk->name : '........................'); ?></u></strong></td>
                <td style="width:50%;"><?php echo e(now()->translatedFormat('d F Y')); ?><br>Supervisor<br><br><br><br><br><strong><u><?php echo e(auth()->user()->name); ?></u></strong></td>
            </tr>
        </table>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH D:\project\supervisi_guru laravel\resources\views\livewire\post-supervisions\form.blade.php ENDPATH**/ ?>