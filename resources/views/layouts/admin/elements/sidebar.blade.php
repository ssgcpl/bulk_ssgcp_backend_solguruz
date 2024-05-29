<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
<div class="navbar-header">
    <ul class="nav navbar-nav flex-row">
        <li class="nav-item mr-auto"><a class="navbar-brand" href="{{route('home')}}">
                <div class="admin_panel_logo"><img src="{{asset('admin_assets/app-assets/images/logo/logo.svg')}}"></div>
               <!--  <h2 class="brand-text mb-0">{{@$app_settings['app_name']}}</h2> -->
        </a></li>

    </ul>
</div>
<div class="shadow-bottom"></div>
<div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
        <li class="{{ (request()->is('admin/home*')) ? 'active' : '' }}  nav-item"><a href="{{route('home')}}"><i class="feather icon-grid"></i><span class="menu-title" >{{trans('sidebar.dashboard')}}</span></a>
        </li>
        @can('permission-list')
        <li class="{{ (request()->is('admin/permissions*')) ? 'active' : '' }} nav-item">
            <a href="{{ route('permissions.index') }}">
                <i class="feather icon-zap"></i> <span class="menu-title">{{trans('sidebar.manage_permissions')}}</span>
            </a>
        </li>
        @endcan
        @can('role-list')
        <li class="{{ (request()->is('admin/roles*')) ? 'active' : '' }}  nav-item">
            <a href="{{ route('roles.index') }}">
                <i class="feather icon-unlock"></i> <span class="menu-title">{{trans('sidebar.manage_roles')}}</span>
            </a>
        </li>
        @endcan

        @can('sub-admin-list')
        <li class="{{ (request()->is('admin/sub_admin*')) ? 'active' : '' }} nav-item"><a href="{{route('sub_admin.index')}}"><i class="feather icon-users"></i><span class="menu-title" >{{trans('sidebar.sub_admin')}}</span></a>
        @endcan

          @if(auth()->user()->can('country-list') || auth()->user()->can('states-list') || auth()->user()->can('city-list') )
        <li class="nav-item"><a href="#"><i class="feather icon-layout"></i><span class="menu-title" data-i18n="">{{trans('sidebar.master_panel')}}</span></a>
            <ul class="menu-content">
                @can('country-list')
                <li class="{{ (request()->is('admin/country*')) ? 'active' : '' }}"> <a class="menu-item" href="{{route('country.index')}}"><i class="feather icon-minus"></i><span class="menu-title" >{{trans('sidebar.countries')}}</span></a></li>
                @endcan

                @can('states-list')
                <li class="{{ (request()->is('admin/states*')) ? 'active' : '' }}"> <a class="menu-item" href="{{route('states.index')}}"><i class="feather icon-minus"></i><span class="menu-title" >{{trans('sidebar.states')}}</span></a></li>
                @endcan

                @can('city-list')
                <li class="{{ (request()->is('admin/cities*')) ? 'active' : '' }}"> <a class="menu-item" href="{{route('cities.index')}}"><i class="feather icon-minus"></i><span class="menu-title" >{{trans('sidebar.cities')}}</span></a></li>
                @endcan

                @can('post_code-list')
                <li class="{{ (request()->is('admin/post_codes*')) ? 'active' : '' }}"> <a class="menu-item" href="{{route('post_codes.index')}}"><i class="feather icon-minus"></i><span class="menu-title" >{{trans('sidebar.postcodes')}}</span></a></li>
                @endcan

                @can('reason-list')
                <li class="{{ (request()->is('admin/reasons*')) ? 'active' : '' }}"> <a class="menu-item" href="{{route('reasons.index')}}"><i class="feather icon-minus"></i><span class="menu-title" >{{trans('sidebar.reasons')}}</span></a></li>
                @endcan

                @can('business-category-list')
                <li class="{{ (request()->is('admin/business_categories*')) ? 'active' : '' }} nav-item"><a href="{{route('business_categories.index')}}"><i class="feather icon-minus"></i><span class="menu-title" >{{trans('sidebar.business_categories')}}</span></a></li>
                @endcan

                @can('nested-category-list')
                <li class="{{ (request()->is('admin/nested_categories*')) ? 'active' : '' }} nav-item"><a href="{{route('nested_categories.index')}}"><i class="feather icon-minus"></i><span class="menu-title" >{{trans('sidebar.nested_categories')}}</span></a></li>
                @endcan

            </ul>
        </li>
        @endif

        @can('customer-list')
        <li class="{{ ((request()->is('admin/customers*'))  || (request()->is('admin/email*')) || (request()->is('admin/notification*'))) ? 'active' : '' }} nav-item"><a href="{{route('customers.index')}}"><i class="feather icon-users"></i><span class="menu-title" >{{trans('sidebar.customers')}}</span></a>
        @endcan

        @can('order-list')
        <li class="{{ ((request()->is('admin/orders*')) || (request()->is('admin/order/view_product_details*')) || (request()->is('admin/coupon_orders*'))) ? 'active' : '' }} nav-item"><a href="{{route('orders.index')}}"><i class="feather icon-shopping-cart"></i><span class="menu-title" >{{trans('sidebar.orders')}}</span></a>
        @endcan

        @can('order-list')
        <li class="{{ (request()->is('admin/pending_orders*'))  ? 'active' : '' }} nav-item"><a href="{{route('pending_orders.index')}}"><i class="feather icon-shopping-cart"></i><span class="menu-title" >{{trans('sidebar.pending_orders')}}</span></a>
        @endcan

        @can('order-return-list')
        <li class="{{ ((request()->is('admin/order_return*')) || (request()->is('admin/order_return/view_product_detail*'))) ? 'active' : '' }} nav-item"><a href="{{route('order_return.index')}}"><i class="feather icon-corner-right-down"></i><span class="menu-title" >{{trans('sidebar.order_return')}}</span></a>
        @endcan


        @can('product-list')
        <li class="{{ (request()->is('admin/products*')) ? 'active' : '' }} nav-item"><a href="{{route('products.index')}}"><i class="feather icon-shopping-cart"></i><span class="menu-title" >{{trans('sidebar.products')}}</span></a>
        @endcan

        @can('stock-list')
        <li class="{{ ((request()->is('admin/stock*')) || (request()->is('admin/view_gro*'))) ? 'active' : '' }} nav-item"><a href="{{route('stock_report.index')}}"><i class="feather icon-box"></i><span class="menu-title" >{{trans('sidebar.stock')}}</span></a>
        @endcan

        @can('product-barcode-list')
        <li class="{{ (request()->is('admin/product_barcodes*')) ? 'active' : '' }} nav-item"><a href="{{route('product_barcodes.index')}}"><i class="fa fa-barcode"></i><span class="menu-title" >{{trans('sidebar.barcodes')}}</span></a>
        @endcan

        @can('coupon-list')
        <li class="{{ (request()->is('admin/coupons*')) ? 'active' : '' }} nav-item"><a href="{{route('coupons.index')}}"><i class="feather icon-tag"></i><span class="menu-title" >{{trans('sidebar.coupon_management')}}</span></a>
        @endcan

         @if(auth()->user()->can('wish-list-list') || auth()->user()->can('wish-return-list')  )
        <li class="nav-item"><a href="#"><i class="feather icon-heart"></i><span class="menu-title" data-i18n="">{{trans('sidebar.wish_management')}}</span></a>
            <ul class="menu-content">
                @can('wish-list-list')
                <li class="{{ (request()->is('admin/wish_list*')) ? 'active' : '' }}"> <a class="menu-item" href="{{route('wish_list.index')}}"><i class="feather icon-minus"></i><span class="menu-title" >{{trans('sidebar.wish_list')}}</span></a></li>
                @endcan
            </ul>
             <ul class="menu-content">
                @can('wish-return-list')
                <li class="{{ (request()->is('admin/wish_return*')) ? 'active' : '' }}"> <a class="menu-item" href="{{route('wish_return.index')}}"><i class="feather icon-minus"></i><span class="menu-title" >{{trans('sidebar.wish_return')}}</span></a></li>
                @endcan
            </ul>
        </li>
        @endif

        @if(auth()->user()->can('ssgc-suggestion-list') || auth()->user()->can('wish-suggestion-list')  )
        <li class="nav-item"><a href="#"><i class="feather icon-inbox"></i><span class="menu-title" data-i18n="">{{trans('sidebar.suggestion_management')}}</span></a>
            <ul class="menu-content">
            @can('ssgc-suggestion-list')
            <li class="{{ (request()->is('admin/ssgc_suggestions*')) ? 'active' : '' }} nav-item"><a href="{{route('ssgc_suggestions.index')}}"><i class="feather icon-minus"></i><span class="menu-title" >{{trans('sidebar.ssgc_suggestions')}}</span></a>
            </li>
            @endcan
            @can('wish-suggestion-list')
            <li class="{{ ((request()->is('admin/wish_suggestions*')) || (request()->is('admin/wish_suggestion_images*'))) ? 'active' : '' }} nav-item"><a href="{{route('wish_suggestions.index')}}"><i class="feather icon-minus"></i><span class="menu-title" >{{trans('sidebar.wish_suggestions')}}</span></a>
            </li>
            @endcan
            </ul>
        </li>
        @endcan

        @can('ticket-list')
        <li class="{{ (request()->is('admin/tickets*')) ? 'active' : '' }} nav-item"><a href="{{route('tickets.index')}}"><i class="feather icon-tag"></i><span class="menu-title" >{{trans('sidebar.tickets')}}</span></a>
        @endcan

        @if(auth()->user()->can('cms-list') || auth()->user()->can('suggestion-list')   )
        <li class="nav-item"><a href="#"><i class="feather icon-box"></i><span class="menu-title" data-i18n="">{{trans('sidebar.cms_management')}}</span></a>
            <ul class="menu-content">
                @can('cms-list')
                <li class="{{ (request()->is('admin/cms*')) ? 'active' : '' }} nav-item"><a href="{{route('cms.index')}}"><i class="feather icon-minus"></i><span class="menu-title" >{{trans('sidebar.cms_pages')}}</span></a></li>
                @endcan

            </ul>
        </li>
        @endif
      
