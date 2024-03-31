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
                    <h2 class="content-header-title float-left mb-0">{{ trans('wish_list.dealer_heading') }}</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('wish_list.show_dealer_list') }}
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
                <h4 class="card-title">{{ trans('wish_list.show_dealer_list') }}</h4>
                 @can('wish-list-list')
                <a href="{{route('wish_list.show',$_GET['product_id'])}}" class="btn btn-success">
                    <i class="fa fa-arrow-left"></i>
                    {{ trans('common.back') }}
                </a>
              @endcan
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="table-responsive">
                    <table class="table zero-configuration data_table_ajax">
                      <input type="hidden" id="wish_list_id" name="wish_list_id" value="{{$id}}">
                      <thead>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('wish_list.user_id')}}</th>
                          <th>{{trans('customers.company_name')}}</th>
                          <th>{{trans('users.phone_number')}}</th>
                          <th>{{trans('customers.user_type')}}</th>
                          <th>{{trans('wish_list.date_time')}}</th>
                         </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('wish_list.user_id')}}</th>
                          <th>{{trans('customers.company_name')}}</th>
                          <th>{{trans('users.phone_number')}}</th>
                          <th>{{trans('customers.user_type')}}</th>
                          <th>{{trans('wish_list.date_time')}}</th>
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
        aaSorting : [[1, 'desc']],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        pageLength: 10,
        serverSide: true,
        serverMethod:'POST',
        processing:true,
        bDestroy:true,
        dom:'Bflrtip',
        buttons: [{
            extend: 'csv',
            text: '<span class="fa fa-file-pdf-o"></span> {{trans("customers.download_csv")}}',
            className:"btn btn-md btn-success export-btn",
            exportOptions: {
                modifier: {
                    search: 'applied',
                    order: 'applied'
                },
                columns: [0, 1, 2 , 3 ,4 ,5]
            },
        }],
        drawCallback: function() {
          var hasRows = this.api().rows({ filter: 'applied' }).data().length > 0;
          $('.buttons-csv')[0].style.visibility = hasRows ? 'visible' : 'hidden'
        },
        language: {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">{{trans("common.loading")}}</span> '},
        ajax: {
            url: "{{route('dt_dealer_ajax_detail')}}",
            data: {
                    "_token": "{{csrf_token()}}",
                    "wish_list_id":$("#wish_list_id").val(),
                    },
           //success:function(data) { console.log(data); },
            error:function(data){ console.log(data); },
        },
        columns: [
           { data: 'id'},
           { data: 'dealer_id',orderable: true},
           { data: 'company_name',orderable: false},
           { data: 'mobile_number',orderable: false},
           { data: 'user_type',orderable: false},
            { data: 'created_at',
           "render": function (data, type, row) {
                  var created_date = new Date(data);
                  var todayDate = new Date();
                  var milliseconds = todayDate.getTime() - created_date.getTime() ;

                  var hours = (Math.abs(milliseconds)/(3600 * 1000));
                  hours = Math.round(hours,2);
                  if(hours <= 24){
                    data = hours+" Hours Ago";
                  }else {
                    data = moment(data).format('DD-MM-YYYY hh:mm A');
                  }
                  return data;},orderable:true},
          
          ]
    });
    }

     $('#user_type').on('change', function(){
      fill_datatable();
    });

    $('#apply_date').on('click', function (event) {
    if($('#start_date').val() != '' && $('#end_date').val() != '') {
      if(new Date($('#start_date').val()) > new Date($('#end_date').val())){
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
