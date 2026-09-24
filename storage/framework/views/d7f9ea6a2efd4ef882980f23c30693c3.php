<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 max-w-2xl space-y-4">
        <div>
            <h3 class="font-semibold text-slate-800"><?php echo e($document->title); ?></h3>
            <p class="text-sm text-slate-500"><?php echo e($document->user->name ?? '-'); ?> · <?php echo e($document->subject->name ?? '-'); ?> · <?php echo e($document->file_size ?? '-'); ?></p>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($document->description): ?><p class="text-sm text-slate-600 mt-1"><?php echo e($document->description); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <a href="<?php echo e(route('documents.download', $document)); ?>" class="inline-block mt-2 text-sm px-3 py-1.5 rounded-lg bg-sky-100 text-sky-700 hover:bg-sky-200">⬇ Unduh File</a>
        </div>
        <form wire:submit="save" class="space-y-4 border-t border-slate-100 pt-4">
            <div>
                <label class="text-sm font-medium">Status</label>
                <select wire:model="status" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                    <option value="pending">Menunggu</option>
                    <option value="approved">Disetujui</option>
                    <option value="rejected">Ditolak</option>
                </select>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-600 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div>
                <label class="text-sm font-medium">Catatan Review</label>
                <textarea wire:model="review_notes" rows="3" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border"></textarea>
            </div>
            <div class="flex gap-2">
                <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">Simpan Review</button>
                <a href="<?php echo e(route('documents.index')); ?>" class="px-4 py-2 rounded-lg bg-slate-100 text-sm text-slate-600 hover:bg-slate-200">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php /**PATH D:\project\supervisi_guru laravel\resources\views\livewire\documents\review.blade.php ENDPATH**/ ?>