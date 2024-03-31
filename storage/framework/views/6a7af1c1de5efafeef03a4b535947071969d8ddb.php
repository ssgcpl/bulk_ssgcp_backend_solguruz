
<?php $__env->startSection('vendor_css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/vendors/css/tables/datatable/datatables.min.css')); ?>">
<style type="text/css">
  td form{
    display: inline;
  }
  .dt-buttons{margin-right: 10px}
  .select2-selection {padding: 0px 5px !important}
  .update_stocks_transfer_popup {cursor: pointer; color: blue}
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
                    <h2 class="content-header-title float-left mb-0"><?php echo e(trans('stocks_transfer.heading')); ?></h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(trans('common.home')); ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?php echo e(trans('stocks_transfer.plural')); ?>

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
                <h4 class="card-title"><?php echo e(trans('stocks_transfer.title')); ?></h4>
              
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stock-create')): ?>
                  <div class="box-tools pull-right">
                    <a href="<?php echo e(route('stocks.index')); ?>?prod_id=<?php echo e($_REQUEST['prod_id']); ?>" class="btn btn-success" style="margin-right: 10px;">
                        <i class="fa fa-arrow-left"></i>
                        <?php echo e(trans('common.back')); ?>

                    </a>
                    <a href="<?php echo e(route('stocks_transfer.create')); ?>?prod_id=<?php echo e($_REQUEST['prod_id']); ?>" class="btn btn-success pull-right">
                    <?php echo e(trans('stocks_transfer.add_new')); ?>

                    </a>
                  </div>
                <?php endif; ?>
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <input type="hidden" name="product_id" id="product_id" value="<?php echo e($_REQUEST['prod_id']); ?>">
                  <div class="table-responsive">
                    <table class="table zero-configuration data_table_ajax">
                      <thead>
                        <tr>
                          <th><?php echo e(trans('common.id')); ?></th>
                          <th><?php echo e(trans('stocks_transfer.gto_in_no')); ?></th>
                          <th><?php echo e(trans('stocks_transfer.gto_in_qty')); ?></th>
                          <th><?php echo e(trans('stocks_transfer.gto_out_no')); ?></th>
                          <th><?php echo e(trans('stocks_transfer.gto_out_qty')); ?></th>
                          <th><?php echo e(trans('stocks_transfer.scrap_no')); ?></th>
                          <th><?php echo e(trans('stocks_transfer.scrap_qty')); ?></th>
                          <th><?php echo e(trans('stocks_transfer.created_date')); ?></th>
                          <th><?php echo e(trans('common.action')); ?></th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                           <th><?php echo e(trans('common.id')); ?></th>
                          <th><?php echo e(trans('stocks_transfer.gto_in_no')); ?></th>
                          <th><?php echo e(trans('stocks_transfer.gto_in_qty')); ?></th>
                          <th><?php echo e(trans('stocks_transfer.gto_out_no')); ?></th>
                          <th><?php echo e(trans('stocks_transfer.gto_out_qty')); ?></th>
                          <th><?php echo e(trans('stocks_transfer.scrap_no')); ?></th>
                          <th><?php echo e(trans('stocks_transfer.scrap_qty')); ?></th>
                          <th><?php echo e(trans('stocks_transfer.created_date')); ?></th>
                          <th><?php echo e(trans('common.action')); ?></th>
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
  $(document).ready(function(){

    fill_datatable();

    function fill_datatable() {


        $('.data_table_ajax').DataTable({ 
       // "scrollY": 300, "scrollX": true,
        aaSorting : [[0, 'desc']],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        pageLength: 10,
        serverSide: true,
        serverMethod:'POST',
        language: {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"><?php echo e(trans("common.loading")); ?></span> '},
        ajax: {
            url: "<?php echo e(route('dt_stocks_transfer')); ?>",
            data: {
                    "_token": "<?php echo e(csrf_token()); ?>",
                    'product_id' : $("#product_id").val(),
                    },
           //success:function(data) { console.log(data); },
            error:function(data){ console.log(data); },
        },
        columns: [
           { data: 'id'},
           { data: 'gto_in_no',orderable: true},
           { data: 'gto_in_qty',orderable: false},
           { data: 'gto_out_no',orderable: true},
           { data: 'gto_out_qty',orderable: false},
           { data: 'scrap_no',orderable: true},
           { data: 'scrap_qty',orderable: false},
           { data: 'created_date',orderable: false},
           {
                mRender:function(data,type,row){
                return '<a class="" href="'+row["show"]+'"><i class="fa fa-eye"></i></a> <a class="" href="'+row["edit"]+'"><i class="fa fa-edit"></i></a> <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("stock-delete")): ?><a class="" href="#"onclick=" return delete_alert('+row["id"]+') "><i class="fa fa-trash"></i><form action="'+row["delete"]+'" method="post" id="form'+row["id"]+'"><?php echo method_field("delete"); ?><?php echo csrf_field(); ?></form></a><?php endif; ?>';
                 }
          },
          ]
    });
    }
  });
</script>
<script type="text/javascript">
   function delete_alert(id) {
      if(confirm("<?php echo e(trans('common.confirm_delete')); ?>")){
        $("#form"+id).submit();
        return true;
      }else{
        return false;
      }
    }
</script>
 
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/admin/stocks_transfer/index.blade.php ENDPATH**/ ?>