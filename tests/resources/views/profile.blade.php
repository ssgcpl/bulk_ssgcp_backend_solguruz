
@extends('layouts.admin.app')

@section('css')
 <style type="text/css">
   .resend p {
      color: #000;
    }
    .resend .countdown__time {
    color: #000;
    }
    .resend p a {
    color: #000;
    }

   .profile-page-otp .form-group    {
    margin-right: 20px;
    display: inline-block;
   }   
 .profile-page-otp .form-group .form-control {
    width: 37px;
    display: inline;
  }
  </style>
  <link href="{{asset('css/filepond/filepond.css')}}" rel="stylesheet">
<link href="{{asset('css/filepond/filepond-plugin-image-preview.css')}}" rel="stylesheet">
<link href="{{asset('css/filepond/filepond-plugin-image-edit.css')}}" rel="stylesheet">
@endsection
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{ucfirst($admin->first_name)}} {{ucfirst($admin->last_name)}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('common.edit') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <!--   <div class="content-body">
      @if ($errors->any())
      <div class="alert alert-danger">
          <b>{{trans('common.whoops')}}</b>
          <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
      @endif -->
      <!-- // Basic multiple Column Form section start -->
      <section id="multiple-column-form">
        <div class="row match-height">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">
                {{ trans('users.edit_profile')}}
                </h4>
              </div>
              <div class="card-content">
                <div class="card-body">
                  <form method="POST" id="users" @if(auth::user()->user_type == 'admin')  action="{{route('admin_update')}}"  @else action="{{route('update_profile')}}" @endif  accept-charset="UTF-8" enctype="multipart/form-data">
                    @csrf
                    <div class="form-body">
                      <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('name', 'Name')}}<span class="text-danger custom_asterisk">*</span>
                                    <input type="hidden" name="user_type" value="{{$admin->user_type}}">
                                    {!!Form::text('name', $admin->first_name,['class' => 'form-control '.($errors->has('name') ? 'is-invalid':''),'placeholder'=>"Enter User's Name",'required'=>'true'])!!}
                                    @if ($errors->has('name')) 
                              <strong class="invalid-feedback">{{ $errors->first('name') }}</strong>
                            @endif
                      
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('email', 'Email')}}<span class="text-danger custom_asterisk">*</span>
                                    {!!Form::email('email', $admin->email,['class' => 'form-control','placeholder'=>"Enter User's Email",'required'=>'true','readonly'])!!}
                                     @if ($errors->has('email')) 
                                    <strong class="invalid-feedback">
                                      {{ @$errors->first('email') }}
                                    </strong>
                                    @endif
                                     <div class="phoneemail-edit">
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#editemail"> <span><i class="fa fa-edit"></i></span></a>
                              </div>
                            
                               
                                </div>  
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('mobile_number', 'Mobile Number')}}<span class="text-danger custom_asterisk">*</span>
                                    {!!Form::text('mobile_number', $admin->mobile_number,['class' => 'form-control '.($errors->has('mobile_number') ? 'is-invalid':''),'placeholder'=>"Enter User's Mobile Number",'required'=>'true','readonly'])!!}
                                    <strong class="help-block alert-danger">
                                      {{ @$errors->first('mobile_number') }}
                                    </strong>

                                     <div class="phoneemail-edit">
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#editphone"> <span><i class="fa fa-edit"></i></span></a>
                              </div>
                               @if ($errors->has('mobile_number')) 
                                    <strong class="invalid-feedback">
                                      {{ @$errors->first('mobile_number') }}
                                    </strong>
                                    @endif
                                    
                            
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('old_password', 'Old Password')}}
                                    {!!Form::password('old_password',['class' => 'form-control '.($errors->has('old_password') ? 'is-invalid':''),'placeholder'=>"Enter Old Password"])!!}
                                     @if ($errors->has('old_password')) 
                                    <strong class="invalid-feedback">
                                      {{ @$errors->first('old_password') }}
                                    </strong>
                                    @endif
                            </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('new_password', 'New Password')}}
                                    {!!Form::password('new_password',['class' => "form-control ".($errors->has('new_password') ? 'is-invalid' : '' ),'placeholder'=>"Enter New Password"])!!}
                                     @if ($errors->has('new_password')) 
                                    <strong class="invalid-feedback">
                                      {{ @$errors->first('new_password') }}
                                    </strong>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{Form::label('confirm_password', 'Confirm Password')}}
                                    {!!Form::password('confirm_password',['class' => 'form-control '.($errors->has('confirm_password') ? 'is-invalid' : '' ),'placeholder'=>"Confirm New Password"])!!}
                                  @if ($errors->has('confirm_password')) 
                                    <strong class="invalid-feedback">
                                      {{ @$errors->first('confirm_password') }}
                                    </strong>
                                    @endif 
                                  </div>
                            </div>
                        </div>
                        <label>{{ trans('users.profile_pic') }}</label> 
                       
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="file" id="image" name="profile_image" required >
                                    @if ($errors->has('profile_image')) 
                                    <strong class="invalid-feedback">
                                      {{ @$errors->first('profile_image') }}
                                    </strong>
                                    @endif 
                                </div>
                             </div>
                        </div> 
                    </div>
                    <div class="form-footer">
                      <button id="edit_btn" type="submit" class="btn btn-info btn-fill btn-wd">{{trans('common.submit')}}</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- // Basic Floating Label Form section end -->
    </div>
  </div>
