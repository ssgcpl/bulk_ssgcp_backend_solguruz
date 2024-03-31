
<?php $__env->startSection('vendor_css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/vendors/css/tables/datatable/datatables.min.css')); ?>">
<style type="text/css">
  td form{
    display: inline;
  }
  .dt-buttons{margin-right: 10px}
  .select2-selection {padding: 0px 5px !important}
  .update_stock_popup {cursor: pointer; color: blue}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-overlay"></div>
  <div class="header-navbar-shadow"></div>
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0"><?php echo e(trans('stocks.stock_report_heading')); ?></h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(trans('common.home')); ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?php echo e(trans('stocks.stock_report_plural')); ?>

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
                <h4 class="card-title"><?php echo e(trans('stocks.stock_report_heading')); ?></h4>
              
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-create')): ?>
                <!--   <div class="box-tools pull-right">
                    <a href="<?php echo e(route('products.create')); ?>" class="btn btn-success pull-right">
                    <?php echo e(trans('products.add_new')); ?>

                    </a>
                  </div> -->
                <?php endif; ?>
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <label class=""><?php echo e(trans('products.stock_status')); ?></label>
                        <select id="stock_status" class = "form-control" name=''>
                          <option value=''><?php echo e(trans('common.select')); ?></option>
                          <option value="in_stock"><?php echo e(trans('products.in_stock')); ?></option>
                          <option value="out_of_stock"><?php echo e(trans('products.out_of_stock')); ?></option>
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
                    <table class="table zero-configuration data_table_ajax">
                      <thead>
                        <tr>
                          <th><input type="checkbox" id="mark_all"></th>
                          <th><?php echo e(trans('common.id')); ?></th>
                          <th><?php echo e(trans('products.product_image')); ?></th>
                          <th><?php echo e(trans('products.name')); ?></th>
                          <th><?php echo e(trans('products.sku_id')); ?></th>
                          <th><?php echo e(trans('products.stock_status')); ?></th>
                          <th><?php echo e(trans('products.mrp')); ?></th>
                          <th><?php echo e(trans('products.dealer_sale_price')); ?></th>
                          <th><?php echo e(trans('products.retailer_sale_price')); ?></th>
                          <th><?php echo e(trans('products.created_date')); ?></th>
                          <th><?php echo e(trans('products.visible_to')); ?></th>
                          <th><?php echo e(trans('products.type')); ?></th>
                          <th><?php echo e(trans('stocks.total_pof')); ?></th>
                          <th><?php echo e(trans('stocks.total_ecm')); ?></th>
                          <th><?php echo e(trans('stocks.total_gro')); ?></th>
                          <th><?php echo e(trans('stocks.return_qty')); ?></th>
                          <th><?php echo e(trans('stocks.supplied_qty')); ?></th>
                          <th><?php echo e(trans('stocks.balance_outside')); ?></th>
                          <th><?php echo e(trans('stocks.balance_inside')); ?></th>
                          <th><?php echo e(trans('stocks.scrap_qty')); ?></th>
                          <th><?php echo e(trans('stocks.total_balance')); ?></th>
                       <!--     <th><?php echo e(trans('common.status')); ?></th> -->
                          <th><?php echo e(trans('common.action')); ?></th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th></th>
                          <th><?php echo e(trans('common.id')); ?></th>
                          <th><?php echo e(trans('products.product_image')); ?></th>
                          <th><?php echo e(trans('products.sub_heading')); ?></th>
                          <th><?php echo e(trans('products.sku_id')); ?></th>
                          <th><?php echo e(trans('products.stock_status')); ?></th>
                          <th><?php echo e(trans('products.mrp')); ?></th>
                          <th><?php echo e(trans('products.dealer_sale_price')); ?></th>
                          <th><?php echo e(trans('products.retailer_sale_price')); ?></th>
                          <th><?php echo e(trans('products.created_date')); ?></th>
                          <th><?php echo e(trans('products.visible_to')); ?></th>
                          <th><?php echo e(trans('products.type')); ?></th>
                          <th><?php echo e(trans('stocks.total_pof')); ?></th>
                          <th><?php echo e(trans('stocks.total_ecm')); ?></th>
                          <th><?php echo e(trans('stocks.total_gro')); ?></th>
                          <th><?php echo e(trans('stocks.return_qty')); ?></th>
                          <th><?php echo e(trans('stocks.supplied_qty')); ?></th>
                          <th><?php echo e(trans('stocks.balance_outside')); ?></th>
                          <th><?php echo e(trans('stocks.balance_inside')); ?></th>
                          <th><?php echo e(trans('stocks.scrap_qty')); ?></th>
                          <th><?php echo e(trans('stocks.total_balance')); ?></th>
                       
                       <!--    <th><?php echo e(trans('common.status')); ?></th> -->
                           <th><?php echo e(trans('common.action')); ?></th></tr> 
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


<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_js'); ?>
<!-- BEGIN: Page Vendor JS-->
<script src="<?php echo e(asset('admin_assets/app-assets/vendors/js/tables/datatable/datatables.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js')); ?>"></script>
<!-- END: Page Vendor JS-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script src="<?php echo e(asset('admin_assets/custom/data_tables/export/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/custom/data_tables/export/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/custom/data_tables/export/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/custom/data_tables/export/buttons.print.min.js')); ?>"></script>
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
            url: "<?php echo e(route('status_product')); ?>",
            data: {
                    "status": status,
                    "id" : id,
                    "_token": "<?php echo e(csrf_token()); ?>"
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
            text: '<span class="fa fa-file-pdf-o"></span> <?php echo e(trans("customers.download_csv")); ?>',
            className:"btn btn-md btn-success export-btn",
            exportOptions: {
                modifier: {
                    search: 'applied',
                    order: 'applied'
                },
                columns: [1, 2 , 3 ,4 ,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]
            },
        },{
            
            text: '<span class="fa fa-file-pdf-o"></span>Download PDF',
            className:"btn btn-md btn-success export-btn",
            attr:{id:"download_pdf",style:"margin-left: 10px;"},
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
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"><?php echo e(trans("common.loading")); ?></span> '},
        ajax: {
            url: "<?php echo e(route('dt_stock_report')); ?>",
            data: {
                    "_token": "<?php echo e(csrf_token()); ?>",
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
           { data: 'image',orderable: true},
           { data: 'name',orderable: false},
           { data: 'sku_id',orderable: false},
           { data: 'stock_status',orderable: false},
           { data: 'mrp',orderable: true},
           { data: 'dealer_sale_price',orderable: false},
           { data: 'retailer_sale_price',orderable: false},
           { data: 'created_date',orderable: false},
           { data: 'visible_to',orderable: false},
           { data: 'type',orderable: false},
           { data: 'total_pof',orderable:false},
           { data: 'total_ecm',orderable:false},
           { data: 'total_gro',orderable:false},
           { data: 'return_go_down',orderable:false},
           { data: 'order_supplied',orderable:false},
           { data: 'balance_outside',orderable:false},
           { data: 'balance_inside',orderable:false},
           { data: 'scrap_qty',orderable:false},
           { data: 'total_balance',orderable:false},
           {
            data: '',
                mRender : function(data, type, row) {
                
                return '<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("stock-edit")): ?><a class="" href="'+row["edit"]+'"><i class="fa fa-edit"></i></a><?php endif; ?>';
               }, orderable: false, searchable: false
            },
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

      $(document).on('click', '#download_pdf', function(){
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
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                });
                $.ajax({
                    url:"<?php echo e(route('download_stock_report_pdf')); ?>",
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/admin/stock_report/index.blade.php ENDPATH**/ ?>