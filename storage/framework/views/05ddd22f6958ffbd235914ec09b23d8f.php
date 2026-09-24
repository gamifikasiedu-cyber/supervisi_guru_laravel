<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 max-w-xl">
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="text-sm font-medium">Tahun Ajaran (cth: 2026/2027)</label>
                <input wire:model="tahun_ajaran" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border" placeholder="2026/2027">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['tahun_ajaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div>
                <label class="text-sm font-medium">Semester</label>
                <select wire:model="semester" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Simpan</button>
                <a href="<?php echo e(route('periods.index')); ?>" class="px-4 py-2 rounded-lg bg-slate-100 text-sm text-slate-600 hover:bg-slate-200">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php /**PATH D:\project\supervisi_guru laravel\resources\views\livewire\periods\form.blade.php ENDPATH**/ ?>