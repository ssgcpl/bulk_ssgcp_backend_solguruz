<!DOCTYPE html>
<html class="loading" lang="<?php echo e(config('app.locale')); ?>" data-textdirection="<?php echo e(config('app.locales')[config('app.locale')]['dir']); ?>">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="PIXINVENT">

    <!-- chat -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="ws_url" content="<?php echo e(config('chat.ws_url')); ?>">
    <meta name="user_id" content="<?php echo e(Auth::id()); ?>">
    <meta name="type_msg" content="<?php echo e(trans('common.type_message_and_hit_enter')); ?>">


    <title><?php echo e(@$app_settings['app_name']); ?> <?php if(@$page_title): ?> - <?php echo e($page_title); ?> <?php endif; ?></title>
    <link rel="apple-touch-icon" href="<?php echo e(asset('admin_assets/app-assets/images/ico/apple-icon-120.png')); ?>">
    <!-- <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset('admin_assets/app-assets/images/ico/favicon.ico')); ?>"> -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset('admin_assets/app-assets/images/ico/favicon.png')); ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600)}}" rel="stylesheet">

    <?php if(config('app.locales')[config('app.locale')]['dir'] == 'rtl'){ ?>
        <!-- BEGIN: Vendor CSS-->
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/vendors/css/vendors-rtl.min.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/vendors/css/charts/apexcharts.css')); ?>">
        <?php echo $__env->yieldContent('vendor_css'); ?>
        <!-- END: Vendor CSS-->

        <!-- BEGIN: Theme CSS-->
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css-rtl/bootstrap.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css-rtl/bootstrap-extended.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css-rtl/colors.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css-rtl/components.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css-rtl/themes/dark-layout.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css-rtl/themes/semi-dark-layout.css')); ?>">

        <!-- BEGIN: Page CSS-->
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css-rtl/core/menu/menu-types/vertical-menu.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css-rtl/core/colors/palette-gradient.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css-rtl/pages/dashboard-analytics.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css-rtl/pages/card-analytics.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css-rtl/plugins/tour/tour.css')); ?>">
        <!-- END: Page CSS-->

        <!-- BEGIN: Custom CSS-->
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/assets/css/style-rtl.css')); ?>">
        <!-- END: Custom CSS-->
    <?php }else{ ?>
        <!-- BEGIN: Vendor CSS-->
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/vendors/css/vendors.min.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/vendors/css/charts/apexcharts.css')); ?>">
        <?php echo $__env->yieldContent('vendor_css'); ?>
        <!-- END: Vendor CSS-->

        <!-- BEGIN: Theme CSS-->
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css/bootstrap.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css/bootstrap-extended.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css/colors.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css/components.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css/themes/dark-layout.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css/themes/semi-dark-layout.css')); ?>">
         <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css/plugins/forms/validation/form-validation.css')); ?>">


         <!-- BEGIN: Page CSS-->
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css/core/menu/menu-types/vertical-menu.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css/core/colors/palette-gradient.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css/pages/dashboard-analytics.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css/pages/card-analytics.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/css/plugins/tour/tour.css')); ?>">
        <!-- END: Page CSS-->

        <!-- BEGIN: Custom CSS-->
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/assets/css/style.css')); ?>">
        <!-- END: Custom CSS-->


    <?php } ?>

    <!-- Toaster -->
    <link rel="stylesheet" href="<?php echo e(asset('admin_assets/custom/toastr/toastr.min.css')); ?>" />  
    <!-- Multiselect --> 
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/custom/multiselect/multiselect.css')); ?>">

     <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <!-- DateTime Picker -->
    <link rel="stylesheet" href="<?php echo e(asset('admin_assets/custom/datetimepicker/bootstrap-datetimepicker.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/vendors/css/pickers/pickadate/pickadate.css')); ?>">
    <?php echo $__env->yieldContent('css'); ?>

    <style type="text/css">
        /* Loader */
        #loader {
            left: 0%;
            top: 0%;
            width: 100%;
            height: 100%;
            position: fixed;
            
            opacity: 0.7;
            z-index: 9999999;
        }

        .overlay__inner {
            
            left: 50%;
            top: 20%;
            width: 10%;
            height: 30%;
            position: relative;
        }

        .overlay__content {
            left: 50%;
            position: absolute;
            top: 50%;
            background: #3c8dbc;
            transform: translate(-50%, -50%);
            padding: 20%;
        }

        .spinner {
            width: 75px;
            height: 75px;
            display: inline-block;
            border-width: 2px;
            border-color: rgba(255, 255, 255, 0.05);
            border-top-color: #fff;
            animation: spin 1s infinite linear;
            border-radius: 100%;
            border-style: solid;
        }

        @keyframes  spin {
          100% {
            transform: rotate(360deg);
          }
        }
        #loader{display: none}

        input:disabled {
         cursor: not-allowed;
        }

        .admin_panel_logo img {width: 100%}


      
    </style>

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern semi-dark-layout 2-columns  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-layout="semi-dark-layout">

    <?php echo $__env->make('layouts.admin.elements.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  
    <?php echo $__env->make('layouts.admin.elements.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  


    
    <?php echo $__env->yieldContent('content'); ?>
    

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <?php echo $__env->make('layouts.admin.elements.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- BEGIN: Custom JS -->
    <script src="<?php echo e(asset('admin_assets/custom/multiselect/multiselect.min.js')); ?>"></script>
    <script>
        document.multiselect('.multiselect1');
    </script>
    <!-- END: Custom JS -->

    <!-- BEGIN: Vendor JS-->
    <script src="<?php echo e(asset('admin_assets/app-assets/vendors/js/vendors.min.js')); ?>"></script>
    <!-- BEGIN Vendor JS-->

    <?php echo $__env->yieldContent('page_js'); ?>


    <!-- BEGIN: Theme JS-->
    <script src="<?php echo e(asset('admin_assets/app-assets/js/core/app-menu.js')); ?>"></script>
    <script>var public_url = "<?php echo e(asset('admin_assets/app-assets')); ?>";</script>
    <script src="<?php echo e(asset('admin_assets/app-assets/js/core/app.js')); ?>"></script>
    <script src="<?php echo e(asset('admin_assets/app-assets/js/scripts/components.js')); ?>"></script>
    <script src="<?php echo e(asset('admin_assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js')); ?>">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
             $('.select2').select2();
        });
    </script>
    

    <!-- END: Theme JS-->

    <!-- Multiselect -->
    <script>
        $('.multiselect-input').attr('autocomplete','off');
        $(document).ready(function(){ 
            $('.error').delay(5000).fadeOut();
            $('.alert-danger').delay(8000).fadeOut();
            $('.multiselect-checkbox').next().first().html("<?php echo e(trans('common.all')); ?>");
        });
    </script>

    <!--Toaster JS-->
    <script src="<?php echo e(asset('admin_assets/custom/toastr/toastr.min.js')); ?>"></script>
    <script type="text/javascript">
        <?php if(Session::has('success')): ?>
          toastr.success("<?php echo e(Session::get('success')); ?>");
        <?php elseif(Session::has('error')): ?>
          toastr.error("<?php echo e(Session::get('error')); ?>");
        <?php elseif(Session::has('warning')): ?>
          toastr.warning("<?php echo e(Session::get('warning')); ?>");
        <?php elseif(Session::has('info')): ?>
          toastr.info("<?php echo e(Session::get('info')); ?>");
        <?php endif; ?>
    </script>

    <!-- DateTime Picker -->
    <script src="<?php echo e(asset('admin_assets/custom/datetimepicker/moment-with-locales.js')); ?>"></script>
    <script src="<?php echo e(asset('admin_assets/custom/datetimepicker/bootstrap-datetimepicker.min.js')); ?>"></script>



    <!-- WEB PUSH NOTOFOCTION -->
    <script type="text/javascript">
      var csrf_for_noti = '<?php echo e(csrf_token()); ?>';
    </script>
   <!-- <script type="text/javascript" src="<?php echo e(asset('admin_assets/custom/push_notification/notification.js')); ?>"></script>-->
    <!-- WEB PUSH NOTOFOCTION -->
    
    <script type="text/javascript">
        $('.numberonly').keypress(function (e) {    
            var charCode = (e.which) ? e.which : event.keyCode  
            var re = new RegExp("^[0-9]+$"); 
            if (re.test(String.fromCharCode(charCode))) {
              return true;                        
            } else {
              return false;
            }
        }); 
        $('.number_decimal').keypress(function (e) {    
            var charCode = (e.which) ? e.which : event.keyCode  
            var re = new RegExp("^[0-9\.]+$"); 
            if (re.test(String.fromCharCode(charCode))) {
              return true;                        
            } else {
              return false;
            }
        }); 

         $(".datepicker").datetimepicker({
            // format: 'YYYY-MM-DD H:mm',
            minDate: new Date(),
            //format: 'YYYY-MM-DD', 
             format: 'DD-MM-YYYY', 
            useCurrent: false,
            showTodayButton: true,
            showClear: true,
            toolbarPlacement: 'bottom',
            sideBySide: true,
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right",
                today: "fa fa-clock-o",
                clear: "fa fa-trash-o",
            }
    })

           $(".datepicker_future_not_allow").datetimepicker({
            // format: 'YYYY-MM-DD H:mm',
            maxDate: '<?php echo e(date("Y-m-d")); ?>', // 30 days from the current day
            format: 'DD-MM-YYYY', 
          //  format: 'YYYY-MM-DD', 
            useCurrent: true,
            showTodayButton: true,
            showClear: true,
            toolbarPlacement: 'bottom',
            sideBySide: true,
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right",
                today: "fa fa-clock-o",
                clear: "fa fa-trash-o",
            }
         })
    </script>

    <?php echo $__env->yieldContent('js'); ?>

</body>
<!-- END: Body-->

</html><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/layouts/admin/app.blade.php ENDPATH**/ ?>