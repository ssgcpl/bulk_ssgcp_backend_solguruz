<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
<div class="navbar-header">
    <ul class="nav navbar-nav flex-row">
        <li class="nav-item mr-auto"><a class="navbar-brand" href="<?php echo e(route('home')); ?>">
                <div class="admin_panel_logo"><img src="<?php echo e(asset('admin_assets/app-assets/images/logo/logo.svg')); ?>"></div>
               <!--  <h2 class="brand-text mb-0"><?php echo e(@$app_settings['app_name']); ?></h2> -->
        </a></li>

    </ul>
</div>
<div class="shadow-bottom"></div>
<div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
        <li class="<?php echo e((request()->is('admin/home*')) ? 'active' : ''); ?>  nav-item"><a href="<?php echo e(route('home')); ?>"><i class="feather icon-grid"></i><span class="menu-title" ><?php echo e(trans('sidebar.dashboard')); ?></span></a>
        </li>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permission-list')): ?>
        <li class="<?php echo e((request()->is('admin/permissions*')) ? 'active' : ''); ?> nav-item">
            <a href="<?php echo e(route('permissions.index')); ?>">
                <i class="feather icon-zap"></i> <span class="menu-title"><?php echo e(trans('sidebar.manage_permissions')); ?></span>
            </a>
        </li>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role-list')): ?>
        <li class="<?php echo e((request()->is('admin/roles*')) ? 'active' : ''); ?>  nav-item">
            <a href="<?php echo e(route('roles.index')); ?>">
                <i class="feather icon-unlock"></i> <span class="menu-title"><?php echo e(trans('sidebar.manage_roles')); ?></span>
            </a>
        </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sub-admin-list')): ?>
        <li class="<?php echo e((request()->is('admin/sub_admin*')) ? 'active' : ''); ?> nav-item"><a href="<?php echo e(route('sub_admin.index')); ?>"><i class="feather icon-users"></i><span class="menu-title" ><?php echo e(trans('sidebar.sub_admin')); ?></span></a>
        <?php endif; ?>

          <?php if(auth()->user()->can('country-list') || auth()->user()->can('states-list') || auth()->user()->can('city-list') ): ?>
        <li class="nav-item"><a href="#"><i class="feather icon-layout"></i><span class="menu-title" data-i18n=""><?php echo e(trans('sidebar.master_panel')); ?></span></a>
            <ul class="menu-content">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('country-list')): ?>
                <li class="<?php echo e((request()->is('admin/country*')) ? 'active' : ''); ?>"> <a class="menu-item" href="<?php echo e(route('country.index')); ?>"><i class="feather icon-minus"></i><span class="menu-title" ><?php echo e(trans('sidebar.countries')); ?></span></a></li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('states-list')): ?>
                <li class="<?php echo e((request()->is('admin/states*')) ? 'active' : ''); ?>"> <a class="menu-item" href="<?php echo e(route('states.index')); ?>"><i class="feather icon-minus"></i><span class="menu-title" ><?php echo e(trans('sidebar.states')); ?></span></a></li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('city-list')): ?>
                <li class="<?php echo e((request()->is('admin/cities*')) ? 'active' : ''); ?>"> <a class="menu-item" href="<?php echo e(route('cities.index')); ?>"><i class="feather icon-minus"></i><span class="menu-title" ><?php echo e(trans('sidebar.cities')); ?></span></a></li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('post_code-list')): ?>
                <li class="<?php echo e((request()->is('admin/post_codes*')) ? 'active' : ''); ?>"> <a class="menu-item" href="<?php echo e(route('post_codes.index')); ?>"><i class="feather icon-minus"></i><span class="menu-title" ><?php echo e(trans('sidebar.postcodes')); ?></span></a></li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reason-list')): ?>
                <li class="<?php echo e((request()->is('admin/reasons*')) ? 'active' : ''); ?>"> <a class="menu-item" href="<?php echo e(route('reasons.index')); ?>"><i class="feather icon-minus"></i><span class="menu-title" ><?php echo e(trans('sidebar.reasons')); ?></span></a></li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('business-category-list')): ?>
                <li class="<?php echo e((request()->is('admin/business_categories*')) ? 'active' : ''); ?> nav-item"><a href="<?php echo e(route('business_categories.index')); ?>"><i class="feather icon-minus"></i><span class="menu-title" ><?php echo e(trans('sidebar.business_categories')); ?></span></a></li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('nested-category-list')): ?>
                <li class="<?php echo e((request()->is('admin/nested_categories*')) ? 'active' : ''); ?> nav-item"><a href="<?php echo e(route('nested_categories.index')); ?>"><i class="feather icon-minus"></i><span class="menu-title" ><?php echo e(trans('sidebar.nested_categories')); ?></span></a></li>
                <?php endif; ?>

            </ul>
        </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customer-list')): ?>
        <li class="<?php echo e(((request()->is('admin/customers*'))  || (request()->is('admin/email*')) || (request()->is('admin/notification*'))) ? 'active' : ''); ?> nav-item"><a href="<?php echo e(route('customers.index')); ?>"><i class="feather icon-users"></i><span class="menu-title" ><?php echo e(trans('sidebar.customers')); ?></span></a>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('order-list')): ?>
        <li class="<?php echo e(((request()->is('admin/orders*')) || (request()->is('admin/order/view_product_details*')) || (request()->is('admin/coupon_orders*'))) ? 'active' : ''); ?> nav-item"><a href="<?php echo e(route('orders.index')); ?>"><i class="feather icon-shopping-cart"></i><span class="menu-title" ><?php echo e(trans('sidebar.orders')); ?></span></a>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('order-list')): ?>
        <li class="<?php echo e((request()->is('admin/pending_orders*'))  ? 'active' : ''); ?> nav-item"><a href="<?php echo e(route('pending_orders.index')); ?>"><i class="feather icon-shopping-cart"></i><span class="menu-title" ><?php echo e(trans('sidebar.pending_orders')); ?></span></a>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('order-return-list')): ?>
        <li class="<?php echo e(((request()->is('admin/order_return*')) || (request()->is('admin/order_return/view_product_detail*'))) ? 'active' : ''); ?> nav-item"><a href="<?php echo e(route('order_return.index')); ?>"><i class="feather icon-corner-right-down"></i><span class="menu-title" ><?php echo e(trans('sidebar.order_return')); ?></span></a>
        <?php endif; ?>


        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-list')): ?>
        <li class="<?php echo e((request()->is('admin/products*')) ? 'active' : ''); ?> nav-item"><a href="<?php echo e(route('products.index')); ?>"><i class="feather icon-shopping-cart"></i><span class="menu-title" ><?php echo e(trans('sidebar.products')); ?></span></a>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stock-list')): ?>
        <li class="<?php echo e(((request()->is('admin/stock*')) || (request()->is('admin/view_gro*'))) ? 'active' : ''); ?> nav-item"><a href="<?php echo e(route('stock_report.index')); ?>"><i class="feather icon-box"></i><span class="menu-title" ><?php echo e(trans('sidebar.stock')); ?></span></a>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-barcode-list')): ?>
        <li class="<?php echo e((request()->is('admin/product_barcodes*')) ? 'active' : ''); ?> nav-item"><a href="<?php echo e(route('product_barcodes.index')); ?>"><i class="fa fa-barcode"></i><span class="menu-title" ><?php echo e(trans('sidebar.barcodes')); ?></span></a>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('coupon-list')): ?>
        <li class="<?php echo e((request()->is('admin/coupons*')) ? 'active' : ''); ?> nav-item"><a href="<?php echo e(route('coupons.index')); ?>"><i class="feather icon-tag"></i><span class="menu-title" ><?php echo e(trans('sidebar.coupon_management')); ?></span></a>
        <?php endif; ?>

         <?php if(auth()->user()->can('wish-list-list') || auth()->user()->can('wish-return-list')  ): ?>
        <li class="nav-item"><a href="#"><i class="feather icon-heart"></i><span class="menu-title" data-i18n=""><?php echo e(trans('sidebar.wish_management')); ?></span></a>
            <ul class="menu-content">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('wish-list-list')): ?>
                <li class="<?php echo e((request()->is('admin/wish_list*')) ? 'active' : ''); ?>"> <a class="menu-item" href="<?php echo e(route('wish_list.index')); ?>"><i class="feather icon-minus"></i><span class="menu-title" ><?php echo e(trans('sidebar.wish_list')); ?></span></a></li>
                <?php endif; ?>
            </ul>
             <ul class="menu-content">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('wish-return-list')): ?>
                <li class="<?php echo e((request()->is('admin/wish_return*')) ? 'active' : ''); ?>"> <a class="menu-item" href="<?php echo e(route('wish_return.index')); ?>"><i class="feather icon-minus"></i><span class="menu-title" ><?php echo e(trans('sidebar.wish_return')); ?></span></a></li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>

        <?php if(auth()->user()->can('ssgc-suggestion-list') || auth()->user()->can('wish-suggestion-list')  ): ?>
        <li class="nav-item"><a href="#"><i class="feather icon-inbox"></i><span class="menu-title" data-i18n=""><?php echo e(trans('sidebar.suggestion_management')); ?></span></a>
            <ul class="menu-content">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ssgc-suggestion-list')): ?>
            <li class="<?php echo e((request()->is('admin/ssgc_suggestions*')) ? 'active' : ''); ?> nav-item"><a href="<?php echo e(route('ssgc_suggestions.index')); ?>"><i class="feather icon-minus"></i><span class="menu-title" ><?php echo e(trans('sidebar.ssgc_suggestions')); ?></span></a>
            </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('wish-suggestion-list')): ?>
            <li class="<?php echo e(((request()->is('admin/wish_suggestions*')) || (request()->is('admin/wish_suggestion_images*'))) ? 'active' : ''); ?> nav-item"><a href="<?php echo e(route('wish_suggestions.index')); ?>"><i class="feather icon-minus"></i><span class="menu-title" ><?php echo e(trans('sidebar.wish_suggestions')); ?></span></a>
            </li>
            <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ticket-list')): ?>
        <li class="<?php echo e((request()->is('admin/tickets*')) ? 'active' : ''); ?> nav-item"><a href="<?php echo e(route('tickets.index')); ?>"><i class="feather icon-tag"></i><span class="menu-title" ><?php echo e(trans('sidebar.tickets')); ?></span></a>
        <?php endif; ?>

        <?php if(auth()->user()->can('cms-list') || auth()->user()->can('suggestion-list')   ): ?>
        <li class="nav-item"><a href="#"><i class="feather icon-box"></i><span class="menu-title" data-i18n=""><?php echo e(trans('sidebar.cms_management')); ?></span></a>
            <ul class="menu-content">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cms-list')): ?>
                <li class="<?php echo e((request()->is('admin/cms*')) ? 'active' : ''); ?> nav-item"><a href="<?php echo e(route('cms.index')); ?>"><i class="feather icon-minus"></i><span class="menu-title" ><?php echo e(trans('sidebar.cms_pages')); ?></span></a></li>
                <?php endif; ?>

            </ul>
        </li>
        <?php endif; ?>
      
<!--         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('notification-list')): ?>
        <li class="<?php echo e((request()->is('admin/notifications*')) ? 'active' : ''); ?> nav-item"><a href="<?php echo e(route('notifications.index')); ?>"><i class="feather icon-bell"></i><span class="menu-title" ><?php echo e(trans('sidebar.notifications')); ?></span></a>
        <?php endif; ?>
 -->
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('setting-list')): ?>
        <li class="<?php echo e((request()->is('admin/settings*')) ? 'active' : ''); ?> nav-item"><a href="<?php echo e(route('settings.index')); ?>"><i class="feather icon-settings"></i><span class="menu-title" ><?php echo e(trans('sidebar.setting')); ?></span></a>
        <?php endif; ?>

    
   </ul>
</div>
</div>
 <!-- END: Main Menu -->
<?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/layouts/admin/elements/sidebar.blade.php ENDPATH**/ ?>