
<?php $__env->startSection('vendor_css'); ?>
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(asset('admin_assets/app-assets/vendors/css/tables/datatable/datatables.min.css')); ?>">
    <style type="text/css">
        td form {
            display: inline;
        }

        .delete_btn {
            padding: 0 !important;
            background-color: none;
        }

        .dt-buttons {
            margin-right: 10px
        }

        .select2-selection {
            padding: 0px 5px !important
        }

        .update_stocks_popup {
            cursor: pointer;
            color: blue
        }
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
                            <h2 class="content-header-title float-left mb-0"><?php echo e(trans('stocks.heading')); ?></h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(trans('common.home')); ?></a>
                                    </li>
                                    <li class="breadcrumb-item active"><?php echo e(trans('stocks.plural')); ?>

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
                                    <h4 class="card-title"><?php echo e(trans('stocks.title')); ?></h4>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stock-create')): ?>
                                        <div class="box-tools pull-right">

                                            <a href="<?php echo e(route('stocks.create')); ?>?prod_id=<?php echo e($_REQUEST['prod_id']); ?>"
                                                class="btn btn-success pull-right">
                                                <?php echo e(trans('stocks.add_new')); ?>

                                            </a>
                                            <a href="<?php echo e(route('stocks_transfer.index')); ?>?prod_id=<?php echo e($_REQUEST['prod_id']); ?>"
                                                class="btn btn-success pull-right" style="margin-right:10px">
                                                <?php echo e(trans('stocks_transfer.transfer_stock')); ?>

                                            </a>
                                            <a href="<?php echo e(route('stock_report.index')); ?>" style="margin-right:10px"
                                                class="btn btn-success pull-right">
                                                <i class="fa fa-arrow-left"></i>
                                                <?php echo e(trans('common.back')); ?>

                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <input type="hidden" name="product_id" id="product_id"
                                            value="<?php echo e($_REQUEST['prod_id']); ?>">
                                        <div class="table-responsive">
                                            <table class="table zero-configuration data_table_ajax">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo e(trans('common.id')); ?></th>
                                                        <th><?php echo e(trans('stocks.pof_no')); ?></th>
                                                        <th><?php echo e(trans('stocks.pof_qty')); ?></th>
                                                        <th><?php echo e(trans('stocks.ecm_no')); ?></th>
                                                        <th><?php echo e(trans('stocks.ecm_qty')); ?></th>
                                                        <th><?php echo e(trans('stocks.gro_no')); ?></th>
                                                        <th><?php echo e(trans('stocks.gro_qty')); ?></th>
                                                        <th><?php echo e(trans('stocks.created_date')); ?></th>
                                                        <th><?php echo e(trans('common.action')); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th><?php echo e(trans('common.id')); ?></th>
                                                        <th><?php echo e(trans('stocks.pof_no')); ?></th>
                                                        <th><?php echo e(trans('stocks.pof_qty')); ?></th>
                                                        <th><?php echo e(trans('stocks.ecm_no')); ?></th>
                                                        <th><?php echo e(trans('stocks.ecm_qty')); ?></th>
                                                        <th><?php echo e(trans('stocks.gro_no')); ?></th>
                                                        <th><?php echo e(trans('stocks.gro_qty')); ?></th>
                                                        <th><?php echo e(trans('stocks.created_date')); ?></th>
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
                <section>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"><?php echo e(trans('stocks.stock_summary')); ?></h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <table class="table zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th><?php echo e(trans('stocks.total_pof')); ?></th>
                                                    <th><?php echo e(trans('stocks.total_ecm')); ?></th>
                                                    <th><?php echo e(trans('stocks.total_gro')); ?></th>
                                                    <th><?php echo e(trans('stocks.order_supplied')); ?></th>
                                                    <th><?php echo e(trans('stocks.gto_in_qty')); ?></th>
                                                    <th><?php echo e(trans('stocks.gto_out_qty')); ?></th>
                                                    <th><?php echo e(trans('stocks.return_go_down')); ?></th>
                                                    <th><?php echo e(trans('stocks.balance_outside')); ?></th>
                                                    <th><?php echo e(trans('stocks.balance_inside')); ?></th>
                                                    <th><?php echo e(trans('stocks.total_balance')); ?></th>
                                                    <th><?php echo e(trans('stocks.scrap_qty')); ?></th>
                                                </tr>
                                                <tr>
                                                    <td id="total_pof"></td>
                                                    <td id="total_ecm"></td>
                                                    <td id="total_gro"></td>
                                                    <td id="order_supplied"></td>
                                                    <td id="gto_in_qty"></td>
                                                    <td id="gto_out_qty"></td>
                                                    <td id="return_go_down"></td>
                                                    <td id="balance_outside"></td>
                                                    <td class="balance_inside"></td>
                                                    <td id="total_balance"></td>
                                                    <td id="scrap_qty"></td>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                        <table>
                                            <tr>
                                                <td>Balance inside :</td>
                                                <td class="balance_inside"></td>
                                            </tr>
                                            <tr>
                                                <td>Actual sale :</td>
                                                <td id="actual_sale"></td>
                                            </tr>
                                            <tr>
                                                <td>Actual Westage :</td>
                                                <td id="actual_westage"></td>
                                            </tr>
                                            <tr>
                                                <td>Available Actual Wastage :</td>
                                                <td id="actual_westage_inside"></td>
                                            </tr>
                                            <tr>
                                                <td>Actual Go down Stock :</td>
                                                <td id="actual_go_down_stock"></td>
                                            </tr>

                                        </table>
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
        $(document).ready(function() {

            fill_datatable();

            function fill_datatable() {

                $('.data_table_ajax').DataTable({
                    /* "scrollX": true,
                     */
                    aaSorting: [
                        [0, 'desc']
                    ],
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    pageLength: 10,
                    serverSide: true,
                    serverMethod: 'POST',
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"><?php echo e(trans('common.loading')); ?></span> '
                    },
                    ajax: {
                        url: "<?php echo e(route('dt_stocks')); ?>",
                        data: {
                            "_token": "<?php echo e(csrf_token()); ?>",
                            'product_id': $("#product_id").val(),
                        },
                        //success:function(data) { console.log(data); },
                        error: function(data) {
                            console.log(data);
                        },
                    },
                    columns: [{
                            data: 'id'
                        },
                        {
                            data: 'pof_no',
                            orderable: true
                        },
                        {
                            data: 'pof_qty',
                            orderable: false
                        },
                        {
                            data: 'ecm_no',
                            orderable: false
                        },
                        {
                            data: 'ecm_qty',
                            orderable: true
                        },
                        {
                            data: 'gro_no',
                            orderable: true
                        },
                        {
                            data: 'gro_total_qty',
                            orderable: false
                        },
                        {
                            data: 'created_date',
                            orderable: false
                        },
                        {
                            mRender: function(data, type, row) {
                                return '<a class="" href="' + row["show"] +
                                    '"><i class="fa fa-eye"></i></a> <a class="" href="' + row[
                                        "edit"] +
                                    '"><i class="fa fa-edit"></i></a> <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stock-delete')): ?><form action="' +
                                    row["delete"] + '"  id="form' + row['id'] +
                                    '" method="post"><a class=" delete_btn" href="javascript:void(0)" onclick=" return delete_alert(' +
                                    row['id'] +
                                    ')"><i class="fa fa-trash"></i></a><?php echo method_field('delete'); ?><?php echo csrf_field(); ?></form><?php endif; ?>';
                            }
                        },
                    ]
                });
            }

            $.ajax({
                url: "<?php echo e(route('stock_summary')); ?>",
                data: {
                    "_token": "<?php echo e(csrf_token()); ?>",
                    'product_id': $("#product_id").val()
                },
                contentType: false,
                dataType: 'json',
                beforeSend: function(xhr) {
                    $('#action_btn').prop('disabled', true);
                    $('#action_btn').text("<?php echo e(trans('common.submitting')); ?>");
                },
                success: function(response) {
                    console.log(response);
                    if (response) {
                        console.log(response);
                        $("#total_pof").text(response.total_pof);
                        $("#total_ecm").text(response.total_ecm);
                        $("#total_gro").text(response.total_gro);
                        $("#order_supplied").text(response.order_supplied);
                        $("#gto_in_qty").text(response.gto_in_qty);
                        $("#gto_out_qty").text(response.gto_out_qty);
                        $("#return_go_down").text(response.return_go_down);
                        $("#balance_outside").text(response.balance_outside);
                        $(".balance_inside").text(response.balance_inside);
                        $("#total_balance").text(response.total_balance);
                        $("#scrap_qty").text(response.scrap_qty);
                        $("#actual_westage").text(response.actual_westage);
                        $("#actual_westage_inside").text(response.actual_westage_inside);
                        $("#actual_sale").text(response.actual_sale);
                        $("#actual_go_down_stock").text(response.actual_go_down_stock);
                    } else {
                        toastr.error('something wen wrong');
                    }
                    $('#action_btn').prop('disabled', false);
                    $('#action_btn').text("<?php echo e(trans('common.submit')); ?>");
                }
            });
        });
    </script>
    <script type="text/javascript">
        function delete_alert(id) {
            if (confirm("<?php echo e(trans('common.confirm_delete')); ?>")) {
                $("#form" + id).submit();
                return true;
            } else {
                return false;
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/admin/stocks/index.blade.php ENDPATH**/ ?>