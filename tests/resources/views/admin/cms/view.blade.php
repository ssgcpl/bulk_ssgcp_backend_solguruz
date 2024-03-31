@extends('customer.layouts.app_cms')

@section('content')

<!-- <div class="main-wapper"> -->
<!-- <section class="cms-page white-bg">
 -->  <div class="container-xl">
   <!--  <div class="about-logo text-center">
      <img src="{{asset('customer/images/logo.png')}}" alt="" />
    </div> -->
    <div class="page-title"><h1>{{ $cms->page_name }}</h1></div>
    <div class="content" id="about_us"><p>{!! $cms->content !!}</p></div>
  </div>
<!-- </section>
 --><!-- </div> -->
@endsection