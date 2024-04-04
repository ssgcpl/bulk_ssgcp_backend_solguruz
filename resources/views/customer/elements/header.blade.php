<!-- Preloader  -->
<div class="loader">
    <div class="loader-inner ball-scale-ripple-multiple ball-scale-ripple-multiple-color">         
      <lottie-player src="{{asset('web_assets/images/loader.json')}}"  background="transparent" speed="0.5" loop autoplay></lottie-player>
    </div>
</div>
<!-- /End Preloader  -->
<header> 
  <div class="container-xxl">
    <nav class="navbar navbar-expand-lg navbar-light">
      <a class="navbar-brand" href="{{route('web_home')}}">        
        <img class="mobile-hide" src="{{asset('web_assets/images/logo.svg')}}" alt="">
      </a>
      <div class="contact-info">
        Mobile: {{\App\Models\Setting::get('customer_care_no')}}<br>
        Email: {{\App\Models\Setting::get('contact_email')}}
      </div>  
      <div class="right-side"> 
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav">
            <li>
              <a href="{{ route('return_products_list') }}" title=""><span class="icon"><img src="{{asset('web_assets/images/return-order.svg')}}" alt="" /></span>Return Product</a>
            </li>
            <li>
              <a href="{{route('my_orders')}}" title=""><span class="icon"><img src="{{asset('web_assets/images/my-order.svg')}}" alt="" /></span>My Order</a>
            </li>
            <li class="dropdown d-none" id="retailer_request_menu">
                <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="icon"><img src="{{asset('web_assets/images/return-cart.svg')}}" alt="" /></span>Retailer Request</a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a href="{{route('retailer_wish_list')}}">Wish List</a></li>
                    <li><a href="{{route('retailer_wish_return')}}">Wish Return</a></li>
                </ul>
            </li> 
            <li><a href="{{route('digital_coupons_list')}}" title=""><span class="icon"><img src="{{asset('web_assets/images/digital-coupons.svg')}}" alt="" /></span> Digital Coupons</a>
                    </li> 
          </ul> 
        </div>
        <div class="menu-list">  
            <ul>               
              <li class="notification"><a href="{{route('notifications')}}" title="" id="notification"><i class="icon-notification"></i><span id="notif_exist"></span></a></li> 
              <li class="cart">
                <a href="{{route('my_cart')}}" title="" id="my_cart"> 
                  <i class="icon-bag" >
                  </i>
                  <span id="cart_item_count">0</span>
                </a>
              </li>
              <li class="dropdown">              
                <a href="#"  class="dropdown-toggle" id="dropdownMenu1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-user"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <div class="login">
                      <span>Welcome Guest,</span><br>
                      <a href="{{route('signin')}}">SignIn</a>
                    </div> 
                    <div class="user_profile_nav">
                      <a href="{{ route('customer.profile')}}">
                        <div class="profile">    
                          <img src="" id="user_image" alt="" class="img-fluid profile-head-img clr-primary">
                          <div class="text" id="user_detail">
                          </div>                            
                        </div>
                      </a> 
                    
                      <ul class="menu">
                        <li>
                          <a href="{{ route('customer.profile')}}" title=""><span class="icon"><img src="{{asset('web_assets/images/edit-profile.svg')}}" alt="" /></span>Edit Profile</a>
                        </li>
                        <li>
                          <a href="{{route('return_cart')}}" title=""><span class="icon"><img src="{{asset('web_assets/images/return-cart.svg')}}" alt="" /></span>Return Cart</a></li>
                        <li>
                          <a href="{{route('wishlist')}}" title=""><span class="icon"><img src="{{asset('web_assets/images/wishlist.svg')}}" alt="" /></span>Wish to Order</a></li>
                        <li>
                          <a href="{{route('wish_return')}}" title=""><span class="icon"><img src="{{asset('web_assets/images/wish-return.svg')}}" alt="" /></span>Wish to Return</a></li> 
                        <li><a href="{{route('refer_earn')}}" title=""><span class="icon"><img src="{{asset('web_assets/images/refer-earn.svg')}}" alt="" /></span>Refer & Earn</a></li>
                        <li>
                          <a href="{{route('suggestions')}}" title=""><span class="icon"><img src="{{asset('web_assets/images/suggestion-icon.svg')}}" alt="" /></span>Suggestion</a></li> 
                        <li>
                          <a href="#" data-bs-toggle="modal" data-bs-target="#logout-modal" title=""><span class="icon"><img src="{{asset('web_assets/images/logout.svg')}}" alt=""></span>Logout</a>
                        </li>
                        <li>
                          <a href="#" data-bs-toggle="modal" data-bs-target="#delete-account-modal" title=""><span class="icon"><img src="{{asset('web_assets/images/delete_icon.svg')}}" alt=""></span>Delete Account</a>
                        </li>
                      </ul>
                    </div>
                </div>
              </li>
            </ul>
        </div>
        <button class="navbar-toggler" id="sidebar_icon" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button> 
      </div> 
    </nav> 
  </div>
</header>