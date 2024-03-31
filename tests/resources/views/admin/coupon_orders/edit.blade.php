@extends('layouts.admin.app')
@section('vendor_css')
<link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
<style type="text/css">
  td form{
    display: inline;
  }
  .dt-buttons{margin-right: 10px}
  .select2-selection {padding: 0px 5px !important}
  .order_summary { background-color:#ececec; padding: 10px;}
</style>
@endsection
@section('css')
@include('layouts.admin.elements.filepond_css')
@endsection
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
          <div class="row breadcrumbs-top">
              <div class="col-12">
                  <h2 class="content-header-title float-left mb-0">{{ trans('orders.heading') }} </h2>
                  <div class="breadcrumb-wrapper col-12">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a
                                  href="{{ route('home') }}">{{ trans('common.home') }}</a>
                          </li>
                          <li class="breadcrumb-item"><a
                                  href="{{ route('orders.index') }}">{{ trans('orders.plural') }}</a></li>
                          <li class="breadcrumb-item active">{{ trans('orders.update') }}
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
                    {{ trans('orders.details') }}
                </h4>
                @can('order-list')
                    <a href="{{route('orders.index')}}" class="btn btn-success">
                        <i class="fa fa-arrow-left"></i>
                        {{ trans('common.back') }}
                    </a>
                @endcan
            </div>
            <div class="card-content">
              <div class="card-body">
                <form method="POST" id="createForm" accept-charset="UTF-8" enctype="multipart/form-data">
                  <input type="hidden" name="order_id" id="order_id" value="{{$order->id}}">
                    @csrf
                    <div class="form-body">
                      <div class="row">
                          <div class="col-md-2"><span><b>Order ID</b> - {{$order->id}}</span><br>
                            <span><b>Customer ID</b> - {{$order->user_id}}</span></div>
                          <div class="col-md-4"><span><b>Order Date/Time</b> - {{date("d-m-Y h:i A",strtotime($order->placed_at))}}</span>
                               <span><b>Order Type</b> - {{ucfirst(str_replace('_',' ',$order->order_type))}}</span>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="">{{trans('orders.order_status')}}</label>
                              <select id="order_status" class = "form-control" name=''>
                                <option value=''>{{trans('common.select')}}</option>
                                <option value="pending_payment" {{@($order->order_status=='pending_payment') ? 'selected':''}}>{{trans('orders.pending_payment') }}</option>
                               <!--  <option value="on_hold" {{@($order->order_status=='on_hold') ? 'selected':'' }}>{{trans('orders.on_hold') }}</option>
                                <option value="processing"  {{@($order->order_status=='processing') ? 'selected':'' }}>{{trans('orders.processing') }}</option>
                                <option value="shipped"  {{@($order->order_status=='shipped') ? 'selected':'' }}>{{trans('orders.shipped') }}</option>-->
                                <option value="completed"  {{@($order->order_status=='completed') ? 'selected':'' }}>{{trans('orders.completed') }}</option>
                                <option value="cancelled"  {{@($order->order_status=='cancelled') ? 'selected':'' }}>{{trans('orders.cancelled') }}</option>
                                <option value="{{@($coupon_code_sold >= 1) ? 'partially_refunded' :'refunded'}}" {{ @($order->order_status=='refunded' ) ? 'selected':'' }}> {{ @($coupon_code_sold >= 1) ? trans('orders.partially_refunded') :trans('orders.refunded') }}</option>
                              </select>
                            </div>
                          </div>
                            <div class="col-md-2">
                              <label></label>
                              <div class="form-group">
                              <a href="javascript:void(0)" id="update_status" class="form-control btn btn-success">Update
                              </a></div>
                            </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                        <h4>Ordered Items</h4>
                            <table class="table zero-configuration data_table_ajax">
                              <thead>
                                <tr>
                                  <th>{{trans('common.id')}}</th>
                                  <th>{{trans('orders.product_name')}}</th>
                                  <th width="10%">{{trans('orders.product_image')}}</th>
                                  <th>{{trans('orders.mrp')}}</th>
                                  <th>{{trans('orders.sale_price')}}</th>
                                  <th>{{trans('orders.ordered_qty')}}</th>
                                  {{-- <th>{{trans('orders.weight')}}</th> --}}
                                  {{-- <th>{{trans('orders.supplied_qty')}}</th> --}}
                                  <th>{{trans('orders.total_sale_price_quantity')}}
                                  {{-- <th>{{trans('orders.available_stock')}}</th> --}}
                                  <th>{{trans('common.action')}}</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th>{{trans('common.id')}}</th>
                                  <th>{{trans('orders.product_name')}}</th>
                                  <th>{{trans('orders.product_image')}}</th>
                                  <th>{{trans('orders.mrp')}}</th>
                                  <th>{{trans('orders.sale_price')}}</th>
                                  <th>{{trans('orders.ordered_qty')}}</th>
                                  {{-- <th>{{trans('orders.weight')}}</th> --}}
                                  {{-- <th>{{trans('orders.supplied_qty')}}</th> --}}
                                  <th>{{trans('orders.total_sale_price_quantity')}}</th>
                                  {{-- <th>{{trans('orders.available_stock')}}</th> --}}
                                  <th>{{trans('common.action')}}</th> </tfoot>
                            </table>
                        </div>
                      </div>
                      <br>
                      <div class="order_summary">
                      <h5>Order Summary</h5>
                      <div class="row">
                        <div class="col-md-6">
                            <p class="details"><b>Total MRP :</b>  {{$order->total_mrp}}<br>
                              <b>Discount Avail on MRP:</b> {{ $order->discount_on_mrp }} <br>
                              <b>Point Discount:</b> {{ $order->redeemed_points_discount }} <br>
                              <b>Payment via : </b>{{$order->payment_type}}<br>
                              <b>Total Payable Amount : </b>  {{$order->total_payable }}<br>
                              </p>
                            @if($order->order_status == 'refunded' || $order->order_status == 'partially_refunded')
                            <hr>
                            <h5>Refund Summary</h5>
                             <b>Refunded Points:</b> {{ $order->refunded_points }} <br>
                              <b>Refunded Amount:</b> {{ $order->refunded_amount }} <br>
                              @endif
                            <p></p>  
                        </div>
                       <div class="col-md-6">
                           <p class="details">
                          Transaction ID :   <br>
                          <input type="text" class="form-control" name="transaction_id" id="transaction_id" value="{{$order->transaction_id}}" disabled>
                          <label></label>
                          <br>
                          Payment Status :   <br>
                            <input type="text" class="form-control" name="payment_status" id="payment_status" value="{{$order->payment_status}}" disabled>
                        <!--   <select name="payment_status" id="payment_status" class="form-control">
                            <option value="pending" {{@($order->payment_status =='pending')? 'selected':''}}>{{trans('orders.pending')}}</option>
                            <option value="paid" {{@($order->payment_status =='paid')? 'selected':''}}>{{trans('orders.paid')}}</option>
                            <option value="failed" {{@($order->payment_status =='failed')? 'selected':''}}>{{trans('orders.failed')}}</option>
                          </select>
 -->
                        </p>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-8"></div>

                        <div class="col-md-4">
                              <div class="form-group">
                              <a href="javascript:void(0)" id="send_update" class="form-control btn btn-success">Send Update
                              </a></div>
                        </div>
                      </div>
                    </div>
                    </div>

                    <div class="modal-footer">
                    </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
<div class="modal" tabindex="-1" role="dialog" id="partial_refund_popup">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="refundForm">
        @csrf
        <input type="hidden" name="order_id" id="order_id"  value="{{$order->id}}">
        <div class="modal-body">
        <h4>Add Refund details</h4>
            <table>
              <tr><td>Redeemed Points</td> <td>{{$order->redeemed_points}}</td></tr>
              <tr><td>Redeemed Points Discount</td> <td>{{$order->redeemed_points_discount}}</td></tr>
              <tr>
                <td>Refunded Points</td>
                <td><input type="text" name="refunded_points" id="refunded_points"  class="form-control" value="{{@($order->redeemed_points == '0' || $order->redeemed_points == 'NULL') ? '0':''}}"></td>
              </tr>
              <tr>
                <td>Refunded Amount</td>
                <td><input type="text" name="refunded_amount" id="refunded_amount"  class="form-control" value="{{@($order->redeemed_points_discount == '0' || $order->redeemed_points == 'NULL') ? '0':''}}"></td>
              </tr>
            </table>
            <input type="submit" class="btn btn-success" id="update_refund_details">
         </div>
        <div class="modal-footer">
        </div>
      </form>
    </div>
  </div>
</div>
  </div>
</div>

@endsection

@section('page_js')
<!-- BEGIN: Page Vendor JS-->
<script src="{{asset('admin_assets/app-assets/vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('admin_assets/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js')}}"></script>
<script src="{{asset('admin_assets/app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('admin_assets/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js')}}"></script>
<!-- END: Page Vendor JS-->
@endsection


@section('js')
<script src="{{asset('admin_assets/custom/data_tables/export/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('admin_assets/custom/data_tables/export/jszip.min.js')}}"></script>
<script src="{{asset('admin_assets/custom/data_tables/export/buttons.html5.min.js')}}"></script>
<script src="{{asset('admin_assets/custom/data_tables/export/buttons.print.min.js')}}"></script>

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
            url: "{{route('dt_coupon_order_items')}}",
            data: {
                    "_token": "{{csrf_token()}}",
                    'order_id':order_id,
                   },
           //success:function(data) { console.log(data); },
            error:function(data){ console.log(data); },
        },
        columns: [
           { data: 'id'},
           { data: 'product_name',orderable: false},
           { data: 'product_image',orderable: false},
           { data: 'mrp',orderable: false},
           { data: 'sale_price',orderable: false},
           { data: 'ordered_qty',orderable: false},
        //    { data: 'weight',orderable: false},
        //    { data: 'supplied_qty',orderable: false},
           { data: 'total_sale_price',orderable: false},
        //    { data: 'available_stock',orderable: false},
           {
            data: '',
                mRender : function(data, type, row) {

                return '<a class="" href="'+row["show"]+'"><i class="fa fa-eye"></i></a>';

               }, orderable: false, searchable: false
            },
          ]
      });
    }

    $("#update_status").click(function(evt){
       var order_id = $("#order_id").val();
       var order_status = $("#order_status").val();
       if(order_status == 'partially_refunded'){
        $("#partial_refund_popup").modal("show");
        return false;
       }
       evt.preventDefault();
       $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
       $.ajax({
                  url: '{{route("update_order_status")}}',
                  data: {
                          "order_id" : order_id,
                          "order_status":order_status,
                        },
                  type: "POST",
                  async:false,
                  beforeSend: function(xhr){
                      $('#update_status').prop('disabled',true);
                    },
                  success: function(response) {
                    if(response.error){
                      toastr.error(response.error);
                      location.reload();
                    }
                    else{
                     location.reload();
                    }

                  }
          });
    })

    $('body').on('click',".edit_supplied_qty",function(){
      var id = $(this).attr("id");
      $("#order_item_id").val(id);
      $("#edit_supplied_qty").val($("#supplied_qty_"+id).val());
      $("#update_supplied_qty_popup").modal("show");
    })

    $("#update_supp_qty").click(function(evt){
       var order_item_id = $("#order_item_id").val();
       var supplied_qty = $("#edit_supplied_qty").val();
       evt.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
        $.ajax({
                  url: '{{route("update_supplied_qty")}}',
                  data: {
                          "order_item_id" : order_item_id,
                          "supplied_qty":supplied_qty,
                        },
                  type: "POST",
                  async:false,
                  beforeSend: function(xhr){
                      $('#update_supp_qty').prop('disabled',true);
                    },
                  success: function(response) {
                    if(response.error){
                      toastr.error(response.error);
                    }
                    else{
                     toastr.success(response.success);
                     location.reload();
                    }

                  }
          });
    })

    $("#add_item").click(function(){
      $("#add_more_item_popup").modal("show");
       $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
        $.ajax({
                  url: '{{route("add_more_item")}}',
                  data: {},
                  type: "POST",
                  async:false,
                  beforeSend: function(xhr){
                    },
                  success: function(response) {
                    if(response.success){
                      var html = '';
                      $.each(response.products, function(key,value) {
                        html+='<option value="'+value.id+'"> '+value.name_english+'</option>';
                      });
                      $("#product_id").append(html);
                   //   toastr.success(response.success);
                    }
                    else{
                       toastr.error(response.error);
                    }

                  }
          });
    });

    $(document).on("click","#add_more_item_btn",function(){
      var order_id = $("#order_id").val();
      var product_id = $("#product_id option:selected").val();
      var ordered_qty = $("#ordered_qty").val();
      //console.log(order_id+"/"+product_id+"/"+ordered_qty);
       $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
      $.ajax({
                  url: '{{route("add_order_item")}}',
                  data: { 'order_id' : order_id , 'product_id':product_id ,'ordered_quantity':ordered_qty},
                  type: "POST",
                  async:false,
                  beforeSend: function(xhr){
                    },
                  success: function(response) {
                    if(response.success){
                      toastr.success(response.success);
                      location.reload();
                    }
                    else{
                       toastr.error(response.error);
                    }

                  }
          });
    })

    $('#createForm').submit(function(evt){
          evt.preventDefault();
          var form = $('#createForm')[0];
          var data = new FormData(form);
          // console.log(data); return false;
          $.ajax({
                  url: "{{route('update_shipping_address')}}",
                  data: data,
                  type: "POST",
                  processData : false,
                  contentType : false,
                  dataType : 'json',
                  beforeSend: function(xhr){
                      $('#action_btn').prop('disabled',true);
                      $('#action_btn').text("{{trans('common.submitting')}}");
                    },
                  success: function(response) {
                      console.log(response);
                      if(response.success) {
                          toastr.success(response.success);
                           location.reload();
                      } else {
                          toastr.error(response.error);
                      }
                      $('#action_btn').prop('disabled',false);
                      $('#action_btn').text("{{trans('common.submit')}}");
                  }
          });
          //console.log('data',data)
    })

    $(document).on("click","#update_order",function(){
          var order_id = $("#order_id").val();
           $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
          $.ajax({

                  url: "{{route('update_order_summary')}}",
                  data: {'order_id':order_id},
                  type: "POST",
                  dataType : 'json',
                  beforeSend: function(xhr){
                      $('#action_btn').prop('disabled',true);
                      $('#action_btn').text("{{trans('common.submitting')}}");
                    },
                  success: function(response) {
                      console.log(response);
                      if(response.success) {
                          toastr.success(response.success);
                           location.reload();
                      } else {
                          toastr.error(response.error);
                      }
                      $('#action_btn').prop('disabled',false);
                      $('#action_btn').text("{{trans('common.submit')}}");
                  }
          });
    })

    $(document).on("click","#send_update",function(){
          var order_id = $("#order_id").val();
           $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
          $.ajax({

                  url: "{{route('notify_order_update')}}",
                  data: {'order_id':order_id},
                  type: "POST",
                  dataType : 'json',
                  beforeSend: function(xhr){
                      $('#send_update').prop('disabled',true);
                      $('#send_update').text("{{trans('common.sending')}}");
                    },
                  success: function(response) {
                      console.log(response);
                      if(response.success) {
                          toastr.success(response.success);
                           location.reload();
                      } else {
                          toastr.error(response.error);
                      }
                      $('#send_update').prop('disabled',false);
                      $('#send_update').text("Send Update");
                  }
          });
    })

    $('#refundForm').submit(function(evt){
          // tinyMCE.triggerSave();
          evt.preventDefault();
          var form = $('#refundForm')[0];
          var data = new FormData(form);

          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
           // console.log(data); return false;
          $.ajax({
                  url: "{{route('update_refund_details')}}",
                  data: data,
                  type: "POST",
                  processData : false,
                  contentType : false,
                  dataType : 'json',
                  beforeSend: function(xhr){
                      $('#action_btn').prop('disabled',true);
                      $('#action_btn').text("{{trans('common.submitting')}}");
                    },
                  success: function(response) {
                      console.log(response);
                      if(response.success) {
                          toastr.success(response.success);
                          location.reload();
                      } else {
                          toastr.error(response.error);
                      }
                      $('#action_btn').prop('disabled',false);
                      $('#action_btn').text("{{trans('common.submit')}}");
                  }
          });
    })

   /* $(document).on('click',".edit_address",function(){
      var id = $(this).attr("id");
      $("#shipping_address_popup").modal("show");
    })

    $(document).on('click',".edit_billing_address",function(){
      var id = $(this).attr("id");
      $("#billing_address_popup").modal("show");
    })
*/
    $('#addressForm').submit(function(evt){
          // tinyMCE.triggerSave();
          evt.preventDefault();
          var form = $('#addressForm')[0];
          var data = new FormData(form);

          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
           // console.log(data); return false;
          $.ajax({
                  url: "{{route('update_shipping_address')}}",
                  data: data,
                  type: "POST",
                  processData : false,
                  contentType : false,
                  dataType : 'json',
                  beforeSend: function(xhr){
                      $('#action_btn').prop('disabled',true);
                      $('#action_btn').text("{{trans('common.submitting')}}");
                    },
                  success: function(response) {
                      console.log(response);
                      if(response.success) {
                          toastr.success(response.success);
                           location.reload();
                      } else {
                          toastr.error(response.error);
                      }
                      $('#action_btn').prop('disabled',false);
                      $('#action_btn').text("{{trans('common.submit')}}");
                  }
          });
    })

    $('#BillingaddressForm').submit(function(evt){
          // tinyMCE.triggerSave();
          evt.preventDefault();
          var form = $('#BillingaddressForm')[0];
          var data = new FormData(form);

          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
           // console.log(data); return false;
          $.ajax({
                  url: "{{route('update_billing_address')}}",
                  data: data,
                  type: "POST",
                  processData : false,
                  contentType : false,
                  dataType : 'json',
                  beforeSend: function(xhr){
                      $('#action_btn').prop('disabled',true);
                      $('#action_btn').text("{{trans('common.submitting')}}");
                    },
                  success: function(response) {
                      console.log(response);
                      if(response.success) {
                          toastr.success(response.success);
                           location.reload();
                      } else {
                          toastr.error(response.error);
                      }
                      $('#action_btn').prop('disabled',false);
                      $('#action_btn').text("{{trans('common.submit')}}");
                  }
          });
    })


    $('#notifyUserForm').submit(function(evt){
          // tinyMCE.triggerSave();
          evt.preventDefault();
          var form = $('#notifyUserForm')[0];
          var data = new FormData(form);

          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
           // console.log(data); return false;
          $.ajax({
                  url: "{{route('notify_user')}}",
                  data: data,
                  type: "POST",
                  processData : false,
                  contentType : false,
                  dataType : 'json',
                  beforeSend: function(xhr){
                      $('#action_btn').prop('disabled',true);
                      $('#action_btn').text("{{trans('common.submitting')}}");
                    },
                  success: function(response) {
                      console.log(response);
                      if(response.success) {
                          toastr.success(response.success);
                           location.reload();
                      } else {
                          toastr.error(response.error);
                      }
                      $('#action_btn').prop('disabled',false);
                      $('#action_btn').text("{{trans('common.submit')}}");
                  }
          });
    })

    })
  </script>
  <script type="text/javascript">
   function delete_alert() {
      if(confirm("{{trans('common.confirm_delete')}}")){
        return true;
      }else{
        return false;
      }
    }
</script>

  @include('layouts.admin.elements.filepond_js')
@endsection