</div>

<div class="modal fade" id="editemail" tabindex="-1" role="dialog" aria-labelledby="editemail" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editemail">{{trans('customers.edit_email')}} {{trans('customers.address')}}</h5>
        <button type="button" class="close reload" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">                 
        <div class="row align-item-center justify-content-center">
          <div class="col-12">
            <div class="profile-card">
              <div class="row">
                <div class="col-12">
                  <form class="myprofile-form emailupdate_form">
                    @csrf
                       
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group">
                          <label for="email">{{trans('customers.email_address')}}<span class="text-danger">*</span></label>
                          <input type="email" class="form-control" name="email" value="{{$admin->email}}"  id="updated_email" required placeholder="{{trans('customers.pl_enter_email_address')}}">
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="update-profile">
                          <button type="submit" id="emailupdate_btn" class="btn btn-info btn-fill btn-wd">{{trans('common.update')}}</button>
                          <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-info btn-fill btn-wd reload">{{trans('common.cancel')}}</a>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>      
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="editphone" tabindex="-1" role="dialog" aria-labelledby="editphone" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editphone">{{trans('common.edit')}} {{trans('customers.mobile_number')}}</h5>
        <button type="button" class="close reload" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>  
      <div class="modal-body">                 
        <div class="row align-item-center justify-content-center">
          <div class="col-12">
            <div class="profile-card">
              <div class="row">
                <div class="col-12">
                  <form class="myprofile-form phoneupdate_form">
                    @csrf
                       
                    <div class="row">
                      <div class="col-8">
                        <div class="form-group">
                          <label for="updated_mobile_number">{{trans('customers.mobile_number')}}<span class="text-danger">*</span></label>
                          <input id="updated_mobile_number" class="form-control" name="mobile_number"  required type="" value="{{$admin->mobile_number}}" maxlength = "10" data-toggle="modal">
                        </div>
                        <div class="update-profile">
                          <button type="submit" id="phoneupdate_btn" class="btn btn-info btn-fill btn-wd">{{trans('common.update')}}</button>
                          <a href="javascript:void(0)" id="cancel" data-dismiss="modal" class="btn btn-info btn-fill btn-wd reload">{{trans('common.cancel')}}</a>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>      
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="verify_otp" tabindex="-1" role="dialog" aria-labelledby="verify_otp" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="verify_otp_heading">{{trans('customers.otp_verification')}}</h5>
        <button type="button" class="close reload" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">                 
        <div class="row align-item-center justify-content-center">
          <div class="col-12">
            <div class="profile-card">
              <div class="row">
                <div class="col-12">
                  <form class="myprofile-form">
                    <div class="row" style="width:83%;margin: auto;">
                      <div class="col-md-12">
                        <!-- <p id="verify_otp_message"></p> -->
                        <p id="error_msg" style="color:red"></p>
                        <div class="otp-code profile-page-otp">
                        <input id="mode" name="mode" type="hidden" value="">
                          <div class="form-group">
                            <input id="otpcode1" type="text" class="form-control" maxlength = "1" onkeypress="javascript:return isNumber(event)" onkeyup="onKeyUpEvent(1, event)" onfocus="onFocusEvent(1)">
                          </div>
                          <div class="form-group">
                            <input id="otpcode2" type="text" class="form-control" maxlength = "1" onkeypress="javascript:return isNumber(event)" onkeyup="onKeyUpEvent(2, event)" onfocus="onFocusEvent(2)">
                          </div>
                          <div class="form-group">
                            <input id="otpcode3" type="text" class="form-control" maxlength = "1" onkeypress="javascript:return isNumber(event)" onkeyup="onKeyUpEvent(3, event)" onfocus="onFocusEvent(3)">
                          </div>
                          <div class="form-group">
                            <input id="otpcode4" type="text" class="form-control" maxlength = "1" onkeypress="javascript:return isNumber(event)" onkeyup="onKeyUpEvent(4, event)" onfocus="onFocusEvent(4)">
                          </div>
                          <div class="form-group">
                            <input id="otpcode5" type="text" class="form-control" maxlength = "1" onkeypress="javascript:return isNumber(event)" onkeyup="onKeyUpEvent(5, event)" onfocus="onFocusEvent(5)">
                          </div>
                          <div class="form-group">
                            <input id="otpcode6" type="text" class="form-control" maxlength = "1" onkeypress="javascript:return isNumber(event)" onkeyup="onKeyUpEvent(6, event)" onfocus="onFocusEvent(6)">
                          </div>
                           </div>
                      </div>
                    </div>
                      <div class="resend text-center">
                        <p class="mb-0"> {{trans('customers.secure_code')}}</p>
                        <div class="countdown">
                          <div class="countdown__time clr-primary font-family-semibold">
                          </div>
                        </div>
                      </div>
                      <div class="resend text-center resend_div" >
                          <p>{{trans('customers.not_received_otp')}} <a href="javascript:void(0);" id="resend_otp">{{trans('customers.resend')}}</a></p>
                      </div>
                      <div class="col-12 mt-3 text-center">
                        <div class="update-profile">
                           <a id="veryfy_otp_btn" href="javascript:void(0);" data-dismiss="modal" aria-label="Close" class="btn btn-info btn-fill btn-wd">{{(trans('common.update'))}}</a>
                           <a href="javascript:void(0);" data-dismiss="modal" aria-label="Close" class="btn btn-info btn-fill btn-wd reload">{{trans('common.cancel')}}</a>
                        </div>
                     </div>
                  </form>
                </div>
              </div>
            </div>      
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<form id="resend_otp_form">
    @csrf
    <input type="hidden" name="user_id" value="{{@$user->id}}">
    <input type="hidden" name="mobile" id='send_mobile_number'value="">
    <input type="hidden" name="email" id='send_email'value="" >
