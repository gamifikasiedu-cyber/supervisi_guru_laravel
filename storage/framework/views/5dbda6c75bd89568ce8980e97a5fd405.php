<div>
    <h2 class="text-lg font-bold text-slate-800">Selamat datang kembali</h2>
    <p class="text-sm text-slate-500 mb-5">Masuk untuk mengelola supervisi akademik</p>

    <form wire:submit="login" class="space-y-4">
        <div>
            <label class="text-sm font-medium text-slate-700">Email</label>
            <input type="email" wire:model="email" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2.5 border focus:border-indigo-500 focus:ring-indigo-500" placeholder="nama@sekolah.id" autofocus>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Password</label>
            <input type="password" wire:model="password" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2.5 border focus:border-indigo-500 focus:ring-indigo-500" placeholder="••••••••">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($periods->isNotEmpty()): ?>
            <div>
                <label class="text-sm font-medium text-slate-700">Periode Akademik</label>
                <select wire:model="period_id" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2.5 border focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Tanpa periode --</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($p->id); ?>"><?php echo e($p->tahun_ajaran); ?> · <?php echo e($p->semester); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </select>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" wire:model="remember" class="rounded border-slate-300 text-indigo-600"> Ingat saya
        </label>
        <button type="submit" wire:loading.attr="disabled" class="w-full py-2.5 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 disabled:opacity-50 transition">
            <span wire:loading.remove>Masuk</span><span wire:loading>Memproses…</span>
        </button>
    </form>
</div>
<?php /**PATH D:\project\supervisi_guru laravel\resources\views/livewire/auth/login.blade.php ENDPATH**/ ?>