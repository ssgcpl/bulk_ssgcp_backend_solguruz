@extends('layouts.admin.app')

@section('vendor_css')
<link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
@endsection 


@section('css')


<meta name="viewport" content="width=device-width, initial-scale=1">
  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.4.0/cropper.css">
@endsection
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{trans('customers.admin_heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('customers.index')}}">{{ trans('customers.plural') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('customers.send_sms') }}
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
              {{ trans('customers.send_sms_details')}}
              </h4>
               <a href="{{route('customers.index')}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
              </a>
             </div>
            <div class="card-content">
              <div class="card-body">  
                <form method="POST" id="SMSForm" action="{{route('send_sms')}}" accept-charset="UTF-8" enctype="multipart/form-data" >
                  <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer->id }}">

                  @csrf
                  <div class="form-body">
                  <div class="row">
                    <div class="col-md-12">
                     <div class="form-group">
                          <label for="mobile_number" class="content-label">{{trans('customers.mobile_number')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input type="text" class="form-control @error('mobile_number') ? is-invalid : ''  @enderror"  placeholder="{{trans('customers.pl_enter_mobile_number')}}" name="mobile_number" maxlength="10" value="{{ old('mobile_number',$customer->mobile_number) }}" required>
                          @error('mobile_number')
                            <div class="invalid-feedback">
                               <strong> {{ @$errors->first('mobile_number') }} </strong>
                            </div>
                          @enderror
                       </div>  
                    </div>
                  </div>
                    <div class="row">
                    <div class="col-md-12">
                     <div class="form-group">
                        <label for="name" class="content-label">{{trans('customers.message')}}<span class="text-danger custom_asterisk">*</span></label>
                        <textarea class="form-control @error('message') ? is-invalid : ''  @enderror" name="body" required></textarea>
                          @error('message')
                            <div class="invalid-feedback">
                               <strong> {{ @$errors->first('message') }} </strong>
                            </div>
                          @enderror
                      </div>  
                    </div>
              </div>
              </div> 
               <div class="modal-footer">
                    <button id="edit_btn" type="submit" class="btn btn-info btn-fill btn-wd">{{trans('common.submit')}}</button>
                  </div>
              </form> 
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!--- Push Notification List -->
   <!-- Zero configuration table -->
      <section id="basic-datatable">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                  <h4 class="card-title">{{ trans('customers.sms_list') }}</h4>
                   <div class="col-md-4">
                    </div>
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="table-responsive">
                    <table class="table zero-configuration data_table_ajax">
                      <thead>
                          <tr>
                            <th>{{ trans('common.id') }}</th>
                            <th>{{ trans('customers.send_by') }}</th>
                             <th>{{ trans('customers.send_to') }}</th>
                            <th>{{ trans('customers.message') }}</th>
                            <th>{{ trans('customers.date') }}</th>
                           </tr>
                      </thead>
                      <tbody>
                          
                      </tbody>
                      <tfoot>
                          <tr>
                            <th>{{ trans('common.id') }}</th>
                            <th>{{ trans('customers.send_by') }}</th>
                            <th>{{ trans('customers.send_to') }}</th>
                            <th>{{ trans('customers.message') }}</th>
                            <th>{{ trans('customers.date') }}</th>
                          </tr>
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
<!-- Push Notification List -->

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

<script type="text/javascript">
$(document).ready(function(){

   fill_datatable();

    function fill_datatable() {

      var user_id = $("#customer_id").val();
      $('.data_table_ajax').DataTable({ 
        "scrollY": 300, "scrollX": true,
        aaSorting : [[0, 'desc']],
        dom: 'Blfrtip',
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        pageLength: 10,
        buttons: [
        
             
        ],
        processing: true,
        serverSide: true,
        serverMethod:'POST',
        processing: true,
        language: {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">{{trans("common.loading")}}</span> '},
        ajax: {
            url: "{{route('dt_sms_list')}}",
            data: {"_token": "{{csrf_token()}}", 'id':user_id},
           //success:function(data) { console.log(data); },
            error:function(data){ console.log(data); },
        },
        columns: [

           { data: 'id'},
           { data: 'send_by',orderable: false},
           { data: 'send_to',orderable: false},
           { data: 'message',orderable: false},
           { data: 'created_date',orderable:false},
          ]
      });
    }
 

})
  
</script>

@endsection