</form>

<!-- END: Content-->
@endsection 

@section('page_js')



@endsection

@section('js')
<script type="text/javascript">
$(document).ready(function() {

  

  $('.emailupdate_form').on('submit', function (event) {
    event.preventDefault();
    var url = '{{ route("admin.update_email") }}';
    var form = $('.emailupdate_form').serialize();
    var data = form;
    $.ajax({
      type: 'POST',
      url: url,
      data:data,
      success: function (response) {
        console.log(response);
        if(response.success) {
          alert(response.success.message);
          $('#send_email').val(response.success.email);
          $('#mode').val('email');
          $('#editemail_forvrf').modal('hide');
          $('#verify_otp').modal('show');
          countdown_timer();
          $('#editemail').modal('hide');
          $('#emailupdate_btn').text('{{trans("common.update")}}');
        } else {
          $('#emailupdate_btn').text('{{trans("common.update")}}');
          alert(response.error);
        }
      },
      beforeSend:function() {
          $('#emailupdate_btn').text('{{trans("customers.processing")}}');

      }
    });
    return false;
})  


$('.phoneupdate_form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      type: 'post',
      url: "{{route('admin.update_mobile_number')}}",
      data: $('.phoneupdate_form').serialize(),
      success: function (response) {
        if(response.success) {
        alert(response.success.message);
        $('#send_mobile_number').val(response.success.mobile_number);
        $('#mode').val('mobile');
        // $('input[name="send_mobile_number"]').val('999999999');
          $('#verify_otp').modal('show');
          countdown_timer();
          $('#otpcode1').focus();
          // $('#verify_otp_message').text(response.success.message);
          $('#editphone').modal('hide');
          $('#phoneupdate_btn').text('{{trans("customers.send_otp")}}');
          // alert(response.success.message);
        } else {
          $('#phoneupdate_btn').text('{{trans("common.update")}}');
          alert(response.error);
          //countdown_timer();
        }
      },
      beforeSend:function() {
          $('#phoneupdate_btn').text('{{trans("customers.processing")}}');
      }
    });
})  

