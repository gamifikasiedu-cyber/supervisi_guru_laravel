<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled(config('livewire-powergrid.plugins.flatpickr.css'))): ?>
    <link
        rel="stylesheet"
        href="<?php echo e(config('livewire-powergrid.plugins.flatpickr.css')); ?>"
    >
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($cssPath)): ?>
    <style>
        <?php echo file_get_contents($cssPath); ?>

    </style>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\project\supervisi_guru laravel\vendor\power-components\livewire-powergrid\resources\views\assets\styles.blade.php ENDPATH**/ ?>