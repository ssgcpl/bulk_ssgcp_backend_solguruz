<ul>
    <?php $__currentLoopData = $childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <input type="checkbox" id="category[]" name="category[]" disabled
        <?php if(!Request::is('admin/products/create')): ?>
        value="<?php echo e($child->id); ?>"
        class="list"
        <?php echo e((in_array($child->id,@$product_category_ids)  || (is_array(old('list')) && in_array($child->id,old('list')))) ? "checked": ''); ?>

        <?php else: ?>
        value="<?php echo e($child->id); ?>" class="list" <?php echo e((is_array(old('list')) && in_array($pm->id,old('list'))) ? "checked": ''); ?>

        <?php endif; ?>

        > <?php echo e($child->category_name); ?>

        <?php if(count($child->sub_category)): ?>
            <?php echo $__env->make('admin.products.manage_child_checkbox_show',[
                'childs' =>
                    $child->sub_category,
                "category" => @$product_category_ids,
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php else: ?>
             <br>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>

<?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/admin/products/manage_checkbox_show.blade.php ENDPATH**/ ?>