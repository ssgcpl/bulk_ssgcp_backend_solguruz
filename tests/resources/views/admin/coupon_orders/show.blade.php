@extends('layouts.admin.app')
@section('vendor_css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin_assets/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
@endsection
@section('content')
<style type="text/css">
  .add_new_gro {cursor: pointer;}
  .form-group label {width:100%;}
  .text-right { float: right; }
  .close_gro_div {float: right; position: relative; padding: 2px; top: 1px}
</style>

<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{trans('coupon_orders.admin_heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('orders.index')}}">{{ trans('coupon_orders.plural') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('coupon_orders.show') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($errors->any())
      <div class="alert alert-danger">
        <b>{{trans('common.whoops')}}</b>
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
              {{ trans('coupon_orders.details')}}
              </h4>
              <a href="{{route('coupons.edit',$order_item->coupon->id)}}" class="btn btn-success">
                  <i class="fa"></i>
                  {{ trans('coupon_orders.coupon_details') }}
              </a>
              <a href="{{route('coupon_orders.edit',$order_item->order_id)}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
              </a>
            </div>
            <div class="card-content">
              <div class="card-body">
                <form method="POST" id="notifyUserForm" accept-charset="UTF-8">
                  @csrf
                   <input type="hidden" name="order_id" id="order_id" value="{{$order_item->id}}">
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-9">
                        <span>Total Ordered Qty : {{ $order_item->ordered_quantity }}</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3">
                        <label>{{trans('coupon_orders.coupon_name')}}</label>
                        <input type="text" name="coupon_name" class="form-control" id="coupon_name" value="{{$order_item->coupon->coupon->name}}" disabled/>
                      </div>
                       <div class="col-md-3">
                        <label>{{trans('coupon_orders.coupon_id')}}</label>
                        <input type="text" name="coupon_id" class="form-control" id="coupon_id" value="{{$order_item->coupon_id}}" disabled/>
                      </div>
                       <div class="col-md-3">
                        <label>{{trans('coupon_orders.coupon_discount')}}</label>
                        <input type="text" name="coupon_discount" class="form-control" id="coupon_discount" value="{{@($order_item->coupon_master->discount)?$order_item->coupon_master->discount:''}}" disabled/>
                      </div>
                      <div class="col-md-3">
                        <label>{{trans('coupon_orders.created_at')}}</label>
                        <input type="text" name="created_at" class="form-control" id="created_at" value="{{date('d-m-Y h:i A',strtotime($order_item->coupon->coupon->created_at))}}" disabled/>
                      </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                    <table class="table zero-configuration data_table_ajax">
                      <thead>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('coupon_orders.unique_code')}}</th>
                          <th width="10%">{{trans('coupon_orders.image')}}</th>
                          <th>{{trans('coupon_orders.sold_customer_name')}}</th>
                          <th>{{trans('coupon_orders.mobile_number_customer')}}</th>
                          <th>{{trans('coupon_orders.sold_to_price')}}</th>
                         </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('coupon_orders.unique_code')}}</th>
                          <th>{{trans('coupon_orders.image')}}</th>
                          <th>{{trans('coupon_orders.sold_customer_name')}}</th>
                          <th>{{trans('coupon_orders.mobile_number_customer')}}</th>
                          <th>{{trans('coupon_orders.sold_to_price')}}</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                  </div>
                </form>
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
  $(document).ready(function() {
    fill_datatable();

    function fill_datatable() {
      var order_id = $("#order_id").val();
      $('.data_table_ajax').DataTable({
        aaSorting : [[0, 'desc']],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        pageLength: 10,
        processing: true,
        serverSide: true,
        serverMethod:'POST',
        processing: true,
        bDestroy: true,
        language: {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">{{trans("common.loading")}}</span> '},
        ajax: {
            url: "{{route('dt_coupon_order_items_qr_codes')}}",
            data: {
                    "_token": "{{csrf_token()}}",
                    'order_item_id':order_id,
                   },
           //success:function(data) { console.log(data); },
            error:function(data){ console.log(data); },
        },
        columns: [
           { data: 'id'},
           { data: 'unique_code',orderable: false},
           { data: 'qr_code',orderable: false},
           { data: 'customer_name',orderable: false},
           { data: 'customer_contact',orderable: false},
           { data: 'sale_price',orderable: false},
          ]
      });
    }
  });
</script>
@endsection