<!--         @can('notification-list')
        <li class="{{ (request()->is('admin/notifications*')) ? 'active' : '' }} nav-item"><a href="{{route('notifications.index')}}"><i class="feather icon-bell"></i><span class="menu-title" >{{trans('sidebar.notifications')}}</span></a>
        @endcan
 -->
        @canany(['order-report', 'datewise-report'])
        <li class="nav-item"><a href="#"><i class="feather icon-align-left"></i><span class="menu-title" >{{trans('sidebar.reports')}}</span></a>
            <ul class="menu-content">
                @can('datewise-report')
                <li class="{{ (request()->is('admin/report*')) ? 'active' : '' }} nav-item"><a href="{{route('reports.index')}}"><i class="feather icon-minus"></i><span class="menu-title" >{{trans('sidebar.datewise_report')}}</span></a></li>
                @endcan
                @can('order-report')
                <li class="{{ (request()->is('admin/orderreport*')) ? 'active' : '' }} nav-item"><a href="{{route('orderreport.index')}}"><i class="feather icon-minus"></i><span class="menu-title" >{{trans('sidebar.order_report')}}</span></a></li>
                @endcan

            </ul>
        </li>
        @endcanany

        @can('setting-list')
        <li class="{{ (request()->is('admin/settings*')) ? 'active' : '' }} nav-item"><a href="{{route('settings.index')}}"><i class="feather icon-settings"></i><span class="menu-title" >{{trans('sidebar.setting')}}</span></a></li>
        @endcan

    
   </ul>
</div>
</div>
 <!-- END: Main Menu -->