$('.reload').click(function(){

  location.reload();
});

$('#veryfy_otp_btn').click(function(){
   mobile_number = $('#updated_mobile_number').val();
    email = $('#updated_email_forvrf').val();
    otp = $('#otpcode1').val()+$('#otpcode2').val()+$('#otpcode3').val()+$('#otpcode4').val()+$('#otpcode5').val()+$('#otpcode6').val();
   
   if(otp == '') {  $("#error_msg").text("Please enter OTP"); return false;}

    if($('#mode').val() == 'mobile'){
        var url = "{{route('admin.verify_updated_mobile_number')}}";
        $.ajax({
          url : url,
          type: 'post',
          data : {
             'otp' : otp,
             'mobile_number' : mobile_number,
             '_token' : "{{ csrf_token() }}",
          },
          dataType : 'json',
          success:function(response){
          //  console.log(response); return false;
            if(response.success){
              alert(response.success.message);
              window.location.href = "{{route('profile','phone_email')}}";
            }else{
              $('#veryfy_otp_btn').text('{{trans("customers.verify_otp")}}');
            //  alert(response.error);
              $("#error_msg").text(response.error);
              $('#verify_otp').modal('show');
          
             // return false;
            }
          }, 
          beforeSend:function() {
            $('#veryfy_otp_btn').text('{{trans("customers.processing")}}');
          }
          
        })
    }else{
      email = $('#updated_email').val();
      email_forvrf = $('#updated_email_forvrf').val();
      if(email || email_forvrf){
        if(email) {
          email = email;
        }
        if(email_forvrf){
          email = email_forvrf;
        }
          var url = "{{route('admin.verify_updated_email')}}";
          $.ajax({
            url : url,
            type: 'post',
            data : {
               'otp' : otp,
               'email' : email,
               '_token' : "{{ csrf_token() }}",
            },
            dataType : 'json',
            success:function(response){
              // console.log(response); return false;
              if(response.success){
                alert(response.success.message);
                window.location.href = "{{route('profile','phone_email')}}";
              }else{
                $('#veryfy_otp_btn').text('{{trans("customers.verify_otp")}}');
              //  alert(response.error);
                $("#error_msg").text(response.error);
              
              }
            },
            beforeSend:function() {
              $('#veryfy_otp_btn').text('{{trans("customers.processing")}}');
            }
          })
      }
    }
})


 $('#resend_otp').click(function(){
 $("#error_msg").text("");
  if($('#send_email').val() != ''  && $('#send_email').val() != 'undefined' ){
    $.ajax({
      type: 'post',
      url: "{{route('admin.resend_email_otp')}}",
      data: $('#resend_otp_form').serialize(),
      success: function (response) {
        // console.log(response);
        if(response.success) {
          $('#verify_otp').modal('show');
          $('#verify_otp_message').text(response.success.message);
          $('#editemail').modal('hide');
          $('#editemail_forvrf').modal('hide');
          $('.resend_div').css('display','none');
          countdown_timer();

          alert(response.success.message);
        } else {
          alert(response.error);
          $('#emailupdate_btn_forvrf').text('{{trans("common.submit")}}');
        }
      },
      beforeSend:function() {
          $('#emailupdate_btn_forvrf').text('{{trans("customers.processing")}}');
      }
    });

  }else{
    $.ajax({
        type: 'post',
        url: "{{route('admin.resend_mobile_otp')}}",
        data: $('#resend_otp_form').serialize(),
        success: function (response) {
          console.log(response);
          if(response.success) {
            $('#verify_otp').modal('show');
            $('#verify_otp_message').text(response.success.message);
            $('#editemail').modal('hide');
            $('#editemail_forvrf').modal('hide');
            $('.resend_div').css('display','none');
            countdown_timer();
            alert(response.success.message);
          } else {
            alert(response.error);
            $('#emailupdate_btn_forvrf').text('{{trans("common.submit")}}');
          }
        },
        beforeSend:function() {
            $('#emailupdate_btn_forvrf').text('{{trans("customer_web.processing")}}');
        }
    });
  }
});



