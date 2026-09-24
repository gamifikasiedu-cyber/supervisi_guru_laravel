<tbody>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $rowId = data_get($row, $this->realPrimaryKey);
            $class = theme_style($theme, 'table.body.tr');
        ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($setUp['detail'])): ?>
            <tr class="<?php echo e($class); ?>">
                <?php echo $__env->make('livewire-powergrid::components.row', [
                    'rowIndex' => $loop->index + 1,
                    'childIndex' => $childIndex,
                    'parentId' => $parentId
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </tr>

            <?php
                $hasDetailView = (bool) data_get(
                    collect($row->__powergrid_rules)->where('apply', true)->last(),
                    'detailView',
                );

                if ($hasDetailView) {
                    $detailView = data_get($row->__powergrid_rules, '0.detailView');
                    $rulesValues = data_get($row->__powergrid_rules, '0.options', []);
                } else {
                    $detailView = data_get($setUp, 'detail.view');
                    $rulesValues = data_get($setUp, 'detail.options', []);
                }
            ?>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('powergrid-detail', ['view' => $detailView,'options' => $rulesValues,'rowId' => $rowId,'trClass' => ''.e($class).'','row' => $row->toArray(),'collapseOthers' => data_get($setUp, 'detail.collapseOthers', false)]);

$__key = 'powergrid-lazy-child-detail-'.e($rowId).'';

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-2627274447-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key);

echo $__html;

unset($__html);
unset($__key);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        <?php else: ?>
            <tr
                class="<?php echo e($class); ?>"
            >
                <?php echo $__env->make('livewire-powergrid::components.row', [
                    'rowIndex' => $loop->index + 1,
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </tr>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php echo $__env->renderWhen(isset($setUp['responsive']), 'livewire-powergrid::components.expand-container', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</tbody>
<?php /**PATH D:\project\supervisi_guru laravel\vendor\power-components\livewire-powergrid\resources\views\livewire\lazy-child.blade.php ENDPATH**/ ?>