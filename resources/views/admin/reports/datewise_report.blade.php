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
                    <h2 class="content-header-title float-left mb-0">{{ trans('stocks.datewise_stock_report_heading') }}</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('stocks.datewise_stock_report_heading') }}
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
                <h4 class="card-title">{{ trans('stocks.datewise_stock_report_heading') }}</h4>
              
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="row">
                    <div class="col-md-2 d-none">
                      <div class="form-group">
                        <label class="">{{trans('products.stock_status')}}</label>
                        <select id="stock_status" class = "form-control" name=''>
                          <option value=''>{{trans('common.select')}}</option>
                          <option value="in_stock">{{trans('products.in_stock') }}</option>
                          <option value="out_of_stock">{{trans('products.out_of_stock') }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label class="content-label">Start Date</label>
                        <input id="start_date" type="" class="form-control datepicker_future_not_allow" autocomplete="off" name="end_date" value="">
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label class="content-label">End Date</label>
                        <input id="end_date" type="" class="form-control datepicker_future_not_allow" name="end_date" autocomplete="off" name="" value="">
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
                    <table class="table zero-configuration data_table_ajax" width="100%">
                      <thead>
                        <tr>
                          <th><input type="checkbox" id="mark_all"></th>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('products.title_of_book')}}</th>
                          <th>{{trans('products.sku_id')}}</th>
                          <th>{{trans('products.order_on_hold_qty')}}</th>
                          <th>{{trans('stocks.balance_inside_qty')}}</th>
                          <th>{{trans('stocks.need')}}</th>
                          <th>{{trans('stocks.balance_outside_qty')}}</th>
                          <th>{{trans('stocks.possiable_book_qty')}}</th>
                          <th>{{trans('stocks.order_qty')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th></th>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('products.title_of_book')}}</th>
                          <th>{{trans('products.sku_id')}}</th>
                          <th>{{trans('products.order_on_hold_qty')}}</th>
                          <th>{{trans('stocks.balance_inside_qty')}}</th>
                          <th>{{trans('stocks.need')}}</th>
                          <th>{{trans('stocks.balance_outside_qty')}}</th>
                          <th>{{trans('stocks.possiable_book_qty')}}</th>
                          <th>{{trans('stocks.order_qty')}}</th>
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

  $(document).on('change','.status',function(){
      var status = this.checked ? 'active' : 'inactive';
      var confirm = status_alert(status);
      if(confirm){
        var id = $(this).attr('data_id');
        var delay = 500;
        var element = $(this);
        $.ajax({
            type:'post',
            url: "{{route('status_product')}}",
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

</script>
<script type="text/javascript">
  $(document).ready(function(){

    $("#start_date,#end_date ").val('');
    fill_datatable();

    function fill_datatable() {

        var stock_status = $("#stock_status").val();
        var visible_to   = $("#visible_to").val();
        var type         = $("#type").val();
        var start_date   = $("#start_date").val();
        var end_date     = $("#end_date").val();



        $('.data_table_ajax').DataTable({ 
        "scrollY": 300, "scrollX": true,
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
                columns: [1,2,3,4,5,6,7,8,9]
            },
        },
        //{
        //     text: '<span class="fa fa-file-pdf-o"></span>Download PDF',
        //     className:"btn btn-md btn-success export-btn",
        //     attr:{id:"download_pdf",style:"margin-left: 10px;"},
        //     // exportOptions: {
        //     //     modifier: {
        //     //         search: 'applied',
        //     //         order: 'applied'
        //     //     },
        //     //     columns: [1, 2 , 3 ,4 ,5,6,7,8,9]
        //     // },
        // }
      ],
        processing: true,
        serverSide: true,
        serverMethod:'POST',
        processing: true,
        bDestroy: true,
        language: {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">{{trans("common.loading")}}</span> '},
        ajax: {
            url: "{{route('dt_datewise_report')}}",
            data: {
                    "_token": "{{csrf_token()}}",
                    'stock_status':stock_status,
                    'start_date':start_date,
                    'end_date' : end_date,
                   },
           //success:function(data) { console.log(data); },
            error:function(data){ console.log(data); },
        },
        columns: [
           { data: 'selected',orderable:false},
           { data: 'id'},
           { data: 'name',orderable: false},
           { data: 'sku_id',orderable: false},
           { data: 'total_hold_order_qty',orderable:false},
           { data: 'balance_inside',orderable:false},
           { data: 'need',orderable:false},
           { data: 'balance_outside',orderable:false},
           { data: 'possiable_book_qty',orderable:false},
           { data: 'order_qty',orderable:false},

          ]
    });
    }

    $('#type,#stock_status,#visible_to').on('change', function(){
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
       $(".data_table_ajax").DataTable().destroy(); 
       fill_datatable();   
      }
       
    }else{
      toastr.error('Please select start date and end date');
      return false;
    }
  });

     $("#mark_all").click(function(){
        if(this.checked){
            $('.mark').each(function(){
                $(".mark").prop('checked', true);
            })
        }else{
            $('.mark').each(function(){
                $(".mark").prop('checked', false);
            })
        }
    });

      $(document).on('click', '#download_pdfs', function(){
        var id = [];
          var tbl = $('.data_table_ajax').DataTable();
         var length = tbl.page.info().length;
         var row_data =tbl.row(0).data();
         var start_id = row_data.id;
         var order =  tbl.order().toString();
         order = order.split(",");
       
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
                    url:"{{ route('download_stock_report_pdf')}}",
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

  });

</script>

@endsection
