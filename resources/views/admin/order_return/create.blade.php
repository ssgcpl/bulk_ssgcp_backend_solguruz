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
                  <h2 class="content-header-title float-left mb-0">{{ trans('order_return.heading') }} </h2>
                  <div class="breadcrumb-wrapper col-12">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a
                                  href="{{ route('home') }}">{{ trans('common.home') }}</a>
                          </li>
                          <li class="breadcrumb-item"><a
                                  href="{{ route('order_return.index') }}">{{ trans('order_return.plural') }}</a></li>
                          <li class="breadcrumb-item active">{{ trans('order_return.create') }}
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
                    {{ trans('order_return.details') }}
                </h4>
                @can('order-return-list')
                    <a href="{{route('order_return.index')}}" class="btn btn-success">
                        <i class="fa fa-arrow-left"></i>
                        {{ trans('common.back') }}
                    </a>
                @endcan
            </div>
            <div class="card-content">
              <div class="card-body">
                <form method="POST" id="createForm" accept-charset="UTF-8" enctype="multipart/form-data">
                  <input type="hidden" name="order_returns_id" id="order_returns_id" value="">
                   <input type="hidden" id="total_mrp" name="total_mrp">
                  <input type="hidden" id="total_weight" name="total_weight">
                  <input type="hidden" id="total_quantity" name="total_quantity">
                  <input type="hidden" id="total_sale_price" name="total_sale_price">
                   @csrf
                    <div class="form-body">
                      <div class="row">
                        <div class="col-md-6">
                           <label>Select Customer</label>
                          <select id="user_id" name="user_id" class="select2 form-control">
                            <option value="">Select</option>
                            @foreach($customers as $customer)
                            <option value="{{$customer->id}}">{{$customer->id }},{{$customer->first_name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                           <label>Enter Barcode</label>
                           <input type="text" class="form-control" id="barcode" name="barcode">
                      
                        </div>
                      </div><br>
                      <div class="row">
                          <div class="col-md-12">
                            <h4>Return Ordered Items</h4>
                          </div>
                       </div>
                            <div class="table-responsive">
                              <table class="table zero-configuration data_table_ajax">
                                <thead>
                                  <tr>
                                    <th>{{trans('common.id')}}</th>
                                    <th>{{trans('order_return.product_name')}}</th>
                                    <th>{{trans('order_return.product_image')}}</th>
                                    <th>{{trans('order_return.mrp')}}</th>
                                    <th>{{trans('order_return.sale_price')}}</th>
                                    <th>{{trans('order_return.ordered_qty')}}</th>
                                    <th>{{trans('order_return.returnable_qty')}}</th>
                                    <th>{{trans('order_return.returned_qty')}}</th>
                                    <th>{{trans('order_return.refused_qty')}}</th>
                                    <th>{{trans('order_return.accepted_qty')}}</th>
                                    <th>{{trans('order_return.refundable_amount')}}</th>
                                    <th>{{trans('order_return.weight')}}</th>
                                    <th>{{trans('order_return.stock')}}</th>
                                    <th>{{trans('common.action')}}</th>
                                  </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <th>{{trans('common.id')}}</th>
                                    <th>{{trans('order_return.product_name')}}</th>
                                    <th>{{trans('order_return.product_image')}}</th>
                                    <th>{{trans('order_return.mrp')}}</th>
                                    <th>{{trans('order_return.sale_price')}}</th>
                                    <th>{{trans('order_return.ordered_qty')}}</th>
                                    <th>{{trans('order_return.returnable_qty')}}</th>
                                    <th>{{trans('order_return.returned_qty')}}</th>
                                    <th>{{trans('order_return.refused_qty')}}</th>
                                    <th>{{trans('order_return.accepted_qty')}}</th>
                                    <th>{{trans('order_return.refundable_amount')}}</th>
                                    <th>{{trans('order_return.weight')}}</th>
                                    <th>{{trans('order_return.stock')}}</th>  <th>{{trans('common.action')}}</th> </tfoot>
                                  </tr>
                              </table>
                            </div> 
                            <div class="row">
                            <div class="col-md-9"></div>
                            <div class="col-md-3">
                                <div class="form-group">
                                <a href="javascript:void(0)" id="create_order_return" class="form-control btn btn-success">Create Order Return </a></div>
                          </div>
                        </div>
                     <!--  <div class="order_summary"> 
                        <h5>Order Return Summary</h5>
                        <div class="row">
                          <div class="col-md-4">
                            <div id="order_summary">
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-9"></div>
                          <div class="col-md-3">
                                <div class="form-group">
                                <a href="javascript:void(0)" id="send_return_update" class="form-control btn btn-success">Update 
                                </a></div>
                          </div>
                        </div>
                      </div> -->
                      </div>
                </form>
                <!--  <h4>Notify User</h4>
                  <form method="POST" id="notifyUserForm" accept-charset="UTF-8">
                      @csrf
                      <input type="hidden" name="order_return_id" id="order_return_id" value="">
                       
                     <div class="row">
                       <div class="col-md-6">
                        <div class="form-group">
                          <label>Transaction ID</label>
                          <input type="text" class="form-control" id="transaction_id" name="transaction_id">
                        </div>
                        <div class="form-group">
                          <label>Payment Status</label>
                          <select class="form-control" id="payment_status" name="payment_status">
                            <option value="">Select</option>
                            <option value="refunded">Refunded</option>
                            <option value="pending">Pending</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label>Customer Note</label>
                          <input type="text" class="form-control" id="customer_note" name="customer_note">
                        </div>
                        <div class="form-group">
                          <label>Admin Note</label>
                          <input type="text" class="form-control" id="admin_note" name="admin_note">
                        </div>
                        <div class="form-group">
                           <input type="submit" class="btn btn-success" id="update_return_note" name="update_return_note" value="Update">
                        </div>
                        </div>
                        <div class="col-md-6">
                          <h4>Note Log</h4>
                          <div id="note_log"></div>
                        </div>
                    </div>
                  </form> -->
                
                 <h4>Barcode Scanner</h4>
                 <div id="result_strip">
                      <ul class="thumbnails"></ul>
                      <ul class="collector"></ul>
                    </div>
                    <div id="interactive" class="viewport"></div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>
</div>

<!-- update quantity popup -->
<div class="modal" tabindex="-1" role="dialog" id="update_rejected_qty_popup">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post">
        @csrf
        <div class="modal-body">
          <input type="hidden" class="" id="order_return_item_id" name="order_return_item_id" >
          <label>Enter rejected Quantity</label>
          <input type="text" class="form-control numberonly" id="edit_rejected_qty" name="edit_rejected_qty">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="update_rejected_qty">Update</button>
         </div>
      </form>
    </div>
  </div>
</div>

<div id="qr-reader" style="width: 600px"></div>

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
<!-- <script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2/dist/quagga.js"></script>
 --><!-- <script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2/dist/quagga.min.js"></script>
 --> <!-- <script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2@1.2.6/dist/quagga.js"></script>
 -->



<script type="text/javascript">
    $(document).ready(function() {

    //fill_datatable();
    function fill_datatable() {
      $('.data_table_ajax').DataTable({ 
        aaSorting : [[0, 'desc']],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "scrollY": true, "scrollX": true,
        pageLength: 10,
        processing: true,
        serverSide: true,
        serverMethod:'POST',
       // dom: 'Blfrtip',
        //processing: true,
        bDestroy: true,
        language: {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">{{trans("common.loading")}}</span>'},
        ajax: {
            url: "{{route('dt_product_list')}}",
            data: {
                    "_token": "{{csrf_token()}}",
                    "user_id":$("#user_id").val(),
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
           { data: 'returnable_qty',orderable: false},
           { data: 'returned_qty',orderable: false},
           { data: 'refused_qty',orderable: false},
           { data: 'accepted_qty',orderable: false},
           { data: 'refundable_amount',orderable: false},
           { data: 'weight',orderable: false},
           { data: 'stock',orderable: false},
           {
            data: '',
                mRender : function(data, type, row) {
                
                return '@can("order-delete")<a href="javascript:void(0)" id="'+row['id']+'" class="delete_btn"><i class="fa fa-trash"></i></a>@endcan';
      
               }, orderable: false, searchable: false
            },
          ]
      });
    }

   
    $("#update_order_return_status").click(function(evt){
        var order_return_id = $("#order_return_id").val();
        var order_return_status = $("#order_return_status").val();
        evt.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
       $.ajax({
                  url: '{{route("update_order_return_status")}}',
                  data: {
                          "order_return_id" : order_return_id,
                          "order_return_status":order_return_status,
                        },
                  type: "POST",
                  async:false,
                  beforeSend: function(xhr){
                      $('#update_order_return_status').prop('disabled',true);
                    },
                  success: function(response) {
                    if(response.error){
                      toastr.error(response.error);
                    }
                    else{
                     location.reload();
                    }

                  }
          });
    })

    $('body').on('click',".edit_rejected_qty",function(){
      var id = $(this).attr("id");
      $("#order_return_item_id").val(id);
      $("#edit_rejected_qty").val($("#rejected_qty_"+id).val());
      $("#update_rejected_qty_popup").modal("show");
    })

    $("#update_rejected_qty").click(function(evt){
       var order_return_item_id = $("#order_return_item_id").val();
       var rejected_qty = $("#edit_rejected_qty").val();
       evt.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
        $.ajax({
                  url: '{{route("update_rejected_qty")}}',
                  data: {
                          "order_return_item_id" : order_return_item_id,
                          "rejected_qty":rejected_qty,
                        },
                  type: "POST",
                  async:false,
                  beforeSend: function(xhr){
                      $('#update_supp_qty').prop('disabled',true);
                    },
                  success: function(response) {
                    if(response.error){
                      toastr.error(response.error);
                      $("#update_rejected_qty_popup").modal("hide");
                    }
                    else{
                     toastr.success(response.success);
                     fill_datatable();
                     $("#update_rejected_qty_popup").modal("hide");
                    // location.reload();
                    }

                  }
          });
    })



    $(document).on("click","#update_order_return",function(){
        update_order_summary()
    })

    function update_order_summary()
    {
            var order_return_id = $("#order_return_id").val();
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
            });
            $.ajax({

                  url: "{{route('update_order_return_summary')}}",
                  data: {'order_return_id':order_return_id},
                  type: "POST",
                  dataType : 'json',
                  beforeSend: function(xhr){
                      $('#update_order_return').prop('disabled',true);
                      $('#update_order_return').text("{{trans('common.updating')}}");
                    },
                  success: function(response) {
                      console.log(response);
                      if(response.success) {
                          toastr.success(response.success);
                      } else {
                          toastr.error(response.error);
                      }
                      $('#update_order_return').prop('disabled',false);
                      $('#update_order_return').text("{{trans('common.submit')}}");
                  }
            });
    }

    $(document).on("click","#send_return_update",function(){
          var order_return_id = $("#order_returns_id").val();
           $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
          $.ajax({

                  url: "{{route('notify_order_return_update')}}",
                  data: {'order_return_id':order_return_id},
                  type: "POST",
                  dataType : 'json',
                  beforeSend: function(xhr){
                      $('#send_return_update').prop('disabled',true);
                      $('#send_return_update').text("{{trans('common.sending')}}");
                    },
                  success: function(response) {
                      console.log(response);
                      if(response.success) {
                          toastr.success(response.success);
                          // location.reload();
                      } else {
                          toastr.error(response.error);
                      }
                      $('#send_return_update').prop('disabled',false);
                      $('#send_return_update').text("Update");
                  }
          });
    })

    $('#notifyUserForm').submit(function(evt){
          // tinyMCE.triggerSave();
          evt.preventDefault();
          var form = $('#notifyUserForm')[0];
          var data = new FormData(form);
           //alert(order_return_id);
          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
           // console.log(data); return false;
          $.ajax({
                  url: "{{route('notify_user_order_return')}}",
                  data: data,
                  type: "POST",
                  processData : false,
                  contentType : false,
                  dataType : 'json',
                  beforeSend: function(xhr){
                      $('#update_return_note').prop('disabled',true);
                      $('#update_return_note').text("{{trans('common.submitting')}}");
                    },
                  success: function(response) {
                      console.log(response);
                      if(response.success) {
                          toastr.success(response.success);
                           var html = `<p>`;
                           var date = new Date(response.order_return_note.created_at);
                           var newDate = date.getDate()+"-"+date.getMonth()+"-"+date.getYear()+" "+date.getHours()+":"+date.getMinutes();
                          if(response.order_return_note.customer_note != '') {
                              html += ` <p class="details">
                                <b><u>Customer Note  `+ newDate +`by `+response.order_return_note.added_by+`</u></b><br>
                                <span>`+response.order_return_note.customer_note +`</span><br>
                                <span>Order Return No.`+response.order_return_note.order_return_id +`  transaction ID : `+response.order_return_note.transaction_id +` and payment status is - `+response.order_return_note.payment_status +` </span><br><br>`;
                              }
                             if(response.order_return_note.admin_note != '') {
                                html  +=`<b><u>Admin Note   `+newDate+` by `+response.order_return_note.added_by+`</u></b><br>
                                  <span>`+response.order_return_note.admin_note +`</span><br>
                                  <span>Order  Return No.`+response.order_return_note.order_return_id +`  transaction ID : `+response.order_return_note.transaction_id +` and payment status is - `+response.order_return_note.payment_status +` </span><br><br>`;
                              }
                              html +=`</p>`;
                            $("#note_log").html(html);
                       //    location.reload();
                      } else {
                          toastr.error(response.error);
                      }
                      $('#update_return_note').prop('disabled',false);
                      $('#update_return_note').text("{{trans('common.submit')}}");
                  }
          });
    }) 
    $("#barcode").attr('disabled',true);
    $("#user_id").on('change',function(){
      if($("#user_id").val() != ''){
        $("#barcode").attr('disabled',false);
      }else {
        $("#barcode").attr('disabled',true);
      }
    });

    $('#barcode').on('keypress', function(event) {
    if (event.keyCode === 13) {
      event.preventDefault();

       /* if($("#user_id").val() == '') {
          $("#barcode").val('');            
          toastr.error("Please select customer first");
          return false;
        }*/
         var user_id = $("#user_id").val();
         var barcode = $("#barcode").val();
          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
          $.ajax({
                  url: '{{route("check_barcodes")}}',
                  data: { 'user_id':user_id,'barcode':barcode },
                  type: "POST",
                  async:false,
                  beforeSend: function(xhr){
                      $('#barcode').prop('disabled',true);
                    },
                  success: function(response) {
                    $("#barcode").val('');
                    if(response.success){
                      $("#total_mrp").val(response.data.total_mrp);
                      $("#total_sale_price").val(response.data.total_sale_price);
                      $("#total_weight").val(response.data.total_weight);
                      $("#total_quantity").val(response.data.total_qty);
                      //barcodes.push(response.data);
                      fill_datatable();
                    }else {
                      toastr.error(response.error);
                    }
                    $("#barcode").prop('disabled',false);
                   
                   /* if(response.id) {
                     
                      fill_datatable();
                      update_order_summary();
                      var html = ` <p class="details"><b>Total MRP :</b>`+response.order_summary.total_sale_price+` <br>
                                    <b>Total Sale Price :</b> `+response.order_summary.total_sale_price+`  <br>
                                    <b>Payment via : </b>`+response.order_summary.payment_type+` <br>
                                    <b>Total Accepted Quantity :</b> `+response.order_summary.accepted_quantity+` <br>
                                    <b>Total Rejected Quantity :</b> `+response.order_summary.rejected_quantity+` <br>
                                    <b>Total Weight (in KG) : </b>`+response.order_summary.total_weight+` <br>
                                   <b>Total Returnable Amount : </b>`+response.order_summary.accepted_sale_price+` <br>
                                  </p>`;
                          $("#order_summary").html(html);
                      // toastr.success(response.success);
                    }
                  */
                
                  }
          });
        }
    })

        
    $("#create_order_return").click(function(evt){
       var user_id = $("#user_id").val();
       var total_sale_price = $("#total_sale_price").val();
       var total_weight = $("#total_weight").val();
       var total_quantity = $("#total_quantity").val();
       var total_mrp = $("#total_mrp").val();
       evt.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
        $.ajax({
                  url: '{{route("create_order_returns_item")}}',
                  data: {
                          "user_id" : user_id,
                          "total_mrp":total_mrp,
                          "total_quantity":total_quantity,
                          "total_sale_price":total_sale_price,
                          "total_weight":total_weight
                        },
                  type: "POST",
                  async:false,
                  beforeSend: function(xhr){
                      $('#create_order_return').prop('disabled',true);
                    },
                  success: function(response) {
                    if(response.error){
                      toastr.error(response.error);
                    }
                    else{
                     toastr.success(response.success);
                     var route = "{{route('order_return.edit',':id')}}";
                     route = route.replace(':id',response.id);
                     location.href = route;
                      $("#order_returns_id").val(response.id);  
                      $("#order_return_id").val(response.id);  
                   
                  /*   var html = ` <p class="details"><b>Total MRP :</b>`+response.order_summary.total_mrp+` <br>
                                    <b>Total Sale Price :</b> `+response.order_summary.total_sale_price+`  <br>
                                    <b>Payment via : </b>`+response.order_summary.payment_type+` <br>
                                    <b>Total Accepted Quantity :</b> `+response.order_summary.accepted_quantity+` <br>
                                    <b>Total Rejected Quantity :</b> `+response.order_summary.rejected_quantity+` <br>
                                    <b>Total Weight (in KG) : </b>`+response.order_summary.total_weight+` <br>
                                   <b>Total Returnable Amount : </b>`+response.order_summary.total_sale_price+` <br>
                                  </p>`;
                          $("#order_summary").html(html);*/
                    }

                  }
        });
    })
    
     $(document).on("click",'.delete_btn',function(){
        var user_id = $("#user_id").val();
        var product_id = $(this).attr('id');
        var route = "{{ route('delete_verified_product',':id')}}";
        route = route.replace(':id',product_id);
      if(confirm("{{trans('common.confirm_delete')}}")){
        $.ajax({
                  url: route,
                  data: { 'user_id':user_id },
                  type: "POST",
                  async:false,
                  beforeSend: function(xhr){
                      $('#create_order_return').prop('disabled',true);
                    },
                  success: function(response) {
                    if(response.success){
                      toastr.success(response.success);
                      fill_datatable();
                      $("#total_mrp").val(response.data.total_mrp);
                      $("#total_sale_price").val(response.data.total_sale_price);
                      $("#total_weight").val(response.data.total_weight);
                      $("#total_quantity").val(response.data.total_qty);
                      //update_order_summary();
                    }
                    else{
                     toastr.error(response.error);
                    // location.reload();
                    }

                  }
        });
      }
      else {
        return false;
      }
     });        

    })
</script>
 
<script type="text/javascript">
   function delete_alert() {
   
    }
</script>

<script type="text/javascript">
/*Quagga.decodeSingle({
    decoder : {
      readers : ["code_128_reader"]
    }, 
    halfSample:true,
    size: 800,
    debug: {
    showCanvas: false,
    showPatches: false,
    showFoundPatches: false,
    showSkeleton: false,
    showLabels: false,
    showPatchLabels: false,
    showRemainingPatchLabels: false,
    boxFromPatches: {
      showTransformed: false,
      showTransformedBox: false,
      showBB: false
    }
  },
    locate: true, // try to locate the barcode in the image
    src: 'http://localhost/ssgc-bulk-order-web/public/uploads/bar_codes/3fc9d3cb51be8e9f5a5fbf8c0772efaa.jpg' // or 'data:image/jpg;base64,' + data
   //src :'data:image/jpg;base64,' + data
}, function(result){
console.log(result);

    if(result.codeResult) {
        console.log("result", result.codeResult.code);
    } else {
        console.log("not detected");
    }
});*/



/* Quagga.init({
    inputStream : {
      name : "Live",
      type : "LiveStream",
      target: document.querySelector('#barcode')    // Or '#yourElement' (optional)
    },
    decoder : {
      readers : ["code_128_reader"]
    }
  }, function(err) {
      if (err) {
          console.log(err);
          return
      }
      console.log("Initialization finished. Ready to start");
      Quagga.start();
  });*/

</script> 
@include('layouts.admin.elements.live_stream_js')
@include('layouts.admin.elements.filepond_js')
@endsection
