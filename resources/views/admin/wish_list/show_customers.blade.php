@extends('layouts.admin.app')
@section('css')


<meta name="viewport" content="width=device-width, initial-scale=1">
  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.4.0/cropper.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
<style type="text/css">
.bootstrap-select.form-control{ border:1px solid #D9D9D9; !important }  
</style>

@endsection
@section('vendor_css')
<link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
@endsection
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{trans('wish_list.show')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('wish_list.show',$_GET['product_id'])}}">{{ trans('wish_list.show') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('customers.show') }}
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
              {{ trans('customers.details')}}
              </h4>
              @can('customer-list')
                <a href="{{route('wish_list.show',$_GET['product_id'])}}" class="btn btn-success">
                    <i class="fa fa-arrow-left"></i>
                    {{ trans('common.back') }}
                </a>
              @endcan
            </div>
            <div class="card-content">
              <div class="card-body">    
                <form method="POST" id="customerForm" accept-charset="UTF-8" enctype="multipart/form-data" >
                     <input name="_method" type="hidden" value="PUT">
                
                  @csrf
                  <div class="form-body">
                 
                    <div class="row">
                    <div class="col-md-4">
                     <div class="form-group">
                        <label for="name" class="content-label">{{trans('customers.profile_image')}}<span class="text-danger custom_asterisk">*</span></label><br>
                        <img src="{{asset($customer->profile_image)}}" width="20%" height="20%" />
                      </div>  
                    </div>
                      <div class="col-md-4">
                        <div class="form-group">
                        <label for="name" class="content-label">{{trans('customers.customer_id')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control" name="customer_id" value="{{ old('user_id',$customer->id) }}" disabled>
                      </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                        <label for="name" class="content-label">{{trans('customers.user_type')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control" name="user_type" value="{{ old('user_type',ucfirst($customer->user_type)) }}" disabled>
                      </div>
                      </div>
                    
                    </div>
                  <div class="row">
                   <div class="col-md-4">
                     <div class="form-group">
                        <label for="name" class="content-label">{{trans('customers.first_name')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control @error('first_name') ? is-invalid : ''  @enderror" minlength="2" maxlength="255" placeholder="{{trans('customers.pl_enter_first_name')}}" name="first_name" value="{{ old('first_name',$customer->first_name) }}" disabled="">
                          @error('first_name')
                            <div class="invalid-feedback">
                               <strong> {{ @$errors->first('first_name') }} </strong>
                            </div>
                          @enderror
                      </div>  
                    </div>
                     <div class="col-md-4">
                     <div class="form-group">
                        <label for="name" class="content-label">{{trans('customers.company_name')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control @error('company_name') ? is-invalid : ''  @enderror" minlength="2" maxlength="255" placeholder="{{trans('customers.pl_enter_company_name')}}" name="company_name" id="company_name" value="{{ old('company_name',$customer->company_name) }}" disabled="">
                          @error('company_name')
                            <div class="invalid-feedback">
                               <strong> {{ @$errors->first('company_name') }} </strong>
                            </div>
                          @enderror
                      </div>  
                    </div>
                      <div class="col-md-4">
                     <div class="form-group">
                        <label for="email" class="content-label">{{trans('customers.email')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input type="email" class="form-control @error('email') ? is-invalid : ''  @enderror" minlength="2" maxlength="255" placeholder="{{trans('customers.pl_enter_email_address')}}" name="email"  value="{{ old('email',$customer->email) }}" disabled>
                      </div>  
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                     <div class="form-group">
                        <label for="mobile_number" class="content-label">{{trans('customers.mobile_number')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input type="text" class="form-control @error('mobile_number') ? is-invalid : ''  @enderror"  placeholder="{{trans('customers.pl_enter_mobile_number')}}" name="mobile_number" maxlength="10" value="{{ old('mobile_number',$customer->mobile_number) }}" disabled>
                       </div>  
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="referral_code" class="content-label">{{trans('customers.referral_code')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input type="text" class="form-control @error('referral_code') ? is-invalid : ''  @enderror"  placeholder="{{trans('customers.pl_enter_referral_code')}}" name="referral_code" maxlength="10" value="{{ old('referral_code',$customer->referral_code) }}" disabled>
                       </div>  
                    </div>
                      <div class="col-md-4">
                      <div class="form-group">
                        <label for="user_discount" class="content-label">{{trans('customers.user_discount')}}<span class="text-danger custom_asterisk"></span></label>
                        <input type="text" class="form-control @error('user_discount') ? is-invalid : ''  @enderror"  placeholder="{{trans('customers.pl_enter_user_discount')}}" name="user_discount" id="user_discount" value="{{ old('user_discount',$customer->user_discount) }}" disabled="">
                       </div>  
                    </div>
                    </div>
                  </div>
                  <div class="row">
                  @if($company_docs)
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="company_documents" class="content-label">{{trans('customers.company_documents')}}<span class="text-danger custom_asterisk"></span></label><br>
                        @foreach($company_docs as $cd)
                          <img src="{{asset($cd)}}"  width="30%" height="30%"/>
                        @endforeach
                       </div>  
                  </div>
                  @endif
                  @if($company_images)
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="company_images" class="content-label">{{trans('customers.company_images')}}<span class="text-danger custom_asterisk"></span></label><br>
                        @foreach($company_images as $cimg)
                          <img src="{{asset($cimg)}}"  width="30%" height="30%"/>
                        @endforeach
                       </div>  
                  </div>
                  @endif
                </div>

                  @if($customer->user_type == 'dealer')
                    <hr>
                  <!--  -->
                  </div>
                @endif
                </div>
                  <!-- <div class="modal-footer">
                    <button id="edit_btn" type="submit" class="btn btn-success">{{trans('common.submit')}}</button>
                  </div> -->
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>

<script type="text/javascript">
   $(document).ready(function(){
    $("select").selectpicker();
    $(document).on('click','.user_type',function(){
      var usertype = $(this).attr('name');
      var id       = $(this).attr('id');
      var confirm = usertype_alert(usertype);
      if(confirm){
        var delay = 500;
        var element = $(this);
        $.ajax({
            type:'post',
            url: "{{route('change_user_type')}}",
            data: {
                    "user_type": usertype,
                    "id" : id,
                    "_token": "{{ csrf_token() }}"
                  },
            beforeSend: function () {
                element.next('.loading').css('visibility', 'visible');
            },
            success: function (data) {
              setTimeout(function() {
                    element.next('.loading').css('visibility', 'hidden');
                    location.reload();
                }, delay);
              location.reload();
              toastr.success(data.success)
            },
            error: function () {
              toastr.error(data.error);
            }
        })
      }else{
      location.reload();
      }
    })

     function delete_alert() {
      if(confirm("{{trans('common.confirm_delete')}}")){
       return true;
      }else{
        return false;
      }
    }

    function usertype_alert(usertype) {
      if(usertype == 'retailer')
      {
        if(confirm("{{trans('customers.confirm_usertype_dealer')}}")){
          return true;
        }else{
          return false;
        }
      }
      else if(usertype =='dealer')
      {
          if(confirm("{{trans('customers.confirm_usertype_retailer')}}")){
          return true;
            }else{
              return false;
            }
      }
    }

    fill_datatable();
    function fill_datatable() {
      $('.data_table_ajax').DataTable({ 
        "scrollY": 300, "scrollX": true,
      aaSorting: [[ 0, "desc" ]],
      dom: 'Blfrtip',
      lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100,"All"]],
      pageLength: 10,
      buttons: [],
      serverSide: true,
      serverMethod:'POST',
      processing: true,
      language: {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
      ajax: {
          url: "{{route('dt_retailers')}}",
          data: {"_token": "{{csrf_token()}}","id":$('#dealer_id').val(),},
          error:function(data){ console.log(data); }
      },
      columns: [

        { data: 'id',  },
        { data: 'id',
            mRender : function(data, type, row) { 
                return row['name'];
              } 
        },
        { data: 'id',
            mRender : function(data, type, row) { 
                return row['company_name'];
              }
        },
        { data: 'id',
            mRender : function(data, type, row) { 
                return row['email'];
              }
        },
        { data: 'id',
            mRender : function(data, type, row) { 
                return row['mobile_number'];
              }
        },
         ]
       });
    }

    $(document).on("click","#add_retailers_btn",function(){
      var retailer_id = $("#retailer_id").val();
      var dealer_id   = $("#dealer_id").val();
      if(retailer_id != '' && dealer_id != ''){
        $.ajax({
            type:'post',
            url: "{{route('add_retailers')}}",
            data: {
                    "retailer_id": retailer_id,
                    "dealer_id" : dealer_id,
                    "_token": "{{ csrf_token() }}"
                  },
            beforeSend: function () {
               
            },
            success: function (response) {
              console.log(response);
              if(response.success){
                toastr.success(response.success);
                location.reload();
              }else {
                toastr.error(response.error);
              }
            },
            error: function () {
              toastr.error(response);
            }
        })
      }
      else { toastr.error('Please Select Retailer'); }
    });

});

</script>

@endsection
