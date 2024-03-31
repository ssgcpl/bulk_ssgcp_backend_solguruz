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
  .errspan {
        float: right;
        margin-right: 6px;
        margin-top: -20px;
        position: relative;
        z-index: 2;
        
    }
    .note_div { max-height: 350px; overflow: auto; }
</style>
@endsection
@section('css')
<style type="text/css">
   .cover_images {width: 40%}
</style>
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
                    <a href="{{route('pending_orders.index')}}" class="btn btn-success">
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
                          <div class="col-md-2"> <label></label><div class="form-group"><span><b>Order ID</b> - {{$order->id}}</span><br>
                             <span><b>Customer ID</b> - {{$order->user_id}}</span> 
                          </div></div>

                          <div class="col-md-4"> <label></label><div class="form-group"><span><b>Order Date/Time</b> - {{date("d-m-Y h:i A",strtotime($order->placed_at))}}</span></div></div>
                          
                      </div>
                      <div class="row">
                        <div class="col-md-2"><h4>Ordered Items</h4></div>
                      </div>
                      <div class="table-responsive">
                        <table class="table zero-configuration data_table_ajax">
                          <thead>
                            <tr>
                              <th>{{trans('common.id')}}</th>
                              <th>{{trans('orders.product_name')}}</th>
                              <th>{{trans('orders.product_image')}}</th>
                              <th>{{trans('orders.mrp')}}</th>
                              <th>{{trans('orders.sale_price')}}</th>
                              <th>{{trans('orders.ordered_qty')}}</th>
                              <th>{{trans('orders.weight')}}</th>
                              <th>{{trans('orders.supplied_qty')}}</th>
                              <th>{{trans('orders.total_sale_price')}}</th>
                              <th>{{trans('orders.available_stock')}}</th>
                              <!-- <th>{{trans('common.action')}}</th> -->
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
                              <th>{{trans('orders.weight')}}</th>
                              <th>{{trans('orders.supplied_qty')}}</th>
                              <th>{{trans('orders.total_sale_price')}}</th>
                              <th>{{trans('orders.available_stock')}}</th>
                            <!--   <th>{{trans('common.action')}}</th> --> </tfoot>
                        </table>
                      </div>
                     <div class="row">
                    <!--     <div class="col-md-7"></div>
                         <div class="col-md-2">
                              <div class="form-group">
                              <a href="javascript:void(0)" id="add_item" class="form-control btn btn-success">Add Item
                              </a></div>
                        </div>
                        <div class="col-md-3">
                              <div class="form-group">
                              <a href="javascript:void(0)" id="update_order" class="form-control btn btn-success">Update Order
                              </a></div -->
                        </div>
                      </div>

                      <div class="order_summary"> 
                      <h5>Order Summary</h5>
                          
                      <div class="row">
                        <div class="col-md-4">
                            <p class="details"><b>Total MRP :</b>  {{$order->total_mrp}}<br>
                              <b>Discount Avail :</b> {{ $order->discount_on_mrp }} <br>
                              <b>Payment via : </b>{{($order->payment_type)? ($order->payment_type):'Offline'}}<br>
                              <b>Delivery Charges for Dealer/Retailer : </b> {{$order->delivery_charges}} <br>
                              <b>Total Payable Amount : </b>  {{$order->total_payable }}<br>
                              <b>Total Weight (in KG) </b> : {{number_format($order->total_weight,'2','.',',')}}<br>
                              <b> Total Supply Quantity </b>: {{ $total_supplied_quantity }}<br>
                            <!--   Bundles : <input type="text" class="form-control" name="bundles" id="bundles" value="{{$order->bundles}}"> -->
                            </p>
                        </div>
                        <div class="col-md-4">
                          @if($user_type == 'dealer')
                          <p class="details">
                          Delivery Charges For Dealers :   <br>
                          <label></label>
                          <input type="text" class="form-control" name="delivery_charges" id="delivery_charges" value="{{$order->delivery_charges}}" >
                          <label></label>
                          </p>
                          @endif
                        </div>
                      
                       </div>
                      <div class="row">
                      </div>
                  
                    </div>
           
                    <div class="modal-footer">
                    </div>
                </form>
                  <div class="form-group">
                          <label for="status" class="content-label">Payments</label>
                          <table class="table">
                            <tr>
                              <th>sr no</th>
                              <th>Order Id</th>
                              <th>Amount</th>
                              <th>Payment Type</th>
                              <th>Reference number</th>
                              <th>Response</th>    
                              <th>Status</th>    
                              <th>Action</th>    
                              <th>Action</th>      
                            </tr>
                            @foreach($all_payments as $key => $value)
                            <tr>
                              <td>{{++$key}}</td>
                              <td>{{$value->order_id}}</td>
                              <td>{{$value->amount}}</td>
                              <td>{{$value->payment_type}}</td>
                              <td>{{$value->tran_ref}}</td>
                              <td title="{{$value->api_response}}">
                                @if(isset($value->api_response) && $value->api_response != '')
                                <button type="button"data-toggle="modal" data-target="#resModel-{{$value->id}}"><i class="fa fa-eye"></i></button>  
                                <div class="modal fade" id="resModel-{{$value->id}}" role="dialog">
                                  <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h4 class="modal-title">API Response</h4>
                                      </div>
                                      <div class="modal-body">
                                        <p>{{$value->api_response}}</p>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                @endif
                              </td>  
                              <td>{{$value->status}}</td>
                              <td>
                                @if($value->order->is_payment_attempt == '1')
                                <button type="button" class="btn btn-success mark_as_paid" data-payment-id = "{{$value->id}}"> 
                                  Mark As paid
                                </button>
                                @endif
                              </td>
                               <td>
                                @if($value->order->is_payment_attempt == '1')
                                  <button type="button" class="btn btn-danger mark_as_failed" data-payment-id = "{{$value->id}}"> 
                                  Mark As failed
                                  </button>
                                @endif
                              </td>

                            </tr>
                            @endforeach
                          </table>                         
                  </div>
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
            url: "{{route('dt_order_items')}}",
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
           { data: 'product_image',
            mRender : function(data, type, row) { 
                return row['product_image'];
              },orderable: false, searchable: false 
        },
           { data: 'mrp',orderable: false},
           { data: 'sale_price',orderable: false},
           { data: 'ordered_qty',orderable: false},
           { data: 'weight',orderable: false},
           { data: 'supplied_qty',orderable: false},
           { data: 'total_sale_price',orderable: false},
           { data: 'available_stock',orderable: false},
          /* {
            data: '',
                mRender : function(data, type, row) {
                
                return '<a href="'+row['view_product']+'"><i class="fa fa-eye"></i></a> @can("order-delete")<a class="" href="#" onclick=" return delete_alert('+row["id"]+') "><i class="fa fa-trash"></i><form action="'+row["delete"]+'"  id="form_'+row['id']+'" method="post">@method("delete")@csrf</form></a>@endcan';
      
               }, orderable: false, searchable: false
            },*/
          ]
      });
    }

  $('.mark_as_paid').click(function() {
    if(confirm("Are you sure you want to Mark this orders as paid and place the order?")){
      var payment_id = $(this).attr('data-payment-id');
      var this_el =  $(this);
      $.ajax({
        url: "{{route('mark_order')}}",
        data: {
          'payment_id' : payment_id,
          "_token": "{{ csrf_token() }}"
        },
        type: "post",
        dataType : 'json',
        beforeSend: function(xhr){
          $(this).attr('disabled',true);
        },
        success: function(response) {
          if(response.success) {
            toastr.success(response.success);
            this_el.hide();
            location.reload();
          } else {
            toastr.error(response.error);
          }
        }
      });
    }
  })

  $('.mark_as_failed').click(function() {
    if(confirm("Are you sure you want to Mark this orders as failed and remove the order?")){
      var payment_id = $(this).attr('data-payment-id');
      var this_el =  $(this);
      $.ajax({
        url: "{{route('mark_order_failed')}}",
        data: {
          'payment_id' : payment_id,
          "_token": "{{ csrf_token() }}"
        },
        type: "post",
        dataType : 'json',
        beforeSend: function(xhr){
          $(this).attr('disabled',true);
        },
        success: function(response) {
          if(response.success) {
            toastr.success(response.success);
            this_el.hide();
            $('button.mark_as_paid').hide();
            location.reload();
          } else {
            toastr.error(response.error);
          }
        }
      });
    }
  })

    $("#update_status").click(function(evt){
       var order_id = $("#order_id").val();
       var order_status = $("#order_status").val();
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
                    }
                    else{
                      toastr.success(response.success);
                     location.reload();
                    }

                  }
          });
    })

    $(".edit_supplied_qty").css('display','none');
  

   /* $('body').on('click',".edit_supplied_qty",function(){
      var id = $(this).attr("id");
      $("#order_item_id").val(id);
      $("#edit_supplied_qty").val($("#supplied_qty_"+id).val());
      $("#update_supplied_qty_popup").modal("show");
    })*/

    $("#update_supp_qty").click(function(evt){
       var order_item_id = $("#order_item_id").val();
       var supplied_qty = $("#edit_supplied_qty").val();
       if(supplied_qty == '') {
        toastr.error("Please add supplied qty first");
        return false;
       }
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
                        html+='<option value="'+value.id+'"> '+value.id+','+value.name+',(SKU:'+value.sku_id+')</option>';
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

    $('#add_detail').click(function(){
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
          var order_id = $("#order_id").val();
          var bundles = $("#bundles").val();
          var transaction_id = $("#transaction_id").val();
          var payment_status = $("#payment_status").val();
          var delivery_charges = $("#delivery_charges").val();
          $.ajax({
                  url: "{{route('update_order_detail')}}",
                  data: {'order_id':order_id ,'bundles':bundles,'payment_status':payment_status,'transaction_id':transaction_id,'delivery_charges':delivery_charges},
                  type: "POST",
                  async:false,
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



    $(document).on('click',".edit_address",function(){
      var id = $(this).attr("id");
      $("#shipping_address_popup").modal("show");
    })

    $(document).on('click',".edit_billing_address",function(){
      var id = $(this).attr("id");
      $("#billing_address_popup").modal("show");
    })

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

    $("#update_note").prop('disabled',true);
    $("#notifyUserForm input[type=text]").keyup(function(){
      if($("#courier_name").val()!='' || $("#tracking_number").val()!='' || $("#customer_note").val()!='' || $("#admin_note").val()!=''){
        $("#update_note").prop('disabled',false);
      }else {
        $("#update_note").prop('disabled',true);
      }
    })

/*    $("#update_note").on("click",function(){
        if($("#courier_name").val()!='' || $("#tracking_number").val()!='' || $("#customer_note").val()!='' || $("#admin_note").val()!=''){
             toastr.error("Please fill any of the details to notify user");
             return false;
          }
        });
*/
    $('#notifyUserForm').submit(function(evt){
          // tinyMCE.triggerSave();
          if($("#customer_note").val() == '' && $("#admin_note").val() == ''){
            toastr.error("Please add atleast one note");
            return false;
          }
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
                      $('#update_note').prop('disabled',true);
                      $('#update_note').val("{{trans('common.updating')}}");
                    },
                  success: function(response) {
                      console.log(response);
                      if(response.success) {
                          toastr.success(response.success);
                           location.reload();
                      } else {
                          toastr.error(response.error);
                      }
                      $('#update_note').prop('disabled',false);
                      $('#update_note').val("{{trans('common.update')}}");
                  }
          });
    }) 

    })
  </script>
  <script type="text/javascript">
   function delete_alert(id) {
      if(confirm("{{trans('common.confirm_delete')}}")){
        $("#form_"+id).submit();
        return true;
      }else{
        return false;
      }
    }
</script>
 
  @include('layouts.admin.elements.filepond_js')
@endsection
