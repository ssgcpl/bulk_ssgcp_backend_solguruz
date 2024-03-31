@extends('customer.layouts.app')

@section('content')

<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active">Refer & Earn</li>
        </ol> 
      </div>
  </section> 
   
  <section class="refer-earn-banner">     
    <div class="container">
      <div class="page-title"><h1>Refer & Earn</h1></div>
      <div class="row align-items-center justify-content-center">
        <div class="col-12">
          <div class="refer-lottie">
            <lottie-player src="{{asset('web_assets/images/refer-earn.json')}}" background="transparent" speed="1" loop="" autoplay=""></lottie-player>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="refer-earn-content white-bg">  

     
    <div class="container">      
      <div class="row align-items-center justify-content-center">
        <div class="col-12">
          <div class="box">
            <h3 id="title"></h3>
            <p id="content"></p>

            <div class="your-code">
              <p>Your Code</p>
              <div class="code"><span id="refer_code"></span> <a href="javascript:void(0)" id="copy_code" title=""><i class="fas fa-clone"></i></a></div>
            </div>

            <div class="button">
              <button class="btn primary-btn w-100" data-bs-toggle="modal" data-bs-target="#share-popup">Refer Now <i class="icon-share ms-2"></i></button>
            </div> 
            <div class="history mt-3">
              <a href="#" data-bs-toggle="modal" data-bs-target="#earnings-modal-modal" class="theme-color text-decoration-underline" title="">History List</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>

<!-- Earnings Popup -->
<div class="modal fade earnings-modal" id="earnings-modal-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content"> 
      <div class="modal-header">
        <h5 class="modal-title">Earnings</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div> 
      <div class="modal-body">
        <div class="custom-tab-box">                  
          <ul class="nav custom-nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <a class="nav-link active" id="tab1" title="history" data-bs-toggle="tab" href="#tab11" role="tab" aria-controls="tab11" aria-selected="true">History</a>
            </li>
            <li class="nav-item" role="presentation">
              <a class="nav-link" id="tab2" data-bs-toggle="tab" title="earn" href="#tab22" role="tab" aria-controls="tab22" aria-selected="false">Earn</a>
            </li>
            <li class="nav-item" role="presentation">
              <a class="nav-link" id="tab3" data-bs-toggle="tab" title="redeem" href="#tab33" role="tab" aria-controls="tab33" aria-selected="false">Redeem</a>
            </li>          
          </ul>
          <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="tab11" role="tabpanel" aria-labelledby="tab1" >
             <div id="history_div"></div>
                <div class="no-data-found d-none" id="no_histpry_data">
                  <div class="box">
                    <img src="{{asset('web_assets/images/empty-earning.svg')}}" alt="">
                    <h5>Oops! You have not earned any rewards yet.</h5>
                    <p>Keep using the app to unlock rewards and benefits.</p>
                  </div>
                </div>
          </div>
          <div class="tab-pane fade show" id="tab22" role="tabpanel" aria-labelledby="tab2">
            <div id="earn_div"></div>
            
              <div class="no-data-found d-none" id="no_earn_data">
                <div class="box">
                  <img src="{{asset('web_assets/images/empty-earning.svg')}}" alt="">
                  <h5>Oops! You have not earned any rewards yet.</h5>
                  <p>Keep using the app to unlock rewards and benefits.</p>
                </div>
              </div>

          </div>
          <div class="tab-pane fade show" id="tab33" role="tabpanel" aria-labelledby="tab3">
             <div id="redeem_div"></div>

            <div class="no-data-found d-none" id="no_redeem_data">
                <div class="box">
                  <img src="{{asset('web_assets/images/empty-reward.svg')}}" alt="">
                  <h5>Zero Reward Redeemed!</h5>
                  <p>Explore, place an order, and redeem your earned rewards.</p>
                </div>
              </div>
          </div> 
          </div>
        </div>
        <!-- <div class="button-list text-center">
          <a href="#" type="button" class="btn primary-btn w-100">Submit</a>          
        </div>  -->         
      </div> 
    </div>
  </div>
</div>



@endsection