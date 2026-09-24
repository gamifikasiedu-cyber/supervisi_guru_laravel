<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <div class="flex justify-between items-center flex-wrap gap-2 mb-3">
            <h3 class="font-semibold text-slate-800">Pengguna</h3>
            <div class="flex items-center gap-2 flex-wrap">
                <form method="POST" action="<?php echo e(route('users.import')); ?>" enctype="multipart/form-data" class="flex items-center gap-2">
                    <?php echo csrf_field(); ?>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="text-xs max-w-[180px]">
                    <button class="text-sm px-3 py-1.5 rounded-lg bg-sky-600 text-white hover:bg-sky-700">⬆ Impor</button>
                </form>
                <a href="<?php echo e(route('users.export')); ?>" class="text-sm px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">⬇ Excel</a>
                <form method="POST" action="<?php echo e(route('users.delete-all')); ?>" onsubmit="return confirm('Hapus SEMUA pengguna kecuali akun Anda?')" class="inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button class="text-sm px-3 py-1.5 rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-200">Hapus Semua</button>
                </form>
                <a href="<?php echo e(route('users.create')); ?>" class="text-sm px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">+ Tambah</a>
            </div>
        </div>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('tables.user-table', []);

$__key = null;

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1803285259-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key);

echo $__html;

unset($__html);
unset($__key);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    </div>
</div>
<?php /**PATH D:\project\supervisi_guru laravel\resources\views\livewire\users\index.blade.php ENDPATH**/ ?>