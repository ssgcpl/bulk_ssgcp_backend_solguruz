@extends('layouts.admin.app')
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
                    <h2 class="content-header-title float-left mb-0">{{trans('orders.admin_heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('orders.index')}}">{{ trans('orders.plural') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('orders.show') }}
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
              {{ trans('orders.details')}}
              </h4>
              <a href="{{route('orders.index')}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
              </a>
            </div>
            <div class="card-content">
              <div class="card-body">  
                <form method="POST" id="notifyUserForm" accept-charset="UTF-8">
                  @csrf
                   <input type="hidden" name="order_id" id="order_id" value="{{$order->id}}">
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-6">
                        <h5>Shipping Address</h5>
                          <table>
                            <tr><td></td><td><a href="javascript:void(0)" class="edit_address" name="shipping" id="{{$order->id}}"><i class="fa fa-edit"></i></a></td></tr>
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
                          <h5>Billing Address</h5>
                          <table>
                             <tr><td></td><td><a href="javascript:void(0)" class="edit_billing_address" name="billing" id="{{$order->id}}"><i class="fa fa-edit"></i></a></td></tr>
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

                      <h4>Notify User</h4>
                      
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
                        <div class="col-md-6">
                          <h4>Note Log</h4>
                            @foreach($order_notes as $order_note)
                            <p class="details">
                              @if($order_note->customer_note !='')
                                <b><u>Customer Note  {{ date('d-m-Y h:m A',strtotime($order_note->created_at))}} by {{App\Models\User::find($order_note->added_by)->first_name}}</u></b><br>
                                <span>{{ $order_note->customer_note }}</span><br>
                                <span>Order No.{{$order_note->order_id}}  shipped by {{$order_note->courier_name}} and tracking number is {{$order_note->tracking_number}} </span><br><br>
                              @endif
                              @if($order_note->admin_note !='')
                                <b><u>Admin Note  {{ date('d-m-Y h:m A',strtotime($order_note->created_at))}} by {{App\Models\User::find($order_note->added_by)->first_name}}</u></b><br>
                                <span>{{ $order_note->admin_note }}</span><br>
                                <span>Order No.{{$order_note->order_id}}  shipped by {{$order_note->courier_name}} and tracking number is {{$order_note->tracking_number}} </span>
                              @endif
                            </p>
                            @endforeach
                          
                        </div>
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
@section('js')
 <script type="text/javascript">
  $(document).ready(function() {
    
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
  });
</script>
@endsection