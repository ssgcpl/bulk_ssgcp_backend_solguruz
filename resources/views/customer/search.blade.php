@extends('customer.layouts.app')

@section('content')
<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active"><a href="{{ Route::currentRouteName() }}">Search</a></li>
        </ol> 
      </div>
  </section> 
  
  <section class="shop-our-book white-bg">
    <div class="container">
      <div class="search-field">
        <div class="box">
          <select class="form-select" id="item_type" aria-label="Default select example">            
            <option value="books" @if(app('request')->input('type') == 'books') selected @endif>Books</option>                        
            <option value="coupons" @if(app('request')->input('type') == 'coupons') selected @endif>Coupons</option>
          </select>
          <input type="search" id="keyword" class="form-control" name="" placeholder="Search here..." value="{{app('request')->input('q')}}">
          <button class="search-btn" id="search_btn"><img src="{{asset('web_assets/images/search.svg')}}" alt=""></button>
        </div>
      </div>
      <div class="section-title">
        <h2>{{ucfirst(app('request')->input('type'))}}</h2>
      </div>
      <div class="row" id="search_results">
        
      </div> 

      <div id="no_data" class="no-data-found d-none">
          <div class="box">
                  <img src="{{ asset('web_assets/images/empty-search.svg') }}" alt="" />
                  <h5>No Result Found</h5>
                  <p>Sorry about that, but we currently don't have what you're looking for.</p>
          </div>
      </div>
    </div>
  </section>
 

</div>
@endsection