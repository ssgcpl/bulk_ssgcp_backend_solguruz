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
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{trans('product_barcodes.heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('product_barcodes.index')}}">{{ trans('product_barcodes.heading') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('product_barcodes.show') }}
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
              {{trans('product_barcodes.heading')}}
              </h4>
              <a href="{{route('product_barcodes.index')}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
              </a>
            </div>
            <div class="card-content">
              <div class="card-body">    
                <form method="POST" id="createForm"  accept-charset="UTF-8" enctype="multipart/form-data" >

                  @csrf
                  <!-- <hr style="border-top: 3px solid blue;"> -->
                  <div class="form-body">
                    <div class="row">
                      <div class="col-md-9">
                         <div class="form-group">
                          <h6>{{ trans('product_barcodes.total_barcode_qty') }} : {{$barcode_qty}}</h6>
                                  </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group float-right">
                             <button id="add_more_barcode" type="button" class="btn btn-success btn-fill btn-wd">{{trans('product_barcodes.add_more_barcode')}}</button>
                        </div>
                      </div>
                    </div>
                       <div class="row">
                      <div class="col-md-4">
                         <div class="form-group">
                          <label for="name_english" class="content-label">{{ trans('product_barcodes.name_english') }}</label>
                                      <input
                                          class="form-control"
                                          placeholder="{{ trans('product_barcodes.name_english') }}"
                                          name="name_english" id="name" value="{{$product->get_name()}}" disabled>
                                  </div>
                      </div>
                      <div class="col-md-4">
                         <div class="form-group">
                          <label for="name_english" class="content-label">{{ trans('product_barcodes.product_id') }}</label>
                                      <input
                                          class="form-control"
                                          placeholder="{{ trans('product_barcodes.product_id') }}"
                                          name="prod_id" id="prod_id" value="{{$product->id}}" disabled>
                                  </div>
                      </div>
                         <div class="col-md-4">
                         <div class="form-group">
                          <label for="name_english" class="content-label">{{ trans('product_barcodes.sku_id') }}</label>
                            <input   class="form-control" placeholder="{{ trans('product_barcodes.sku_id') }}"   name="sku_id" id="sku_id" value="{{$product->sku_id}}" disabled>
                          </div>
                      </div>
                    </div>
                      <div class="row">
                      <div class="col-md-2">
                      <div class="form-group">
                        <label class="content-label">Start Date</label>
                        <input id="start_date" type="" class="form-control datepicker_future_not_allow" autocomplete="off" name="start_date" value="">
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label class="content-label">End Date</label>
                        <input id="end_date" type="" class="form-control datepicker_future_not_allow" name="end_date" autocomplete="off"  value="">
                      </div>
                    </div>
                    <div class="col-md-2">  
                      <div class="form-group">
                        <label class="content-label"> </label>
                        <a href="javascript:void(0)" id="apply_date" class="form-control btn btn-success">Apply
                        </a>
                      </div>
                    </div>

                    <div class="col-md-3">
                      <label class="content-label"> </label>
                      <a href="javascript:void(0)" id="download_pdf" class="btn btn-success pull-right form-control waves-effect waves-light">Download PDF
                        </a>
                    </div>
                 <div class="col-md-3">
                        <label class="content-label"> </label>
                          @if($is_download_requested)
                        <a href="{{asset($is_download_requested->filename)}}" id="download_csv_link" target="_blank" class="btn btn-success pull-right form-control waves-effect waves-light" download="product_all_barcodes_{{$product->id}}.csv">Click Here to download</a>
                        @else
                          <a href="javascript:void(0)" id="download_all_barcodes" class="btn btn-success pull-right form-control waves-effect waves-light">Download All Barcode
                        </a>
                        @endif
                    </div>

                  </div>
                    
                    <div class="table-responsive">
                    <table class="table zero-configuration data_table_ajax">
                      <thead>
                        <tr>
                          <th><input type="checkbox" id="mark_all"></th>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('product_barcodes.product_title')}}</th>
                          <th>{{trans('product_barcodes.barcode_value')}}</th>
                          <th>{{trans('product_barcodes.barcode_image')}}</th>
                          <th>{{trans('product_barcodes.created_date')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr><th></th>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('product_barcodes.product_title')}}</th>
                          <th>{{trans('product_barcodes.barcode_value')}}</th>
                          <th>{{trans('product_barcodes.barcode_image')}}</th>
                          <th>{{trans('product_barcodes.created_date')}}</th>
                     </tfoot>
                    </table>
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


