@extends('layouts.admin.app')
@section('vendor_css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin_assets/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <style type="text/css">
        td form {
            display: inline;
        }

        .dt-buttons {
            margin-right: 10px
        }

        .select2-selection {
            padding: 0px 5px !important
        }

        .update_stock_popup {
            cursor: pointer;
            color: blue
        }
    </style>
@endsection
@section('css')
@endsection

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">{{ trans('coupons.heading') }}</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ trans('common.home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ trans('coupons.plural') }}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Zero configuration table -->
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ trans('coupons.title') }}</h4>
                                    @can('coupon-create')
                                        <div class="box-tools pull-right">
                                            <a href="{{ route('coupons.create') }}" class="btn btn-success pull-right">
                                                {{ trans('coupons.add_new') }}
                                            </a>
                                        </div>
                                    @endcan
                                </div>
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                  <label class="">{{trans('coupons.coupon_state')}}</label>
                                                  <select id="coupon_state" class = "form-control" name=''>
                                                    <option value=''>{{trans('common.select')}}</option>

                                                    <option value='available'>{{trans('coupons.available')}}</option>

                                                    <option value='expired'>{{trans('coupons.expired')}}</option>

                                                  </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                  <label class="">{{trans('coupons.status')}}</label>
                                                  <select id="status" class = "form-control" name=''>
                                                    <option value=''>{{trans('common.select')}}</option>
                                                    <option value="active">{{trans('coupons.active') }}</option>
                                                    <option value="inactive">{{trans('coupons.inactive') }}</option>
                                                  </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                  <label class="">{{trans('common.type')}}</label>
                                                  <select id="type" class = "form-control" name='type'>
                                                    <option value=''>{{trans('common.select')}}</option>
                                                    @foreach($item_types as $item_type)
                                                    <option value="{{ $item_type->name }}">
                                                        {{ trans('coupons.' . $item_type->name) }}</option>
                                                    @endforeach
                                                  </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="content-label">Start Date</label>
                                                    <input id="start_date" type=""
                                                        class="form-control datepicker_future_not_allow" autocomplete="off"
                                                        name="" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="content-label">End Date</label>
                                                    <input id="end_date" type=""
                                                        class="form-control datepicker_future_not_allow" name=""
                                                        autocomplete="off" name="" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="content-label"> </label>
                                                    <a href="javascript:void(0)" id="apply_date"
                                                        class="form-control btn btn-success">Apply
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table zero-configuration data_table_ajax">
                                                <thead>
                                                    <tr>
                                                        <th>{{ trans('common.sr_no') }}</th>
                                                        <th>{{ trans('coupons.image') }}</th>
                                                        <th>{{ trans('coupons.coupon_name') }}</th>
                                                        <th>{{ trans('coupons.coupon_id') }}</th>
                                                        <th>{{trans('coupons.business_category')}}</th>
                                                        <th>{{ trans('coupons.mrp') }}</th>
                                                        <th>{{ trans('coupons.sale_price') }}</th>
                                                        <th>{{ trans('coupons.item_type') }}</th>
                                                        <th>{{ trans('coupons.listing_date') }}</th>
                                                        <th>{{ trans('coupons.available_quantity') }}</th>
                                                        <th>{{ trans('coupons.coupon_state') }}</th>
                                                        <th>{{ trans('coupons.status') }}</th>
                                                        <th>{{ trans('common.action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>{{ trans('common.sr_no') }}</th>
                                                        <th>{{ trans('coupons.image') }}</th>
                                                        <th>{{ trans('coupons.coupon_name') }}</th>
                                                        <th>{{ trans('coupons.coupon_id') }}</th>
                                                        <th>{{trans('coupons.business_category')}}</th>
                                                        <th>{{ trans('coupons.mrp') }}</th>
                                                        <th>{{ trans('coupons.sale_price') }}</th>
                                                        <th>{{ trans('coupons.item_type') }}</th>
                                                        <th>{{ trans('coupons.listing_date') }}</th>
                                                        <th>{{ trans('coupons.available_quantity') }}</th>
                                                        <th>{{ trans('coupons.coupon_state') }}</th>
                                                        <th>{{ trans('coupons.status') }}</th>
                                                        <th>{{ trans('common.action') }}</th>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Zero configuration table -->
            </div>
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
        $(document).on('change', '.status', function() {
            var status = this.checked ? 'active' : 'inactive';
            var confirm = status_alert(status);
            if (confirm) {
                var id = $(this).attr('data_id');

                var delay = 500;
                var element = $(this);
                $.ajax({
                    type: 'post',
                    url: "{{ route('status_sub_coupon') }}",
                    data: {
                        "status": status,
                        "id": id,
                        "_token": "{{ csrf_token() }}"
                    },
                    beforeSend: function() {
                        element.next('.loading').css('visibility', 'visible');
                    },
                    success: function(data) {
                        setTimeout(function() {
                            element.next('.loading').css('visibility', 'hidden');
                        }, delay);
                        location.reload();
                        toastr.success(data.success)
                    },
                    error: function() {
                        toastr.error(data.error);
                    }
                })
            } else {
                location.reload();
            }
        })

        function status_alert(status) {
            if (status == 'active') {
                if (confirm("{{ trans('common.confirm_status_active') }}")) {
                    return true;
                } else {
                    return false;
                }
            } else if (status == 'inactive') {
                if (confirm("{{ trans('common.confirm_status_inactive') }}")) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#start_date,#end_date").val('');
            fill_datatable();

            function fill_datatable() {

                $('.data_table_ajax').DataTable({
                    'scrollY':true,'scrollX':true,
                    aaSorting: [
                        [3, 'desc']
                    ],
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    pageLength: 10,
                    serverSide: true,
                    serverMethod: 'POST',
                    bDestroy: true,
                    dom: 'Bflrtip',
                    
                    buttons: [{
                        extend: 'csv',
                        text: '<span class="fa fa-file-pdf-o"></span> {{ trans('customers.download_csv') }}',
                        className: "btn btn-md btn-success export-btn",
                        exportOptions: {
                            modifier: {
                                search: 'applied',
                                order: 'applied'
                            },
                            columns: [0,2,3,4,5,6,7,8,9,10]
                        },
                    }],
                    drawCallback: function() {
                        var hasRows = this.api().rows({
                            filter: 'applied'
                        }).data().length > 0;
                        $('.buttons-csv')[0].style.visibility = hasRows ? 'visible' : 'hidden'
                    },

                    language: {
                        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">{{ trans('common.loading') }}</span> '
                    },
                    ajax: {
                        url: "{{ route('dt_coupons') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "start_date": $("#start_date").val(),
                            "end_date": $("#end_date").val(),
                            "type": $("#type").val(),
                            "status": $("#status").val(),
                            "coupon_state": $("#coupon_state").val(),
                        },
                        //success:function(data) { console.log(data); },
                        error: function(data) {
                            // console.log(data);
                        },
                    },
                    columns: [
                            { render: function (data, type, row, meta) {
                                //return
/*                                var length= meta.settings._iRecordsTotal - 1;
                                for(var i=length; i>=1; i--) {
                                    return i;
                                }*/
                                 return meta.row + meta.settings._iDisplayStart + 1;
                              //  console.log(meta.settings);
                                }, orderable:false
                        },
                         { data: 'coupon_image',
                            mRender : function(data, type, row) { 
                                return row['coupon_image'];
                              },orderable: false, searchable: false 
                        },
                        {
                            data: 'coupon_name',
                            orderable: false
                        },
                        {
                            data: 'coupon_id',
                            orderable: false
                        },
                        {
                            data: 'business_category',
                            orderable: false
                        },
                        {
                            data: 'mrp',
                            orderable: false
                        },
                        {
                            data: 'sale_price',
                            orderable: true
                        },
                        {
                            data: 'item_type',
                            orderable: true
                        },
                        {
                            data: 'created_date',
                            orderable: false
                        },
                        {
                            data: 'available_quantity',
                            orderable: false
                        },
                        {
                            data: 'state',
                            orderable: false
                        },
                        {
                            data: 'status',
                            mRender: function(data, type, row) {

                                var status = data;

                                if (status == 'active') {
                                    type = "checked";
                                } else {
                                    type = '';
                                }
                                var status_label = status.charAt(0).toUpperCase() + "" + status
                                    .slice(1);
                                return '<label>' + status_label +
                                    '</label><div class="custom-control custom-switch custom-switch-success mr-2 mb-1"><input type="checkbox"' +
                                    type +
                                    ' + class="status custom-control-input" id="customSwitch' + row[
                                        "sub_coupon_id"] + '" data_id="' + row["sub_coupon_id"] +
                                    '"><label class="custom-control-label" for="customSwitch' + row[
                                        "sub_coupon_id"] + '"></label></div>';

                            },
                            orderable: false
                        },
                        {
                            data: 'is_live',
                                mRender : function(data, type, row) {

                                return '@can("coupon-edit")<a class="" href="'+row["edit"]+'"><i class="fa fa-edit"></i></a>@endcan <a class="" href="'+row["show"]+'"><i class="fa fa-eye"></i></a>@csrf</form>';
                            }, orderable: false, searchable: false
                        },
                    ]
                });
            }
            $('#apply_date').on('click', function (event) {
                if($('#start_date').val() != '' && $('#end_date').val() != '') {
                    var from_date = $("#start_date").val();
                    var to_date = $("#end_date").val();
                    var dateArr = from_date.split("-");
                    from_date = new Date(dateArr[2]+'-'+dateArr[1]+'-'+dateArr[0]);
                    var dateArr1 = to_date.split("-");
                    to_date = new Date(dateArr1[2]+'-'+dateArr1[1]+'-'+dateArr1[0]);
                    if(from_date > to_date){
                        toastr.error("End date should not be less than start date");
                        return false;
                    }
                    else {
                    fill_datatable();
                    }
                }else{
                toastr.error('Please select start date and end date');
                return false;
                }
            });

            $('#status,#type,#coupon_state').on('change', function(){
                fill_datatable();
            });

        });

    </script>
    <script type="text/javascript">
        function delete_alert() {
            if (confirm("{{ trans('common.confirm_delete') }}")) {
                return true;
            } else {
                return false;
            }
        }
    </script>
    </script>
@endsection
