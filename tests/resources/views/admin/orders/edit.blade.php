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
                          <div class="col-md-2"> <label></label><div class="form-group"><span><b>Order ID</b> - {{$order->id}}</span><br>
                             <span><b>Customer ID</b> - {{$order->user_id}}</span> 
                          </div></div>

                          <div class="col-md-4"> <label></label><div class="form-group"><span><b>Order Date/Time</b> - {{date("d-m-Y h:i A",strtotime($order->placed_at))}}</span></div></div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="">{{trans('orders.order_status')}}</label>
                              <select id="order_status" class = "form-control" name=''>
                                <option value=''>{{trans('common.select')}}</option>
                                <option value="pending_payment" {{@($order->order_status=='pending_payment') ? 'selected':''}}>{{trans('orders.pending_payment') }}</option>
                                <option value="on_hold" {{@($order->order_status=='on_hold') ? 'selected':'' }}>{{trans('orders.on_hold') }}</option>
                                <option value="processing"  {{@($order->order_status=='processing') ? 'selected':'' }}>{{trans('orders.processing') }}</option>
                                <option value="shipped"  {{@($order->order_status=='shipped') ? 'selected':'' }}>{{trans('orders.shipped') }}</option>
                                <option value="completed"  {{@($order->order_status=='completed') ? 'selected':'' }}>{{trans('orders.completed') }}</option>
                                <option value="cancelled"  {{@($order->order_status=='cancelled') ? 'selected':'' }}>{{trans('orders.cancelled') }}</option>
                                <option value="refunded" {{ @($order->order_status=='refunded') ? 'selected':'' }}>{{trans('orders.refunded') }}</option>
                              </select>
                            </div></div>
                            <div class="col-md-2">
                              
                              <div class="form-group">
                                <label></label>
                              <a href="javascript:void(0)" id="update_status" class="form-control btn btn-success">Update
                              </a></div>
                            </div>
                          
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
                              <th>{{trans('orders.weight')}}</th>
                              <th>{{trans('orders.supplied_qty')}}</th>
                              <th>{{trans('orders.total_sale_price')}}</th>
                              <th>{{trans('orders.available_stock')}}</th>
                              <th>{{trans('common.action')}}</th> </tfoot>
                        </table>
                      </div>
                     <div class="row">
                        <div class="col-md-7"></div>
                         <div class="col-md-2">
                              <div class="form-group">
                              <a href="javascript:void(0)" id="add_item" class="form-control btn btn-success">Add Item
                              </a></div>
                        </div>
                        <div class="col-md-3">
                              <div class="form-group">
                              <a href="javascript:void(0)" id="update_order" class="form-control btn btn-success">Update Order
                              </a></div>
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
                              Bundles : <input type="text" class="form-control" name="bundles" id="bundles" value="{{$order->bundles}}">
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
                        <div class="col-md-4">
                           <p class="details">
                          Transaction ID :   <br>
                          <input type="text" class="form-control" name="transaction_id" id="transaction_id" value="{{$order->transaction_id}}">
                          <label></label>
                          <br>
                          Payment Status :   <br>
                          <select name="payment_status" id="payment_status" class="form-control">
                            <option value="pending" {{@($order->payment_status =='pending')? 'selected':''}}>{{trans('orders.pending')}}</option>
                            <option value="paid" {{@($order->payment_status =='paid')? 'selected':''}}>{{trans('orders.paid')}}</option>
                            <option value="failed" {{@($order->payment_status =='failed')? 'selected':''}}>{{trans('orders.failed')}}</option>
                          </select>
                        
                        </p>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-7"></div>
                         <div class="col-md-2">
                             <div class="form-group">
                             <a href="javascript:void(0)" id="add_detail" name="add_detail" class="form-control btn btn-success">Add</a>
                              </div>
                      </div>
                      <div class="col-md-3">
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

                    <div class="row">
                      <div class="col-md-6">
                        <h5>Shipping Address &nbsp; <a href="javascript:void(0)" class="edit_address" name="shipping" id="{{$order->id}}"><i class="fa fa-edit"></i></a></h5>
                          <table>
                            <tr>
                              <td>Customer Name :</td>
                              <td>{{ $shipping_address->customer_name }}</td>
                            </tr>
                            <tr>
                              <td>Email :</td>
                              <td>{{ $shipping_address->email }}</td>
                            </tr>
                             <tr>
                              <td>Phone :</td>
                              <td>{{ $shipping_address->contact_number }}</td>
                            </tr>
                            <tr>
                              <td>House :</td>
                              <td>{{ $shipping_address->house_no }}</td>
                            </tr>
                            <tr>
                              <td>Street :</td>
                              <td>{{ $shipping_address->street }}</td>
                            </tr>
                            <tr>
                              <td>Landmark :</td>
                              <td>{{ $shipping_address->landmark }}</td>
                            </tr>
                            <tr>
                              <td>Area :</td>
                              <td>{{ $shipping_address->area }}</td>
                            </tr>
                            <tr>
                              <td>City :</td>
                              <td>{{ $shipping_address->city }}</td>
                            </tr>
                            <tr>
                              <td>Postcode :</td>
                              <td>{{ $shipping_address->postal_code }}</td>
                            </tr>
                            <tr>
                              <td>State :</td>
                              <td>{{ $shipping_address->state }}</td>
                            </tr>
                          </table>
                      </div>

                      <div class="col-md-6">
                          <h5>Billing Address   &nbsp;<a href="javascript:void(0)" class="edit_billing_address" name="billing" id="{{$order->id}}"><i class="fa fa-edit"></i></a></h5>
                          <table>
                              <tr>
                                <td>Customer Name :</td>
                                <td>{{ $billing_address->customer_name }}</td>
                              </tr>
                              <tr>
                                <td>Email :</td>
                                <td>{{ $billing_address->email }}</td>
                              </tr>
                               <tr>
                                <td>Phone :</td>
                                <td>{{ $billing_address->contact_number }}</td>
                              </tr>
                              <tr>
                                <td>House :</td>
                                <td>{{ $billing_address->house_no }}</td>
                              </tr>
                              <tr>
                                <td>Street :</td>
                                <td>{{ $billing_address->street }}</td>
                              </tr>
                              <tr>
                                <td>Landmark :</td>
                                <td>{{ $billing_address->landmark }}</td>
                              </tr>
                              <tr>
                                <td>Area :</td>
                                <td>{{ $billing_address->area }}</td>
                              </tr>
                              <tr>
                                <td>City :</td>
                                <td>{{ $billing_address->city }}</td>
                              </tr>
                              <tr>
                                <td>Postcode :</td>
                                <td>{{ $billing_address->postal_code }}</td>
                              </tr>
                              <tr>
                                <td>State :</td>
                                <td>{{ $billing_address->state }}</td>
                              </tr>
                          </table>
                      </div>
                    </div>
                    <br>
                      <h4>Notify User</h4>
                      <form method="POST" id="notifyUserForm" accept-charset="UTF-8">
                      @csrf
                      <input type="hidden" name="order_id" id="order_id" value="{{$order->id}}">
                       
                    <div class="row">
                       <div class="col-md-6">
                        <div class="form-group">
                          <label>Courier Name</label>
                          <input type="text" class="form-control" id="courier_name" name="courier_name">
                        </div>
                        <div class="form-group">
                          <label>Tracking Number</label>
                          <input type="text" class="form-control" id="tracking_number" name="tracking_number">
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
                           <input type="submit" class="btn btn-success" id="update_note" name="update_note" value="Update">
                        </div>
                        </div>
                        <div class="col-md-6 note_div">
                          <h4>Note Log</h4>
                            @foreach($order_notes as $order_note)
                            <p class="details">
                              @if($order_note->customer_note !='')
                                <b><u>Customer Note  {{ date('d-m-Y h:i A',strtotime($order_note->created_at))}} by {{App\Models\User::find($order_note->added_by)->first_name}}</u></b><br>
                                <span>{{ $order_note->customer_note }}</span><br>
                                <span>Order No.{{$order_note->order_id}}  shipped by {{@($order_note->courier_name) ? $order_note->courier_name : '-' }} and tracking number is {{@($order_note->tracking_number) ? $order_note->tracking_number : '-' }} </span><br><br>
                              @endif
                              @if($order_note->admin_note !='')
                                <b><u>Admin Note  {{ date('d-m-Y h:i A',strtotime($order_note->created_at))}} by {{App\Models\User::find($order_note->added_by)->first_name}}</u></b><br>
                                <span>{{ $order_note->admin_note }}</span><br>
                                <span>Order No.{{$order_note->order_id}}  shipped by {{@($order_note->courier_name) ? $order_note->courier_name : '-' }} and tracking number is {{@($order_note->tracking_number) ? $order_note->tracking_number : '-' }}</span>
                              @endif
                            </p>
                            @endforeach
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

