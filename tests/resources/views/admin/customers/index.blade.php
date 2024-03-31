@extends('layouts.admin.app')

@section('vendor_css')
<link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
@endsection

@section('css')
<style type="text/css">
.export-btn
{
  margin-right:5px;
}

</style>
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
                    <h2 class="content-header-title float-left mb-0">{{ trans('customers.heading') }}</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('customers.plural') }}
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
                  <h4 class="card-title">{{ trans('customers.title') }}</h4>
                   <div class="col-md-4">
                      <div class="form-group">
                        <select id="filter_user_type" class = "form-control" name='booking'>
                        <option value='all'>{{trans('common.type')}}</option>
                        <option value="dealer">{{trans('customers.dealer') }}</option>
                        <option value="retailer">{{trans('customers.retailer') }}</option>
                        </select>
                      </div>
                    </div>
                     <div class="col-md-4">
                      <div class="form-group">
                        <select id="filter_type" class = "form-control" name='booking'>
                        <option value='all'>{{trans('common.status')}}</option>
                        <option value="active">{{trans('common.active') }}</option>
                        <option value="inactive">{{trans('common.inactive') }}</option>
                        </select>
                      </div>
                    </div>
              
              </div>
               <div class="row">
                    <div class="col-lg-4 col-lg-4 col-md-4 col-sm-12">
                      <div class="card">
                          <div class="card-header d-flex align-items-start pb-0">
                              <div>
                                  <h2 class="text-center">{{$total_customers}}</h2>
                                  <p>Total Customers</p>
                              </div>
                          </div>
                      </div> 
                    </div>
                    <div class="col-lg-4 col-lg-4 col-md-4 col-sm-12">
                      <div class="card">
                          <div class="card-header d-flex align-items-start pb-0">
                              <div>
                                  <h2 class="text-center">{{$total_retailers}}</h2>
                                  <p>Total Retailers</p>
                              </div>
                          </div>
                      </div> 
                    </div>
                    <div class="col-lg-4 col-lg-4 col-md-4 col-sm-12">
                      <div class="card">
                          <div class="card-header d-flex align-items-start pb-0">
                              <div>
                                  <h2 class="text-center">{{$total_dealers}}</h2>
                                  <p>Total Dealers</p>
                              </div>
                          </div>
                      </div> 
                    </div>
                  </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="table-responsive">
                    <table class="table zero-configuration data_table_ajax">
                      <thead>
                          <tr>
                            <th>{{ trans('common.id') }}</th>
                            <th>{{ trans('customers.name') }}</th>
                            <th>{{ trans('customers.company_name') }}</th>
                            <th>{{ trans('customers.email') }}</th>
                            <th>{{ trans('customers.phone_number') }}</th>
                            <th>{{ trans('customers.user_type') }}</th>
                             <th>{{ trans('common.status') }}</th>
                            <!--<th>{{ trans('common.action') }}</th>-->
                            <th width="20%">{{ trans('customers.send') }}</th>
                            <th hidden="hidden">{{trans('common.status')}}</th>
                          </tr>
                      </thead>
                      <tbody>

                      </tbody>
                      <tfoot>
                          <tr>
                            <th>{{ trans('common.id') }}</th>
                            <th>{{ trans('customers.name') }}</th>
                            <th>{{ trans('customers.company_name') }}</th>
                            <th>{{ trans('customers.email') }}</th>
                            <th>{{ trans('customers.phone_number') }}</th>
                            <th>{{ trans('customers.user_type') }}</th>
                            <th>{{ trans('common.status') }}</th>
                            <th width="20%">{{ trans('customers.send') }}</th>
                            <th hidden="hidden">{{trans('common.status')}}</th>
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
    </div>
  </div>
</div>




<!-- END: Content-->
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

