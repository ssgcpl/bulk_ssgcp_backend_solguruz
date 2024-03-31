@extends('customer.layouts.app')

@section('content')
<div class="main-wapper">
  <section class="home-search">      
    <div class="container">
      <div class="search-field">
        <div class="box">
          <select class="form-select" id="item_type" aria-label="Default select example">            
            <option value="books">Books</option>                        
            <option value="coupons">Coupons</option>
          </select>
          <input type="search" id="keyword" class="form-control" name="" placeholder="Search here...">
          <button class="search-btn" id="search_btn"><img src="{{asset('web_assets/images/search.svg')}}" alt=""></button>
        </div>
      </div>
    </div>
  </section>  
  <section class="category-list white-bg">
    <div class="container">
      <ul id="business_categories_div">
      </ul>
    
    </div>
    
  </section>
  <div id="business_categories_section">
  </div>

    
  

  <section class="make-return white-bg d-none" id="make_my_return_div">
    <div class="container">
      <div class="section-title">
        <h2>Make My Return</h2>
         <!-- href="{{route('make_my_return')}}" -->
        <a title="" class="d-none" id="my_return_view_all">View All</a>        
      </div>
      <div class="custom-tab-box">                  
        <ul class="nav custom-nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" id="tab1" data-bs-toggle="tab" href="#tab11" role="tab" aria-controls="tab11" aria-selected="true">Returnable Books</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="tab2" data-bs-toggle="tab" href="#tab22" role="tab" aria-controls="tab22" aria-selected="false">Previous Orders</a>
          </li>          
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="tab11" role="tabpanel" aria-labelledby="tab1">
            <div class="row" id="returnable_books_div">
            </div>
          </div>
          <div class="tab-pane fade" id="tab22" role="tabpanel" aria-labelledby="tab2">
            <div class="row" id="previous_orders_div">
            </div> 
          </div>
        </div>
      </div> 
    </div>
  </section>

  <section class="trending-book white-bg">
    <div class="container">
      <div class="section-title">
        <h2>Trending Books</h2>
        <a href="{{route('trending_books')}}" title="" class="view_all_btn">View All</a>        
      </div>
      <div class="row" id="trending_books_div">
        
      </div>
    </div>
  </section>

</div>
@endsection

@section('js')

@endsection