<div class="modal" tabindex="-1" role="dialog" id="add_barcode_popup">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="add_barcode_form" method="post">
        @csrf
        <div class="modal-body">
          <input type="hidden" class="" id="product_id" name="product_id" value="{{$product->id}}">
          <label>Enter Quantity</label>
          <input type="text" class="form-control numberonly" id="barcode_qty" name="barcode_qty">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" id="add_barcode_btn">Generate</button>
         </div>
      </form>
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

      var start_date = $("#start_date").val();
      var end_date = $("#end_date").val();

      $('.data_table_ajax').DataTable({ 
        aaSorting : [[1, 'desc']],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        pageLength: 10,
        serverSide: true,
        serverMethod:'POST',
        dom:'Bflrtip',
        processing:true,
        bDestroy:true,
        buttons: [/*{
            extend: 'csv',
            text: '<span class="fa fa-file-pdf-o"></span> {{trans("customers.download_csv")}}',
            className:"btn btn-md btn-success export-btn",
            exportOptions: {
                modifier: {
                    search: 'applied',
                    order: 'applied'
                },
                columns: [1,2,3,5]
            }
          },*/
          {
            extend: 'pdf',
            text: '<span class="fa fa-file-pdf-o"></span> {{trans("customers.download_pdf")}}',
            className:"btn btn-md btn-success export-btn",
            exportOptions: {
                modifier: {
                    search: 'applied',
                    order: 'applied'
                },
                columns: [1,2,3,5 ]
            },
        }],
         drawCallback: function() {
          var hasRows = this.api().rows({ filter: 'applied' }).data().length > 0;
          //$('.buttons-csv')[0].style.visibility = hasRows ? 'visible' : 'hidden'
        //  $('.buttons-pdf')[1].style.visibility = hasRows ? 'visible' : 'hidden'
        },
        language: {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">{{trans("common.loading")}}</span> '},
        ajax: {
            url: "{{route('dt_barcodes')}}",
            dataSrc: 'aaData',
            data: {
                    "_token": "{{csrf_token()}}",
                    "start_date":start_date,
                    "end_date":end_date,
                    "product_id" : $("#prod_id").val()
                    },
           //success:function(data) { console.log(data); },
            error:function(data){ console.log(data); },
        },
        columns: [
           {data:'selected',orderable:false},
           { data: 'id',orderable:true},
           { data:'product_title','visible':false},
           { data: 'barcode_value',orderable: false},
           { data: 'barcode_image',orderable: false},
           { data: 'created_date',orderable: false},
          ]
    });
    }


    $('#start_date,#end_date').on('change', function(){
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

     $('body').on('click', '#add_more_barcode', function() {
      var id = $(this).attr('data_id');
      $('#add_barcode_popup').modal();
    });


      $('#add_barcode_form').submit(function(evt){
          // tinyMCE.triggerSave();
          evt.preventDefault();
          var form = $('#add_barcode_form')[0];
          var data = new FormData(form);
           // console.log(data); return false;
          $.ajax({
                  url: "{{route('product_barcodes.store')}}",
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
                          setTimeout(function(){
                            location.reload();
                          }, 2000);
                      } else {
                          toastr.error(response.error);
                      }
                      $('#action_btn').prop('disabled',false);
                      $('#action_btn').text("{{trans('common.submit')}}");
                  }
          });
          //console.log('data',data)
    }); 


    $(document).on('click', '#download_pdf', function(){
        var id = [];
        var product_id = $("#prod_id").val();
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
                    url:"{{ route('download_pdf')}}",
                    method:"post",
                    data:{'id':id,'prod_id':product_id},
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


    $(document).on('click', '#download_all_barcodes', function(){
       var product_id = $("#prod_id").val();
       if(confirm("Are you sure want to download all barcodes?"))
        {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                $.ajax({
                    url:"{{ route('download_barcode_csv')}}",
                    method:"post",
                    data:{'product_id':product_id},
                    beforeSend: function(xhr){
                      $('#download_all_barcodes').prop('disabled',true);
                      $('#download_all_barcodes').text("{{trans('common.downloading')}}");
                    },
                    success: function(response) {
                        if (response.success) {
                           toastr.success(response.success);
                           location.reload(true);
                        } else {
                            toastr.error(response.error);
                        }
                        $('#download_all_barcodes').prop('disabled',false);
                        $('#download_all_barcodes').text("Download All Barcode");
                         
                    }
                });
           
        }
    }); 

    $(document).on("click","#download_csv_link",function(){
            var product_id = $("#prod_id").val();
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
            $.ajax({
                    url:"{{ route('reset_download')}}",
                    method:"post",
                    data:{'product_id':product_id},
                    /*beforeSend: function(xhr){
                      $('#download_all_barcodes').prop('disabled',true);
                      $('#download_all_barcodes').text("{{trans('common.downloading')}}");
                    },*/
                    success: function(response) {
                        if (response.success) {
                          //toastr.success(response.success);
                          location.reload(true);
                        } else {
                          toastr.error(response.error);
                        }
                       
                    }
                });
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

  })
</script>
@endsection