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
                    <h2 class="content-header-title float-left mb-0">{{ trans('wish_return.heading') }}</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('wish_return.show') }}
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
                <h4 class="card-title">{{ trans('wish_return.show') }}</h4>
                 @can('wish-list-list')
                <a href="{{route('wish_return.index')}}" class="btn btn-success">
                    <i class="fa fa-arrow-left"></i>
                    {{ trans('common.back') }}
                </a>
              @endcan
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="row">
                    <div class="col-md-2"><b>Product ID </b>: {{$product->id}}</div><br>
                    <div class="col-md-4"><b>Product Name </b>: {{$product->get_name()}}</div>
                  </div>
                  <hr>
                  <div class="row">
                     <div class="col-md-2">
                          <div class="form-group">
                            <label class="">{{trans('wish_list.user_type')}}</label>
                            <select id="user_type" class = "form-control" name=''>
                            <option value=''>{{trans('common.select')}}</option>
                            <option value="dealer">{{trans('wish_return.dealer') }}</option>
                            <option value="retailer">{{trans('wish_return.retailer') }}</option>
                            </select>
                          </div>
                      </div>
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
                          <th>{{trans('wish_return.user_id')}}</th>
                          <th>{{trans('customers.company_name')}}</th>
                          <th>{{trans('users.phone_number')}}</th>
                          <th>{{trans('customers.user_type')}}</th>
                          <th>{{trans('wish_return.wish_return_qty')}}</th>
                          <th>{{trans('wish_return.dealer_name')}}</th>
                          <th>{{trans('wish_return.description')}}</th>
                          <th>{{trans('wish_return.date_time')}}</th>
                          <th>{{trans('common.action')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('wish_return.user_id')}}</th>
                          <th>{{trans('customers.company_name')}}</th>
                          <th>{{trans('users.phone_number')}}</th>
                          <th>{{trans('customers.user_type')}}</th>
                          <th>{{trans('wish_return.wish_return_qty')}}</th>
                          <th>{{trans('wish_return.dealer_name')}}</th>
                          <th>{{trans('wish_return.description')}}</th>
                          <th>{{trans('wish_return.date_time')}}</th>
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
        aaSorting : [[8, 'desc']],
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
                columns: [0, 1, 2 , 3, 4, 5 , 6,7,8]
            },
        }],
         drawCallback: function() {
          var hasRows = this.api().rows({ filter: 'applied' }).data().length > 0;
          $('.buttons-csv')[0].style.visibility = hasRows ? 'visible' : 'hidden'
          // $('.buttons-pdf')[0].style.visibility = hasRows ? 'visible' : 'hidden'
        },
        language: {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">{{trans("common.loading")}}</span> '},
        ajax: {
            url: "{{route('dt_wishreturn_detail')}}",
            data: {
                    "_token": "{{csrf_token()}}",
                    "start_date":$("#start_date").val(),
                    "end_date":$("#end_date").val(),
                    "product_id": "{{$product->id}}",
                    "user_type":$("#user_type").val(),
                    },
           //success:function(data) { console.log(data); },
            error:function(data){ console.log(data); },
        },
        columns: [
           { data: 'id'},
           { data: 'user_id',orderable: true},
           { data: 'company_name',orderable: false},
           { data: 'mobile_number',orderable: false},
           { data: 'user_type',orderable: false},
           { data: 'wish_return_qty',orderable: true},
           { data: 'dealer_name',orderable: false},
           { data: 'description',orderable: false},
            { data: 'created_at',
           "render": function (data, type, row) {
                  var created_date = new Date(data);
                  var todayDate = new Date();
                  var milliseconds = todayDate.getTime() - created_date.getTime();

                  //var hours = Math.floor(milliseconds / (3600 * 1000));
                  var hours = Math.abs(milliseconds)/(3600 * 1000);
                  hours = Math.round(hours,2);
               
                //  hours = Math.abs(hours);
                  if(hours <= 24){
                    data = hours+" Hours Ago";
                  }else {
                    data = moment(data).format('DD-MM-YYYY hh:mm A');
                  }
                /*  current_date = moment(current_date).format('DD-MM-YYYY hh:mm A');
                  data = moment(data).format('DD-MM-YYYY hh:mm A');
                */
                  return data;},orderable:true},
           {
                mRender:function(data,type,row){
                return '<a class="" href="'+row["show"]+'"><i class="fa fa-eye"></i></a>  @can("stock-delete")<form action="'+row["delete"]+'" id="form'+row['id']+'" method="post"><a class=" delete_btn" href="javascript:void(0)" onclick=" return delete_alert('+row['id']+')"><i class="fa fa-trash"></i></button>@method("delete")@csrf</form>@endcan';
                 }
          },
          ]
    });
    }
    
    $('#user_type').on('change', function(){
      fill_datatable();
    });

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
   function delete_alert(id) {
      if(confirm("{{trans('common.confirm_delete')}}")){
        $("#form"+id).submit();
        return true;
      }else{
        return false;
      }
    }
</script>
</script>

@endsection