//jQuery(function($) 
  $(".resend_div").css('display','none');
function countdown_timer(){
      //   Function counts down from 1 minute, display turns orange at 20 seconds and red at 5 seconds.
        var countdownTimer = {
          init: function() {
              this.cacheDom();
              this.render();
          },
          cacheDom: function() {
              this.$el = $('.countdown');
              this.$time = this.$el.find('.countdown__time');
             // this.$reset = this.$el.find('.countdown__reset');
          },
          render: function() {
              var totalTime = 60 * 1,
                  display = this.$time;
              this.startTimer(totalTime, display);
            //   this.$time.removeClass('countdown__time--red');
            // this.$time.removeClass('countdown__time--orange');
          }, 
          startTimer: function(duration, display, icon) {
            var timer = duration, minutes, seconds;
            var interval = setInterval(function() {
            minutes = parseInt(timer / 60, 10);
             seconds = parseInt(timer % 60, 10);
             minutes = minutes < 10 ? '0' + minutes : minutes;
             seconds = seconds < 10 ? '0' + seconds : seconds;
             display.text(minutes + ':' + seconds);
              if (--timer < 0) {
                clearInterval(interval);
              };
              if(timer <= 0)
              {
                  $(".resend_div").css('display','block');
              }
              
            }, 1000);
           },
        };

          countdownTimer.init();
 }

})

</script>

<script type="text/javascript">
  function getOtpCodeElement(index) {
      return document.getElementById('otpcode' + index);
    }
    
   function onKeyUpEvent(index, event) {
      const eventCode = event.which || event.keyCode;
      if (getOtpCodeElement(index).value.length === 1) {
       if (index !== 6) {
        getOtpCodeElement(index+ 1).focus();
       } else {
        getOtpCodeElement(index).blur();
        // Submit code
        console.log('submit code ');
       }
      }
      if (eventCode === 8 && index !== 1) {
       getOtpCodeElement(index - 1).focus();
      }
    }
    function onFocusEvent(index) {
      for (item = 1; item < index; item++) {
       const currentElement = getOtpCodeElement(item);
       if (!currentElement.value) {
          currentElement.focus();
          break;
       }
      }
    }

      function isNumber(evt) {
          var iKeyCode = (evt.which) ? evt.which : evt.keyCode
          if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
              return false;  
          return true;
  }  


