@extends('customer.layouts.app')

@section('content')

<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active">  <a href="{{ route('suggestions') }}">Suggestion</a>  </li>
        </ol> 
      </div>
  </section> 

  <section class="suggestion-page white-bg">
    <div class="container"> 
      <div class="page-title justify-content-center"><h1>Suggestion</h1></div>
      <div class="custom-tab-box">                  
        <ul class="nav custom-nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" id="tab1" data-bs-toggle="tab" href="#tab11" role="tab" aria-controls="tab11" aria-selected="true">Publish by SSGC</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="tab2" data-bs-toggle="tab" href="#tab22" role="tab" aria-controls="tab22" aria-selected="false">Wish Publish</a>
          </li>          
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="tab11" role="tabpanel" aria-labelledby="tab1">
             <ul class="nav custom-nav-tabs" id="myTabLang" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active" id="hindi" data-bs-toggle="tab" href="#tab11_hindi" role="tab" aria-controls="tab11_hindi" aria-selected="true">हिन्दी</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" id="english" data-bs-toggle="tab" href="#tab22_english" role="tab" aria-controls="tab22_english" aria-selected="false">English</a>
              </li>          
            </ul>
            <!-- <div class="search-field">
              <div class="box">                  
                <input type="search" class="form-control" name="" placeholder="Search here...">
                <button class="search-btn"><img src="images/search.svg" alt=""></button>
              </div>
            </div>  -->
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
            <div class="row" id="product_list">
            </div>
             <div id="no_data" class="no-data-found d-none">
                <div class="box">
                        <img src="{{ asset('web_assets/images/no-purchase-yet.png') }}" alt="" />
                        <h5>Sorry! No Result Found :)</h5>
                        <p>We Couldn't Find What You're Looking For</p>
                </div>
            </div>
          </div>
          <div class="tab-pane fade" id="tab22" role="tabpanel" aria-labelledby="tab2">
            <div class="row justify-content-center">
              <div class="col-xl-5 col-lg-5 col-md-12 col-12">
                <div class="wish-publish">
                  <h6>Add Book Detail</h6>
                  <form id="wishSuggestionForm" method="post" enctype="multipart/form-data">
                  <div class="form-group mb-3">
                    <label>Book Title</label>
                    <input type="text" name="book_name" class="form-control" placeholder="Book Title">
                  </div>
                  <div class="form-group mb-3">
                    <label>Subject</label>
                    <input type="text" class="form-control" placeholder="Subject" name="subject">
                  </div>
                  <div class="form-group mb-3">
                    <label>Suggestion Box</label>
                    <textarea class="form-control" name="description" placeholder="Write here..."></textarea>
                  </div>
                  <div class="form-group mb-3">
                    <div class="common-upload">
                        <p><label>Upload the sample Images.</label></p>
                        <div class="box">                        
                            <div class="preview-img" id="book_images_div">
                              <!--   <div class="img">
                                    <img src="images/profile.png" alt="">
                                    <a href="#" class="close">×</a>  
                                    Front Image                                  
                                </div>  -->
                            </div>
                            <div class="upload-img">
                                <input type="file" accept="image/png, image/jpeg,image/jpg" name="images" id="images" class="file-input-input" multiple>
                                <label class="file-input-label" for="images">
                                    <img src="{{asset('web_assets/images/upload-img.png')}}" alt="">
                                    Add Images
                                </label>
                            </div>
                        </div>
                    </div>
                  </div> 
                    <div class="form-group mb-3">
                    <div class="common-upload">
                        <p><label>Upload the sample PDF</label></p>
                        <div class="box">                        
                            <div class="preview-img" id="book_pdf_div">
<!--                                 <div class="img">
                                    <img src="images/profile.png" alt="">
                                    <a href="#" class="close">×</a>  
                                    Front Image                                  
                                </div>  -->
                            </div>
                            <div class="upload-img" id="pdf_div">
                                <input type="file" name="pdf" id="pdf" accept=".pdf" class="file-input-input" multiple>
                                <label class="file-input-label" for="pdf">
                                    <img src="{{asset('web_assets/images/upload-img.png')}}" alt="">
                                    Add pdf
                                </label>
                            </div>
                        </div>
                    </div>
                  </div> 
                  <h6>Customer Details</h6>
                  <div class="form-group mb-3">
                    <label>Mobile Number<span class="required-star">*</span></label>
                    <input type="text" class="form-control" placeholder="Mobile Number" name="mobile_number" id="mobile_number" required="">
                  </div>
                  <div class="form-group mb-3">
                    <label>Email ID<span class="required-star">*</span></label>
                    <input type="text" class="form-control" placeholder="Email ID" name="email" id="email" required="">
                  </div>
                  <div class="form-group mb-3">
                    <button type="submit"  id="add_wish_suggestion_btn" class="w-100 btn primary-btn">Submit</button>
                  </div>
                </form>
                </div>
              </div>
            </div> 
          </div>
        </div>
      </div>

    </div>
  </section>
</div>
 

<!-- Suggestion -->
<div class="modal fade add-suggestion" id="add-suggestion" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Suggestion</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <form id="ssgcSuggestionForm" method="post" enctype="multipart/form-data">
          <input type="hidden" name="product_id" id="product_id">
        <div class="product-list-box mb-3">
          <div class="img"><img src="" id="product_img" alt=""></div>
          <div class="detail">
            <h6 id="product_name"></h6> 
            <p class="secondary-color"><span id="product_description"></span></p>
          </div>
        </div>        
        <div class="form-group mb-3">
          <label>Description</label>
          <textarea class="form-control" id="description" name="description" placeholder="Write here..." spellcheck="false"></textarea>
        </div>

        <div class="customer-detail">
          <h6>Customer Details</h6>
          <div class="form-group mb-3">
            <label>Mobile Number<span class="required-star">*</span></label>
            <input type="text" class="form-control" placeholder="Mobile Number" name="mobile_number" id="mobile_number">
          </div>
          <div class="form-group mb-3">
            <label>Email ID<span class="required-star">*</span></label>
            <input type="text" class="form-control" placeholder="Email ID" name="email" id="email">
          </div>
        </div> 
      </div>
      <div class="custom-footer">
        <button type="submit" class="w-100 btn primary-btn">Submit</button>
      </div>  
      </form>    
    </div>
  </div>
</div>


<!-- Suggestion Submitted -->
<div id="success-suggestion" class="fade modal delete-confirm">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">        
          <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body"> 
        <div class="success-img">
          <lottie-player src="{{asset('web_assets/images/success.json')}}" background="transparent" speed="1" loop autoplay></lottie-player>
        </div>
        <h4 class="green-color">Success</h4>  
        <p>Your Suggestion has been Submitted</p> 
      </div>     
    </div>
  </div>
</div>
@endsection