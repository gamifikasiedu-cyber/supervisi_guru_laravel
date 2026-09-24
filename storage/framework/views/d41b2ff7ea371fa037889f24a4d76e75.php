<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
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
                <table class="w-full text-sm min-w-[1000px]">
                    <thead>
                        <tr class="text-left text-xs uppercase text-slate-400 border-b border-slate-100">
                            <th class="px-4 py-2.5 w-10">No</th>
                            <th class="px-4 py-2.5">Pertanyaan / Fokus</th>
                            <th class="px-4 py-2.5 w-44">Catatan Guru</th>
                            <th class="px-4 py-2.5 w-44">Catatan Supervisor</th>
                            <th class="px-4 py-2.5 w-44">Kesepakatan</th>
                            <th class="px-4 py-2.5 w-44">Tindak Lanjut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-4 py-2 text-slate-400"><?php echo e($i + 1); ?></td>
                                <td class="px-4 py-2 text-slate-700"><?php echo e($it['pertanyaan']); ?></td>
                                <td class="px-4 py-2"><textarea wire:model="items.<?php echo e($i); ?>.catatan_guru" rows="2" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border"></textarea></td>
                                <td class="px-4 py-2"><textarea wire:model="items.<?php echo e($i); ?>.catatan_supervisor" rows="2" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border"></textarea></td>
                                <td class="px-4 py-2"><textarea wire:model="items.<?php echo e($i); ?>.kesepakatan" rows="2" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border"></textarea></td>
                                <td class="px-4 py-2"><textarea wire:model="items.<?php echo e($i); ?>.tindak_lanjut" rows="2" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border"></textarea></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-slate-100">
                <label class="text-sm font-medium">Foto Dokumentasi (maks 5)</label>
                <input type="file" wire:model="photos" accept="image/*" multiple class="mt-1 block w-full text-sm">
                <div wire:loading wire:target="photos" class="text-xs text-indigo-600 mt-1">Mengunggah…</div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['photos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($existingPhotos)): ?>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $existingPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $path): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="relative">
                                <img src="<?php echo e(Storage::url($path)); ?>" class="w-24 h-24 object-cover rounded-lg border">
                                <button type="button" wire:click="removeExistingPhoto(<?php echo e($idx); ?>)" class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-rose-600 text-white text-xs">✕</button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="px-5 py-4 flex gap-2">
                <button class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Simpan</button>
                <a href="<?php echo e(route('konferensis.index')); ?>" class="px-4 py-2 rounded-lg bg-slate-100 text-sm text-slate-600 hover:bg-slate-200">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php /**PATH D:\project\supervisi_guru laravel\resources\views\livewire\konferensis\form.blade.php ENDPATH**/ ?>