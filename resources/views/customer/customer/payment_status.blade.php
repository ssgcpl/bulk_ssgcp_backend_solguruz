@extends('customer.layouts.app')

@section('content')

<div class="main-wapper">
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active">{{$status_title}}</li>
        </ol> 
      </div>
  </section>  

  <section class="thank-you">     
    <div class="container">
      <!-- <div class="page-title text-center"><h1>Thank You</h1></div> -->
      <div class="box">
        <div class="content">
          <h1 class="theme-red-color">{{$status_title}}</h1>
          <p>{{$message}}</p>
          
        </div>
      </div>

    </div>
  </section>

</div>
@endsection
