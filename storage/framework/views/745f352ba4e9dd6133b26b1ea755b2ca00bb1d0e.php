
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
                    <h2 class="content-header-title float-left mb-0"><?php echo e(trans('product_barcodes.heading')); ?></h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(trans('common.home')); ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?php echo e(trans('product_barcodes.plural')); ?>

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
                <h4 class="card-title"><?php echo e(trans('product_barcodes.title')); ?></h4>
              
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-create')): ?>
                  <div class="box-tools pull-right">
                    <a href="<?php echo e(route('product_barcodes.create')); ?>" class="btn btn-success pull-right">
                    <?php echo e(trans('product_barcodes.add_new')); ?>

                    </a>
                  </div>
                <?php endif; ?>
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="row">

                <div class="col-md-2">
                    <div class="form-group">
                      <label class=""><?php echo e(trans('common.type')); ?></label>
                      <select id="type" class = "form-control" name=''>
                        <option value=''><?php echo e(trans('common.select')); ?></option>
                        <?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t->id); ?>"><?php echo e(ucfirst($t->category_name)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                         <!--  <th><input type="checkbox" id="mark_all"></th> -->
                          <th><?php echo e(trans('product_barcodes.sr_no')); ?></th>
                          <th><?php echo e(trans('product_barcodes.id')); ?></th>
                         <!--  <th><?php echo e(trans('product_barcodes.product_id')); ?></th> -->
                          <th width="15%"><?php echo e(trans('product_barcodes.name_english')); ?></th>
                          <th><?php echo e(trans('product_barcodes.sku_id')); ?></th>
                          <th><?php echo e(trans('product_barcodes.created_date')); ?></th>
                          <th><?php echo e(trans('product_barcodes.type')); ?></th>
                          <th><?php echo e(trans('product_barcodes.total_barcode_qty')); ?></th>
                          <th><?php echo e(trans('common.action')); ?></th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                         <!--  <th></th> -->
                           <th><?php echo e(trans('product_barcodes.sr_no')); ?></th>
                          <th><?php echo e(trans('product_barcodes.id')); ?></th>
                          <!-- <th><?php echo e(trans('product_barcodes.product_id')); ?></th> -->
                          <th><?php echo e(trans('product_barcodes.name_english')); ?></th>
                          <th><?php echo e(trans('product_barcodes.sku_id')); ?></th>
                          <th><?php echo e(trans('product_barcodes.created_date')); ?></th>
                          <th><?php echo e(trans('product_barcodes.type')); ?></th>
                          <th><?php echo e(trans('product_barcodes.total_barcode_qty')); ?></th>
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

<div class="modal" tabindex="-1" role="dialog" id="add_barcode_popup">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="add_barcode_form" method="post">
        <?php echo csrf_field(); ?>
        <div class="modal-body">
          <input type="hidden" class="" id="product_id" name="product_id" >
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
  $(document).ready(function(){

    $("#start_date,#end_date ").val('');
    fill_datatable();

    function fill_datatable() {

        var type         = $("#type").val();
        var start_date   = $("#start_date").val();
        var end_date     = $("#end_date").val();
        var info = '';
        var j = 1;
        $('.data_table_ajax').DataTable({ 
        //aaSorting : [[4, 'desc']],
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
                columns: [0, 1, 2 , 3 ,4 ,5,6]
            },

        },
      ],
        processing: true,
        serverSide: true,
        serverMethod:'POST',
        processing: true,
        bDestroy: true,
        language: {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"><?php echo e(trans("common.loading")); ?></span> '},
        ajax: {
            url: "<?php echo e(route('dt_product_barcodes')); ?>",
            data: {
                    "_token": "<?php echo e(csrf_token()); ?>",
                    'type' : type,
                    'start_date':start_date,
                    'end_date':end_date,
                   },
            //success:function(data) { console.log(data); },
            error:function(data){ console.log(data); },
        },
      /*    initComplete: function () {
            //After the ajax has run, initComplete can get the total count of lines .
             info = this.api().page.info();
             console.log('Total records', info.recordsTotal);
        },*/
       /* "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:first", nRow).html(iDisplayIndex +1);
               return nRow;
            },*/
        columns: [
        /*   { data:'selected',orderable:false },*/
           { render: function (data, type, row, meta) {
        return meta.row + meta.settings._iDisplayStart + 1;
        }, orderable:false},
           { data: 'id',orderable: false},
           { data: 'name',orderable: false},
           { data: 'sku_id',orderable: false},
           { data: 'created_at',  
           "render": function (data, type, row) {
                  data = moment(data).format('DD-MM-YYYY hh:mm A');
                  return data;},orderable: false},
           { data: 'type',orderable: false},
           { data: 'total_barcode_qty',orderable: false},
           {
                mRender:function(data,type,row){
                return '<a class="" href="'+row["show"]+'"><i class="fa fa-eye"></i></a><br>'+row['link'];
                 },orderable:false
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
       fill_datatable();   
      }
       
    }else{
      toastr.error('Please select start date and end date');
      return false;
    }
  });

     $('body').on('click', '.add_more_barcode', function() {
      var id = $(this).attr('id');
      $("#product_id").val(id);
      $('#add_barcode_popup').modal();

    });


      $('#add_barcode_form').submit(function(evt){
          // tinyMCE.triggerSave();
          evt.preventDefault();
          var form = $('#add_barcode_form')[0];
          var data = new FormData(form);
          
           // console.log(data); return false;
          $.ajax({
                  url: "<?php echo e(route('product_barcodes.store')); ?>",
                  data: data,
                  type: "POST",
                  processData : false,
                  contentType : false,
                  dataType : 'json',
                  beforeSend: function(xhr){
                      $('#action_btn').prop('disabled',true);
                      $('#action_btn').text("<?php echo e(trans('common.submitting')); ?>");
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
                      $('#action_btn').text("<?php echo e(trans('common.submit')); ?>");
                  }
          });
          //console.log('data',data)
    }); 


     
/*    $(document).on('click', '#download_pdf', function(){
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
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                });
                $.ajax({
                    url:"<?php echo e(route('download_pdf')); ?>",
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
*/
 

  });

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/admin/product_barcodes/index.blade.php ENDPATH**/ ?>