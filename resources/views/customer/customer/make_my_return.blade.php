@extends('customer.layouts.app')

@section('content')
<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active"><a href="{{ Route::currentRouteName() }}">My Return </a> </li>
        </ol> 
      </div>
  </section> 

  <section class="shop-our-book white-bg">
    <div class="container">
      <div class="section-title">
        <h2>Make My Return</h2>
        <!-- <a href="book-list.php" title="">View All</a>         -->
      </div>
      <div class="custom-tab-box">                  
        <ul class="nav custom-nav-tabs" id="myTabLang" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" id="hindi" data-bs-toggle="tab" href="#tab11" role="tab" aria-controls="tab11" aria-selected="true">हिन्दी</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="english" data-bs-toggle="tab" href="#tab22" role="tab" aria-controls="tab22" aria-selected="false">English</a>
          </li>          
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tag-list">
              <ul class="selected_category">
              </ul>
          </div>
          <div class="tag-list" id="main_tag_list">
          </div>
          <div class="tag-list" id="tag_list">
            <ul class="sub-cate">
            </ul>
          </div>

          <div class="tab-pane fade show active" id="tab11" role="tabpanel" aria-labelledby="tab1">
            <div class="row" id="books_list_hindi">
            </div>
            <div id="no_data_hindi" class="no-data-found d-none">
                <div class="box">
                        <img src="{{ asset('web_assets/images/no-purchase-yet.png') }}" alt="" />
                        <h5>Sorry! No Result Found :)</h5>
                        <p>We Couldn't Find What You're Looking For</p>
                </div>
            </div>
          </div>
          <div class="tab-pane fade" id="tab22" role="tabpanel" aria-labelledby="tab2">
            <div class="row" id="books_list">
            </div>
            <div id="no_data" class="no-data-found d-none">
                <div class="box">
                        <img src="{{ asset('web_assets/images/no-purchase-yet.png') }}" alt="" />
                        <h5>Sorry! No Result Found :)</h5>
                        <p>We Couldn't Find What You're Looking For</p>
                </div>
            </div>
          </div>
        </div>
      </div> 
    </div>
  </section>
 

</div>
@endsection
