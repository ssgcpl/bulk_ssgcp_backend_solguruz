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
  .status_text label { color:red !important;  text-decoration: underline;  }

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
                    <h2 class="content-header-title float-left mb-0">{{ trans('orders.heading') }}</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('orders.plural') }}
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
                <h4 class="card-title">{{ trans('orders.title') }}</h4>

              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="row">
                    <div class="col-md-12 status_text">
                          <label>Cancelled (<span>{{$cancelled}}</span>)
                           |</label><label>Refunded (<span>{{$refunded}}</span>)
                           |
                          <label>On Hold (<span>{{$on_hold}}</span>)
                           |</label><label>Pending Payment (<span>{{$pending_payment}}</span>)
                           |</label><label>Processing (<span>{{$processing}}</span>)
                           |</label><label>Shipped (<span>{{$shipped}}</span>)
                           |</label><label>Completed (<span>{{$completed}}</span>)
                           </label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                      <label></label>
                      <h3><b>Total Sale : </b>Rs. {{$total_sale_price}} </h3>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                  <div class="col-md-3">
                      <div class="form-group">
                        <label class="content-label">Print Status</label>
                        <select id="print_status" class = "form-control">
                        <option value=''>All</option>
                        <option value='remaining'>{{trans('orders.remaining')}}</option>
                        <option value="printed">{{trans('orders.printed') }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="">{{trans('orders.order_type')}}</label>
                        <select id="order_type" class = "form-control" name='order_type'>
                          <option value=''>{{trans('common.select')}}</option>
                          <option value="physical_books">{{trans('orders.physical')}}</option>
                          <option value="digital_coupons">{{trans('orders.digital')}}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="">{{trans('orders.order_status')}}</label>
                        <select id="order_status" class = "form-control" name=''>
                          <option value=''>{{trans('common.select')}}</option>
                          <option value="pending_payment">{{trans('orders.pending_payment') }}</option>
                          <option value="on_hold">{{trans('orders.on_hold') }}</option>
                          <option value="processing">{{trans('orders.processing') }}</option>
                          <option value="shipped">{{trans('orders.shipped') }}</option>
                          <option value="completed">{{trans('orders.completed') }}</option>
                          <option value="cancelled">{{trans('orders.cancelled') }}</option>
                          <option value="refunded">{{trans('orders.refunded') }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="">{{trans('orders.user_type')}}</label>
                        <select id="user_type" class = "form-control" name=''>
                        <option value=''>{{trans('common.select')}}</option>
                        <option value="dealer">{{trans('orders.dealer') }}</option>
                        <option value="retailer">{{trans('orders.retailer') }}</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                          <label class="content-label">Start Date</label>
                          <input id="start_date" type="" class="form-control datepicker_future_not_allow" autocomplete="off" name="" value="">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                          <label class="content-label">End Date</label>
                          <input id="end_date" type="" class="form-control datepicker_future_not_allow" name="" autocomplete="off" name="" value="">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                          <label class="content-label"> </label>
                          <a href="javascript:void(0)" id="apply_date" class="form-control btn btn-success">Apply
                          </a>
                        </div>
                    </div>
                  </div>
                  <!-- <div class="row ">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="content-label"> </label>
                        <a href="javascript:void(0)" id="download_pdf" class="btn btn-success pull-right form-control waves-effect waves-light">Download PDF
                          </a>
                      </div>
                    </div>
                  </div>
                  <br> -->
                  <div class="table-responsive">
                    <table class="table zero-configuration data_table_ajax" id="order_tbl">

                      <thead>
                        <tr>
                          <th><input type="checkbox" id="mark_all"></th>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('orders.company_name')}}</th>
                          <th>{{trans('orders.order_status')}}</th>
                          <th width="35%">{{trans('orders.shipping_address')}}</th>
                          <th>{{trans('orders.number')}}</th>
                          <th>{{trans('orders.order_amount')}}</th>
                          <th>{{trans('orders.order_type')}}</th>
                          <th>{{trans('orders.print_status')}}</th>
                          <th>{{trans('orders.created_date')}}</th>
                          <th>{{trans('common.action')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th></th>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('orders.company_name')}}</th>
                          <th>{{trans('orders.order_status')}}</th>
                          <th>{{trans('orders.shipping_address')}}</th>
                          <th>{{trans('orders.number')}}</th>
                          <th>{{trans('orders.order_amount')}}</th>
                          <th>{{trans('orders.order_type')}}</th>
                          <th>{{trans('orders.print_status')}}</th>
                          <th>{{trans('orders.created_date')}}</th>
                          <th>{{trans('common.action')}}</th> </tfoot>
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

    $("#start_date,#end_date ").val('');
    fill_datatable();

    function fill_datatable() {

        var print_status = $("#print_status").val();
        var order_status = $("#order_status").val();
        var order_type   = $("#order_type").val();
        var user_type    = $("#user_type").val();
        var start_date   = $("#start_date").val();
        var end_date     = $("#end_date").val();


        $('.data_table_ajax').DataTable({
        "scrollY": 600, "scrollX": true,
        aaSorting : [[1, 'desc']],
        dom: 'Blfrtip',
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
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
                columns: [1, 2 , 3 ,4 ,5,6,7,8,9]
            },
        },{
            
            text: '<span class="fa fa-file-pdf-o"></span> Download Invoice',
            className:"btn btn-md btn-success export-btn",
            attr:{id:"download_pdf",style:"margin-left: 10px;"},
            // exportOptions: {
            //     modifier: {
            //         search: 'applied',
            //         order: 'applied'
            //     },
            //     columns: [1, 2 , 3 ,4 ,5,6,7,8,9]
            // },
        },{
            
            text: '<span class="fa fa-file-pdf-o"></span> Mark As Printed',
            className:"btn btn-md btn-success export-btn",
            attr:{id:"mark_as_printed",style:"margin-left: 10px;"},
            // exportOptions: {
            //     modifier: {
            //         search: 'applied',
            //         order: 'applied'
            //     },
            //     columns: [1, 2 , 3 ,4 ,5,6,7,8,9]
            // },
        }],
        processing: true,
        serverSide: true,
        serverMethod:'POST',
        processing: true,
        bDestroy: true,
        language: {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">{{trans("common.loading")}}</span> '},
        ajax: {
            url: "{{route('dt_orders')}}",
            data: {
                    "_token": "{{csrf_token()}}",
                    'user_type':user_type,
                    'order_type':order_type,
                    'order_status':order_status,
                    'print_status':print_status,
                    'start_date':start_date,
                    'end_date':end_date,
                   },
           //success:function(data) { console.log(data); },
            error:function(data){ console.log(data); },
        },
        columns: [
           { data: 'selected',orderable:false},
           { data: 'id'},
           { data: 'company_name',orderable: false},
           { data: 'order_status',orderable: false},
           { data: 'shipping_address',orderable: false},
           { data: 'number',orderable: false},
           { data: 'order_amount',orderable: false},
           { data: 'order_types',orderable: false},
           { data: 'print_status',orderable: false},
           { data: 'created_date',orderable: false},
           {
            data: '',
                mRender : function(data, type, row) {

                return '@can("order-edit")<a class="" href="'+row["edit"]+'"><i class="fa fa-edit"></i></a>@endcan';
               }, orderable: false, searchable: false
            },
          ]
    });
    }

    $('#order_type,#order_status,#user_type,#print_status').on('change', function(){
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
    
      //if(new Date($('#start_date').val()) > new Date($('#end_date').val())){
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



    $(document).on('click', '#download_pdf', function(){
         var tbl = $('.data_table_ajax').DataTable();
         var length = tbl.page.info().length;
         var row_data =tbl.row(0).data();
         var start_id = row_data.id;
         var order =  tbl.order().toString();
         order = order.split(",");
         var id = [];
         $('.mark:checked').each(function(){
            id.push($(this).val());
         });

        if(confirm("Are you sure want to download pdf?"))
        {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                $.ajax({
                    url:"{{ route('download_order_pdf')}}",
                    method:"post",
                    data:{'id':id,'order':order[1],'length':length,'start_id':start_id},
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.success);
                            setTimeout(function() {
                              window.open(response.data,'_blank');
                            }, 1000);
                        } else {
                            toastr.error(response.error);
                        }
                    }
                });
        }
    });

     $("#mark_all").click(function(){
        var tbl = $('.data_table_ajax').DataTable();
        var length = tbl.page.info().length;
        if(this.checked){
            $('.mark').each(function(){
                //$(".mark").prop('checked', true);
                $(".mark").slice(0,length).prop('checked',true);
            })
        }else{
            $('.mark').each(function(){
                //$(".mark").prop('checked', false);
                $(".mark").slice(0,length).prop('checked',false);
            })
        }
    });


   $(document).on('click', '#mark_as_printed', function(){
        var id = [];
        $('.mark:checked').each(function(){
            id.push($(this).val());
        });
        if(id.length == 0) {
          alert("Please select atleast one checkbox");
          return false;
        }
        if(confirm("Are you sure you want mark orders as printed?"))
        {
            if(id.length > 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                $.ajax({
                    url:"{{ route('bulk_mark_as_printed')}}",
                    method:"post",
                    data:{id:id},
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.success);
                            setTimeout(function() {
                                location.href = "{{ route('orders.index') }}";
                            }, 2000);
                        } else {
                            toastr.error(response.error);
                        }
                    }
                });
            }
            else
            {
                alert("Please select atleast one checkbox");
            }
        }
    });


 });

</script>


@endsection
