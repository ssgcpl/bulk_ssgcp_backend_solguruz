<?php echo $__env->make('customer.elements.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->make('customer.elements.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  

<?php echo $__env->yieldContent('css'); ?>

<?php echo $__env->yieldContent('content'); ?>

<?php echo $__env->make('customer.elements.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->make('customer.elements.footer_links', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->make('customer.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->yieldContent('js'); ?>
<?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/customer/layouts/app.blade.php ENDPATH**/ ?>