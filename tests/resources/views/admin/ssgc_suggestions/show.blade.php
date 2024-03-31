@extends('layouts.admin.app')
@section('vendor_css')
<link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
<style type="text/css">
  td form{
    display: inline;
  }
  .dt-buttons{margin-right: 10px}
  .select2-selection {padding: 0px 5px !important}
  .update_stock_popup {cursor: pointer; color: blue}
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
                    <h2 class="content-header-title float-left mb-0">{{ trans('ssgc_suggestions.heading') }}</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('ssgc_suggestions.show') }}
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
                <h4 class="card-title">{{ trans('ssgc_suggestions.show') }}</h4>
                 @can('wish-list-list')
                <a href="{{route('ssgc_suggestions.index')}}" class="btn btn-success">
                    <i class="fa fa-arrow-left"></i>
                    {{ trans('common.back') }}
                </a>
              @endcan
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="row">
                    <div class="col-md-2"><b>Product ID </b>: {{$product->id}}</div><br>
                    <div class="col-md-4"><b>Product Name </b>: {{$product->get_name();}}</div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <label class="content-label">Start Date</label>
                        <input id="start_date" type="" class="form-control datepicker_future_not_allow" autocomplete="off" name="" value="">
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label class="content-label">End Date</label>
                        <input id="end_date" type="" class="form-control datepicker_future_not_allow" name="" autocomplete="off" name="" value="">
                      </div>
                    </div>
                    <div class="col-md-2">  
                       <div class="form-group">
                        <label class="content-label"> </label>
                        <a href="javascript:void(0)" id="apply_date" class="form-control btn btn-success">Apply
                        </a>
                       </div>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table zero-configuration data_table_ajax">
                      <thead>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('ssgc_suggestions.user_id')}}</th>
                          <th>{{trans('customers.company_name')}}</th>
                          <th>{{trans('users.phone_number')}}</th>
                          <th>{{trans('users.email')}}</th>
                          <th>{{trans('customers.user_type')}}</th>
                          <th style="width:200px !important;">{{trans('ssgc_suggestions.description')}}</th>
                          <th>{{trans('ssgc_suggestions.date_time')}}</th>
                          <th>{{trans('common.action')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('ssgc_suggestions.user_id')}}</th>
                          <th>{{trans('customers.company_name')}}</th>
                          <th>{{trans('users.phone_number')}}</th>
                          <th>{{trans('users.email')}}</th>
                          <th>{{trans('customers.user_type')}}</th>
                          <th style="width:200px !important;">{{trans('ssgc_suggestions.description')}}</th>
                          <th>{{trans('ssgc_suggestions.date_time')}}</th>
                          <th>{{trans('common.action')}}</th></tr>
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
  $(document).ready(function(){
    $("#start_date,#end_date").val('');
    fill_datatable();
    function fill_datatable() {

        $('.data_table_ajax').DataTable({ 
        "scrollX": true,
        aaSorting : [[0, 'desc']],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        pageLength: 10,
        serverSide: true,
        serverMethod:'POST',
        processing:true,
        bDestroy:true,
        language: {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">{{trans("common.loading")}}</span> '},
        ajax: {
            url: "{{route('dt_ssgc_suggestions_detail')}}",
            data: {
                    "_token": "{{csrf_token()}}",
                    "start_date":$("#start_date").val(),
                    "end_date":$("#end_date").val(),
                    "product_id": "{{$product->id}}",
                    },
           //success:function(data) { console.log(data); },
            error:function(data){ console.log(data); },
        },
        columns: [
           { data: 'id'},
           { data: 'user_id',orderable: true},
           { data: 'company_name',orderable: false},
           { data: 'mobile_number',orderable: false},
           { data: 'email',orderable: false},
           { data: 'user_type',orderable: false},
           { data: 'description',orderable: false},
           { data: 'date_time',orderable:false},
           {
                mRender:function(data,type,row){
                return '<a class="" href="'+row["show"]+'"><i class="fa fa-eye"></i></a> ';
                 }
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

  });
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
</script>

@endsection
