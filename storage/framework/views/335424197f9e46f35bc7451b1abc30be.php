<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['items' => []]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['items' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<details class="relative inline-block text-left">
    <summary
        class="inline-flex items-center gap-1 pl-2.5 pr-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-600 hover:border-indigo-300 hover:text-indigo-600 text-xs font-semibold cursor-pointer list-none select-none"
        style="list-style:none;">⋮<span>Aksi</span></summary>

    <div
        class="absolute right-0 z-30 mt-1 w-44 origin-top-right rounded-xl bg-white py-1 shadow-xl ring-1 ring-slate-900/5 text-sm">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($item['method'] ?? 'GET') === 'GET'): ?>
                <a href="<?php echo e($item['url']); ?>"
                    class="flex items-center gap-2.5 px-4 py-2 text-slate-600 hover:bg-slate-50">
                    <span class="w-4 text-center"><?php echo e($item['icon'] ?? '›'); ?></span><span><?php echo e($item['label']); ?></span>
                </a>
            <?php else: ?>
                <form method="POST" action="<?php echo e($item['url']); ?>"
                    <?php if(! empty($item['confirm'])): ?> onsubmit="return confirm('<?php echo e($item['confirm']); ?>')" <?php endif; ?>>
                    <?php echo csrf_field(); ?>
                    <?php echo method_field($item['method']); ?>
                    <button type="submit"
                        class="flex w-full items-center gap-2.5 px-4 py-2 <?php echo e(! empty($item['danger']) ? 'text-rose-600 hover:bg-rose-50' : 'text-slate-600 hover:bg-slate-50'); ?>">
                        <span class="w-4 text-center"><?php echo e($item['icon'] ?? '›'); ?></span><span><?php echo e($item['label']); ?></span>
                    </button>
                </form>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</details>
<?php /**PATH D:\project\supervisi_guru laravel\resources\views\components\row-menu.blade.php ENDPATH**/ ?>