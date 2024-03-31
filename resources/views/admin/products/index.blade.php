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
                    <h2 class="content-header-title float-left mb-0">{{ trans('products.heading') }}</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('products.plural') }}
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
                <h4 class="card-title">{{ trans('products.title') }}</h4>
              
                @can('product-create')
                  <div class="box-tools pull-right">
                    <a href="{{ route('products.create') }}" class="btn btn-success pull-right">
                    {{ trans('products.add_new') }}
                    </a>
                  </div>
                @endcan
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="row">

                    <div class="col-md-2">
                        <div class="form-group">
                          <label class="">{{trans('common.type')}}</label>
                          <select id="business_category_id" class = "form-control" name=''>
                            <option value=''>{{trans('common.select')}}</option>
                            @foreach($type as $t)
                            <option value="{{$t->id}}">{{ucfirst($t->category_name) }}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="col-md-2">
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
                          <label class="">{{trans('products.visible_to')}}</label>
                          <select id="visible_to" class = "form-control" name=''>
                          <option value=''>{{trans('common.select')}}</option>
                          <option value="dealer">{{trans('products.dealer') }}</option>
                          <option value="retailer">{{trans('products.retailer') }}</option>
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
                  <div class="table-responsive" width="100%">
                    <table class="table zero-configuration data_table_ajax" width="100%">
                      <thead>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('products.product_image')}}</th>
                          <th width="15%">{{trans('products.name')}}</th>
                          <th>{{trans('products.sku_id')}}</th>
                          <th>{{trans('products.stock_status')}}</th>
                          <th>{{trans('products.mrp')}}</th>
                          <th>{{trans('products.dealer_sale_price')}}</th>
                          <th>{{trans('products.retailer_sale_price')}}</th>
                          <th>{{trans('products.created_date')}}</th>
                          <th>{{trans('products.visible_to')}}</th>
                          <th>{{trans('products.type')}}</th>
                          <th>{{trans('products.stock')}}</th>
                          <th>{{trans('common.status')}}</th>
                          <th>{{trans('common.action')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('products.product_image')}}</th>
                          <th width="15%">{{trans('products.name')}}</th>
                          <th>{{trans('products.sku_id')}}</th>
                          <th>{{trans('products.stock_status')}}</th>
                          <th>{{trans('products.mrp')}}</th>
                          <th>{{trans('products.dealer_sale_price')}}</th>
                          <th>{{trans('products.retailer_sale_price')}}</th>
                          <th>{{trans('products.created_date')}}</th>
                          <th>{{trans('products.visible_to')}}</th>
                          <th>{{trans('products.type')}}</th>
                          <th>{{trans('products.stock')}}</th>
                          <th>{{trans('common.status')}}</th>
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
        var business_category_id         = $("#business_category_id").val();
        var start_date   = $("#start_date").val();
        var end_date     = $("#end_date").val();


       var table = $('.data_table_ajax').DataTable({ 
        "scrollY": 300, "scrollX": true,
        aaSorting : [[8, 'desc'],[0, 'desc']],
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
                columns: [0, 1, 2 , 3 ,4 ,5,6,7,8,9,10,11,12]
            },
        }],
        processing: true,
        serverSide: true,
        serverMethod:'POST',
        processing: true,
        bDestroy: true,
        language: {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">{{trans("common.loading")}}</span> '},
        ajax: {
            url: "{{route('dt_products')}}",
            data: {
                    "_token": "{{csrf_token()}}",
                    'business_category_id' : business_category_id,
                    'visible_to':visible_to,
                    'stock_status':stock_status,
                    'start_date':start_date,
                    'end_date':end_date,
                   },
           //success:function(data) { console.log(data); },
            error:function(data){ console.log(data); },
        },
        columns: [
           { data: 'id',orderable: true},
           { data: 'image',orderable: false},
           { data: 'name',orderable: false},
           { data: 'sku_id',orderable: false},
           { data: 'stock_status',orderable: true},
           { data: 'mrp',orderable: true},
           { data: 'dealer_sale_price',orderable: false},
           { data: 'retailer_sale_price',orderable: false},
           { data: 'published_at',  
            "render": function (data, type, row) {
                  data = moment(data).format('DD-MM-YYYY HH:mm A');
                  return data;},orderable: true},
           { data: 'visible_to',orderable: false},
           { data: 'type',orderable: false},
           { data: 'stock',orderable:false},
           { data: 'status',
              mRender : function(data, type, row) {

                  var status=data;

                  if(status=='active'){
                    type = "checked";
                  }else{
                    type = '';
                  }
                  var status_label = status.charAt(0).toUpperCase() +""+status.slice(1);
                  return '<label>'+status_label +'</label><div class="custom-control custom-switch custom-switch-success mr-2 mb-1"><input type="checkbox"'+type+' + class="status custom-control-input" id="customSwitch'+row["id"]+'" data_id="'+row["id"]+'"><label class="custom-control-label" for="customSwitch'+row["id"]+'"></label></div>';

                },orderable: false
           },
           {
            data: 'is_live',
                mRender : function(data, type, row) {
                var is_live=data;

                  if(is_live=='1'){
                    type = "checked";
                  }else{
                    type = '';
                  }

                return '<label><input name ="publish" class= "is_live" type=checkbox data_id="'+row["id"]+'" '+type+'>{{trans("business_categories.publish")}}</label> @can("product-edit")<a class="" href="'+row["edit"]+'"><i class="fa fa-edit"></i></a>@endcan <a class="" href="'+row["show"]+'"><i class="fa fa-eye"></i></a>@csrf</form>';
               }, orderable: false, searchable: false
            },
          ]
    });

       // table.columns.adjust().draw();
    }

    $('#business_category_id,#stock_status,#visible_to').on('change', function(){
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

  $(document).on('change','.is_live',function(){
      var is_live = this.checked ? 1 : 0;
      var confirm = is_live_alert(is_live);
      if(confirm){
        var id = $(this).attr('data_id');
        var delay = 500;
        var element = $(this);
        $.ajax({
            type:'post',
            url: "{{route('is_live_product')}}",
            data: {
                    "is_live": is_live,
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
              toastr.success(data.success);
              location.reload();

            },
            error: function () {
              toastr.error(data.error);
            }
        })

      }else{
      location.reload();
      }
    })

  });

</script>
<script>
    function status_alert(status) {
    if(status == 'active')
    {
      if(confirm("{{trans('common.confirm_status_active')}}")){
        return true;
      }else{
        return false;
      }
    }
      else if(status =='inactive')
      {
        if(confirm("{{trans('common.confirm_status_inactive')}}")){
        return true;
          }else{
            return false;
          }
      }
    }

    function is_live_alert(is_live) {
      if(is_live == '1')
      {
        if(confirm("{{trans('common.confirm_is_live_active')}}")){
          return true;
        }else{
          return false;
        }
      }
      else if(is_live =='0')
      {
        if(confirm("{{trans('common.confirm_is_live_inactive')}}")){
        return true;
          }else{
            return false;
          }
      }
    }



    
</script>

@endsection