<script type="text/javascript">

  $(document).ready(function(){

    fill_datatable();
   
   $('#filter_type').on('change', function(){
      $('.data_table_ajax').DataTable().destroy();
      fill_datatable();
    });


   $('#filter_user_type').on('change', function(){
      $('.data_table_ajax').DataTable().destroy();
      fill_datatable();
    });


  });

  $(document).on('change','.status',function(){
      var status = this.checked ? 'active' : 'inactive';
      var confirm = status_alert(status);
      if(confirm){
        var id = $(this).attr('data_id');
        var delay = 500;
        var element = $(this);
        $.ajax({
            type:'post',
            url: "{{route('status_customers')}}",
            data: {
                    "status": status,
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
             
              toastr.success(data.message);
               fill_datatable();
            },
            error: function (data) {

             
              toastr.error(data.message);
              fill_datatable();
            }
        })
      }else{
      fill_datatable();
      }
  })


  function fill_datatable() {
      var table = $('.data_table_ajax').DataTable({ 
        "scrollY": 500, "scrollX": true,
      aaSorting: [[ 0, "desc" ]],
      dom: 'Blfrtip',
      lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100,"All"]],
       bDestroy: true,
      pageLength: 10,
      buttons: [{
            extend: 'csv',
            text: '<span class="fa fa-file-pdf-o"></span> {{trans("customers.download_csv")}}',
            className:"btn btn-md btn-success export-btn",
            exportOptions: {
                modifier: {
                    search: 'applied',
                    order: 'applied'
                },
                columns: [0, 1, 2 , 3 ,4 ,5 ,8]
            },
        }],
          drawCallback: function() {
          var hasRows = this.api().rows({ filter: 'applied' }).data().length > 0;
          $('.buttons-csv')[0].style.visibility = hasRows ? 'visible' : 'hidden'
       },
      serverSide: true,
      serverMethod:'POST',
      processing: true,
      language: {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
      ajax: {
          url: "{{route('dt_customers')}}",
          data: {"_token": "{{csrf_token()}}","filter_type":$('#filter_type').val(),"filter_user_type":$("#filter_user_type").val()},
          error:function(data){ console.log(data); }
      },
      columns: [

        { data: 'id',  },
        { data: 'id',
            mRender : function(data, type, row) { 
                return row['name'];
              },orderable: false, searchable: false 
        },
        { data:'company_name'},
        { data: 'email'},
        { data: 'mobile_number'},
        { data: 'user_type' },
        /*{ data:'status', orderable:false },
        */{  data:'status',
            mRender : function(data, type, row) {
                      var status=data;
                      // console.log(status);
                  if(status=='active'){
                    type = "checked";
                  }
                  else if(status=='inactive'){
                        inactive_selected="selected";
                  }

                  var status_label = status.charAt(0).toUpperCase() +""+status.slice(1);

                  return '<label>'+status_label+'</label><div class="custom-control custom-switch custom-switch-success mr-2 mb-1"><input type="checkbox"'+type+' + class="status custom-control-input" id="customSwitch'+row["id"]+'" data_id="'+row["id"]+'"><label class="custom-control-label" for="customSwitch'+row["id"]+'"></label></div>';

                      }
              },
              {
                mRender:function(data,type,row){
                return '<a class="" href="'+row["show"]+'"><i class="fa fa-eye"></i></a> <a class="" href="'+row["edit"]+'"><i class="fa fa-edit"></i></a> <a class="" href="'+row["send_email"]+'"><i class="fa fa-envelope"></i></a> <a class="" href="'+row["notification"]+'"><i class="fa fa-bell"></i></a>';

                },orderable: false, searchable: false,
              },

            { data:'status',visible:false },
         ]
       });
    };
  function delete_alert() {
    if(confirm("{{trans('common.confirm_delete')}}")){
      return true;
    }else{
      return false;
    }
  }


</script>

<script>
  function status_alert(status) {
    if(status == 'active')
    {
      if(confirm("{{trans('customers.confirm_status_active')}}")){
        return true;
      }else{
        return false;
      }
    }
      else if(status =='inactive')
      {
        if(confirm("{{trans('customers.confirm_status_inactive')}}")){
        return true;
          }else{
            return false;
          }
      }
  }


</script>


@endsection
