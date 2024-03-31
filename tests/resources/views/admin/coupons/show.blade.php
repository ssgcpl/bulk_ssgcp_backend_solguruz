@extends('layouts.admin.app')
@section('css')
    <style type="text/css">
        .cover_images {
            width: 40%;
            margin-bottom: 10px;
        }
    </style>
    @include('layouts.admin.elements.filepond_css')
@endsection

@section('vendor_css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin_assets/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">

@endsection

@section('content')
    <!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">{{ trans('coupons.singular') }} </h2>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ trans('common.home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="{{ route('coupons.index') }}">{{ trans('coupons.plural') }}</a></li>
                                <li class="breadcrumb-item active">{{ trans('coupons.show') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <b>{{ trans('common.whoops') }}</b>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- // Basic multiple Column Form section start -->
        <section id="multiple-column-form">
            <div class="row match-height">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                {{ trans('coupons.details') }}
                            </h4>
                            @can('coupon-list')
                                <a href="{{ route('coupons.index') }}" class="btn btn-success">
                                    <i class="fa fa-arrow-left"></i>
                                    {{ trans('common.back') }}
                                </a>
                            @endcan
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form method="POST" id="createForm" accept-charset="UTF-8"
                                    enctype="multipart/form-data">
                                    @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="categories"
                                                class="content-label">{{ trans('coupons.categories') }}<span
                                                    class="text-danger custom_asterisk">*</span></label>
                                            <ul class="nav nav-pills" role="tablist">
                                                @foreach ($categories as $category)
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="" data-toggle="pill"
                                                            href="#tab_{{ $category->id }}" role="tab"
                                                            aria-selected="true">{{ $category->category_name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="tab-content">
                                                @foreach ($categories as $category)
                                                    <div class="tab-pane" id="tab_{{ $category->id }}"
                                                        role="tabpanel">
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div id="treeview-checkbox-demo">
                                                                        <ul>{{-- <li> --}}
                                                                            <input type="checkbox"
                                                                                id="category[]"
                                                                                name="category[]"
                                                                                value="{{ $category->id }}"
                                                                                disabled class="list"
                                                                                {{ in_array($category->id, $coupon_category_ids) || (is_array(old('list')) && in_array($category->id, old('list'))) ? 'checked' : '' }}
                                                                                disabled>
                                                                            {{ $category->category_name }}
                                                                            @include(
                                                                                'admin.coupons.manage_checkbox',
                                                                                [
                                                                                    'childs' =>
                                                                                        $category->sub_category,
                                                                                    'category' => $coupon_category_ids,
                                                                                ]
                                                                            )
                                                                            {{-- </li> --}}
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="item_type"
                                                class="content-label">{{ trans('coupons.item_type') }}<span
                                                    class="text-danger custom_asterisk">*</span></label>
                                            <p class="details">{{ $coupon_master->item_type }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="coupon_name"
                                                class="content-label">{{ trans('coupons.coupon_name') }}<span
                                                    class="text-danger custom_asterisk">*</span></label>
                                            <p class="details">{{ $coupon_master->name }} </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="coupon_name"
                                                class="content-label">{{trans('coupons.business_category')}}<span
                                                    class="text-danger custom_asterisk">*</span></label>
                                            <p class="details">{{ $coupons->business_category->category_name }} </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="content:"
                                                class="content-label">{{ trans('coupons.coupon_id_ssgc1') }}<span
                                                    class="text-danger custom_asterisk">*</span></label>
                                            <p class="details">{{ $coupon_master->coupon_id }} </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="content:"
                                                class="content-label">{{ trans('coupons.discount_in_percent') }}<span
                                                    class="text-danger custom_asterisk">*</span></label>
                                            <p class="details">{{ $coupon_master->discount }} </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="content"
                                                class="content-label">{{ trans('coupons.qty') }}<span
                                                    class="text-danger custom_asterisk">*</span></label>
                                            <p class="details">{{ $coupon_master->quantity }} </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="content"
                                                class="content-label">{{ trans('coupons.count_usage') }}<span
                                                    class="text-danger custom_asterisk">*</span></label>
                                            <p class="details">{{ $coupon_master->usage_limit }} </p>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="content:"
                                                class="content-label">{{ trans('coupons.applicable_on_item_name') }}<span
                                                    class="text-danger custom_asterisk">*</span></label>
                                            <p class="details">{{ $coupon_master->item_name }} </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="content:"
                                                class="content-label">{{ trans('coupons.expiry_date_and_time') }}<span
                                                    class="text-danger custom_asterisk">*</span></label>
                                            <p class="details">{{ $coupon_master->end_date->format('d-m-Y h:i A');}} </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="price"
                                                class="content-label">{{ trans('coupons.enter_the_mrp') }}<span
                                                    class="text-danger custom_asterisk">*</span></label>
                                            <p class="details">{{ $coupons->mrp }} </p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="content:"
                                                class="content-label">{{ trans('coupons.enter_the_sale_price') }}<span
                                                    class="text-danger custom_asterisk">*</span></label>
                                            <p class="details">{{ $coupons->sale_price }} </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="content"
                                                class="content-label">{{ trans('coupons.description') }}<span
                                                    class="text-danger custom_asterisk">*</span></label>
                                            <p class="details">{{ $coupons->description }} </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="image"
                                                class="content-label">{{ trans('coupons.listing_image') }}<span
                                                    class="text-danger custom_asterisk">*</span></label><br>
                                            <img src="{{ asset($coupons->image) }}" class="cover_images">

                                        </div>
                                        <label id="image-error" for="image"></label>
                                        <strong class="help-block alert-danger">
                                            {{ @$errors->first('image') }}
                                        </strong>
                                    </div>
                                    <div class="col-md-8" id="coupon_cover_images">
                                        <div class="form-group">
                                            <label id="image-error"
                                                for="image">{{ trans('coupons.cover_images') }} <span
                                                    class="text-danger custom_asterisk">*</span></label><br>
                                            @foreach ($coupons->cover_images as $cvr_img)
                                                
                                                
                                            <img src="{{ asset($cvr_img->cover_image) }}"class="cover_images" style="width: 20%" >
                                    
                                            @endforeach
                                        </div>
                                    </div>
                                </div>



                                </form>
                            </div>


                        <h4 class="card-title">{{ trans('coupons.qr_codes') }}</h4>

                        <div class="table-responsive">
                            <table class="table zero-configuration data_table_ajax">
                              <thead>
                                <tr>
                                    <th>{{trans('common.id')}}</th>
                                    <th>{{trans('coupons.qr_code')}}</th>
                                    <th>{{trans('coupons.unique_code')}}</th>
                                    <th>{{trans('coupons.for')}}</th>
                                    <th>{{trans('coupons.end_date')}}</th>
                                    <th>{{trans('common.state')}}</th>

                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                              {{-- <tfoot>
                                <tr>

                                    <th>{{trans('common.id')}}</th>
                                    <th>{{trans('coupons.qr_code')}}</th>
                                    <th>{{trans('coupons.unique_code')}}</th>
                                    <th>{{trans('coupons.for')}}</th>
                                    <th>{{trans('coupons.end_date')}}</th>
                                    <th>{{trans('common.state')}}</th>
                                </tr>
                              </tfoot> --}}
                            </table>
                        </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</div>
@endsection
@section('page_js')
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('admin_assets/app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('admin_assets/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin_assets/app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin_assets/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
    <!-- END: Page Vendor JS-->
@endsection
@section('js')
<script src="{{ asset('admin_assets/custom/data_tables/export/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin_assets/custom/data_tables/export/jszip.min.js') }}"></script>
    <script src="{{ asset('admin_assets/custom/data_tables/export/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('admin_assets/custom/data_tables/export/buttons.print.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $("ul.nav-pills li:first").find('a:first').addClass("active");
            $(".tab-content .tab-pane:first").addClass("active");
        });

        $(document).ready(function() {
            fill_datatable();
            function fill_datatable() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                var table = $('.data_table_ajax').DataTable({
                    /*"scrollY": 300,
                    "scrollX": true,*/
                    aaSorting: [
                        [0, 'desc']
                    ],
                    dom: 'Blfrtip',
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    buttons: [],
                    pageLength: 10,
                    processing: true,
                    serverSide: true,
                    serverMethod: 'POST',
                    processing: true,
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">{{ trans('common.loading') }}</span> '
                    },
                    ajax: {
                        url: "{{ route('qr_coupon_ajax') }}",
                        data: {
                            'id': {{ $coupons->id }}
                        },
                        //success:function(data) { console.log(data); },
                        error: function(data) {
                            console.log(data);
                        },
                    },
                    columns: [
                        // { data : "checkbox", orderable:false, searchable:false},
                        {
                            data: 'id',
                            mRender: function(data, type, row) {
                                return row['number'];
                            },
                            orderable: false,
                            searchable: false
                        },
                        // { data: 'name'},
                        {
                            data: 'qr_code_image'
                        },
                        {
                            data: 'qr_code_value'
                        },
                        {
                            data: 'for',
                            orderable: false
                        },
                        {
                            data: 'end_date',
                            orderable: false
                        },
                        // { data: 'for',orderable: false},
                        {
                            data: 'state'
                        },
                    ],
                });
            }
        })
    </script>
    @include('layouts.admin.elements.filepond_js')
@endsection
