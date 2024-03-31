@extends('customer.layouts.app')

@section('content')

<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active">  <a href="{{ Route::currentRouteName() }}">Ticket History</a> </li>
        </ol> 
      </div>
  </section> 

  <section class="ticket-history white-bg">
    @if(isset($_GET['ticket_id']))
       <input type="hidden" id="ticket_id" name="ticket_id" value="{{$_GET['ticket_id']}}">
    @endif
   
    <div class="container"> 
      <div class="page-title"><h1>Ticket History</h1> 
        <a href="javascript:void(0)" title="" class="btn primary-btn" data-bs-toggle="modal" data-bs-target="#add-ticket-modal" id="add_new_ticket_btn">+ Add New</a>
      </div> 
       <div class="no-data-found d-none">
        <div class="box">
          <img src="{{asset('web_assets/images/empty-ticket.svg')}}" alt="">
          <h5>Got stuck somewhere?</h5>
          <p>No problem! We're here to assist you.</p> 
          <a href="{{route('contact_us')}}" title="" class="btn primary-btn">Get Support</a>
        </div>
      </div>
      <div class="ticket-list"> 
        
    
      </div>
    </div>
  </section>

</div>

<!-- Add Ticket Modal -->
<div class="modal fade add-ticket-modal" id="add-ticket-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Ticket</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body"> 
        <form id="contactSupportForm" method="post" enctype="multipart/form-data">       
          <div class="form-group mb-3">
            <label>Full Name<span class="required-star">*</span></label>
            <input type="text" class="form-control" placeholder="Full Name" name="full_name" id="full_name">
          </div>
          <div class="form-group mb-3">
            <label>Mobile Number<span class="required-star">*</span></label>
            <input type="text" maxlength="10" id="mobile_number" class="form-control" placeholder="Mobile Number" name="mobile_number">
          </div>
          <div class="form-group mb-3">
            <label>Email ID<span class="required-star">*</span></label>
            <input type="email" id="email"  class="form-control" placeholder="Email ID" name="email">
          </div>
          <div class="form-group mb-3">          
            <label>Select Reason<span class="required-star">*</span></label>
            <select name="reason_id" id="reason_id" class="form-select" aria-label="Default select example">
            </select>
          </div>
          <div class="form-group mb-3">
            <label>Description</label>
            <textarea class="form-control" name="message" id="message" placeholder="Write here..."></textarea>
          </div>
          <div class="custom-footer text-center">            
            <button type="submit" class="btn primary-btn">Send</button>
          </div>
        </form>
      </div>      
    </div>
  </div>
</div>

<!-- Ticket Detail -->
<div class="modal fade ticket-popup" id="ticket-detail" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ticket Details</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <div class="ticket-box" id="ticket_info"> 
        </div>
      </div>      
    </div>
  </div>
</div>
@endsection