</script>
<script src="{{asset('js/filepond/filepond.min.js')}}"></script>
<!-- include FilePond plugins -->
<script src="{{asset('js/filepond/filepond-plugin-image-preview.min.js')}}"></script>
<script src="{{asset('js/filepond/filepond-plugin-file-validate-type.js')}}"></script>
<script src="{{asset('js/filepond/filepond-plugin-image-crop.js')}}"></script>
<script src="{{asset('js/filepond/filepond-plugin-image-resize.js')}}"></script>
<script src="{{asset('js/filepond/filepond-plugin-image-validate-size.js')}}"></script>
<script src="{{asset('js/filepond/filepond-plugin-image-transform.js')}}"></script>
<script src="{{asset('js/filepond/filepond-plugin-image-edit.js')}}"></script>
<script src="{{asset('js/filepond/ar_locale.js')}}"></script>
<!-- <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script> -->
<!-- include FilePond jQuery adapter -->
<script src="{{asset('js/filepond/filepond.jquery.js')}}"></script>

<script>
$(function(){
  
  FilePond.registerPlugin(

          FilePondPluginImagePreview,
          FilePondPluginFileValidateType,
          FilePondPluginImageCrop,
          FilePondPluginImageResize,
          FilePondPluginImageValidateSize,
          FilePondPluginImageTransform,
          FilePondPluginImageEdit,

        );
  
  const input = document.querySelector('#image');
  const pond1 = FilePond.create(input,{
      imageTransformImageFilter: (file) => new Promise(resolve => {

        // no gif mimetype, do transform
        if (!/image\/gif/.test(file.type)) return resolve(true);

        const reader = new FileReader();
        reader.onload = () => {

            var arr = new Uint8Array(reader.result),
            i, len, length = arr.length, frames = 0;

            // make sure it's a gif (GIF8)
            if (arr[0] !== 0x47 || arr[1] !== 0x49 || 
                arr[2] !== 0x46 || arr[3] !== 0x38) {
                // it's not a gif, we can safely transform it
                resolve(true);
                return;
            }

            for (i=0, len = length - 9; i < len, frames < 2; ++i) {
                if (arr[i] === 0x00 && arr[i+1] === 0x21 &&
                    arr[i+2] === 0xF9 && arr[i+3] === 0x04 &&
                    arr[i+8] === 0x00 && 
                    (arr[i+9] === 0x2C || arr[i+9] === 0x21)) {
                    frames++;
                }
            }

            // if frame count > 1, it's animated, don't transform
            if (frames > 1) {
                return resolve(false);
            }

            // do transform
            resolve(true);
        }
        reader.readAsArrayBuffer(file);

    }),
      imagePreviewMaxHeight: 100,
      imagePreviewMaxWidth: 100,
      storeAsFile:true,
      credits:false,
      allowImageCrop:true,
      allowImageTransform:true,
      imageCropAspectRatio:'1:1',
      acceptedFileTypes:['image/png', 'image/jpeg','image/jpg','image/gif'],
      allowImageResize:true,
      imageResizeTargetWidth:250,
      imageResizeTargetHeight:250,
      imageResizeUpscale:true,
      imageValidateSizeMinWidth:50,
      imageValidateSizeMinHeight:50,
      files: [
         
            {
                // the server file reference
                source: "{{$admin->profile_image ? asset($admin->profile_image) : asset('admin_assets/app-assets/images/user.jpg')}}",

                // set type to local to indicate an already uploaded file
                options: {
                    type: 'remote',
                    
                
                },
            },
            
          ],
      
     
  });

   //Change Locale
  @if(config("app.locale") == 'ar'){
   FilePond.setOptions(labels);
  }
  @endif
});

</script>

@endsection