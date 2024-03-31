<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="PIXINVENT">
    <title>{{ @$app_settings['app_name'] }} @if(@$page_title) - {{$page_title}} @endif</title>
    <link rel="apple-touch-icon" href="{{asset('admin_assets/app-assets/images/ico/apple-icon-120.png')}}">
    
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('web_assets/images/favicon.png')}}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600)}}" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/vendors/css/vendors.min.css')}}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/css/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/css/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/css/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/css/themes/dark-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/css/themes/semi-dark-layout.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/css/core/colors/palette-gradient.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/css/pages/authentication.css')}}">


    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin_assets/assets/css/style.css')}}">
    <!-- END: Custom CSS-->

    @yield('css')

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern semi-dark-layout 1-column  navbar-floating footer-static bg-full-screen-image  blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column" data-layout="semi-dark-layout">

    @yield('content')
 <!-- BEGIN: Vendor JS-->
<script src="asset('admin_assets/app-assets/vendors/js/vendors.min.js')"></script>
<!-- BEGIN Vendor JS-->
<script>var public_url = "{{asset('admin_assets/app-assets')}}";</script>
<!-- BEGIN: Theme JS-->
<script src="{{asset('admin_assets/app-assets/js/core/app-menu.js')}}"></script>
<script src="{{asset('admin_assets/app-assets/js/core/app.js')}}"></script>
<script src="{{asset('admin_assets/app-assets/js/scripts/components.js')}}"></script>
<!-- END: Theme JS-->

<!--Toaster JS-->
<script src="{{asset('admin_assets/custom/toastr/toastr.min.js')}}"></script>
<script type="text/javascript">
    @if(Session::has('success'))
      toastr.success("{{ Session::get('success') }}");
    @elseif(Session::has('error'))
      toastr.error("{{ Session::get('error') }}");
    @elseif(Session::has('warning'))
      toastr.warning("{{ Session::get('warning') }}");
    @elseif(Session::has('info'))
      toastr.info("{{ Session::get('info') }}");
    @endif
</script>

    @yield('js')

</body>
<!-- END: Body-->

</html>