<!-- update quantity popup -->
<div class="modal" tabindex="-1" role="dialog" id="update_supplied_qty_popup">
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
          <input type="hidden" class="" id="order_item_id" name="order_item_id" >
          <label>Enter Supplied Quantity</label>
          <input type="text" class="form-control numberonly" id="edit_supplied_qty" name="edit_supplied_qty">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="update_supp_qty">Update</button>
         </div>
      </form>
    </div>
  </div>
</div>


<!-- add more item into order  popup-->
<div class="modal" tabindex="-1" role="dialog" id="add_more_item_popup">
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
          <input type="hidden" class="" id="order_id" name="order_id" value="{{$order->id}}">
          <div id="products_div"></div>
          <label>Select product</label>
          <select name="product_id" id="product_id" class ="select2 form-control">
          </select>
          <label>Enter Quantity</label>
          <input type="text" class="form-control numberonly" id="ordered_qty" name="ordered_qty">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="add_more_item_btn">Add</button>
         </div>
      </form>
    </div>
  </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id="shipping_address_popup">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="addressForm">
        @csrf
        <input type="hidden" name="order_id" id="order_id"  value="{{$shipping_address->order_id}}">
        <div class="modal-body">
        <h4>Edit Address</h4>
            <table>
                   <tr>
                     <td>Customer Name :</td>
                     <td><input type="text" class="form-control" value="{{ $shipping_address->customer_name }}" id="customer_name" name="customer_name"></td>
                   </tr>
                            <tr>
                              <td>Email :</td>
                              <td><input type="text" class="form-control" value="{{ $shipping_address->email }}" id="email" name="email"></td>
                            </tr>
                             <tr>
                              <td>Phone :</td>
                              <td><input type="text" class="form-control" value="{{ $shipping_address->contact_number }}" id="contact_number" name="contact_number"></td>
                            </tr>
                            <tr>
                              <td>House :</td>
                              <td><input type="text" class="form-control" value="{{ $shipping_address->house_no }}" id="house_no" name="house_no"></td>
                            </tr>
                            <tr>
                              <td>Street :</td>
                              <td><input type="text" class="form-control" value="{{ $shipping_address->street }}" id="street" name="street"></td>
                            </tr>
                            <tr>
                              <td>Landmark :</td>
                              <td><input type="text" class="form-control" value="{{ $shipping_address->landmark }}" id="landmark" name="landmark"></td>
                            </tr>
                            <tr>
                              <td>Area :</td>
                              <td><input type="text" class="form-control" value="{{ $shipping_address->area }}" id="area" name="area"></td>
                            </tr>
                            <tr>
                              <td>City :</td>
                              <td><input type="text" class="form-control" value="{{ $shipping_address->city }}" id="city" name="city"></td>
                            </tr>
                            <tr>
                              <td>Postcode :</td>
                              <td><input type="text" class="form-control" value="{{ $shipping_address->postal_code }}" id="postal_code" name="postal_code"></td>
                            </tr>
                            <tr>
                              <td>State :</td>
                              <td><input type="text" class="form-control" value="{{ $shipping_address->state }}" id="state" name="state"></td>
                            </tr>
            </table>
            <input type="submit" class="btn btn-success" id="update_shipping_address">
         </div>
        <div class="modal-footer">
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id="billing_address_popup">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="BillingaddressForm">
        @csrf
        <input type="hidden" name="order_id" id="order_id"  value="{{$billing_address->order_id}}">
        <div class="modal-body">
        <h4>Edit Address</h4>
            <table>
                   <tr>
                     <td>Customer Name :</td>
                     <td><input type="text" class="form-control" value="{{ $billing_address->customer_name }}" id="customer_name" name="customer_name"></td>
                   </tr>
                            <tr>
                              <td>Email :</td>
                              <td><input type="text" class="form-control" value="{{ $billing_address->email }}" id="email" name="email"></td>
                            </tr>
                             <tr>
                              <td>Phone :</td>
                              <td><input type="text" class="form-control" value="{{ $billing_address->contact_number }}" id="contact_number" name="contact_number"></td>
                            </tr>
                            <tr>
                              <td>House :</td>
                              <td><input type="text" class="form-control" value="{{ $billing_address->house_no }}" id="house_no" name="house_no"></td>
                            </tr>
                            <tr>
                              <td>Street :</td>
                              <td><input type="text" class="form-control" value="{{ $billing_address->street }}" id="street" name="street"></td>
                            </tr>
                            <tr>
                              <td>Landmark :</td>
                              <td><input type="text" class="form-control" value="{{ $billing_address->landmark }}" id="landmark" name="landmark"></td>
                            </tr>
                            <tr>
                              <td>Area :</td>
                              <td><input type="text" class="form-control" value="{{ $billing_address->area }}" id="area" name="area"></td>
                            </tr>
                            <tr>
                              <td>City :</td>
                              <td><input type="text" class="form-control" value="{{ $billing_address->city }}" id="city" name="city"></td>
                            </tr>
                            <tr>
                              <td>Postcode :</td>
                              <td><input type="text" class="form-control" value="{{ $billing_address->postal_code }}" id="postal_code" name="postal_code"></td>
                            </tr>
                            <tr>
                              <td>State :</td>
                              <td><input type="text" class="form-control" value="{{ $billing_address->state }}" id="state" name="state"></td>
                            </tr>
            </table>
            <input type="submit" class="btn btn-success" id="update_shipping_address">
         </div>
        <div class="modal-footer">
        </div>
      </form>
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
           {
            data: '',
                mRender : function(data, type, row) {
                
                return '<a href="'+row['view_product']+'"><i class="fa fa-eye"></i></a> @can("order-delete")<a class="" href="#" onclick=" return delete_alert('+row["id"]+') "><i class="fa fa-trash"></i><form action="'+row["delete"]+'"  id="form_'+row['id']+'" method="post">@method("delete")@csrf</form></a>@endcan';
      
               }, orderable: false, searchable: false
            },
          ]
      });
    }

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
                    console.log(response);
                    if(response.error){
                      toastr.error(response.error);
                    }
                    else{
                     toastr.success(response.success);
                     //location.reload();
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
