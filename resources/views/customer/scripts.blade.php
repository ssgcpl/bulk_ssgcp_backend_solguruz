<script> 
  if(window.navigator.offLine){
    console.log('no internet');
  }
$(document).ready(function(){
	const BASE_URL = "{{config('adminlte.base_api_url')}}";
  var interval;
  var token = '';
  var userid = '';
  var user_type = '';
 	if(localStorage.getItem("user_token") === null){
      $('.user_profile_nav').css("display","none");
      $('.navbar-nav').addClass('d-none');
      $(".login").css("display","block");
      $("#ticket_history_link").addClass('d-none');
      $("#sidebar_icon").addClass('d-none');
  } 
  else{
      token = localStorage.getItem("user_token");
      userid = localStorage.getItem("userid");
      user_type = localStorage.getItem("user_type");
      set_user_profile();
      get_notification_count();
      $(".user_profile_nav").css("display","block");
      $('.navbar-nav').removeClass("d-none");
      $(".login").css("display","none");
      $("#ticket_history_link").removeClass('d-none');
      $("#sidebar_icon").removeClass('d-none');
  }
  get_social_media_links();
  function get_social_media_links(){
     $.ajax({
                 url: BASE_URL+"get_social_media_link",
                 data: { },
                 type: "GET",
                 beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      // xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','');
                  },
                  error:function(response){
                   if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                  },
                 success: function(response) {
                  $('.loader').css('visibility','hidden');
                    if(response.status == "200") {
                         console.log(response);
                         $("#facebook").attr('href',response.data.facebook_url);
                         $("#twitter").attr('href',response.data.twitter_url);
                         $("#instagram").attr('href',response.data.instagram_url);
                         $("#telegram").attr('href',response.data.telegram_url);
                         $("#whatsapp").attr('href',response.data.whatsapp_url);
                    }
                    $('.loader').css('visibility','hidden');

                 }
          });
  }

  function get_notification_count()
        {
          $.ajax({
                 url: BASE_URL+"notification_count",
                 data: { },
                 type: "GET",
                 beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                        $('.loader').css('visibility','');
                  },
                  error:function(response){
                   if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                  },
                 success: function(response) {
                  $('.loader').css('visibility','hidden');
                    if(response.status == "200") {
                        // console.log(response)
                        if(response.data.count > 0)
                        {
                          $("#notif_exist").removeClass('d-none');
                          // $("#notif_exist").text(response.data.count);
                        }
                        else
                        {
                          $("#notif_exist").addClass('d-none');
                        }

                    }
                    $('.loader').css('visibility','hidden');

                 }
          });
        }

  function auth_guard_route(token = '') {
        if(!token || token === "") {
          setTimeout(function(){
            $("#sign_in_modal").modal('show');
          },500)
          //location.href = "{{route('signin')}}"+"?url="+window.location.href;
        }

        if(token == '401'){
          window.location ="{{route('signin')}}";
          localStorage.removeItem('user_token');
        }
       
  }

  function set_user_token(token) {
         localStorage.setItem('user_token',token);
         return localStorage.getItem('user_token');
  }

  var interval = '';
  //countdown timer function 
  function countdown_timer(){
        var countdownTimer = {
          init: function() {
              this.cacheDom();
              this.render();
          },
          cacheDom: function() {
              this.$el = $('.countdown');
              this.$time = this.$el.find('.countdown__time');
          },
          render: function() {
              var totalTime = 60 * 1,
              display = this.$time;
              this.startTimer(totalTime, display);
          }, 
          startTimer: function(duration, display, icon) {
            var timer = duration, minutes, seconds;
            interval = setInterval(function() {
            minutes = parseInt(timer / 60, 10);
             seconds = parseInt(timer % 60, 10);
             minutes = minutes < 10 ? '0' + minutes : minutes;
             seconds = seconds < 10 ? '0' + seconds : seconds;
             display.text(minutes + ':' + seconds);
              if (--timer < 0) {
                clearInterval(interval);
              }
              if(timer <= 0 ){
              	display.text('');
                $(".resend").removeClass('d-none');                
              }
            }, 1000);
          },
        };
        countdownTimer.init();
  }

  /*#####Set user data in profile page  #####*/
  function set_user_profile(){
    auth_guard_route(token);
    $.ajax({
        url: BASE_URL+"profile",
        data: { },
            type: "GET",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              xhr.setRequestHeader('Authorization', 'Bearer '+token);
              $('.loader').css('visibility','visible');
        },
        error:function(response){
         if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
         
         },
        success: function(response) {
          // console.log(response);
          if(response.status == "200") {
            localStorage.setItem('userid', response.data.id);
            localStorage.setItem('user_type', response.data.user_type);
            $('#user_image').attr('src',response.data.profile_image);
            $("#user_detail").html("<h5>"+response.data.first_name+"</h5><p>"+response.data.mobile_number+"</p>");
            if(response.data.user_type == "dealer")
            {
              $("#retailer_request_menu").removeClass('d-none')
            }
           }
            $('.loader').css('visibility','hidden');
          }
    });
  }
  $(document).on('click',"#my_cart",function(e){
    e.preventDefault();
    auth_guard_route(token);
    if(token)
    {
      location.href = "{{route('my_cart')}}";
    }
  });
  /* Common verify otp for signin,signup ::STARTS */
  @if(\Request::route()->getName() == 'signup' || \Request::route()->getName() == 'signin')
    $('#verify_otp').click(function(e){
            var otp = $("#otp_code1").val()+""+$("#otp_code2").val()+""+$("#otp_code3").val()+""+$("#otp_code4").val()+""+$("#otp_code5").val()+""+$("#otp_code6").val();
            var user_id = $("#user_id").val();
            var mobile_number = $('#phone').val();
            if(otp ==''){
                toastr.error("Please Enter OTP");
                $('#otp_modal').modal({
                   backdrop: 'static',
                   keyboard: false
                });
                return false;
            }
            $.ajax({
                url: BASE_URL+"verify_otp",
                data: { 'mobile_number' : mobile_number, 'user_id' : user_id,'otp':otp},
                type: "POST",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                   $('.loader').css('visibility','visible');
                },
             
                success: function(response) {
                  //console.log(response);
                  if(response.status == "200") {
                      var token = set_user_token(response.data.token);
                      auth_guard_route(token);
                      @if(\Request::route()->getName() == 'signup')
                        location.href = "{{route('web_home')}}?is_first=1";
                      @else 
                        location.href = "{{route('web_home')}}";
                      @endif
                      
                  } 
                  else 
                  {
                    toastr.error(response.message);
                    $("#otp_code1").val('');
                    $("#otp_code2").val('')
                    $("#otp_code3").val('')
                    $("#otp_code4").val('')
                    $("#otp_code5").val('')
                    $("#otp_code6").val('')
                    $("#otp_modal").modal('show');
                   // toastr.error(response.message);
                  }
                   $('.loader').css('visibility','hidden');
                }
            });
    });

    $('.resend').click(function(){
          var mobile_number = $("#mobile_number").val();
          var user_id = $("#user_id").val();  
          $.ajax({
            url: BASE_URL+"resend_otp",
            data: { 'mobile_number' : mobile_number ,'user_id':user_id },
            type: "POST",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
               $('.loader').css('visibility','visible');
            },
          
            success: function(response) {
              if(response.status == "200") {
                countdown_timer();
                $(".resend").addClass('d-none');
                toastr.success(response.message);
              } else {
                toastr.error(response.message);
              }
               $('.loader').css('visibility','hidden');
            }
          });
    });

     $(document).on('click','.password_icon',function(){
             if($('.password_icon').hasClass('fa-lock'))
            {
              $('.password_icon').removeClass('fa-lock');
              $('.password_icon').addClass('fa-unlock');
              $("#password").attr('type','text');
            }
            else
            {
              $('.password_icon').addClass('fa-lock');
              $('.password_icon').removeClass('fa-unlock');
              $("#password").attr('type','password');
            }
          })


      	$(document).on('click','.c_password_icon',function(){
             if($('.c_password_icon').hasClass('fa-lock'))
            {
              $('.c_password_icon').removeClass('fa-lock');
              $('.c_password_icon').addClass('fa-unlock');
              $("#confirm_password").attr('type','text');
            }
            else
            {
              $('.c_password_icon').addClass('fa-lock');
              $('.c_password_icon').removeClass('fa-unlock');
              $("#confirm_password").attr('type','password');
            }
        })
  @endif
  /* Common verify otp for signin,signup  :: ENDS*/

	/* ########## LOGIN/REGISTER - AUTH SCRIPTS STARTS ############ */
	@if(\Request::route()->getName() == 'signin')
	   
  		// Login with OTP
  		$("#login_with_otp").click(function(){
  			var mobile_number = $("#mobile_number").val();
  			if(mobile_number == '') {
  				toastr.error('Please enter mobile number first'); 
  				return false;
  			}
  			    $(".countdown__time").empty();
  	        clearInterval(interval);
  	        $.ajax({
  	            url: BASE_URL+"login_with_otp",
  	            data: { 'mobile_number' : mobile_number},
  	            type: "POST",
  	            beforeSend: function(xhr){
  	              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
  	               $('.loader').css('visibility','visible');
  	               $("#login_with_otp").prop("disabled",true);
  	            },
  	            success: function(response) {
  	              if(response.status == "200") {
  	                $('#user_id').val(response.data.id);
                    $('#phone').val(mobile_number);
  	                $('#otp-modal').modal('show');
                    countdown_timer();
                  } else {
  	                toastr.error(response.message);
  	              }
  	               $("#login_with_otp").prop("disabled",false);
  	               $('.loader').css('visibility','hidden');
  	            }
  	        });
  		})

  	  $("#login_with_password").click(function(){
              var mobile_or_email  = $("#mobile_or_email").val();
              var password = $("#password").val();
              
              if(mobile_or_email.length == '10' || mobile_or_email.match(/^\d+$/)){
                var data = { 'mobile_number' : mobile_or_email,'password':password};
              }else {
                var data = {'email':mobile_or_email,'password':password};
              }

              $.ajax({
                url: BASE_URL+"login_with_password",
                data: data,
                type: "POST",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                   $('.loader').css('visibility','visible');
                   $("#login_with_otp").prop("disabled",true);
                },
                success: function(response) {
                  if(response.status == "200") {
                    var token = set_user_token(response.data.token);
                    auth_guard_route(token);
                    location.href = "{{route('web_home')}}";
                  } else {
                    toastr.error(response.message);
                  }
                   $("#login_with_otp").prop("disabled",false);
                   $('.loader').css('visibility','hidden');
                }
              });
      })
  @endif
  /* ########## LOGIN/REGISTER - AUTH SCRIPTS ENDS ############ */

  /* ########## REGISTER - SCRIPTS STARTS ############ */
  @if(\Request::route()->getName() == 'signup')
  
    $(document).on('click','#signup_btn',function(evt){
            
            // var password = $("#password").val();
            // var c_password = $("#confirm_password").val();
            /*if(password.length < 8 || password.length >16){
            	toastr.error('Password length must be between 8 to 16 characters');
            	return false;
            }
            if(c_password.length < 8 || c_password.length >16){
            	toastr.error('Confirm Password length must be between 8 to 16 characters');
            	return false;
            }
            if(password != c_password){
            	toastr.error('Password and Confirm Password must be same');
            	return false;
            }*/
           /* if($("#profile_image").val() == ''){
            	toastr.error('Please Upload Profile Image');
            	return false;
            }
            if($('#company_images')[0].files.length == '0'){
            	toastr.error('Please Upload Company Image');
            	return false;
            }
             if($('#company_docs')[0].files.length == '0'){
            	toastr.error('Please Upload Company Document Images');
            	return false;
            }*/
           // (".countdown__time").empty();
            //clearInterval(interval);
            if($("#first_name").val() && $("#company_name").val() && $("#email").val() && $("#mobile_number").val() && $("#password").val()  && $("#confirm_password").val() && $("#profile_image").val() && $('#company_images')[0].files.length > 0 && $('#company_docs')[0].files.length > 0 )
            {
                if(!$("#terms").is(':checked')) { 
                  toastr.error("Please accept terms and conditions first");
                  return false;
                }
            }
          
             if($("#first_name").val() && $("#company_name").val() && $("#email").val() && $("#mobile_number").val() && $("#password").val()  && $("#confirm_password").val() )
              {
                evt.preventDefault();
                var formData = new FormData($("#signupForm")[0]);
                // formData = formData.replaceAll("<\/script>", "");
                formData.append('profile_image',$('#profile_image')[0].files);
                formData.append('device_token','00000');
                formData.append('device_type','web');
                for(var j=0; j< $('#company_images')[0].files.length; j++){
                  formData.append('company_images[]',$('#company_images')[0].files[j]);
                }
                for(var i=0; i< $('#company_docs')[0].files.length; i++){
                  formData.append('company_documents[]',$('#company_docs')[0].files[i]);
                }
                
                $.ajax({
                    url: BASE_URL+"signup",
                    data: formData,
                    type: "POST",
                    processData: false,  // Important!
                    contentType: false,
                    cache:false,
                    beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      $("#signup_btn").prop("disabled",true);
                      $('#preloader').removeClass('d-none');
                      $('#preloader').addClass('d-flex');
                      
                    },
                    success: function(response) {
                      if(response.status == "200") {
                        $("#phone").val(response.data.mobile_number);
                        $('#user_id').val(response.data.id);
                        $('#otp-modal').modal('show');
                        countdown_timer();
                      } else {
                        toastr.error(response.message);
                      }
                      $('#preloader').addClass('d-none');
                      $('#preloader').removeClass('d-flex');
                      $("#signup_btn").prop("disabled",false);
                    }
                });
              }

              
            
    })
  	
    $(document).on('change','#profile_image',function(){
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
               $("#profile_img_preview").html('<div class="img"><img src="'+e.target.result+'"/><a href="javascript:void(0)" class="close remove_preview">×</a>Image 1</div>');
            }
            $("#profile_img_btn").addClass("d-none");
            reader.readAsDataURL(this.files[0]);
        }
    });
    $(document).on('click','.remove_preview',function(){
      $("#profile_img_preview").html('');
      $("#profile_img_preview").attr('src','');
      $("#profile_image").val("");
      $("#profile_img_btn").removeClass("d-none");
    });

    var cnt = '1';
    var cnt1 = '1';
         
    $(document).on('change','#company_images',function(){
      //readURL(this,'profile_img_preview');
        if (this.files) {
          var imgs = '';
            for(var j=0; j< $(this)[0].files.length; j++){
              var reader = new FileReader();
              reader.onload = function (e) {
                 $('<div class="img" id="Image '+cnt+'"><img src="'+e.target.result+'"/><a href="javascript:void(0);" class="close remove_preview_images" id="'+cnt+'">×</a>Image '+cnt+'</div>', {
                   }).appendTo("#comp_images");
                 cnt++;
              }

              reader.readAsDataURL(this.files[j]);
            }
        }
    })

    $(document).on('change','#company_docs',function(){
        if (this.files) {
          var imgs = '';
            for(var j=0; j< $(this)[0].files.length; j++){
              var reader = new FileReader();
              reader.onload = function (e) {
                  $('<div class="img" id="doc_'+cnt1+'"><img src="'+e.target.result+'" /><a href="javascript:void(0)" class="close remove_preview_docs" id="'+cnt1+'">×</a>Image '+cnt1+'</div>', {
                  }).appendTo("#document_images");
                  cnt1++;
              }
              reader.readAsDataURL(this.files[j]);
            }
        }
    })
     $(document).on('click','.remove_preview_images',function(){
      var id = $(this).attr('id');
      $("#Image "+id).remove();
      $(this).parent('div').remove();
      if($("#comp_images img").length == 0)
      {
        $("#company_images").val("");
      }
     });
     $(document).on('click','.remove_preview_docs',function(){
    	var id = $(this).attr('id');
    	$("#doc_"+id).remove();
    	$(this).parent('div').remove();
      if($("#document_images img").length == 0)
      {
        $("#company_docs").val("");
      }
	 });
  @endif
	/* ########## REGISTER - SCRIPTS ENDS ############ */

  /* ########## FORGOT PASSWORD - SCRIPTS STARTS ############ */
  @if(\Request::route()->getName() == 'forgot_password')
    $("#reset_password").on('click',function(){
      var data = '';
      var type = '';
      if($("input[name=sendoption]:checked").attr('id') == 'mobile_chk'){
        type = 'mobile';
        data = { 'send_in':'mobile' , 'mobile_number':$("#mobile").val() }
      }
      else if($("input[name=sendoption]:checked").attr('id') == 'email_chk'){
        type = 'email';
        data = { 'send_in':'email' , 'email':$("#email").val() }
      }
      $.ajax({
                url: BASE_URL+"forgot_password",
                data: data,
                type: "POST",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                   $('.loader').css('visibility','visible');
                   $("#reset_password_btn").prop("disabled",true);
                },
              
                success: function(response) {
                  if(response.status == "200") {
                    if($("input[name=sendoption]:checked").attr('id') == 'email_chk'){
                      toastr.success('Link for reset password sent successfully');
                      location.href ="{{route('signin')}}";
                    }else {
                      countdown_timer();
                      $("#phone_no").val($("#mobile").val()); 
                      $('#forgot-password-otp-modal').modal('show');
                    }
                  } else {
                    toastr.error(response.message);
                  }
                   $("#reset_password_btn").prop("disabled",false);
                   $('.loader').css('visibility','hidden');
                }
      });
    });

    $('#verify_fpassword_otp').click(function(e){
            var otp = $("#otp_code1").val()+""+$("#otp_code2").val()+""+$("#otp_code3").val()+""+$("#otp_code4").val()+""+$("#otp_code5").val()+""+$("#otp_code6").val();
            var mobile_number = $('#phone_no').val();
            if(otp ==''){
                toastr.error("Please Enter OTP");
                $('#otp_modal').modal({
                   backdrop: 'static',
                   keyboard: false
                });
                return false;
            }
            $.ajax({
                url: BASE_URL+"forgot_password_verify_otp",
                data: { 'mobile_number' : mobile_number,'otp':otp},
                type: "POST",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                   $('.loader').css('visibility','visible');
                },
                success: function(response) {
                  console.log(response);
                  if(response.status == "200") {
                    var route = "{{ route('reset_password')}}?otp="+otp+"&phone="+mobile_number;
                    location.href = route;
                    toastr.success(response.message);
                  } 
                  else 
                  {
                    toastr.error(response.message);
                    $("#otp_code1").val('');
                    $("#otp_code2").val('')
                    $("#otp_code3").val('')
                    $("#otp_code4").val('')
                    $("#otp_code5").val('')
                    $("#otp_code6").val('')
                    $("#otp_modal").modal('show');
                  }
                   $('.loader').css('visibility','hidden');
                }
            });
    });

    $('#resend_fpassword').click(function(){
          var mobile_number = $("#phone_no").val();
          $.ajax({
            url: BASE_URL+"forgot_password_resend_otp",
            data: { 'mobile_number' : mobile_number },
            type: "POST",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
               $('.loader').css('visibility','visible');
            },
            success: function(response) {
              if(response.status == "200") {
                countdown_timer();
                $(".resend").addClass('d-none');
                toastr.success(response.message);
              } else {
                toastr.error(response.message);
              }
               $('.loader').css('visibility','hidden');
            }
          });
    });
  @endif

  @if(\Request::route()->getName() == 'reset_password')

    $("#reset_pwd_btn").click(function(){
      var otp = $("#hdn_otp").val();
      var mobile_number = $("#hdn_mobile_number").val();
      var password = $("#password").val();
      var confirm_password = $("#confirm_password").val();

       $.ajax({
                url: BASE_URL+"reset_password",
                data: { 'mobile_number' : mobile_number,'otp':otp,'password':password,'confirm_password':confirm_password},
                type: "POST",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                   $('.loader').css('visibility','visible');
                },
                success: function(response) {
                  //console.log(response);
                  if(response.status == "200") {
                    location.href = "{{route('signin')}}";
                    toastr.success(response.message);
                  } 
                  else 
                  {
                    toastr.error(response.message);
                  }
                   $('.loader').css('visibility','hidden');
                }
            });
    });
  @endif
  /* ########## FORGOT PASSWORD - SCRIPTS ENDS ############ */

	/* ########## LOGOUT STARTS ############ */
	$("#logout_btn").click(function(){

		  $.ajax({
                 url: BASE_URL+"logout",
                 data: { 'device_token':token },
                 type: "POST",
                 beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                       $('.loader').css('visibility','visible');

                  },
                  error:function(response){
                   if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                  },
                 success: function(response) {
                    if(response.status == "200") {
                          localStorage.removeItem("user_token");
                          /*localStorage.removeItem("userid");
                          localStorage.removeItem("address_id");*/
                          window.location.href = "{{route('signin')}}";
                    } else {
                     toastr.error(response.message);
                    }
                     $('.loader').css('visibility','hidden');
                 }
          });
	})
  /* ########## LOGOUT ENDS ############ */

  /*############## PROFILE - SCRIPT STARTS #################*/
  @if(Request::route()->getName() == 'customer.profile')


        get_user_profile();
        get_user_addresses();
        $(document).on('click','.password_icon',function(){
            if($('.password_icon').hasClass('fa-lock'))
            {
              $('.password_icon').removeClass('fa-lock');
              $('.password_icon').addClass('fa-unlock');
              $("#old_password").attr('type','text');
            }
            else
            {
              $('.password_icon').addClass('fa-lock');
              $('.password_icon').removeClass('fa-unlock');
              $("#old_password").attr('type','password');
            }
        })

        $(document).on('click','.new_password_icon',function(){
            if($('.new_password_icon').hasClass('fa-lock'))
            {
              $('.new_password_icon').removeClass('fa-lock');
              $('.new_password_icon').addClass('fa-unlock');
              $("#new_password").attr('type','text');
            }
            else
            {
              $('.new_password_icon').addClass('fa-lock');
              $('.new_password_icon').removeClass('fa-unlock');
              $("#new_password").attr('type','password');
            }
        })

        $(document).on('click','.c_password_icon',function(){
            if($('.c_password_icon').hasClass('fa-lock'))
            {
              $('.c_password_icon').removeClass('fa-lock');
              $('.c_password_icon').addClass('fa-unlock');
              $("#confirm_password").attr('type','text');
            }
            else
            {
              $('.c_password_icon').addClass('fa-lock');
              $('.c_password_icon').removeClass('fa-unlock');
              $("#confirm_password").attr('type','password');
            }
        })

        function get_user_profile(){
          auth_guard_route(token);
          $.ajax({
            url: BASE_URL+"profile",
            data: { },
                type: "GET",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
            },
            error:function(response){
             if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
            },
            success: function(response) {
               if(response.status == "200") {
                
                var userid         = response.data.id;
                var profile_image  = response.data.profile_image;
                var first_name     = response.data.first_name;
                var email          = response.data.email;
                var mobile_number  = response.data.mobile_number;
                var company_name   = response.data.company_name;
                var company_images = response.data.company_images;
                var company_docs   = response.data.company_documents;
                localStorage.setItem('userid', userid);
                localStorage.setItem('user_type', response.data.user_type);
                $('#user_img').attr('src',profile_image);
                $("#user_info").html("<h4>"+first_name+"</h4><p>"+email+"</p>");
                $("#user_name").text(first_name);
                $("#comp_name").text(company_name);
                $("#Email").text(email);
                $("#mobile_number").append(mobile_number);
                $("#hdn_mobile_number").val(mobile_number);
                if(response.data.email_verified == '0'){
                  $("#verify_btn").removeClass('d-none');
                }else {
                  $("#verify_btn").addClass('d-none');
                  $("#verify_txt").text('Verified');
                }
                var html = '';
                for(var i=0; i< company_images.length; i++){
                  num = i+1;
                  html += `<div class="preview-img">
                              <div class="img">
                              <img src="`+company_images[i].image+`" alt="">
                              Image `+num+` </div></div>`;
                }
                $("#company_imgs").append(html);
                var html_docs = '';
                for(var j=0; j< company_docs.length; j++){
                  num1 = j+1;
                  html_docs += `<div class="preview-img">
                              <div class="img"><img src="`+company_docs[j].document+`" alt="">Image `+num1+`</div> 
                          </div>`;
                }
                $("#company_docs").append(html_docs);
              }
                $('.loader').css('visibility','hidden');
              }
          });
        } 
        var num   = 0;
        var num1  = 0;
        var cnt   = 0;
        var cnt1  = 0;
   
    $(document).on('click','#edit_images',function(){
      $("#edit-images-modal").modal("show");
      $("#company_documents_div").html('');
      $("#company_images_div").html('');
       $.ajax({
        url: BASE_URL+"profile",
        data: { },
            type: "GET",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              xhr.setRequestHeader('Authorization', 'Bearer '+token);
              //$('.loader').css('visibility','visible');
              $('#preloader').removeClass('d-none');
              $('#preloader').addClass('d-flex');
        },
        error:function(response){
         if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
        },
        success: function(response) {
           if(response.status == "200") {
            var html = '';
            var company_images = response.data.company_images;
            var company_docs = response.data.company_documents;
            cnt  = company_images.length + 1;
            cnt1 = company_docs.length + 1;
            for(var i=0; i< company_images.length; i++){
              num = i+1;
              html += `
                          <div class="img">
                          <img src="`+company_images[i].image+`" alt=""><a href="javascript:void(0)" class="close remove_preview_images" id="`+num+`">×</a>Image `+num+` </div>`;
            }
            $("#company_images_div").append(html);
            
            var html_docs = '';
            for(var j=0; j< company_docs.length; j++){
              num1 = j+1;
              html_docs += `
                          <div class="img"><img src="`+company_docs[j].document+`" alt="" ><a href="javascript:void(0)" class="close remove_preview_docs" id="`+num1+`">×</a>Image `+num1+`</div> 
                      `;
            }
            $("#company_documents_div").append(html_docs);
          }
           // $('.loader').css('visibility','hidden');
            $('#preloader').addClass('d-none');
            $('#preloader').removeClass('d-flex');
          }
      });
    });

    $(document).on('click','#select_address',function(){
        var address_id = $("input[name=sendoption]:checked").attr('id'); 
        var result = edit_address_info(address_id);
        var state_id = result.data.state_id;
        var city_id = result.data.state_id; 
        var postcode_id = result.data.postcode_id;
        $.ajax({
                       url: BASE_URL+'address/update',
                       data: {'is_delivery_address':'yes','state_id':state_id,'city_id':city_id,'postcode_id':postcode_id,'address_id':address_id},
                       type: "POST",
                       beforeSend: function(xhr){
                          xhr.setRequestHeader('Authorization', 'Bearer '+token);
                          xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                          $('.loader').css('visibility','visible');
                          $("#save_address_btn").prop('disabled',true);
                        },
                        error:function(response){
                         if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                        },
                        success: function(response) {
                          if(response.status == "200") {
                            toastr.success(response.message);
                            //   get_user_addresses(token);
                                setTimeout(function(){
                                    location.reload();
                                }, 1500);
                           } else {
                            toastr.error(response.message);
                            e.preventDefault();
                          }
                          $("#save_address_btn").prop('disabled',false);
                          $('.loader').css('visibility','hidden');
                       }
              });    
    })
 
    $(document).on('click','.delete',function(){
         var address_id = $(this).attr('id');
         $("#id").val(address_id);
         $("#save-address").modal("hide");
    });
  
    $(document).on('click','#remove_item',function(){
         var address_id = $("#id").val();
         $.ajax({
                     url: BASE_URL+"address/delete",
                     data: {'address_id':address_id},
                     type: "POST",
                     beforeSend: function(xhr){
                          xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                          xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      },
                      error:function(response){
                       if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                      },
                     success: function(response) {
                        if(response.status == "200") {
                          toastr.success(response.message);
                          setTimeout(function(){
                            location.reload();
                          },1000);
                        } else {
                         toastr.error(response.message);
                        }
                     }
                });
    });
  
    $("#file_input_doc").on('change',function(){
       if (this.files) {
            for(var j=0; j< $(this)[0].files.length; j++){
              var reader = new FileReader();
              reader.onload = function (e) {
                  $('<div class="img" id="doc_'+cnt1+'"><img src="'+e.target.result+'" /><a href="javascript:void(0)" class="close remove_preview_docs" id="'+cnt1+'">×</a>Image '+cnt1+'</div>', {
                  }).appendTo("#company_documents_div");
                  cnt1++;
              }
               reader.readAsDataURL(this.files[j]);
            
            }
        }
    });
    
    $("#file_input_img").on('change',function(){
      if (this.files) {
            for(var j=0; j< $(this)[0].files.length; j++){
              var reader = new FileReader();
              reader.onload = function (e) {
                  $('<div class="img" id="Image '+cnt+'"><img src="'+e.target.result+'" /><a href="javascript:void(0)" class="close remove_preview_images" id="'+cnt+'">×</a>Image '+cnt+'</div>', {
                  }).appendTo("#company_images_div");
                  cnt++;
              }
              reader.readAsDataURL(this.files[j]);
            }
        }
    });

    $(document).on('click','.remove_preview_images',function(){
      var id = $(this).attr('id');
      $("#Image "+id).remove();
      if(cnt > 1) { cnt = cnt - 1; } else { cnt = '1'; } 
      $(this).parent('div').remove();
    });
    
    $(document).on('click','.remove_preview_docs',function(){
      var id = $(this).attr('id');
      $("#doc_"+id).remove();
      if(cnt1 > 1) { cnt1 = cnt1-1; } else { cnt1 = '1'; } 
      $(this).parent('div').remove();
    });

    function getBase64Image(img) {
      // Create an empty canvas element
      var canvas = document.createElement("canvas");
      canvas.width = img.naturalHeight;
      canvas.height = img.naturalHeight;

      // Copy the image contents to the canvas
      var ctx = canvas.getContext("2d");
      ctx.drawImage(img, 0, 0);

      // Get the data-URL formatted image
      // Firefox supports PNG and JPEG. You could check img.src to guess the
      // original format, but be aware the using "image/jpg" will re-encode the image.
      var dataURL = canvas.toDataURL("image/png");

      return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
    }

    
    $("#update_docs_images").on('click',function(){
        var company_documents = [];
        var company_images = [];
        var formData  = new FormData();
        // formData = formData.replaceAll("<\/script>", "");
      /*  for(var i=0; i< $('#file_input_doc')[0].files.length; i++){
            formData.append('company_documents[]', $('#file_input_doc')[0].files[i]);
        }
        for(var j=0; j< $('#file_input_img')[0].files.length; j++){
            formData.append('company_images[]',$('#file_input_img')[0].files[j]);
        }*/

        $('#company_images_div img').each(function(){
          formData.append('company_images[]', getBase64Image($( this )[ 0 ]));
        });

        $('#company_documents_div img').each(function(){
          
          formData.append('company_documents[]', getBase64Image($( this )[ 0 ]));
        });
        formData.append('platform','web');
        
        $.ajax({
           url: BASE_URL+"profile/update_company_images",
           data: formData,
           type: "POST",
           processData : false,
           contentType: false,
           // async:false, 
           cache:false,
           beforeSend: function(xhr){
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                $('#preloader').removeClass('d-none');
                $('#preloader').addClass('d-flex');
            },
            error:function(response){
             if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
            },
           success: function(response) {
              if(response.status == "200") {
                toastr.success(response.message);
                setTimeout(function(){
                  location.reload();
                },1000);
              } else {
               toastr.error(response.message);
              }
              $('#preloader').addClass('d-none');
              $('#preloader').removeClass('d-flex');
           }
      });
    });

    $("#edit_personal_info").on('click',function(){
      var username     = $("#user_name").text();
      var company_name = $("#comp_name").text();
      $("#first_name").val(username);
      $("#company_name").val(company_name);
      $("#personal-details").modal("show");
    });

    $("#personalInfoForm").on('submit',function(e){
        e.preventDefault();
        var data = $('#personalInfoForm').serialize();
        // var first_name = $("#first_name").val();
        //var company_name = $("#company_firm_name").val();
       $.ajax({
          url: BASE_URL+"profile/update_personal_details",
          data: data,
              type: "POST",
              beforeSend: function(xhr){
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                $('.loader').css('visibility','visible');
          },
          error:function(response){
           if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
          },
          success: function(response) {
            if(response.status == "200") {
              toastr.success(response.message);
              setTimeout(function(){
                location.reload();
              },1000);
              
            }
            else {
              toastr.error(response.message);
            }
              $('.loader').css('visibility','hidden');
            }
        });
    });

    $("#profile_image").on('change',function(e){
      e.preventDefault();
      $('#imageForm').submit();
    });

    $("#imageForm").on('submit',function(evt){
                evt.preventDefault();
                var form = $('#imageForm')[0];
                var data = new FormData(form);
               // data = data.replaceAll("<\/script>", "");
                $.ajax({
                     url: BASE_URL+"profile/update_image",
                     data: data,
                     type: "POST",
                     processData : false,
                     contentType: false,
                     // async:false, 
                     cache:false,
                     beforeSend: function(xhr){
                          xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                          xhr.setRequestHeader('Authorization', 'Bearer '+token);
                          $('#preloader').removeClass('d-none');
                          $('#preloader').addClass('d-flex');
                      },
                      error:function(response){
                       if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                      },
                      success: function(response) {
                        if(response.status == "200") {
                          toastr.success(response.message);
                          setTimeout(function(){
                            location.reload();
                          },1000);
                        } else {
                         toastr.error(response.message);
                        }
                        $('#preloader').addClass('d-none');
                        $('#preloader').removeClass('d-flex');
                     }
                });
    });

    $("#change_password_btn").on('click',function(){
      var old_password = $("#old_password").val();
      var new_password = $("#new_password").val();
      var confirm_password = $("#confirm_password").val();
        $.ajax({
          url: BASE_URL+"change_password",
          data: { 'old_password':old_password,'new_password':new_password,'confirm_password':confirm_password },
              type: "POST",
              beforeSend: function(xhr){
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                $('.loader').css('visibility','visible');
          },
          error:function(response){
           if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
          },
          success: function(response) {
            if(response.status == "200") {
              toastr.success(response.message);
              setTimeout(function(){
                location.reload();
              },1000);
              
            }
            else {
              toastr.error(response.message);
            }
              $('.loader').css('visibility','hidden');
            }
        });
    });

    $("#edit_mobile_number").on('click',function(){
      var mobile_number = $("#hdn_mobile_number").val();
      $("#phone_number").val(mobile_number);
      $("#phone-number-modal").modal("show");
    });

    $("#update_phone_number").on('click',function(){
        var mobile_number = $("#phone_number").val();
        $(".countdown__time").empty();
        clearInterval(interval);
        $.ajax({
          url: BASE_URL+"profile/update/mobile",
          data: { 'mobile_number':mobile_number },
              type: "POST",
              beforeSend: function(xhr){
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                $('.loader').css('visibility','visible');
          },
          error:function(response){
           if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
          },
          success: function(response) {
            if(response.status == "200") {
              $("#phone_num").val(mobile_number);
              $("#verify-otp-modal").modal("show");
              countdown_timer();
            }
            else {
              toastr.error(response.message);
            }
              $('.loader').css('visibility','hidden');
            }
        });
    });

    $('#verify_mobile_otp').click(function(e){
            var otp = $("#otpcode1").val()+""+$("#otpcode2").val()+""+$("#otpcode3").val()+""+$("#otpcode4").val()+""+$("#otpcode5").val()+""+$("#otpcode6").val();
            var mobile_number = $('#phone_num').val();
            if(otp ==''){
                toastr.error("Please Enter OTP");
                $('#verify-otp-modal').modal({
                   backdrop: 'static',
                   keyboard: false
                });
                return false;
            }
            $.ajax({
                url: BASE_URL+"profile/verify/mobile",
                data: { 'mobile_number' : mobile_number,'otp':otp},
                type: "POST",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  //console.log(response);
                  if(response.status == "200") {
                    toastr.success(response.message);
                    setTimeout(function(){
                      location.reload();
                    },1000);
                  } 
                  else 
                  {
                    toastr.error(response.message);
                    $("#otpcode1").val('');
                    $("#otpcode2").val('')
                    $("#otpcode3").val('')
                    $("#otpcode4").val('')
                    $("#otpcode5").val('')
                    $("#otpcode6").val('')
                  }
                  $('.loader').css('visibility','hidden');
                }
            });
    });

    $('#resend_mobile_otp').click(function(){
          var mobile_number = $("#phone_num").val();
          var user_id = $("#user_id").val();  

          $.ajax({
            url: BASE_URL+"profile/resend_mobile_otp",
            data: { 'mobile_number' : mobile_number },
            type: "POST",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              xhr.setRequestHeader('Authorization', 'Bearer '+token);
              $('.loader').css('visibility','visible');
            },
            error:function(response){
             if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
            },
            success: function(response) {
              if(response.status == "200") {
                countdown_timer();
                $(".resend").addClass('d-none');
                toastr.success(response.message);
              } else {
                toastr.error(response.message);
              }
               $('.loader').css('visibility','hidden');
            }
          });
    });

    $("#edit_email").on('click',function(){
      var email = $("#Email").text();
      $("#email_id").val(email);
      $("#change-email").modal("show");
    });

    $("#update_email_id").on('click',function(){
      var email = $("#email_id").val();
     // $(".countdown__time").empty();
     // clearInterval(interval);
      $.ajax({
                url: BASE_URL+"profile/update/email",
                data: { 'email' : email},
                type: "POST",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  //console.log(response);
                  if(response.status == "200") {
                    $("#updated_email_id").val(email);
                    countdown_timer();
                    $("#verify-email-otp-modal").modal("show");
                  } 
                  else 
                  {
                    toastr.error(response.message);
                  }
                  $('.loader').css('visibility','hidden');
                }
      });
    });

    $is_verify_request = 0;
    $(document).on('click','#verify_btn',function(){
      var email = $.trim($("#Email").text());
      $is_verify_request = 1;
     // $(".countdown__time").empty();
      //clearInterval(interval);
      $.ajax({
                url: BASE_URL+"profile/update/email",
                data: { 'email' : email},
                type: "POST",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
                  $("#verify_btn").attr('disabled',true);
                  $("#verify_btn").text('Verifying..');
                },
                success: function(response) {
                  //console.log(response);
                  if(response.status == "200") {
                    $("#verify-email-otp-modal").modal("show");
                    $("#updated_email_id").val(email);
                    countdown_timer();
                  } 
                  else 
                  {
                    toastr.error(response.message);
                  }
                  $("#verify_btn").attr('disabled',false);
                  $('.loader').css('visibility','hidden');
                }
      });
    })
    
    $('#verify_email_otp').click(function(e){
            var otp = $("#otpcode_1").val()+""+$("#otpcode_2").val()+""+$("#otpcode_3").val()+""+$("#otpcode_4").val()+""+$("#otpcode_5").val()+""+$("#otpcode_6").val();
            var email = $('#updated_email_id').val();
            if(otp ==''){
                toastr.error("Please Enter OTP");
                $('#verify-otp-modal').modal({
                   backdrop: 'static',
                   keyboard: false
                });
                return false;
            }
            $.ajax({
                url: BASE_URL+"profile/verify/email",
                data: { 'email' : email,'otp':otp},
                type: "POST",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  //console.log(response);
                  if(response.status == "200") {
                    if($is_verify_request == '1'){
                      toastr.success('Email verified successfully');
                    }else {
                      toastr.success(response.message);  
                    }
                    setTimeout(function(){
                      location.reload();
                    },1000);
                  } 
                  else 
                  {
                    toastr.error(response.message);
                    $("#otpcode_1").val('');
                    $("#otpcode_2").val('')
                    $("#otpcode_3").val('')
                    $("#otpcode_4").val('')
                    $("#otpcode_5").val('')
                    $("#otpcode_6").val('')
                  }
                  $('.loader').css('visibility','hidden');
                }
            });
    });

    $('#resend_email_otp').click(function(){
          var email = $("#updated_email_id").val();
          var user_id = $("#user_id").val();  
         // $(".countdown__time").empty();
          //clearInterval(interval);
          $.ajax({
            url: BASE_URL+"profile/resend_email_otp",
            data: { 'email' : email },
            type: "POST",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              xhr.setRequestHeader('Authorization', 'Bearer '+token);
              $('.loader').css('visibility','visible');
            },
            error:function(response){
             if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
            },
            success: function(response) {
              if(response.status == "200") {
                toastr.success(response.message);
                $(".resend").addClass('d-none');
                countdown_timer();
              } else {
                toastr.error(response.message);
              }
               $('.loader').css('visibility','hidden');
            }
          });
    });


  @endif
  @if(\Request::route()->getName() == 'customer.profile' || \Request::route()->getName() == 'books_checkout')

    function get_user_addresses(){
       $.ajax({
            url: BASE_URL+"addresses",
            data: {  },
            type: "GET",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                $('.loader').css('visibility','visible');
            },
            error:function(response){
               if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
            },
            success: function(response) {

              if(response.status == "200") {
                  var html = '';
                  var is_delivery_address;
                  console.log(response);
                  if(response.data.length > 0) {
                  $.each(response.data,function(){
                    if(this.address_type == 'Home'){
                      var icon = `<i class="icon-home"></i>`;
                    }else if(this.address_type == 'Office'){
                      var icon = `<i class="fas fa-building"></i>`;
                    }else if(this.address_type == 'Other'){
                      var icon = `<i class="fas fa-map-pin"></i>`;
                    }
                    var contact_name =  this.contact_name.charAt(0).toUpperCase() +""+this.contact_name.slice(1);
                    if(this.is_delivery_address == "yes") { is_delivery_address ="checked";
                    html += ` <div class="form-group white-bg"> 
                             <label class="radio-box">
                            <input type="radio" name="is_delivery_address" `+is_delivery_address+` disabled><span class="checkmark"></span>
                                       <h5>`+icon+` `+this.address_type+`</h5><a href="javascript:void(0)" title="" class="theme-color fa-lg d-inline align-middle edit_address" id="`+this.id+`"><i class="icon-edit-address"></i></a> <div class="row">
                                     <!-- <div class="col-xl-4 col-lg-4 col-md-9 col-9"></div>
                                       <div class="col-xl-4 col-lg-4 col-md-3 col-3"> <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#delete-confirm" class="delete" id="`+this.id+`"><i class="fa fa-trash" ></i></a></div>-->
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                          <label class="secondary-color">Full Name</label>
                                          <p>`+this.contact_name+`</p> </div> <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                          <label class="secondary-color">Company Name</label><p>`+this.company_name+`</p>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                          <label class="secondary-color">Phone Number</label>
                                          <p>+91 `+this.contact_number+`</p>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                                          <label class="secondary-color">Email Address</label>
                                          <p>`+this.email+`</p>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                                          <label class="secondary-color">Address</label>
                                          <p>`+this.house_no+`, `+this.street+`, `+this.landmark+`, `+this.area+`, `+this.city_name+`, `+this.state_name+` - `+this.postcode+`</p>
                                        </div>
                                      </div>
                                    </label>`;
                                }
                  });
              	 /*
                    else { is_delivery_address = ''; }
                    */
                  html+=`<div class="text-center"><a class="theme-color text-decoration-underline saved_address"  href="javascript:void(0)">Select From Saved Address</a></div> </div>`;

                    /*if(html == "") {
                      html = `<div class="col-12">No Saved addresses found</div> <a href="javascript:void(0)" class="theme-color text-decoration-underline add_new_address" title="">Add New</a>`;
                  	}*/
                    $(".saved_address").removeClass('d-none');  
                    $("#address_list").append(html);
                	}else {
                		   html = `<div class="text-left"><div class="col-12">No Saved addresses found</div> <a href="javascript:void(0)" class="theme-color text-decoration-underline add_new_address" title="">Add New</a></div>`;
                		 $(".saved_address").addClass('d-none');  
                   		 $("#address_list").append(html);
                	}
              } 
              else {
                toastr.error(response.message);
              }
              $('.loader').css('visibility','hidden');
            }
       });
    }
  
   /* setTimeout(function() {
      @if(\Request::input('add_address') == 'true')
        $(".add_new_address").trigger('click');
      @endif
    },500);*/
    var checkout_address_type = $("#type").val();
    $(document).on('click','.add_new_address',function(){
      $("#address_title").text('Add Address');
      $('#saveAddressForm input').not(':submit').not(':radio').not(':checkbox').val('');
      $('#saveAddressForm select').val('');
      get_state_list();
      get_profile_data();
    
      $("#save-address").modal("hide");
      $("#add-address").modal("show");
    });

    function get_profile_data(){
      $.ajax({
            url: BASE_URL+"profile",
            data: { },
                type: "GET",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
            },
            error:function(response){
             if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
            },
            success: function(response) {
              if(response.status == "200") {
                $("#contact_name").val(response.data.first_name);
                $("input[name=company_name]").val(response.data.company_name);
                $("#contact_number").val(response.data.mobile_number);
                $("#email").val(response.data.email);
              }
            }
          });
    }

    function get_state_list(state_id='')
    {
        $.ajax({
                     url: BASE_URL+"states",
                     data: { },
                     type: "GET",
                     beforeSend: function(xhr){
                          xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      },
                      success: function(response) {
                        if(response.status == "200") {
                          $("#state_id").empty();
                          $("#state_id").append('<option value="">Select</option>');
                          $.each(response.data,function(){
                            if(this.id==state_id){
                                 $("#state_id").append('<option value="'+this.id+'" selected>'+this.name+'</option>');
                              }
                              else{
                                $("#state_id").append('<option value="'+this.id+'">'+this.name+'</option>');
                              }
                          });
                          }
                         else {
                         toastr.error(response.message);
                        }
                      }
        });
    }
    function get_city_list(state_id,city_id="")
    {
          $.ajax({
                     url: BASE_URL+"cities/"+state_id,
                     data: { },
                     type: "GET",
                     beforeSend: function(xhr){
                          xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                           $('.loader').css('visibility','visible');

                      },
                     success: function(response) {
                        if(response.status == "200") {
                           $("#city_id").empty();
                           $("#city_id").append('<option value="">Select</option>');

                            $.each(response.data,function(){
                              if(this.id==city_id){
                                 $("#city_id").append('<option value="'+this.id+'" selected>'+this.name+'</option>');
                              }
                              else{
                                  $("#city_id").append('<option value="'+this.id+'">'+this.name+'</option>');
                              }
                            });
                          /*  $("#city_id").append('<option value="add_new">Other City</option>');*/
                          }
                        else {
                         toastr.error(response.message);
                        }
                         $('.loader').css('visibility','hidden');
                     }
          });
    }

    function get_postcode_list(city_id,postcode_id)
    {
        $.ajax({
                     url: BASE_URL+"postcodes/"+city_id,
                     data: { },
                     type: "GET",
                     beforeSend: function(xhr){
                          xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                           $('.loader').css('visibility','visible');

                      },
                     success: function(response) {
                        if(response.status == "200") {
                            $("#postcode_id").empty();
                            $("#postcode_id").append('<option value="">Select</option>');
                            $.each(response.data,function(){
                              if(this.id==postcode_id){
                                 $("#postcode_id").append('<option value="'+this.id+'" selected>'+this.postcode+'</option>');
                              }else{
                                  $("#postcode_id").append('<option value="'+this.id+'">'+this.postcode+'</option>');
                              }
                            });
                           /* $("#postcode_id").append('<option value="add_new">Other Postcode</option>');*/
                          }
                          else {
                            toastr.error(response.message);
                          }
                          $('.loader').css('visibility','hidden');
                     }
        });
    }

    $('#state_id').change(function(){
              var state_id = $(this).val();
              $("#city_id").empty();
              $("#postcode_id").empty();
              if(state_id){
                  get_city_list(state_id);
              }
    });

    $('#city_id').change(function()
    {
        var city_id = $(this).val();
        $("#postcode_id").empty();
        if(city_id){
          get_postcode_list(city_id);
        }
    });

    /* SAVE ADDRESS FORM */
    $('#saveAddressForm').on('submit', function (e) {
              var address_type = $('input[name=options]:checked').val();
              var checkout_address_type = $("#type").val();
              var flag;
              var is_delivery_address = $('input[name=is_delivery_address]:checked').val();
              
              if(is_delivery_address=="on") { flag="yes"; } else { flag="no"; }
              if($('input[name=options]').is(':checked') == false) { 
                toastr.error("Please select Save As"); 
                return false; 
              }
              e.preventDefault();
              var data = $('#saveAddressForm').serialize();
              data += '&address_type='+address_type+"&is_delivery_address="+flag;
              var url_type = 'add';

              if($('#address_id').val() != ''){
                url_type = 'update';
              }
              $.ajax({
                       url: BASE_URL+'address/'+url_type,
                       data: data,
                       type: "POST",
                       beforeSend: function(xhr){
                          xhr.setRequestHeader('Authorization', 'Bearer '+token);
                          xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                          $('.loader').css('visibility','visible');
                          $("#save_address_btn").prop('disabled',true);
                        },
                        error:function(response){
                         if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                        },
                        success: function(response) {
                          if(response.status == "200") {
                            toastr.success(response.message);
                               get_user_addresses(token);
                                if(checkout_address_type != ''){
                                  if(checkout_address_type == 'shipping'){
                                    set_shipping_address(response.data[0]);
                                  }else {
                                    set_billing_address(response.data[0]);
                                  }
                                  $("#add-address").modal("hide");
                                }else {
                                 setTimeout(function(){
                                      location.reload();
                                  }, 1500);
                                }
                              $('#saveAddressForm input').not(':submit').not(':radio').not(':checkbox').val('');
                              $('#saveAddressForm select').val('');
                          } else {
                            toastr.error(response.message);
                            e.preventDefault();
                          }
                          $("#save_address_btn").prop('disabled',false);
                          $('.loader').css('visibility','hidden');
                       }
              });
    })


    /* Edit Addresss */
    $(document).on('click','.edit_address,.edit_shipping_address,.edit_billing_address',function() {
                    $("#address_title").text('Edit Address');
                    $('#save_address_form').show();
                    var response =  edit_address_info($(this).attr('id'));
                    console.log(response);
                     if(response.status == "200") {
                                  $('#address_id').val(response.data.id);
                                  $("#contact_name").val(response.data.contact_name);
                                  $("#contact_number").val(response.data.contact_number);
                                  $("#email").val(response.data.email);
                                  $("input[name='company_name']").val(response.data.company_name);
                                  $('#house_no').val(response.data.house_no);
                                  $('#street').val(response.data.street);
                                  $('#area').val(response.data.area);
                                  $("#landmark").val(response.data.landmark);
                                  //$('#state_id').val(response.data.state_id);
                                  //get_state_list(response.data.state_name);
                                  get_state_list(response.data.state_id);
                                  get_city_list(response.data.state_id,response.data.city_id)
                                  get_postcode_list(response.data.city_id,response.data.postcode_id);
                                  if(response.data.address_type == "Home"){
                                    $('input[name=options][value=Home]').prop('checked',true);
                                  }
                                  else if(response.data.address_type == "Office"){
                                    $('input[name=options][value=Office]').prop('checked',true);
                                  }else{
                                    $('input[name=options][value=Other]').prop('checked',true);
                                  }
                                  if(response.data.is_delivery_address == "yes"){
                                      $('input[name=is_delivery_address]').prop('checked',true);
                                  }
                                  else
                                  {
                                    $('input[name=is_delivery_address]').removeAttr('checked');
                                  }

                                  $("#add-address").modal('show');
                              } else {
                                  toastr.error(response.message);
                              }
    });

    function edit_address_info(address_id)
    {
    	  var result = '';
    	  $.ajax({
                    url: BASE_URL+"address/edit",
                    data: {'address_id': address_id},
                           type: "POST",
                           async:false,
                           beforeSend: function(xhr){
                              xhr.setRequestHeader('Authorization', 'Bearer '+token);
                              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                            },
                            error:function(response){
                             if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                            },
                           success: function(response) {
                            // console.log(response);
                            result = response;
                           }
                    });
    	  return result;
    }	


    $(document).on("click",".saved_address",function(){
       $.ajax({
            url: BASE_URL+"addresses",
            data: {  },
            type: "GET",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                $('.loader').css('visibility','visible');
            },
            error:function(response){
             if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
            },
            success: function(response) {
              if(response.status == "200") {
                  var html = '';
                  var is_delivery_address;
                  $.each(response.data,function(){
                    var contact_name =  this.contact_name.charAt(0).toUpperCase() +""+this.contact_name.slice(1);
                     if(this.address_type == 'Home'){
                      var icon = `<i class="icon-home"></i>`;
                    }else if(this.address_type == 'Office'){
                      var icon = `<i class="fas fa-building"></i>`;
                    }else if(this.address_type == 'Other'){
                      var icon = `<i class="fas fa-map-pin"></i>`;
                    }
                    if(this.is_delivery_address == "yes") 
                    	{ is_delivery_address ="checked";  }
                    else { is_delivery_address = ''; }
                    html += ` <div class="form-group white-bg"> <label class="radio-box">
                            <input type="radio" name="sendoption" id="`+this.id+`" `+is_delivery_address+` ><span class="checkmark"></span>
                                      <h5>`+icon+` `+this.address_type+`</h5> <a href="javascript:void(0)" title="" class="theme-color fa-lg d-inline align-middle edit_address" id="`+this.id+`"><i class="icon-edit-address"></i></a> <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#delete-confirm" class="theme-color delete" id="`+this.id+`"><i class="fa fa-trash" ></i></a>                                      <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                          <label class="secondary-color">Full Name</label>
                                          <p>`+this.contact_name+`</p> </div> <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                          <label class="secondary-color">Company Name</label><p>`+this.company_name+`</p>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                          <label class="secondary-color">Phone Number</label>
                                          <p>+91 `+this.contact_number+`</p>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                                          <label class="secondary-color">Email Address</label>
                                          <p>`+this.email+`</p>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                                          <label class="secondary-color">Address</label>
                                          <p>`+this.house_no+`, `+this.street+`, `+this.landmark+`, `+this.area+`, `+this.city_name+`, `+this.state_name+` - `+this.postcode+`</p>
                                        </div>
                                      </div>
                                    </label></div>`;
                  });
                  $("#addresses_list").html(html);
              } 
              else {
                toastr.error(response.message);
              }
              $('.loader').css('visibility','hidden');
            }
       });
       $("#save-address").modal("show");
    });
  @endif
  /*############## PROFILE - SCRIPT ENDS ###################*/

  /* ################# DIGITAL COUPONS :: SCRIPT STARTS ################# */
  @if(\Request::route()->getName() == 'digital_coupons_list')
        var order_status  = [];
        $("input[name=order_status]:checked").each(function(i){
        order_status[i] =  this.value;
    	});	
        var order_type = [];
        var last_page   = '';
        $("input[name=order_type]:checked").each(function(i){
            order_type[i] = this.value; 
        });
        var page = 1; //track user scroll as page number, right now page number is 1
        if(order_type !='undefined' || order_status !='undefined') {
          load_more(page,order_type,order_status); //initial content load
        }else {
          load_more(page); //initial content load
        }
        $("#digital_coupons_list").html('');
        $(window).scroll(function() { //detect page scroll
            if($(window).scrollTop() + $(window).height() >= $(document).height() - 250) { //if user scrolled from top to bottom of the page
                if(page < last_page)
                {
                  page++; //page number increment
              	  load_more(page); //initial content load
                }
            }
        });
       
        function load_more(page,order_status,order_type)
        {
          $.ajax({
            url: BASE_URL+"my_digital_coupons?page="+page,
            data: {'type[]':order_type , 'status[]':order_status},
            type: "post",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
            },
            error:function(response){
               if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
            },
            success: function(response) {
                if(response.status == "200") {
                var html ='';
                $.each(response.data,function(key,value){
                    if(this.order_status == 'Completed') {
                      var show_page = "{{ route('digital_coupon_detail_expired',':id') }}";
                          show_page = show_page.replace(':id',this.id);
                    }else {
                      var show_page = "{{ route('digital_coupon_detail_purchased',':id') }}";
                          show_page = show_page.replace(':id',this.id);
                    }
                    html += `<div class="col-xl-6 col-lg-6 col-md-12 col-12"> 
                              <a href="`+show_page+`" title="">
                                <div class="order-list">
                                  <div class="head">
                                    <p><label>Purchased ID:</label>`+this.id+`</p>
                                    <div><span class="theme-color">`+this.order_status+`</span></div>
                                  </div>
                                  <div class="details"> 
                                    <ul>
                                      <li><label>Purchased Date</label>`+this.order_date+`</li>
                                      <li><label>Coupon Amount</label>₹ `+this.order_total  +`</li>
                                      <li><label>Purchased Time</label>`+this.order_time+`</li>
                                    </ul>
                                  </div>
                                </div>
                              </a>
                            </div>`;
                  })
                 if(order_type!='undefined' || order_status!='undefined'){
                  $('#digital_coupons_list').html(html);
                 }
                 else { 
                 $('#digital_coupons_list').append(html); }
                 
                 $('#digital_coupons_list').removeClass('d-none'); 	
                 $(".no-data-found").addClass('d-none');
                // $("#coupon_filter").removeClass('d-none');
                } 
                else {
                //$('#digital_coupons_list').html('<span>No Data Found</span>');
                $('#digital_coupons_list').addClass('d-none'); 	
                //$("#coupon_filter").addClass('d-none'); 	
                $(".no-data-found").removeClass('d-none');
               // toastr.error(response.message);
                }
            }
          });
        }
       
        $("#apply_filter_btn").click(function(){
            var order_status  = [];
	        $("input[name=order_status]:checked").each(function(j){
	        	order_status[j] =  this.value;
	    	});
          	var order_type = [];
          	$("input[name=order_type]:checked").each(function(i){
            	order_type[i] = this.value; 
          	});
          	if(order_type !='undefined' || order_status !='undefined') {
            	load_more('1',order_status,order_type);
            	$("#filter-modal").modal("hide");
         	}
        });

    	$(document).on('click','#clear_all',function(){
        $('input[name="order_type"]:checked').removeAttr('checked');
        $('input[name="order_status"]:checked').removeAttr('checked');
    		$('input[name="sendoption"]:checked').removeAttr('checked');
    		 load_more('1');
    		$("#filter-modal").modal("hide");
	    });


  @endif

  @if(\Request::route()->getName() == 'digital_coupon_detail_purchased' || \Request::route()->getName() == 'digital_coupon_detail_expired')
      var order_id = $("#hdn_order_id").val();
      $.ajax({
            url: BASE_URL+"my_digital_coupon_details/"+order_id,
            data: {},
            type: "get",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
            },
            error:function(response){
             if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
            },
            success: function(response) {
                 console.log(response);
                if(response.status == "200") {
                  var html ='';
                  var active_coupon_id = '';
                  $("#order_id").text(response.data.order_id);
                  $("#status").text(response.data.order_status);
                  $("#order_date").text(response.data.order_date);
                  $("#order_time").text(response.data.order_time);
                  $("#amount").text(response.data.order_total);
                  $.each(response.data.coupon_items,function(index,value){
                  	/*var active_class =*/ 
                      if(index == 0) { 
                        var active_class = 'active'; 
                        active_coupon_id = this.coupon_id;
                      }
                    else { var active_class = ''; }
                   	@if(\Request::route()->getName() == 'digital_coupon_detail_purchased') 
                  	
                 
                  	html += `<a href="javascript:void(0)" class="coupon_item" id="`+this.coupon_id+`"><div class="product-list-box `+active_class+`" id="coupon_`+this.coupon_id+`"> 
       						 <div class="img"><img src="`+this.cover_image+`" alt=""></div><div class="detail">
                              <h6>`+this.name+`</h6> <p class="secondary-color mb-0">`+this.description+`</p>
                              <div class="sale-price">₹ `+this.sale_price+`</div>
                              <div class="price-qty"><div class="type">Type: <span>`+this.type+`</span></div>   <div class="qty-items">Qty. `+this.quantity+`</div>
                              </div><div class="date">Expiry Date: `+this.expiry_date+`</div></div></div></a>`;
                    @else
                  	html += `<a href="javascript:void(0)" class="coupon_item" id="`+this.coupon_id+`"><div class="product-list-box" `+active_class+`" id="coupon_`+this.coupon_id+`">
       						 <div class="img"><img src="`+this.cover_image+`" alt=""></div><div class="detail">
                              <h6>`+this.name+`</h6> <p class="secondary-color mb-0">`+this.description+`</p>
                              <div class="sale-price">₹ `+this.sale_price+`</div>
                              <div class="price-qty"><div class="type">Type: <span>`+this.type+`</span></div>   <div class="qty-items">Qty. `+this.quantity+`</div>
                              </div><div class="date">Expiry Date: `+this.expiry_date+`</div></div></div></a>`;
                  	@endif
               /*      coupon_item_id = this.coupon_id;
                     available_coupons[coupon_item_id] = [];
                     $.each(this.available_coupons,function(i){
                      available_coupons[coupon_item_id] = [{qr_id:this.qr_id,qr_image:this.qr_image,qr_code_value:this.qr_code_value}];
                    });
               */ 
                  });
                  get_coupon_codes(response.data.coupon_items,active_coupon_id);
                  $("#coupon_items").append(html);
                } 
                else {
                  toastr.error(response.message);
                }
            }
      });

      $(document).on('click','.coupon_item',function(){
      	  var coupon_id = $(this).attr('id');

      	  $.ajax({
            url: BASE_URL+"my_digital_coupon_details/"+order_id,
            data: {},
            type: "get",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
            },
            error:function(response){
             if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
            },
            success: function(response) {
                 console.log(response);
                if(response.status == "200") {
                  get_coupon_codes(response.data.coupon_items,coupon_id);
                  $(".product-list-box").removeClass('active');
                  $("#coupon_"+coupon_id).addClass('active');

                } 
                else {
                  toastr.error(response.message);
                }
            }
      });
      });

      function get_coupon_codes(coupon_items,active_coupon_id){
      	var available_coupons = '';
        var sold_coupons = '';
        var available_count = '0';
        var sold_count = '0';
                 
      	$.each(coupon_items,function(index){
      		if(this.coupon_id == active_coupon_id){
      				available_count = this.available_coupons.length;
                    $("#available_count").text(available_count);
                    console.log(this.available_coupons);
      	
                    $.each(this.available_coupons,function(){
                      available_coupons += `<li>
                                            <div class="box">
                                              <a href="javascript:void(0)" class="btn code qr_code" id="`+this.qr_id+`" name="`+this.qr_code_value+`" title="`+this.qr_image+`">`+this.qr_code_value+`<img src="`+this.qr_image+`"></a>
                                              <a href="javascript:void(0)"  class="btn primary-btn sale_coupon" id="`+this.qr_id+`">Sale Coupon</a>
                                            </div>
                                          </li>`;
                    });
                    if(available_coupons == '') {
                      available_coupons = `<li class="redeemed">No Available Coupons Found</li>`;
                    }
                    $("#available_coupons_list").html(available_coupons);
                    sold_count = this.sold_coupons.length;
                    $("#sold_count").text(sold_count);
                    $.each(this.sold_coupons,function(){
                      if(this.state == 'redeemed'){
                        var link = ` <div class="btn redeemed">Redeemed</div>`
                      }else {
                        var link = `<a href="javascript:void(0);" class="btn primary-btn share_coupon_code" data-code= "`+this.qr_code_value+`">Share <i class="icon-share ms-2"></i></a>`;
                      }
                      sold_coupons +=`  <li>
                                        <div class="info">
                                          <div class="list">
                                            <div class="secondary-color">Full Name</div>
                                            <p>`+this.customer_name+`</p>                
                                          </div>
                                          <div class="list">
                                            <div class="secondary-color">Mobile Number</div>
                                            <p>+91 `+this.customer_contact+`</p>                
                                          </div>
                                          <div class="list">
                                            <div class="secondary-color">Price</div>
                                            <p>₹ `+this.sale_price+`</p>                
                                          </div>
                                        </div>
                                        <div class="box">
                                          <a href="javascript:void(0)" class="btn code">`+this.qr_code_value+` <img src="`+this.qr_image+`"></a>
                                        `+link+`</div> </li> `;
                    });
                    if(sold_coupons == '') {
                      sold_coupons = `<li class="redeemed">No Sold Coupons Found</li>`;
                    }
                    $("#sold_coupons_list").html(sold_coupons);
           }
      	});
      }

      $(document).on('click','.share_coupon_code',function(){
        var code =  $(this).data('code');
        $("#share-popup").modal("show");
        $("#refer_earn_facebook").attr('href','http://www.facebook.com/share.php?u=http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/&text='+code);
        $("#refer_earn_wtapp").attr('href','https://wa.me/?text='+code);
        $("#refer_earn_twitter").attr('href','https://twitter.com/intent/tweet?text='+code);
        $("#refer_earn_instagram").attr('href','https://www.instagram.com/?url=http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/');
      })

      $(document).on('click','.qr_code',function(){
        var qr_id = $(this).attr('id');
        var qr_image = $(this).attr('title');
        var qr_code_value = $(this).attr('name');
         $("#qr_code").text(qr_code_value);
        $("#qr_image").attr('src',qr_image);
        $('.sale_coupon').attr('id',qr_id);
        $("#qrcode-modal").modal("show");
      })
  
      $(document).on('click','.sale_coupon',function(){
        var qr_id = $(this).attr('id');
        $("#qr_id").val(qr_id);
        $("#qrcode-modal").modal("hide");
        $("#add-customer-modal").modal("show");
      })  

      $("#saleCouponForm").on('submit',function(e){
           e.preventDefault();
           var formData = new FormData($(this)[0]);
          // formData = formData.replaceAll("<\/script>", "");
           $.ajax({
                   url: BASE_URL+"sale_coupon",
                   data: formData,
                   processData:false,
                   contentType: false,
                   type: "POST",
                   cache:false,
                   // async:false,
                   beforeSend: function(xhr){
                        xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                        xhr.setRequestHeader('Authorization', 'Bearer '+token);
                         $('.loader').css('visibility','visible');
                    },
                    error:function(response){
                     if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                    },
                   success: function(response) {
                      if(response.status == "200") {
                        toastr.success(response.message);
                        setTimeout(function(){
                          location.reload();
                        },1000);
                      } else {
                       toastr.error(response.message);
                      }
                       $('.loader').css('visibility','hidden');
                   }
           });
      })
  @endif
  /* ################# DIGITAL COUPONS :: SCRIPT ENDS ################# */

  /* ################# REFER EARN :: SCRIPT STARTS ################# */
  @if(\Request::route()->getName() == 'refer_earn')
      
      var type = $("ul#myTab li a.active").attr('title');
      get_earn_accounts(type);

      $.ajax({
        url: BASE_URL+"refer_and_earn",
        data: { },
            type: "GET",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              xhr.setRequestHeader('Authorization', 'Bearer '+token);
              $('.loader').css('visibility','visible');
        },
        error:function(response){
         if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
        },
        success: function(response) {
           //console.log(response);
           if(response.status == "200") {
           // toastr.success(response.message);
            $("#refer_code").text(response.data.referral_code);
            var content = `Invite Your Friends! The referral will get 1000 points when the referred user does his first purchase via Ghatna Chakra App. Use this Code: `+response.data.referral_code;
            $("#refer_earn_facebook").attr('href','http://www.facebook.com/share.php?u=http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/&title='+response.data.referral_code+"&text="+content);
            $("#refer_earn_wtapp").attr('href','https://wa.me/?text='+content);
            $("#refer_earn_twitter").attr('href','https://twitter.com/intent/tweet?text='+content);
            $("#refer_earn_instagram").attr('href','https://www.instagram.com/?url=http://cloud1.kodyinfotech.com:7000/ssgc-bulk-order-web/public/');
            $("#title").text(response.data.title);
            $("#content").html(response.data.content);
           }
           else {
            toastr.error(response.message);
           }
            $('.loader').css('visibility','hidden');
          }
      });
     
      function get_earn_accounts(type) {
        $.ajax({
                   url: BASE_URL+"profile/earn_accounts",
                   data: { 'type':type},
                   type: "POST",
                   beforeSend: function(xhr){
                        xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                        xhr.setRequestHeader('Authorization', 'Bearer '+token);
                         $('.loader').css('visibility','visible');
                    },
                    error:function(response){
                     if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                    },
                   success: function(response) {
                    console.log(response);
                      if(response.status == "200") {
                        var html_history = '';
                        var html_earn = '';
                        var html_redeem = '';
                        var style_class = '';
                        if(type == 'history'){
                          var balance_points = response.data.balance_points;
                          html_history = ` <div class="balance-point">Balance Points:`+balance_points+`</div>`;
                          if(response.data.data.length == 0)
                          {
                            $("#no_histpry_data").removeClass('d-none');
                          }
                          else
                          {
                            $("#no_histpry_data").addClass('d-none');
                          }
                          $.each(response.data.data, function(){
                              if(this.type == "Welcome Points" || this.type == "Wishlist Points" || this.type == "Wish Return Points" || this.type == "Refunded Points") {
                              style_class = 'green-color';

                              html_history += `<div class="list">
                                <div class="box">
                                  <div class="detail"> <h6>`+this.type+`</h6>
                                  <p>`+this.date+`</p> </div></div>
                                <div class="point"><h4 class="`+style_class+`">`+this.points+` Points</h4></div>
                              </div>`;

                              }else if(this.type == "Earned Points"){
                                style_class = 'green-color';
                                 html_history += `<div class="list">
                                <div class="box"><div class="img"><img src="`+this.customer_image+`" alt=""></div>
                                  <div class="detail"> <h6>`+this.customer_name+`</h6>
                                  <p>`+this.date+`</p> </div></div>
                                <div class="point"><h4 class="green-color">`+this.points+` Points</h4></div>
                              </div>`;
                              }else if(this.type == "Redeem Points"){
                                style_class = 'red-color';
                                html_history += ` <div class="list">
                                              <div class="box">
                                                <div class="img"><img src="{{asset('web_assets/images/earning-point.svg')}}" alt=""></div>
                                                <div class="detail">
                                                  <h6>Purchased ID: <span>`+this.order_id+`</span></h6>
                                                  <p>`+this.date+`</p>
                                                </div>                  
                                              </div>
                                              <div class="point"><h4 class="red-color">`+this.points+` Points</h4></div>
                                            </div>`;
                              }
                          });
                         	if(html_history !='') {
                          		$("#history_div").html(html_history);
                          		$("#no_history_data").addClass('d-none');
                      		}else {
                      			$("#no_history_data").removeClass('d-none');
                      		}
                        }
                        else if(type == 'earn'){
                           $.each(response.data.data, function(){
                             if(this.type == "Welcome Points" || this.type == "Wishlist Points" || this.type == "Wish Return Points" || this.type == "Refunded Points") {
                              style_class = 'green-color';

                              html_earn += `<div class="list">
                                <div class="box">
                                  <div class="detail"> <h6>`+this.type+`</h6>
                                  <p>`+this.date+`</p> </div></div>
                                <div class="point"><h4 class="`+style_class+`">`+this.points+` Points</h4></div>
                              </div>`;

                              }else if(this.type == "Earned Points"){
                              html_earn += `<div class="list">
                                <div class="box"><div class="img"><img src="`+this.customer_image+`" alt=""></div>
                                  <div class="detail"> <h6>`+this.customer_name+`</h6>
                                  <p>`+this.date+`</p> </div></div>
                                <div class="point"><h4 class="green-color">`+this.points+` Points</h4></div>
                              </div>`;
                            }
                          });
                           	if(html_earn !='') {
                          		$("#earn_div").html(html_earn);
                          		$("#no_earn_data").addClass('d-none');
                      		}else {
                      			$("#no_earn_data").removeClass('d-none');
                      		}
                        }
                          else if(type == 'redeem'){
                           $.each(response.data.data, function(){
                              html_redeem += ` <div class="list">
                                              <div class="box">
                                                <div class="img"><img src="{{asset('web_assets/images/earning-point.svg')}}" alt=""></div>
                                                <div class="detail">
                                                  <h6>Purchased ID: <span>`+this.order_id+`</span></h6>
                                                  <p>`+this.date+`</p>
                                                </div>                  
                                              </div>
                                              <div class="point"><h4 class="red-color">`+this.points+` Points</h4></div>
                                            </div>`;
                          });
                           	if(html_redeem !='') {
                          		$("#redeem_div").html(html_redeem);
                          		$("#no_redeem_data").addClass('d-none');
                      		}else {
                      			$("#no_redeem_data").removeClass('d-none');
                      		}
                          
                        }
                      } else {
                       toastr.error(response.message);
                      }
                       $('.loader').css('visibility','hidden');
                   }
        });
      }

      $(document).on('click','ul#myTab li a',function(){
        var type = $(this).attr('title');
        get_earn_accounts(type);
      })

      $("#copy_code").on('click',function(){
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($("#refer_code").text()).select();
        document.execCommand("copy");
        $temp.remove();
        toastr.success("Copied to clipboard");
      });
  @endif
  /* ################# REFER EARN :: SCRIPT ENDS ################# */

  /* ################# HOME :: SCRIPT STARTS ################# */
      @if(\Request::route()->getName() == 'web_home')
        if(token !== null){
          var urlParams = new URLSearchParams(window.location.search);
          var is_first_login = urlParams.get('is_first'); //success
          if(is_first_login == '1')
          {
            $(window).load(function(){
              $('#welcome_page').modal('show');
            });
          }
        }
        //business categories
        $.ajax({
            url: BASE_URL+"business_categories",
            data: { },
            type: "GET",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              $('.loader').css('visibility','visible');

            },
            success: function(response) {
                if(response.status == "200") {
                  
                    $.each(response.data,function(key,value){
                      if(this.type == "books"){
                        var link = "{{route('books_list',':cat_id')}}";
                        link = link.replace(':cat_id',value.id);
                        $('#business_categories_div').append('<li><a href="'+link+'" title=""><div class="box"><img src="'+value.image+'" alt=""></div><p>'+value.name+'</p></a></li>');
                      }
                      else
                      {

                        // var link = "{{route('latest_digital_coupons')}}";
                        var link = "{{route('latest_digital_coupons',':cat_id')}}";
                        link = link.replace(':cat_id',value.id);
                        $('#business_categories_div').append('<li><a href="'+link+'" title=""><div class="box"><img src="'+value.image+'" alt=""></div><p>'+value.name+'</p></a></li>');
                      }
                      
                    });
                    
                    //if(token !== null || token !== '')
                    if(localStorage.getItem("user_token") !== null)
                    {
                      var link = "{{route('make_my_return')}}";
                      $('#business_categories_div').append('<li><a href="'+link+'" title=""><div class="box"><img src="{{asset("web_assets/images/cat2.svg")}}" alt=""></div><p>My Return</p></a></li>');
                    }
                  
                }
                $('.loader').css('visibility','hidden');

            }
        });

        //business categories section
        $.ajax({
            url: BASE_URL+"home/business_category_section_data",
            data: { },
            type: "GET",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Authorization', 'Bearer '+token);
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              $('.loader').css('visibility','visible');

            },
            error:function(response){
             if(response.status == '401'){
                   // auth_guard_route(response.status);
                  }
            },
            success: function(response) {
                if(response.status == "200") {
                  
                    $.each(response.data, function (key,category) {
                      if(category.type == 'books')
                      {
                        var view_all_link = "{{route('books_list',':cat_id')}}";
                        view_all_link = view_all_link.replace(':cat_id',category.business_category_id);
                        
                      }
                      else
                      {
                        // var view_all_link = "{{route('latest_digital_coupons')}}";
                        var view_all_link = "{{route('latest_digital_coupons',':cat_id')}}";
                        view_all_link = view_all_link.replace(':cat_id',category.business_category_id);

                      }
                      
                      var list_html = '';
                      $.each(category.list, function (key,value) {
                        if(key < 6)
                        {
                          if(category.type == 'books')
                          {
                            var detail_url = "{{route('book_detail',':book_id')}}";
                            detail_url = detail_url.replace(':book_id',value.product_id);
                          }
                          else
                          {
                            var detail_url = "{{route('digital_coupon_details',':coupon_id')}}";
                            detail_url = detail_url.replace(':coupon_id',value.product_id);
                          }
                          if(value.added_to_cart == '1')
                          {
                            var view_cart = "{{route('my_cart')}}";
                            var cart_btn = '<a href="'+view_cart+'" class="btn green-btn">View Cart <i class="icon-bag"></i></a>';

                            var qty_input = `<div class="qty-bulk">Qty.`+value.quantity+`</div>`;
                          }
                          else
                          {
                            if(category.type == 'books')
                            {
                              var cart_btn = '<a href="javascript:void(0);" data-book-id="'+value.product_id+'" class="btn secondary-btn add_to_cart_book">Add to Cart <i class="icon-bag"></i></a>';
                            }
                            else
                            {
                              var cart_btn = '<a href="javascript:void(0);" data-coupon-id="'+value.product_id+'" class="btn secondary-btn add_to_cart">Add to Cart <i class="icon-bag"></i></a>';
                            }

                            var qty_input = `<div class="qty-items">
                                          <input type="button" value="-" class="qty-minus">
                                          <input type="number" value="`+value.quantity+`" class="qty" min="0">
                                          <input type="button" value="+" class="qty-plus">
                                        </div>`;
                          }

                          if(category.type == 'books')
                          {
                            list_html += `<div class="col-xl-2 col-lg-2 col-md-4 col-6">
                                  <div class="book-box">
                                    <div class="img"><a href="`+detail_url+`" title=""><img src="`+value.image+`" alt=""></a></div>
                                    <div class="detail">
                                      <h6><a href="`+detail_url+`" title="">`+value.name+`</a></h6>
                                      <div class="price-qty">
                                        <div class="sale-price">₹`+value.sale_price+`<span>₹`+value.mrp+`</span></div>
                                        `+qty_input+`
                                      </div>`+cart_btn+`
                                    </div>
                                  </div>
                                </div>`;
                          }
                          else
                          {
                            list_html += `<div class="col-xl-2 col-lg-2 col-md-4 col-6">
                                <div class="book-box">
                                  <div class="img"><a href="`+detail_url+`" title=""><img src="`+value.image+`" alt=""></a></div>
                                  <div class="detail">
                                    <h6><a href="`+detail_url+`" title="">`+value.name+`</a></h6>
                                    <div class="price-qty">
                                      <div class="sale-price">₹`+value.sale_price+`<span>₹`+value.mrp+`</span></div>
                                      `+qty_input+`
                                    </div>
                                    <div class="type">Type: <span>`+value.type_label+`</span></div>
                                    <div class="date">Expiry Date: `+value.expiry_date+`</div> 
                                    `+cart_btn+`
                                  </div>
                                </div>
                              </div>`;
                          }
                        }
                        
                        
                      });
                      if(category.container.length > 0)
                      {
                        view_all_link = `<a href="`+view_all_link+`" title="">View All</a>`;
                      }
                      else
                      {
                        view_all_link = '';
                      }
                    $("#business_categories_section").append(`<section class="shop-list white-bg">
                          <div class="container">
                            <div class="section-title">
                              <h2>`+category.category_name+`</h2>
                              `+view_all_link+`        
                            </div>
                            <div class="row">`+list_html+`</div>

                            <div class="book-slider" id="book_slider_`+category.business_category_id+`">
                            </div>
                          </div>
                        </section>`);
                        var slider_list_html = '';
                        setTimeout(function(){ 
                          $("#book_slider_"+category.business_category_id).slick({
                             slidesToShow: 9,
                             infinite:false,
                             slidesToScroll: 1,
                             autoplay: true,
                             autoplaySpeed: 2000,
                             dots: true,
                             arrows: false,
                             responsive: [
                                {
                                  breakpoint: 1024,
                                  settings: {
                                    slidesToShow: 7,
                                    slidesToScroll: 1,
                                    adaptiveHeight: false,
                                  },
                                },
                                {
                                  breakpoint: 991,
                                  settings: {
                                    slidesToShow: 4,
                                    slidesToScroll: 1,
                                  },          
                                },
                                {
                                  breakpoint: 568,
                                  settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 1,
                                  },          
                                },
                              ],
                              // dots: false, Boolean
                              // arrows: false, Boolean
                          });
                          $.each(category.container, function (ckey,cvalue) {
                            if(category.type == 'books')
                            {
                              var detail_url = "{{route('book_detail',':book_id')}}";
                              detail_url = detail_url.replace(':book_id',cvalue.product_id);
                            }
                            else
                            {
                              var detail_url = "{{route('digital_coupon_details',':coupon_id')}}";
                              detail_url = detail_url.replace(':coupon_id',cvalue.product_id);
                            }
                            slider_list_html += '<div class="item"><a href="'+detail_url+'" title=""><div class="box"><img src="'+cvalue.image+'" alt="Demo"><p>'+cvalue.name+'</p></div></a></div>';
                          });

                          $("#book_slider_"+category.business_category_id).slick('slickAdd', slider_list_html);
                        }, 1000);
                     
                    });
                 
                }
                $('.loader').css('visibility','hidden');

            }
        });

        //trending books
        $.ajax({
            url: BASE_URL+"home/trending_book_list",
            data: { },
            type: "GET",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Authorization', 'Bearer '+token);
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              $('.loader').css('visibility','visible');

            },
            error:function(response){
             if(response.status == '401'){
                  //  auth_guard_route(response.status);
                  }
            },
            success: function(response) {
                if(response.status == "200") {
                    if(response.data.length == 0)
                    {
                      $('.trending-book').addClass('d-none');
                    }
                    else
                    {
                      $.each(response.data, function (key,value) {
                        if(key < 3)
                        {
                          if(value.added_to_cart == '1')
                          {
                            var view_cart = "{{route('my_cart')}}";
                            var cart_btn = '<a href="'+view_cart+'" class="btn green-btn">View Cart <i class="icon-bag"></i></a>';
                          }
                          else
                          {
                            var cart_btn = '<a href="javascript:void(0);" data-book-id="'+value.book_id+'" class="btn secondary-btn add_to_cart_book">Add to Cart <i class="icon-bag"></i></a>';
                          }
                          var book_detail_url = "{{route('book_detail',':book_id')}}";
                          book_detail_url = book_detail_url.replace(':book_id',value.book_id);
                          $('.view_all_btn').hide();
                          $("#trending_books_div").append(`<div class="col-xl-4 col-lg-4 col-md-12 col-12"> 
                              <div class="product-list-box">
                                <div class="img"><a href="`+book_detail_url+`" title=""><img src="`+value.image+`" alt=""></a></div>
                                <div class="detail">
                                  <h6><a href="`+book_detail_url+`" title="">`+value.name+`</a></h6> 
                                  <div class="sale-price">₹`+value.sale_price+`<span>₹`+value.mrp+`</span></div> 
                                  <div class="qty-items">
                                    <input type="button" value="-" class="qty-minus">
                                    <input type="number" value="`+value.quantity+`" class="qty" min="0">
                                    <input type="button" value="+" class="qty-plus">
                                  </div>`+cart_btn+`
                                </div>
                              </div>
                            </div>`);
                        }
                        else
                        {
                          $('.view_all_btn').show();
                        }
                        
                      });
                    }
                    
                }
            }
        });

        //make my return (only for logged in users)
        if(token !== null)
        {
           var active_tab = $("ul#myTab li a.active").attr('id');
           change_view_all_link(active_tab);
           $(document).on('click',"ul#myTab li a",function(){
            var active_tab = $("ul#myTab li a.active").attr('id');
            change_view_all_link(active_tab);
           });
       
           function change_view_all_link(active_tab){
              if(active_tab == 'tab1'){
                $("#my_return_view_all").attr('href','{{route("make_my_return")}}');
              }else if(active_tab == 'tab2'){
                $("#my_return_view_all").attr('href','{{route("return_products_list")}}');
              }
           }

          $.ajax({
              url: BASE_URL+"home/make_my_return",
              data: { },
              type: "GET",
              beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                $('.loader').css('visibility','visible');

              },
              error:function(response){
               if(response.status == '401'){
                   // auth_guard_route(response.status);
                  }
              },
              success: function(response) {
                  if(response.status == "200") {
                      
                      if(response.data.returnable_books.length > 0 || response.data.previous_orders.length > 0)
                      {
                        $('#make_my_return_div').removeClass('d-none');
                      }

                      if(response.data.returnable_books.length > 6)
                      {
                        $("#my_return_view_all").removeClass('d-none');
                      }
                      //returnable books
                      $.each(response.data.returnable_books,function(key,value){
                        if(key < 6)
                        {
                          if(value.added_to_cart == '1')
                          {
                            var return_cart_btn = `<a href="{{route('return_cart')}}" class="btn green-btn">View Return Cart <i class="icon-bag"></i></a>`;
                          }
                          else
                          {
                            var return_cart_btn = `<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#return-prduct" class="btn secondary-btn return_product" data-order-item-id="`+value.order_item_id+`" data-total-qty="`+value.quantity+`" data-available-return-qty="`+this.max_returnable_qty+`" data>Return Product <i class="icon-reply"></i></a>`;
                          }
                          $("#returnable_books_div").append(`<div class="col-xl-2 col-lg-2 col-md-4 col-6">
                                <div class="book-box">
                                  <div class="img"><img src="`+value.image+`" alt=""></div>
                                  <div class="detail">
                                    <h6>`+value.name+`</h6>
                                    <div class="price-qty">
                                      <div class="sale-price">₹`+value.sale_price+`<span>₹`+value.mrp+`</span></div>
                                      <div class="qty-bulk">Qty.`+value.quantity+`</div>
                                    </div>
                                    `+return_cart_btn+`
                                  </div>
                                </div>
                              </div>`)
                        }
                        
                      });

                      //previous_orders
                      $.each(response.data.previous_orders,function(key,value){
                        if(key <= 4)
                        {
                          if(value.added_to_cart == '1')
                          {
                            var return_cart_btn = `<a href="{{route('return_cart')}}" class="btn green-btn">View Return Cart <i class="icon-bag"></i></a>`;
                          }
                          else
                          {
                            var available_return_qty = parseInt((value.quantity * value.returnable_qty)/100);
                            var return_cart_btn = `<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#return-prduct" class="btn secondary-btn return_product" data-order-item-id="`+value.order_item_id+`" data-total-qty="`+value.quantity+`" data-available-return-qty="`+available_return_qty+`" data>Return Product <i class="icon-reply"></i></a>`;
                          }

                          var span_class = '';
                          var return_product_url = '';
                          if(this.status == 'in_review')
                          { 
                            span_class ='orange-color';  
                            return_product_url = "{{route('return_placed',':id')}}";
                            return_product_url = return_product_url.replace(':id',value.order_return_id);
                          }
                          else if(this.status == 'return_placed')
                          { 
                            span_class ='theme-color';  
                            return_product_url = "{{route('return_placed',':id')}}";
                            return_product_url = return_product_url.replace(':id',value.order_return_id);
                          }
                          else if(this.status == 'dispatched')
                          {  
                            span_class ='text-warning';  
                            return_product_url = "{{route('return_dispatched',':id')}}";
                            return_product_url = return_product_url.replace(':id',value.order_return_id);
                          }
                          else if(this.status == 'rejected')
                          { 
                            span_class ='theme-red-color';  
                            return_product_url = "{{route('return_rejected',':id')}}";
                            return_product_url = return_product_url.replace(':id',value.order_return_id);
                          }
                          else if(this.status == 'accepted')
                          {
                            span_class ='green-color';  
                            return_product_url = "{{route('return_accepted',':id')}}";
                            return_product_url = return_product_url.replace(':id',value.order_return_id);
                          }
                          $("#previous_orders_div").append(`<div class="col-xl-6 col-lg-6 col-md-12 col-12">
                                <a href="`+return_product_url+`" title="">
                                  <div class="order-list">
                                    <div class="head">
                                      <p><label>Order ID:</label>`+value.order_return_id+`</p>
                                      <div><span class="`+span_class+`">`+value.status_label+`</span></div>
                                    </div>
                                    <div class="details"> 
                                      <ul>
                                        <li><label>Return Date</label>`+value.return_date+`</li>
                                        <li><label>Total Return Qty</label>`+value.total_return_quantity+`</li>
                                        <li><label>Total Price</label>₹`+value.total_sale_price+`</li>
                                      </ul>
                                    </div>
                                  </div>
                                </a>
                              </div>`);
                        }
                      });
                    
                    
                  }
                  $('.loader').css('visibility','hidden');

              }
          });
          
          $(document).on('click','.return_product',function(){
            var total_qty = $(this).data('total-qty');
            var available_return_qty = parseInt($(this).data('available-return-qty'));
            var order_item_id = $(this).data('order-item-id');
            $("#return_book_price_weight").html(`<ul>
              <li><label>Total Qty</label>`+total_qty+`</li>                
              <li><label>Available Return Qty</label><p class="theme-red-color">`+available_return_qty+`</p></li>
            </ul>`);
            $("#order_item_id").val(order_item_id);
          })

          $(document).on('click','.add_to_return_cart',function(){
              auth_guard_route(token);
              var order_item_id = $("#order_item_id").val();
              var return_qty = $(".return_qty").val();

              
              $.ajax({
                url: BASE_URL+"my_return/add_to_cart",
                data: {'order_item_id':order_item_id,'quantity':return_qty},
                type: "POST",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  if(response.status == "200") {
                    $("#return-place-success").modal('show');
                    /*toastr.success(response.message);
                    setTimeout(function(){
                      location.reload();
                    },1000);*/
                  }
                  else {
                    toastr.error(response.message);
                  }
                  $('.loader').css('visibility','hidden');
                }
              });
          });

        }

        //add to cart for book
        $(document).on('click','.add_to_cart_book',function(){
              auth_guard_route(token);
              var quantity = $(this).prev().find('.qty').val();
              var book_id = $(this).data('book-id');
              $.ajax({
                url: BASE_URL+"books/add_to_cart",
                data: {'book_id':book_id,'quantity':quantity},
                type: "POST",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  console.log(response);
                  if(response.status == "200") {
                    toastr.success(response.message);
                    /*setTimeout(function(){
                      location.reload();
                    },1000);*/
                  }
                  else {
                    toastr.error(response.message);
                  }
                  $('.loader').css('visibility','hidden');
                }
              });
        });

        //add to cart for coupon
        $(document).on('click','.add_to_cart',function(){
            auth_guard_route(token);
            var quantity = $(this).parent().find('.qty').val();
            var coupon_id = $(this).data('coupon-id');
            $.ajax({
              url: BASE_URL+"coupon/add_to_cart",
              data: {'coupon_id':coupon_id,'quantity':quantity},
              type: "POST",
              beforeSend: function(xhr){
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    $('.loader').css('visibility','visible');
              },
               error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
              success: function(response) {
                if(response.status == "200") {
                  toastr.success(response.message);
                  setTimeout(function(){
                    location.reload();
                  },1000);
                }
                else {
                  toastr.error(response.message);
                }
                $('.loader').css('visibility','hidden');
              }
            });
        });
      @endif
  /* ################# HOME :: SCRIPT ENDS ################# */

  /* ################# SEARCH :: SCRIPT ENDS ################# */
    /*  $(document).on("input","#keyword",function(){
          if($("#item_type").val() == '' && $("#keyword").val().length ==1){
            toastr.error("Please select category");
            return false;
          }
          if($("#keyword").val().length > 2){
            var query = $("#keyword").val();
            var type = $("#item_type").val();
            window.location.href = "{{route('search')}}?q="+query+"&type="+type
          }
          else {
            $("#search_results").empty();
          }
      });*/
      $('#keyword').keypress(function (e) {
          var key = e.keyCode;
          if(key == 13)  // the enter key code
            {
              if($("#keyword").val() ==''){
                toastr.error("Please type some text");
                return false;
              }
              if($("#item_type").val() == '' && $("#keyword").val().length == 1){
                        toastr.error("Please select category");
                        return false;
                     }
                   if($("#keyword").val().length > 2){
                        var query = $("#keyword").val();
                    var type = $("#item_type").val();
                    window.location.href = "{{route('search')}}?q="+query+"&type="+type
                    }
                  else {
                      $("#search_results").empty();
                    }
          }
      });
      $(document).on('click','#search_btn',function(e){
        e.preventDefault();
        if($("#item_type").val() == ''){
          toastr.error("Please select category");
          return false;
        }
        if($("#keyword").val() ==''){
          toastr.error("Please type some text");
          return false;
        }
        var query = $("#keyword").val();
        var type = $("#item_type").val();
        window.location.href = "{{route('search')}}?q="+query+"&type="+type
      });
      @if(\Request::route()->getName() == 'search')

        var page        = 1;
        var last_page   = '';
        get_search_results(page); 
        $("#search_results").html("");
        $(window).scroll(function() { //detect page scroll
              if($(window).scrollTop() + $(window).height() >= $(document).height() - 250) { //if user scrolled from top to bottom of the page
                page++; //page number increment
                if(page <= last_page)
                {
                  get_search_results(page); //load content
                }
              }
        });

        function get_search_results(page)
        {
          var search_string = "{{ app('request')->input('q') }}";
          var search_type = "{{ app('request')->input('type') }}";

          $.ajax({
              url: BASE_URL+"home/home_search?page="+page,
              data: {"search_string":search_string,"type":search_type },
              type: "POST",
              beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                $('.loader').css('visibility','visible');

              },
              error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
              },
              success: function(response) {
                  if(response.status == "200") {
                      if(page == 1)
                      {
                        $("#search_results").html("");
                      }
                      if(response.data.length == 0){
                        $("#no_data").removeClass('d-none');
                      }
                      else
                      {
                        $("#no_data").addClass('d-none');
                        $.each(response.data,function(key,value){
                          last_page = response.meta.last_page;

                          if(search_type == 'books')
                          {
                            if(value.added_to_cart == '1')
                            {
                              var view_cart = "{{route('my_cart')}}";
                              var cart_btn = '<a href="'+view_cart+'" class="btn green-btn">View Cart <i class="icon-bag"></i></a>';
                            }
                            else
                            {
                              var cart_btn = '<a href="javascript:void(0);" data-lang="'+value.language+'" data-book-id="'+value.product_id+'" class="btn secondary-btn add_to_cart_book">Add to Cart <i class="icon-bag"></i></a>';
                            }
                            var book_detail_url = "{{route('book_detail',':book_id')}}?lang="+value.language;
                            book_detail_url = book_detail_url.replace(':book_id',value.product_id);
                            $("#search_results").append(`<div class="col-xl-4 col-lg-4 col-md-12 col-12"> 
                                <div class="product-list-box">
                                  <div class="img"><a href="`+book_detail_url+`" title=""><img src="`+value.image+`" alt=""></a></div>
                                  <div class="detail">
                                    <h6><a href="`+book_detail_url+`" title="">`+value.name+`</a></h6> 
                                    <div class="sale-price">₹`+value.sale_price+`<span>₹`+value.mrp+`</span></div> 
                                    <div class="qty-items">
                                      <input type="button" value="-" class="qty-minus">
                                      <input type="number" value="`+value.quantity+`" class="qty" min="0">
                                      <input type="button" value="+" class="qty-plus">
                                    </div> 
                                    `+cart_btn+`
                                  </div>
                                </div>
                              </div>`); 
                          }
                          else
                          {
                            var detail_url = "{{route('digital_coupon_details',':coupon_id')}}";
                            detail_url = detail_url.replace(':coupon_id',value.product_id);
                            if(value.added_to_cart == '1')
                            {
                              var view_cart = "{{route('my_cart')}}";
                              var cart_btn = '<a href="'+view_cart+'" class="btn green-btn">View Cart <i class="icon-bag"></i></a>';
                            }
                            else
                            {
                              var cart_btn = '<a href="javascript:void(0);" data-coupon-id="'+value.product_id+'" class="btn secondary-btn add_to_cart">Add to Cart <i class="icon-bag"></i></a>';
                            }

                            $("#search_results").append(`<div class="col-xl-4 col-lg-4 col-md-12 col-12">
                              <div class="product-list-box">
                                <div class="img"><a href="`+detail_url+`" title=""><img src="`+value.image+`" alt=""></a></div>
                                <div class="detail">
                                  <h6><a href="`+detail_url+`" title="">`+value.name+`</a></h6>
                                  <div class="price-qty">
                                    <div class="sale-price">₹`+value.sale_price+`<span>₹`+value.mrp+`</span></div>
                                    <div class="qty-items">
                                      <input type="button" value="-" class="qty-minus">
                                      <input type="number" value="`+value.quantity+`" class="qty" min="0">
                                      <input type="button" value="+" class="qty-plus">
                                    </div>
                                  </div>
                                  <div class="type">Type: <span>`+value.type_label+`</span></div>
                                  <div class="date">Expiry Date: `+value.expiry_date+`</div> 
                                  `+cart_btn+`
                                </div>
                              </div>
                            </div>`); 
                          }                      
                            
                        });
                      }
                      
                    
                    
                  }
                  $('.loader').css('visibility','hidden');

              }
          });
        }

        //add to cart for book
        $(document).on('click','.add_to_cart_book',function(){
              auth_guard_route(token);
              var quantity = $(this).prev().find('.qty').val();
              var book_id = $(this).data('book-id');
              var language = $(this).data('lang');
              $.ajax({
                url: BASE_URL+"books/add_to_cart",
                data: {'book_id':book_id,'quantity':quantity,'language':language},
                type: "POST",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                 error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  console.log(response);
                  if(response.status == "200") {
                    toastr.success(response.message);
                    setTimeout(function(){
                      location.reload();
                    },1000);
                  }
                  else {
                    toastr.error(response.message);
                  }
                  $('.loader').css('visibility','hidden');
                }
              });
        });

        //add to cart for coupon
        $(document).on('click','.add_to_cart',function(){
            auth_guard_route(token);
            var quantity = $(this).parent().find('.qty').val();
            var coupon_id = $(this).data('coupon-id');
            $.ajax({
              url: BASE_URL+"coupon/add_to_cart",
              data: {'coupon_id':coupon_id,'quantity':quantity},
              type: "POST",
              beforeSend: function(xhr){
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    $('.loader').css('visibility','visible');
              },
              error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
              },
              success: function(response) {
                if(response.status == "200") {
                  toastr.success(response.message);
                  setTimeout(function(){
                    location.reload();
                  },1000);
                }
                else {
                  toastr.error(response.message);
                }
                $('.loader').css('visibility','hidden');
              }
            });
        });
      @endif
  /* ################# SEARCH :: SCRIPT ENDS ################# */
  
  /* ################# BOOK LIST :: SCRIPT STARTS ################# */
      @if(\Request::route()->getName() == 'books_list')
        var page        = 1;
        var last_page   = '';
        var category_id = $("li.breadcrumb").last().attr('name');
        /*if(!category_id)
        {
          category_id = $("[name=main_category]:checked").attr('id');
        }
     */     
        var language    = $("ul#myTabLang li a.active").attr('id');
        load_more_books(page,category_id,language); 
        $("#books_list").html("");
        $("#books_list_hindi").html("");
        $('#books_list_all').html("");
        $(window).scroll(function() { //detect page scroll
              if($(window).scrollTop() + $(window).height() >= $(document).height() - 250) { //if user scrolled from top to bottom of the page
               //  console.log(page+"/"+last_page);
               if(page < last_page)
                {
                  page++; //page number increment
                  var language = $("ul#myTabLang li a.active").attr('id');
                  var category_id = $("li.breadcrumb").last().attr('name');
                  load_more_books(page,category_id,language); //load content
                }
              }
        });
        
        function load_more_books(page,category_id,language)
        {
          var business_category_id = $("#business_category_id").val();
             $.ajax({
              url: BASE_URL+"books_list?page="+page,
              data: {"business_category_id":business_category_id,"category_id":category_id,"language":language,"user_id":userid },
              type: "GET",
              async:false,
              beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                $('.loader').css('visibility','visible');
                
              },
              error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                  if(response.status == '429'){
                    toastr.error("There are too many attempts for this page so please wait for a while");
                    location.reload(true);
                  }
              },
              success: function(response) {
                  if(response.status == "200") {
                      if(page == 1)
                      {
                        $("#books_list_hindi").html("");
                        $("#books_list").html("");
                        $('#books_list_all').html("");
                      }
                      if(response.data.length == 0){
                        if(language == 'hindi')
                        {
                          $("#no_data_hindi").removeClass('d-none');
                        }else if(language == 'all')
                        {
                          $("#no_data_all").removeClass('d-none');
                        }
                        else
                        {
                          $("#no_data").removeClass('d-none');
                        }
                      }
                      else
                      {
                        $("#no_data_hindi").addClass('d-none');
                        $("#no_data").addClass('d-none');
                        $("#no_data_all").addClass('d-none');
                        $.each(response.data,function(key,value){
                          last_page = response.meta.last_page;
                          if(value.added_to_cart == '1')
                          {
                            var view_cart = "{{route('my_cart')}}";
                            var cart_btn = '<a href="'+view_cart+'" class="btn green-btn">View Cart <i class="icon-bag"></i></a>';
                          }
                          else
                          {
                            var cart_btn = '<a href="javascript:void(0);" data-book-id="'+value.book_id+'" class="btn secondary-btn add_to_cart_book">Add to Cart <i class="icon-bag"></i></a>';
                          }

                          var book_detail_url = "{{route('book_detail',':book_id')}}?lang="+language;
                          book_detail_url = book_detail_url.replace(':book_id',value.book_id);
                          var books_html = `<div class="col-xl-4 col-lg-4 col-md-12 col-12"> 
                              <div class="product-list-box">
                                <div class="img"><a href="`+book_detail_url+`" title=""><img src="`+value.image+`" alt=""></a></div>
                                <div class="detail">
                                  <h6><a href="`+book_detail_url+`" title="">`+value.name+`</a></h6> 
                                  <div class="sale-price">₹`+value.sale_price+`<span>₹`+value.mrp+`</span></div> 
                                  <div class="qty-items">
                                    <input type="button" value="-" class="qty-minus">
                                    <input type="number" value="`+value.quantity+`" class="qty" min="0">
                                    <input type="button" value="+" class="qty-plus">
                                  </div> 
                                  `+cart_btn+`
                                </div>
                              </div>
                            </div>`;                        
                          if(language == 'hindi')
                          {
                            $("#books_list_hindi").append(books_html);
                          }else if(language == 'all'){
                            $("#books_list_all").append(books_html);
                          }
                          else
                          {
                            $("#books_list").append(books_html);
                          }
                        });
                      }
                  }
                  $('.loader').css('visibility','hidden');
              }
          });   
        }
        
        /*$(document).on('click',"ul#myTab li a",function(){
          var language = $("ul#myTab li a.active").attr('id');
          var business_category = $("#business_category_id").val();
          var category_id = $("li.breadcrumb").last().attr('name');

          if(!category_id)
          {
            category_id = $("[name=main_category]:checked").attr('id');
          }
          page = 1;
          load_more_books(page,category_id,language);
        });*/

        $(document).on('click','.add_to_cart_book',function(){
              auth_guard_route(token);
              var quantity = $(this).prev().find('.qty').val();
              var book_id = $(this).data('book-id');
              var language = $("ul#myTab li a.active").attr('id');
              
              $.ajax({
                url: BASE_URL+"books/add_to_cart",
                data: {'book_id':book_id,'quantity':quantity,'language':language},
                type: "POST",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  console.log(response);
                  if(response.status == "200") {
                    toastr.success(response.message);
                    /*setTimeout(function(){
                      location.reload();
                    },1000);*/
                    var cart_count = parseInt($('#cart_item_count').html());  
                    var total_cart_item_count = cart_count+1;
                    $('#cart_item_count').html(total_cart_item_count);
                    var add_to_cart_btn = $("a[data-book-id='" + book_id + "']"); 
                    var view_cart = "{{route('my_cart')}}";
                    $("a[data-book-id='" + book_id + "']").prop('href', view_cart);
                    $("a[data-book-id='" + book_id + "']").removeClass('secondary-btn');
                    $("a[data-book-id='" + book_id + "']").removeClass('add_to_cart_book');
                    $("a[data-book-id='" + book_id + "']").addClass('green-btn');
                    $("a[data-book-id='" + book_id + "']").html('View Cart <i class="icon-bag"></i>');         
                    $("a[data-book-id='" + book_id + "']").removeAttr('data-book-id');
                  } else {
                    toastr.error(response.message);
                  }
                  $('.loader').css('visibility','hidden');
                }
              });
        });
      @endif
  /* ################# BOOK LIST :: SCRIPT ENDS ################# */

  /* ################# TRENDING BOOK LIST :: SCRIPT STARTS ################# */
      @if(\Request::route()->getName() == 'trending_books')
        var page        = 1;
        var last_page   = '';
        load_more_trending_books(page); 
        $("#trending_books").html("");
        $(window).scroll(function() { //detect page scroll
              if($(window).scrollTop() + $(window).height() >= $(document).height() - 250) { //if user scrolled from top to bottom of the page
                if(page < last_page)
                {
                  page++; //page number increment
                  load_more_trending_books(page); //load content
                }
              }
        });
        function load_more_trending_books(page)
        {
          $.ajax({
              url: BASE_URL+"home/trending_book_list?page="+page,
              data: { },
              type: "GET",
              beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                $('.loader').css('visibility','visible');

              },
              error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
              },
              success: function(response) {
                  if(response.status == "200") {
                      if(page == 1)
                      {
                        $("#trending_books").html("");
                      }
                      if(response.data.length == 0){
                        $("#no_data_hindi").removeClass('d-none');
                        $("#no_data_all").removeClass('d-none');
                      }
                      else
                      {
                        $.each(response.data,function(key,value){
                          last_page = response.meta.last_page;
                          if(value.added_to_cart == '1')
                          {
                            var view_cart = "{{route('my_cart')}}";
                            var cart_btn = '<a href="'+view_cart+'" class="btn green-btn">View Cart <i class="icon-bag"></i></a>';
                          }
                          else
                          {
                            var cart_btn = '<a href="javascript:void(0);" data-book-id="'+value.book_id+'" class="btn secondary-btn add_to_cart_book">Add to Cart <i class="icon-bag"></i></a>';
                          }
                          var book_detail_url = "{{route('book_detail',':book_id')}}";
                          book_detail_url = book_detail_url.replace(':book_id',value.book_id);
                          $("#trending_books").append(`<div class="col-xl-4 col-lg-4 col-md-12 col-12"> 
                              <div class="product-list-box">
                                <div class="img"><a href="`+book_detail_url+`" title=""><img src="`+value.image+`" alt=""></a></div>
                                <div class="detail">
                                  <h6><a href="`+book_detail_url+`" title="">`+value.name+`</a></h6> 
                                  <div class="sale-price">₹`+value.sale_price+`<span>₹`+value.mrp+`</span></div> 
                                  <div class="qty-items">
                                    <input type="button" value="-" class="qty-minus">
                                    <input type="number" value="`+value.quantity+`" class="qty" min="0">
                                    <input type="button" value="+" class="qty-plus">
                                  </div> 
                                  `+cart_btn+`
                                </div>
                              </div>
                            </div>`);                        
                            
                        });
                      }
                      
                    
                    
                  }
                  $('.loader').css('visibility','hidden');

              }
          });
        }

        $(document).on('click','.add_to_cart_book',function(){
              auth_guard_route(token);
              var quantity = $(this).prev().find('.qty').val();
              var book_id = $(this).data('book-id');
              
              $.ajax({
                url: BASE_URL+"books/add_to_cart",
                data: {'book_id':book_id,'quantity':quantity},
                type: "POST",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  console.log(response);
                  if(response.status == "200") {
                    toastr.success(response.message);
                    setTimeout(function(){
                      location.reload();
                    },1000);
                  }
                  else {
                    toastr.error(response.message);
                  }
                  $('.loader').css('visibility','hidden');
                }
              });
        });
      @endif
  /* ################# TRENDING BOOK LIST :: SCRIPT ENDS ################# */

  /* ################# MAKE MY RETURN LIST :: SCRIPT STARTS ################# */
      @if(\Request::route()->getName() == 'make_my_return')
        var page        = 1;
        var last_page   = '';
        var tab_changed ;
        var category_id = $("li.breadcrumb").last().attr('name');
        // get main categories
      get_main_categories_return();
      $(document).on('click',"ul#myTabLang li a",function(){
            var type = $("ul#myTab li a.active").attr('id');
            var language = $("ul#myTabLang li a.active").attr('id');
            var category_id = $("li.breadcrumb").last().attr('name');
            tab_changed = 1;
            $("[class*=sub_cat_]").remove();
           // $("[class*=selected_cat_]").remove();
            get_main_categories_return();
            if(!category_id)
            {
              category_id = $("li.breadcrumb").first().attr('id');
            }
            page = 1;
             load_more_books(page,category_id,language);
      });

      function get_breadcrumb()
      {
            //active_tab = $("ul#myTab li a.active").attr('id');
            
            var category_id = $("input[name='category']:checked").attr('id');
            var category_name = $("input[name='category']:checked").closest('li').find('.checkmark').text();
            var class_name = $("input[name='category']:checked").closest('li').find('.change-text').attr('class');
            if(class_name){
              class_name = class_name.replace('change-text ','');
            }
            var breadcrumb = '';
            breadcrumb += `<li class="breadcrumb selected_cat_`+category_id+`" id="`+class_name+`" name="`+category_id+`"><div class="tag-box"><label class="slot-box"><input class="change-text selected_cat_`+category_id+`"  name="category" id="`+category_id+`" type="radio" selected="true"><span class="checkmark" style="border-color: #E8E8E8;  background: #E8E8E8;  color: #000;">`+category_name+` <i class="fa fa-window-close close_level" id="`+category_id+`"></i></span></label></div></li>`;

            if($(':radio[name=category][class*=selected_cat_'+category_id+']', '.selected_category').length)
            {
              $("li.selected_cat_"+category_id).nextAll().remove();
              $('.selected_category').find("li.selected_cat_"+category_id).remove();
              $('.selected_category').append(breadcrumb);
            }
            else
            {
              $('.selected_category').append(breadcrumb);
            } 
      }

      $(document).on('click','.close_level',function(e){
            var id = $(this).attr('id');
            var language = $("ul#myTabLang li a.active").attr('id');
            // e.preventDefault();
            var prev_id_class = $("li.selected_cat_"+id).prev().attr('id');
            var prev_id = $("li.selected_cat_"+id).prev().attr('name');
           // var cat_id = $("input[name='main_category']:checked").attr('id');
            if(prev_id_class == undefined){
              $("[class*=sub_cat_]").remove();
              tab_changed = 0;
              get_main_categories_return();
            }else {
              eval(prev_id_class+'('+prev_id+')');
            }
         
            $("li.selected_cat_"+id).nextAll().remove();
            $('.selected_category').find("li.selected_cat_"+id).remove();
      });
      function get_main_categories_return(category_id) {
         var token =  localStorage.getItem('user_token');
          //var language  = active_tab;
          var language = $("ul#myTabLang li a.active").attr('id');
       
           $.ajax({
              url: BASE_URL+"nested_categories_return/?lang="+language,
              data: { },
              type: "get",
              beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              },
              success: function(response) {
                if(response.status == "200") {
                          var category = "";
                          category = '<ul class="main-cat">';
                              $.each( response.data, function( index, value ){
                              category +=
                                  `<li>
                                      <div class="tag-box">
                                          <label class="slot-box">
                                          <input class="change-text sub_category_1" name="main_category" id="`+value.category_id+`" type="radio"><span class="checkmark">`+value.category_name+`</span>
                                          </label>
                                      </div>
                                  </li>`
                              });
                              category += `</ul>`;
                              $("#main_tag_list").html(category);
                              if(!tab_changed){
                                load_more_books('1',category_id,language);
                              }
                } 
                else {
                    toastr.error(response.message);
                }
              }
          });
      }
        // get sub categories level 2
      $(document).on('click','.sub_category_1',function(){
           // get_breadcrumb();
           var category_id = $("input[name='main_category']:checked").attr('id');
            var category_name = $("input[name='main_category']:checked").closest('li').find('.checkmark').text();
            var class_name = $("input[name='main_category']:checked").closest('li').find('.change-text').attr('class');
             if(class_name){
              class_name = class_name.replace('change-text ','');
            }

            var breadcrumb = `<li class="breadcrumb selected_cat_`+category_id+`" id="`+class_name+`" name="`+category_id+`"><div class="tag-box"><label class="slot-box"><input class="selected_cat_`+category_id+`"  name="main_category" id="`+category_id+`" type="radio" selected="true"><span class="checkmark" style="border-color: #E8E8E8;  background: #E8E8E8;  color: #000;">`+category_name+` <i class="fa fa-window-close close_level" id="`+category_id+`"></i></span></label></div></li>`;
            $(".selected_category").html(breadcrumb);
            sub_category_1(this.id);
      })
  
      function sub_category_1(id)
      {
            var parent_cat_id = id;
            var language  = $("ul#myTabLang li a.active").attr('id');
            load_more_books('1',parent_cat_id,language);
           
            for(var i=1; i<=7; i++) {
              $(".sub_cat_"+i).remove();
            }
            $.ajax({
                    url: BASE_URL+"nested_categories_return/?lang="+language,
                    data: { },
                    type: "get",
                    beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    },
                    error:function(response){
                      if(response.status == '401'){
                        auth_guard_route(response.status);
                      }
                    },
                    success: function(response) {
                    if(response.status == "200") {
                        //console.log(response);
                            var sub_category = '';
                            $('.sub_cat_1').html('');
                            $.each( response.data, function( index, value ){
                             if(parent_cat_id == this.category_id){
                              if(this.sub_cat.length==0){ $(".sub_cat_2").remove(); }
                              else {
                                sub_category = '<ul class="sub_cat_2">';
                              $.each( this.sub_cat, function( index, value ){
                              sub_category+=
                               `<li>
                                    <div class="tag-box">
                                        <label class="slot-box">
                                        <input class="change-text sub_category_2" name="category" id="`+this.category_id+`" type="radio"><span class="checkmark">`+this.category_name+`</span>
                                        </label>
                                    </div>
                                </li>`;
                               });
                              }
                              sub_category+= '</ul>';
                              if($(':radio[name=category][class=sub_category_2]', '#tag-list').length ==0) {
                                $('#tag_list').html(sub_category);
                              }
                              }
                                });

                    } else {
                        toastr.error(response.message);
                    }
                    }
            });     
      }
      
      // get sub categories level 3
      $(document).on('click','.sub_category_2',function(){
            //console.log("sub_category_1("+this.id+")");
             get_breadcrumb();
             sub_category_2(this.id);
             $(this.id).attr('checked',true);
      })

      function sub_category_2(id)
      {
        for(var i=2; i<=7; i++) {
            $(".sub_cat_"+i).remove();
        }
        var parent_cat_id = id;
        var language = $("ul#myTabLang li a.active").attr('id');
        load_more_books('1',parent_cat_id,language);
        $.ajax({
                    url: BASE_URL+"nested_categories_return/?lang="+language,
                    data: { },
                    type: "get",
                    beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    },
                    error:function(response){
                      if(response.status == '401'){
                        auth_guard_route(response.status);
                      }
                    },
                    success: function(response) {
                    if(response.status == "200") {
                          var sub_category = '';
                           $('.sub_cat').html('');
                            $.each( response.data, function( index, value ){
                              $.each( this.sub_cat, function( index, value ){
                             // console.log(parent_cat_id+"/"+this.category_id);
                                   if(parent_cat_id == this.category_id){
                                   // console.log(this.sub_cat.length);
                                    if(this.sub_cat.length==0){ $('.sub_cat_3').remove(); }
                                    else {
                                        sub_category = '<ul class="sub_cat_3">';
                                      $.each(this.sub_cat, function( index, value ){
                                     sub_category+=
                                     `<li>
                                          <div class="tag-box">
                                              <label class="slot-box">
                                              <input class="change-text sub_category_3" name="category" id="`+this.category_id+`" type="radio"><span class="checkmark">`+this.category_name+`</span>
                                              </label>
                                          </div>
                                      </li>`;
                                     });
                                      sub_category += '</ul>';
                                    }
                                     if($(':radio[name=category][class=sub_category_3]', '#tag-list').length ==0) {
                                     $('#tag_list').html(sub_category);
                                        }
                                    }
                                });
                              });
                    } else {
                        toastr.error(response.message);
                    }
                    }
        });
      }
      
      // get sub categories level 4
      $(document).on('click','.sub_category_3',function(){
             get_breadcrumb();
             sub_category_3(this.id);
      })

      function sub_category_3(id)
      {
              for(var i=3; i<=7; i++) {
              $(".sub_cat_"+i).remove();
              }
              load_more_books('1',parent_cat_id,language);
              $.ajax({
                    url: BASE_URL+"nested_categories_return/?lang="+language,
                    data: { },
                    type: "get",
                    beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    },
                    error:function(response){
                      if(response.status == '401'){
                        auth_guard_route(response.status);
                      }
                    },
                    success: function(response) {
                    if(response.status == "200") {
                          var sub_category = '';
                           $('.sub_cat_3').html('');
                           //console.log(response.data);
                            $.each( response.data, function( index, value ){
                              $.each( this.sub_cat, function( index, value ){
                              $.each( value.sub_cat, function( index, value ){
                             // console.log(parent_cat_id+"/"+this.category_id);
                             
                             if(parent_cat_id == this.category_id){
                             // console.log(this.sub_cat.length);
                              if(this.sub_cat.length==0){ $('.sub_cat_4').remove(); }
                              else {
                                  sub_category = '<ul class="sub_cat_4">';
                                $.each(this.sub_cat, function( index, value ){
                               sub_category+=
                               `<li>
                                    <div class="tag-box">
                                        <label class="slot-box">
                                        <input class="change-text sub_category_4" name="category" id="`+this.category_id+`" type="radio"><span class="checkmark">`+this.category_name+`</span>
                                        </label>
                                    </div>
                                </li>`;
                               });
                                sub_category += '</ul>';
                              }
                                 if($(':radio[name=category][class=sub_category_4]', '#tag-list').length ==0) {
                                $('#tag_list').html(sub_category);   
                                  }
                              }
                                });
                               });
                            });
                    } else {
                        toastr.error(response.message);
                    }
                    }
              });
      }   

      // get sub categories level 5
      $(document).on('click','.sub_category_4',function(){
          get_breadcrumb();
          sub_category_4(this.id);
      })

      function sub_category_4(id)
      {
          for(var i=4; i<=7; i++) {
            $(".sub_cat_"+i).remove();
          }
          var parent_cat_id = id;
          var language = $("ul#myTabLang li a.active").attr('id');
          load_more_books('1',parent_cat_id,language);
          $.ajax({
                    url: BASE_URL+"nested_categories_return?lang="+language,
                    data: { },
                    type: "get",
                    beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    },
                    error:function(response){
                      if(response.status == '401'){
                        auth_guard_route(response.status);
                      }
                    },
                    success: function(response) {
                    if(response.status == "200") {
                          var sub_category = '';
                           //console.log(response.data);
                            $.each( response.data, function( index, value ){
                              $.each( this.sub_cat, function( index, value ){
                              $.each( value.sub_cat, function( index, value ){
                              $.each( value.sub_cat, function( index, value ){
                         
                             // console.log(parent_cat_id+"/"+this.category_id);
                             
                             if(parent_cat_id == this.category_id){
                             // console.log(this.sub_cat.length);
                              if(this.sub_cat.length==0){ $(".sub_cat_5").remove(); }
                              else {
                                   sub_category = '<ul class="sub_cat_5">';
                                $.each(this.sub_cat, function( index, value ){
                               sub_category+=
                               `<li>
                                    <div class="tag-box">
                                        <label class="slot-box">
                                        <input class="change-text sub_category_5" name="category" id="`+this.category_id+`" type="radio"><span class="checkmark">`+this.category_name+`</span>
                                        </label>
                                    </div>
                                </li>`;
                               });
                                sub_category += '</ul>';
                              }
                                 if($(':radio[name=category][class=sub_category_5]', '#tag-list').length ==0) {
                                  $('#tag_list').html(sub_category);
                                      
                               }
                              }
                                });
                               });
                            });
                            });
                    } else {
                        toastr.error(response.message);
                    }
                    }
          });
      }

      // get sub categories level 6
      $(document).on('click','.sub_category_5',function(){
        get_breadcrumb();
        sub_category_5(this.id);
      })

      function sub_category_5(id)
      {
              $(".sub_cat_5").remove();
              $(".sub_cat_6").remove();
              $(".sub_cat_7").remove();
              var parent_cat_id = id;
              var language = $("ul#myTabLang li a.active").attr('id');
              load_more_books('1',parent_cat_id,language);
             
              $.ajax({
                    url: BASE_URL+"nested_categories_return/?lang="+language,
                    data: { },
                    type: "get",
                    beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    },
                    error:function(response){
                      if(response.status == '401'){
                        auth_guard_route(response.status);
                      }
                    },
                    success: function(response) {
                    if(response.status == "200") {
                          var sub_category = '';
                           //console.log(response.data);
                            $.each( response.data, function( index, value ){
                              $.each( this.sub_cat, function( index, value ){
                              $.each( value.sub_cat, function( index, value ){
                              $.each( value.sub_cat, function( index, value ){
                                $.each( value.sub_cat, function( index, value ){
                         
                              //console.log(parent_cat_id+"/"+this.category_id);
                             
                             if(parent_cat_id == this.category_id){
                             // console.log(this.sub_cat.length);
                              if(this.sub_cat.length==0){ $(".sub_cat_6").remove(); }
                              else {
                                  sub_category = '<ul class="sub_cat_6">';
                                $.each(this.sub_cat, function( index, value ){
                               sub_category+=
                               `<li>
                                    <div class="tag-box">
                                        <label class="slot-box">
                                        <input class="change-text sub_category_6" name="category" id="`+this.category_id+`" type="radio"><span class="checkmark">`+this.category_name+`</span>
                                        </label>
                                    </div>
                                </li>`;
                               });
                                sub_category += '</ul>';
                              }
                                 if($(':radio[name=category][class=sub_category_6]', '#tag-list').length ==0) {
                                 $('#tag_list').html(sub_category);
                                      
                               }
                              }
                                });
                               });
                            });
                            });
                            });
                    } else {
                        toastr.error(response.message);
                    }
                    }
              });
      }

      $(document).on('click','.sub_category_6',function(){
            get_breadcrumb();
            sub_category_6(this.id);
      })

      function sub_category_6(id)
      {
                $(".sub_cat_6").remove();
                $(".sub_cat_7").remove();
                var parent_cat_id = id;
                var language = $("ul#myTabLang li a.active").attr('id');
                load_more_books('1',parent_cat_id,language);
                $.ajax({
                    url: BASE_URL+"nested_categories_return/?lang="+language,
                    data: { },
                    type: "get",
                    beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    },
                    error:function(response){
                      if(response.status == '401'){
                        auth_guard_route(response.status);
                      }
                    },
                    success: function(response) {
                    if(response.status == "200") {
                          var sub_category = '';
                           //console.log(response.data);
                            $.each( response.data, function( index, value ){
                              $.each( this.sub_cat, function( index, value ){
                              $.each( value.sub_cat, function( index, value ){
                              $.each( value.sub_cat, function( index, value ){
                              $.each( value.sub_cat, function( index, value ){
                              $.each( value.sub_cat, function( index, value ){
 
                              //console.log(parent_cat_id+"/"+this.category_id);
                             
                             if(parent_cat_id == this.category_id){
                             // console.log(this.sub_cat.length);
                              if(this.sub_cat.length==0){ $(".sub_cat_7").remove() }
                              else {
                                  sub_category = '<ul class="sub_cat_7">';
                                $.each(this.sub_cat, function( index, value ){
                               sub_category+=
                               `<li>
                                    <div class="tag-box">
                                        <label class="slot-box">
                                        <input class="change-text sub_category_7" name="category" id="`+this.category_id+`" type="radio"><span class="checkmark">`+this.category_name+`</span>
                                        </label>
                                    </div>
                                </li>`;
                               });
                                sub_category += '</ul>';
                              }
                                  if($(':radio[name=category][class=sub_category_7]', '#tag-list').length ==0) {
                                 $('#tag_list').html(sub_category);
                                      
                               }
                              }
                                });
                               });
                            });
                            });
                            });
                            });
                    } else {
                        toastr.error(response.message);
                    }
                    }
                });
      }

      $(document).on('click','.sub_category_7',function(){
        get_breadcrumb();
        sub_category_7(this.id)
      });

      // get sub categories level 7
      function sub_category_7(id)
      {
        $(".sub_cat_7").remove();
        var parent_cat_id = id;
        var language = $("ul#myTabLang li a.active").attr('id');
        load_more_books('1',parent_cat_id,language);
      }  
  
       /* if(!category_id)
        {
          category_id = $("[name=main_category]:checked").attr('id');
        }*/
        var language    = $("ul#myTabLang li a.active").attr('id');
        load_more_books(page,category_id,language); 
        $("#books_list").html("");
        $("#books_list_hindi").html("");
        $('#books_list_all').html("");
        $(window).scroll(function() { //detect page scroll
              if($(window).scrollTop() + $(window).height() >= $(document).height() - 250) { //if user scrolled from top to bottom of the page
                if(page < last_page)
                {
                  page++; //page number increment
                  var language    = $("ul#myTabLang li a.active").attr('id');
                  var category_id = $("li.breadcrumb").last().attr('name');
                  load_more_books(page,category_id,language); //load content
                }
              }
        });
        function load_more_books(page,category_id = '',language)
        { 
          $.ajax({
              url: BASE_URL+"my_return/make_my_return_list?page="+page,
              data: {"category_id":category_id,"language":language},
              type: "POST",
              beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                $('.loader').css('visibility','visible');

              },
              success: function(response) {
                  if(response.status == "200") {
                      if(page == 1)
                      {
                        $("#books_list_hindi").html("");
                        $("#books_list").html("");
                        $('#books_list_all').html("");
                      }
                      if(response.data.length == 0){
                        if(language == 'hindi')
                        {
                          $("#no_data_hindi").removeClass('d-none');
                        }else if(language == 'all')
                        {
                          $("#no_data_all").removeClass('d-none');
                        }
                        else
                        {
                          $("#no_data").removeClass('d-none');
                        }
                      }
                      else
                      {
                        $("#no_data_hindi").addClass('d-none');
                        $("#no_data").addClass('d-none');
                        $("#no_data_all").addClass('d-none');
                        $.each(response.data,function(key,value){
                          last_page = response.meta.last_page;
                          
                          var book_detail_url = "{{route('book_detail',':book_id')}}?lang="+language;
                          book_detail_url = book_detail_url.replace(':book_id',value.book_id);

                          if(value.added_to_cart == '1')
                          {
                            var cart_btn = `<a href="{{route('return_cart')}}" class="btn green-btn">View Return Cart <i class="icon-bag"></i></a>`;
                          }
                          else
                          {
                            var cart_btn = `<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#return-prduct" class="btn secondary-btn return_product" data-order-item-id="`+value.order_item_id+`" data-total-qty="`+value.quantity+`" data-available-return-qty="`+value.max_returnable_qty+`" data>Return Product <i class="icon-reply"></i></a>`;
                          }
                          var books_html = `<div class="col-xl-4 col-lg-4 col-md-12 col-12"> 
                                <div class="book-box book-list">
                                  <div class="img"><img src="`+value.image+`" alt=""></div>
                                  <div class="detail">
                                    <h6>`+value.name+`</h6>
                                    <div class="price-qty">
                                      <div class="sale-price">₹`+value.sale_price+`<span>₹`+value.mrp+`</span></div> 
                                      <div class="qty-bulk">Qty.`+value.quantity+`</div>
                                    </div>
                                    `+cart_btn+`
                                  </div>
                                </div>
                              </div> `;                   
                          if(language == 'hindi')
                          {
                            $("#books_list_hindi").append(books_html);
                          }else if(language == 'all'){
                            $("#books_list_all").append(books_html);
                          }
                          else
                          {
                            $("#books_list").append(books_html);
                          }
                        });
                      }
                      
                    
                    
                  }
                  $('.loader').css('visibility','hidden');

              }
          });
        }

        /*$(document).on('click',"ul#myTab li a",function(){
          var language = $("ul#myTab li a.active").attr('id');
          var category_id = $("li.breadcrumb").last().attr('name');
          if(!category_id)
          {
            category_id = $("[name=main_category]:checked").attr('id');
          }
          page = 1;
          load_more_books(page,category_id,language);
        });*/

        $(document).on('click','.return_product',function(){
          var total_qty = $(this).data('total-qty');
          var available_return_qty = parseInt($(this).data('available-return-qty'));
          console.log(available_return_qty)
          var order_item_id = $(this).data('order-item-id');
          $("#return_book_price_weight").html(`<ul>
            <li><label>Total Qty</label>`+total_qty+`</li>                
            <li><label>Available Return Qty</label><p class="theme-red-color">`+available_return_qty+`</p></li>
          </ul>`);
          $("#order_item_id").val(order_item_id);
        })

        $(document).on('click','.add_to_return_cart',function(){
              auth_guard_route(token);
              var order_item_id = $("#order_item_id").val();
              var return_qty = $(".return_qty").val();

              
              $.ajax({
                url: BASE_URL+"my_return/add_to_cart",
                data: {'order_item_id':order_item_id,'quantity':return_qty},
                type: "POST",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                success: function(response) {
                  if(response.status == "200") {
                    /*toastr.success(response.message);
                    setTimeout(function(){
                      location.reload();
                    },1000);*/
                    $("#return-place-success").modal('show');
                  }
                  else {
                    toastr.error(response.message);
                  }
                  $('.loader').css('visibility','hidden');
                }
              });
          });
      @endif
  /* ################# MAKE MY RETURN LIST :: SCRIPT ENDS ################# */

  /* ################# BOOK DETAIL :: SCRIPT STARTS ################# */
      @if(\Request::route()->getName() == 'book_detail')
          function nl2br (str, is_xhtml) {   
              var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
              return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
          }
          var book_id = $("#book_id").val();
          var language = "{{ app('request')->input('lang') }}";
          $.ajax({
              url: BASE_URL+"book_details",
              data: {"book_id" : book_id, "user_id" : userid, "language" : language },
              type: "POST",
              beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                $('.loader').css('visibility','visible');

              },
              error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
              },
              success: function(response) {
                  if(response.status == '201'){
                    location.href="{{route('web_home')}}";
                  }else if(response.status == "200") {

                    var book = response.data;
                    $.each(book.cover_images,function(){
                      $('#book_images').slick('slickAdd','<div class="item"><div class="img"><img src="'+this.image+'" alt=""></div></div>');
                    });
                    setTimeout(function(){
                      $('.pro-slide').slick("unslick");
                      sliderInit();
                    },500);

                    if(book.added_to_cart == '1')
                    {
                      var cart_btn = '<a href="javascript:void(0);" id="'+book.cart_item_id+'" class="btn green-btn update_qty">Update <i class="icon-bag"></i></a>';
                    }
                    else
                    {
                      var cart_btn = '<a href="javascript:void(0);" data-book-id="'+book.book_id+'" class="btn secondary-btn add_to_cart_book_detail">Add to Cart <i class="icon-bag"></i></a>';
                    }
                    $("#buttons").html(`<div class="qty-items">
                      <input type="button" value="-" class="qty-minus">
                      <input type="number" value="`+book.quantity+`" id="quantity" class="qty" min="0">
                      <input type="button" value="+" class="qty-plus">
                    </div>`+cart_btn);

                    $("#name").html(book.name);
                    $("#sub_heading").html(book.sub_heading);
                    $("#description").html((book.description));
                    if(!book.description)
                    {
                      $("#desc_heading").addClass('d-none');
                    }

                    $(".book-price-weight").html(`<ul>
                        <li><label><img class="rupee" src="{{asset('web_assets/images/book-price.svg')}}" alt=""> Book Price</label><div class="price">₹ `+book.sale_price+`<span>₹ `+book.mrp+`</span></div></li>                
                        <li><label>Weight</label><p>`+book.weight+` K.G</p></li>
                      </ul>`);

                   // $("#last_returnable").html('You Can Return Only '+returnable_qty+'% of The Product Within '+returnable_days+' Days After Placing The Order');
                    $("#last_returnable").html('You Can Return Only '+book.returnable_qty+'% of The Product By '+book.last_returnable_date);

                    $("#additional_info").html(nl2br(book.additional_info));
                    if(!book.additional_info)
                    {
                      $("#add_info_heading").addClass('d-none');
                    }
                    
                    if(book.related_products.length == 0)
                    {
                      $('.related-product').addClass('d-none');
                    }
                    $.each(book.related_products,function(){
                      var book_detail_url = "{{route('book_detail',':book_id')}}";
                      book_detail_url = book_detail_url.replace(':book_id',this.book_id);
                      $('#related_products').slick('slickAdd',`<div class="item">
                            <div class="book-box">
                              <div class="img"><a href="`+book_detail_url+`" title=""><img src="`+this.image+`" alt=""></a></div>
                              <div class="detail">
                                <h6><a href="`+book_detail_url+`" title="">`+this.name+`</a></h6>
                                <div class="price-qty">
                                  <div class="sale-price"><span>₹`+this.mrp+`</span> ₹`+this.sale_price+`</div> 
                                </div>              
                              </div>
                            </div>
                          </div>`);
                    });
                  }
                  $('.loader').css('visibility','hidden');

              }
          });
          
          $(document).on('click','.add_to_cart_book_detail',function(){
              auth_guard_route(token);
              var quantity = $(this).prev().find('.qty').val();
              var book_id = $(this).data('book-id');
              
              $.ajax({
                url: BASE_URL+"books/add_to_cart",
                data: {'book_id':book_id,'quantity':quantity,'language':language},
                type: "POST",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  console.log(response);
                  if(response.status == "200") {
                   // toastr.success(response.message);
                    $("#add_more_btn_detail").attr('href',"{{route('search')}}?type=books");
                    $("#add_to_cart_modal").modal("show");

                   /* setTimeout(function(){
                      location.reload();
                    },1000);*/
                  }
                  else {
                    toastr.error(response.message);
                  }
                  $('.loader').css('visibility','hidden');
                }
              });
          });

          $(document).on('click','.update_qty',function(e){
              e.preventDefault();
              var cart_item_id = $(this).attr('id');
              var quantity = $("#quantity").val();
              // console.log(quantity)  
              update_quantity(cart_item_id,quantity);
          });

          function update_quantity(cart_item_id,quantity){
            $.ajax({
                url: BASE_URL+"books/update_quantity",
                data: {'cart_item_id':cart_item_id,'quantity':quantity},
                type: "POST",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  if(response.status == "200") {
                    toastr.success(response.message);
                    setTimeout(function(){
                      location.reload();
                    },1000);
                  }
                  else {
                   toastr.error(response.message);
                    setTimeout(function(){
                      location.reload();
                    },1000);
                  }
                 
                  $('.loader').css('visibility','hidden');
                }
            });
          }
      @endif
  /* ################# BOOK DETAIL :: SCRIPT ENDS ################# */

  /* ################# MY ORDERS :: SCRIPT STARTS ################# */
      @if(\Request::route()->getName() == 'my_orders')
        var page        = 1;
        var last_page   = '';
        var order_status = '';
        var type    = $("ul#myTab li a.active").attr('id'); // upcoming, past
        get_filter_data(type);
        function get_filter_data(type) {

          if(type =="upcoming"){
            var html = `<div class="common-check">
                          <label class="checkbox"><span>On Hold</span>
                            <input type="checkbox" name="order_status" value="on_hold"><span class="checkmark"></span>
                          </label> 
                        </div>
                       <!-- <div class="common-check">
                          <label class="checkbox"><span>Under Process</span>
                            <input type="checkbox" name="order_status" value="under_process"><span class="checkmark"></span>
                          </label> 
                        </div>  -->
                        <div class="common-check">
                          <label class="checkbox"><span>Pending Payment</span>
                            <input type="checkbox" name="order_status" value="pending_payment"><span class="checkmark"></span>
                          </label> 
                        </div>  
                        <div class="common-check">
                          <label class="checkbox"><span>Processing</span>
                            <input type="checkbox" name="order_status" value="processing"><span class="checkmark"></span>
                          </label> 
                        </div>
                        <div class="common-check">
                          <label class="checkbox"><span>Shipped</span>
                            <input type="checkbox" name="order_status" value="shipped"><span class="checkmark" ></span>
                          </label> 
                        </div>`;
          }else {
              var html = `<div class="common-check">
                          <label class="checkbox"><span>Completed</span>
                            <input type="checkbox" name="order_status" value="completed"><span class="checkmark"></span>
                          </label> 
                        </div>
                        <div class="common-check">
                          <label class="checkbox"><span>Cancelled</span>
                            <input type="checkbox" name="order_status" value="cancelled"><span class="checkmark"></span>
                          </label> 
                        </div>  
                         <div class="common-check">
                          <label class="checkbox"><span>Failed</span>
                            <input type="checkbox" name="order_status" value="failed"><span class="checkmark"></span>
                          </label> 
                        </div>  
                        <div class="common-check">
                          <label class="checkbox"><span>Refunded</span>
                            <input type="checkbox" name="order_status" value="refunded"><span class="checkmark"></span>
                          </label> 
                        </div>`;
          }
          $("#order_status_div").html(html);
        }
        get_my_orders(page,type); 
        $("#upcoming_orders").html("");
        $("#past_orders").html("");
        $(window).scroll(function() { //detect page scroll
              if($(window).scrollTop() + $(window).height() >= $(document).height() - 250) { //if user scrolled from top to bottom of the page
                if(page < last_page)
                {
                  page++; //page number increment
                  var type = $("ul#myTab li a.active").attr('id');
                  get_my_orders(page,type); //load content  
                }
              }
        });
        function get_my_orders(page,type,order_status)
        {
          $.ajax({
              url: BASE_URL+"my_orders?page="+page,
              data: {"type":type ,"order_status[]":order_status},
              type: "POST",
              beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                $('.loader').css('visibility','visible');

              },
              error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
              },
              success: function(response) {

                  if(response.status == "200") {
                      if(page == 1)
                      {
                        $("#upcoming_orders").html("");
                        $("#past_orders").html("");
                      }
                      if(response.data.length == 0){
                        if(type == 'upcoming')
                        {
                          $("#no_data_upcoming").removeClass('d-none');
                        }
                        else
                        {
                          $("#no_data_past").removeClass('d-none');
                        }
                      }
                      else
                      {
                        $("#no_data_upcoming").addClass('d-none');
                        $("#no_data_past").addClass('d-none');
                        last_page = response.meta.last_page;
                        var style_class = '';
                        $.each(response.data,function(key,value){
                          if(value.order_status == 'Cancelled'){
                          	style_class = 'text-danger';
                          }
                          else if(value.order_status == 'Refunded'){
                            style_class = 'text-warning';
                          }	
                          else {
                          	style_class = 'theme-color';
                          }
                          var order_detail_url = "{{route('order_details',':id')}}";
                          order_detail_url = order_detail_url.replace(':id',this.id);
                          var orders_html = `<div class="col-xl-6 col-lg-6 col-md-12 col-12">
                                              <a href="`+order_detail_url+`" title="">
                                                <div class="order-list">
                                                  <div class="head">
                                                    <p><label>Order ID:</label>`+value.id+`</p>
                                                    <div><span class="`+style_class+`">`+value.order_status+`</span></div>
                                                  </div>
                                                  <div class="details"> 
                                                    <ul>
                                                      <li><label>Order Date</label>`+value.order_date+`</li>
                                                      <li><label>Order Amount</label>₹ `+value.order_total+`</li>
                                                      <li><label>Order Time</label>`+value.order_time+`</li>
                                                    </ul>
                                                  </div>
                                                </div>
                                              </a>
                                            </div>`;

                          if(type == 'upcoming')
                          {
                            $("#upcoming_orders").append(orders_html);
                          }
                          else
                          {
                            $("#past_orders").append(orders_html);
                          }
                        });
                      }
                  }
                  $('.loader').css('visibility','hidden');

              }
          });
        }

        $(document).on('click',"ul#myTab li a",function(){
          type = $("ul#myTab li a.active").attr('id');
          page = 1;
          get_filter_data(type);
          get_my_orders(page,type);
        });

          $("#apply_filter_btn").click(function(){
            var order_status = [];
            $("input[name=order_status]:checked").each(function(i){
              order_status[i] = this.value; 
            });
            if(order_status !='undefined') {
              page = 1;
              get_my_orders(page,type,order_status);
              $("#filter-modal").modal("hide");
          }
        });

      $(document).on('click','#clear_all',function(){
         $('input[name="order_status"]:checked').removeAttr('checked');
         page = 1;
         get_my_orders(page,type);
         $("#filter-modal").modal("hide");
      });

        
      @endif
  /* ################# MY ORDERS :: SCRIPT ENDS ################# */

  /* ################# ORDER DETAIL :: SCRIPT STARTS ################# */
      @if(\Request::route()->getName() == 'order_details')
          function formatDate(date) {
              var d = new Date(date),
                  month = '' + (d.getMonth() + 1),
                  day = '' + d.getDate(),
                  year = d.getFullYear();

              if (month.length < 2) 
                  month = '0' + month;
              if (day.length < 2) 
                  day = '0' + day;

              return [year, month, day].join('-');
          }
          var order_id = $("#order_id").val();
          var order_user_type = '';
          $.ajax({
              url: BASE_URL+"order_detail/"+order_id,
              data: { },
              type: "GET",
              beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                $('.loader').css('visibility','visible');

              },
              error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
              },
              success: function(response) {

                  if(response.status == "200") {

                    var order = response.data;
                    order_user_type  = order.user_type;
                    var shipping_address = order.shipping_address.house_no+', '+order.shipping_address.street+', '+order.shipping_address.landmark+', '+order.shipping_address.area+', '+order.shipping_address.city_name+', '+order.shipping_address.state_name+' - '+order.shipping_address.postcode;

                    var billing_address = order.billing_address.house_no+', '+order.billing_address.street+', '+order.billing_address.landmark+', '+order.billing_address.area+', '+order.billing_address.city_name+', '+order.billing_address.state_name+' - '+order.billing_address.postcode;
                     if(order.order_status == 'Cancelled'){
                          var style_class = 'text-danger';
                          }	
                         else if(order.order_status == 'Refunded'){
                            style_class = 'text-warning';
                          } 
                          else {
                           var style_class = 'theme-color';
                          }
                    $("#order_list").html(`<div class="head">
                          <p><label>Order ID:</label>`+order.order_id+`</p>
                          <div><span class="`+style_class+`">`+order.order_status+`</span></div>
                        </div>
                        <div class="details">
                          <div class="row"> 
                            <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                              <label class="secondary-color">Order Date</label>
                              <p>`+order.order_date+`</p>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                              <label class="secondary-color">Order Amount</label>
                              <p>₹ `+order.order_total+`</p>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                              <label class="secondary-color">Order Time</label>
                              <p>`+order.order_time+`</p>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                              <label class="secondary-color">Shipping Address</label>
                              <p>`+shipping_address+`</p>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                              <label class="secondary-color">Billing Address</label>
                              <p>`+billing_address+`</p>
                            </div>
                          </div>              
                        </div>`);

                    if(order.order_status == 'Completed')
                    {
                      $("#order_again").removeClass('d-none');
                    }
                    else
                    {
                      $("#order_again").addClass('d-none');
                    }
                    $(order.book_items).each(function(){
                      var order_book_view_url = "{{route('order_book_view',':order_item_id')}}?lang="+this.language;
                      order_book_view_url = order_book_view_url.replace(':order_item_id',this.order_item_id);
                      var available_return_qty = parseInt((this.quantity * this.returnable_qty)/100);
                      
                      var return_product_btn = '';
                      if(order.order_status == 'Completed')
                      {

                        if(this.added_to_return_cart == '0')
                        {
                          var CurrentDate = new Date();
                          var dateArr = this.last_returnable_date.split("-");
                          var last_returnable_date = new Date(dateArr[2]+'-'+dateArr[1]+'-'+dateArr[0]);
                          if(last_returnable_date > CurrentDate)
                          {
                            return_product_btn = `<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#return-prduct" class="btn secondary-btn return_product" data-order-item-id="`+this.order_item_id+`" data-total-qty="`+this.quantity+`" data-available-return-qty="`+available_return_qty+`" data>Return Product <i class="icon-reply"></i></a>`;
                          }
                        }
                        else
                        {
                          return_product_btn = `<a href="{{route('return_cart')}}" class="btn green-btn">View Return Cart <i class="icon-bag"></i></a>`;
                        }
                      }

                      $("#order_items").append(`<div class="box">
                          <div class="img">
                            <a href="`+order_book_view_url+`"><img src="`+this.cover_image+`" alt=""></a> 
                          </div>
                          <div class="details">
                            <div class="head">
                              <h5><a href="`+order_book_view_url+`" title="">`+this.name+`</a></h5>
                              <p class="secondary-color">`+this.description+`</p>                
                            </div>
                            <div class="price-qty">
                              <div class="sale-price">₹`+this.sale_price+`</div>
                              <div class="qty-bulk">Qty.`+this.quantity+`</div>
                            </div>
                            <div><span  class="secondary-color">Weight:</span> `+this.weight+` K.G Per Book</div> 
                            <div class="secondary-color">Returnable Percentage(%): 
                              <span class="green-color">`+this.returnable_qty+`% of The Product</span>
                            </div>                
                            <div class="secondary-color">Last Returnable Date: <span class="theme-red-color">`+this.last_returnable_date+`</span></div>
                            <div class="detail-btn">
                              <a href="`+order_book_view_url+`" class="view">View Detail</a>
                              `+return_product_btn+`
                            </div>
                          </div> 
                        </div>`)
                      if(this.stock_status == 'in_stock'){
                        var stock_status = `<div class="text-success out">`+this.stock_status_label+`</div>`;
                      }else {
                        var stock_status = `<div class="theme-red-color out">`+this.stock_status_label+`</div>`;
                        
                      }
                      var wishlist_cart_btn = '';
                      if(this.stock_status == 'out_of_stock')
                      {
                        if(this.added_to_wishlist == '0'){
                        var create_wishlist_url = "{{route('create_wishlist',':book_id')}}";
                            create_wishlist_url = create_wishlist_url.replace(':book_id',this.product_id);
                        var wishlist_cart_btn = `<a href="`+create_wishlist_url+`?qty=`+this.quantity+`" class="btn secondary-btn create_wishlist_btn" >Add to Wishlist</a>`;
                        }else {
                          var wishlist_url = "{{route('wishlist')}}?t=my_wishlist";
                          var wishlist_cart_btn = `<a href="`+wishlist_url+`" class="btn secondary-btn">View Wishlist</a>`;
                        }

                        var quantity = ` <div class="qty-items qty">
                                  <input type="button" value="-" class="qty-minus update_wishlist_qty" id="`+this.order_item_id+`">
                                  <input type="number" value="`+this.quantity+`" class="qty update_text_wishlist_qty" min="0" data-id="`+this.order_item_id+`" id="quantity_`+this.order_item_id+`">
                                  <input type="button" value="+" class="qty-plus update_wishlist_qty" id="`+this.order_item_id+`">
                                </div> `;
                      }
                      else
                      {
                        if(this.added_to_cart == '0')
                        {
                          /*var wishlist_cart_btn = `<button class="btn secondary-btn add_to_cart_book" data-lang="`+this.language+`" data-book-id="`+this.product_id+`">Add to Cart</button>`;*/
                          var wishlist_cart_btn = `<a href='javascript:void(0)' class="btn secondary-btn again_add_to_cart_book" data-lang="`+this.language+`" data-book-id="`+this.product_id+`">Add to Cart</a>`;

                        }
                        else
                        {
                          var wishlist_cart_btn = `<a href="{{route('my_cart')}}" class="btn secondary-btn">View Cart</a>`;
                        }

                        var quantity = `<div class="qty-items qty added_qty_`+this.product_id+`">
                                  <input type="button" value="-" class="qty-minus update_qty">
                                  <input type="number" value="`+this.quantity+`" id="again_quantity_`+this.product_id+`" class="qty update_qty" min="0">
                                  <input type="button" value="+" class="qty-plus update_qty">
                                </div>`;

                      }
                      $("#buy_order_items").append(`<div class="col-xl-6"> 
                          <div class="product-list-box">
                              <div class="img"><a href="javascript:void(0);" title=""><img src="`+this.cover_image+`" alt=""></a></div>
                              <div class="detail">
                                <h6><a href="javascript:void(0);" title="">`+this.name+`</a></h6> 
                                <div class="out-of-stock">
                                  <div class="sale-price">₹`+this.sale_price+`<span>₹`+this.mrp+`</span></div> 
                                  `+stock_status+`
                                </div>
                                `+quantity+`
                                `+wishlist_cart_btn+`
                                <!--<div id="wishlist_btn">`+wishlist_cart_btn+`</div>-->                 
                              </div>
                            </div>
                        </div>`)

                        if(this.added_to_cart == '1')
                        {
                          $(".added_qty_"+this.product_id).html("Qty. "+this.added_quantity);
                        }

                    });
                    if(order.user_type == "dealer"){
                        if(order.order_summary.delivery_charges != '0'){
                          var delivery_charges = `<div class="title secondary-color">Delivery Charges</div><div class="theme-color">₹ `+order.order_summary.delivery_charges+`</div>`;  
                        }else {
                          var delivery_charges = `<div class="title secondary-color">Delivery Charges</div><div class="theme-color">Freight Charges</div>`;  
                        }
                        
                    }else {
                      var delivery_charges = `<div class="title secondary-color">Delivery Charges</div><div class="theme-color">₹ `+order.order_summary.delivery_charges+`</div>`;
                    }

                    $(".cart-summary").html(`<div class="priceHeader">Price Details (`+order.book_items.length+` Item)</div>
                        <ul>
                          <li><div class="title secondary-color">Total MRP</div><div class="price">₹ `+order.order_summary.total_mrp+`</div></li>              
                          <li><div class="title secondary-color">Discount on MRP</div><div class="green-color">- ₹ `+order.order_summary.discount_on_mrp+`</div></li>               
                          <li>`+delivery_charges+`</li>             
                          <li><div class="title"><b>Total Payable Amount</b></div><div class="price"><b id="total_payable_amount">₹ `+order.order_summary.total_payable+`</b></div></li>
                        </ul>    `);
                  }
                  $('.loader').css('visibility','hidden');

              }
          });


          $(document).on('click','.update_wishlist_qty',function(e){
            e.preventDefault();
            e.stopPropagation();
            var order_item_id = $(this).attr('id');
            update_wishlist_qty(order_item_id);
          });
          $(document).on('input','.update_text_wishlist_qty',function(){
            var order_item_id = $(this).data('id');
            update_wishlist_qty(order_item_id);
          });

          function update_wishlist_qty(order_item_id){
            var quantity = $("#quantity_"+order_item_id).val(); 
            var wishlist_url = $(".create_wishlist_btn").attr('href');
            var url = wishlist_url.split("?");
            url[1] = "?qty="+quantity;
            wishlist_url = url[0]+url[1];
            $(".create_wishlist_btn").attr('href',wishlist_url);
           }

    
          $(document).on('click','.add_to_wishlist_btn',function(){
            var quantity = $(this).data("quantity");
            var book_id = $(this).attr('id');
            $.ajax({
              url: BASE_URL+"wishlist/add_to_wishlist",
              data: {'book_id':book_id,'quantity':quantity},
              type: "POST",
              beforeSend: function(xhr){
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    $('.loader').css('visibility','visible');
              },
              error:function(response){
               if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
              },
              success: function(response) {
                if(response.status == "200") {
                   toastr.success(response.message);
                  //$("#success-wish").modal('show');
                  setTimeout(function(){
                   // location.href = "{{route('wishlist')}}?t=my_wishlist";
                   location.reload();
                  },1000);
                }
                else {
                  toastr.error(response.message);
                }
                $('.loader').css('visibility','hidden');
              }
            });
          });
          
          $(document).on('click','.return_product',function(){
            var total_qty = $(this).data('total-qty');
            var available_return_qty = parseInt($(this).data('available-return-qty'));
            var order_item_id = $(this).data('order-item-id');
            $("#return_book_price_weight").html(`<ul>
              <li><label>Total Qty</label>`+total_qty+`</li>                
              <li><label>Available Return Qty</label><p class="theme-red-color">`+available_return_qty+`</p></li>
            </ul>`);
            $("#order_item_id").val(order_item_id);
          })

          $(document).on('click','.again_add_to_cart_book',function(){
              auth_guard_route(token);
              var item_id = $(this).attr('id');
              var book_id = $(this).data('book-id');
              var quantity = $("#again_quantity_"+book_id).val();
              var language = $(this).data('lang');
              
              $.ajax({
                url: BASE_URL+"books/add_to_cart",
                data: {'book_id':book_id,'quantity':quantity,'language':language},
                type: "POST",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  console.log(response);
                  if(response.status == "200") {
                    toastr.success(response.message);
                    @if(\Request::route()->getName()=="order_details")
                     var view_cart_link = "{{route('my_cart')}}";
                    
                      $('.again_add_to_cart_book[data-book-id='+book_id+']').attr('href',view_cart_link);
                      $('.again_add_to_cart_book[data-book-id='+book_id+']').text('View Cart');
                      $('.again_add_to_cart_book[data-book-id='+book_id+']').removeClass('.add_to_cart_book');
                      $(".added_qty_"+book_id).html("Qty. "+quantity);
                     $("#order-again").modal("show");
                    
                    @else 
                    setTimeout(function(){
                      location.reload();
                    },1000);
                    @endif
                  }
                  else {
                    toastr.error(response.message);
                  }
                  $('.loader').css('visibility','hidden');
                }
              });
          });

          $(document).on('click','.add_to_return_cart',function(){
              auth_guard_route(token);
              var order_item_id = $("#order_item_id").val();
              var return_qty = $(".return_qty").val();
              
              $.ajax({
                url: BASE_URL+"my_return/add_to_cart",
                data: {'order_item_id':order_item_id,'quantity':return_qty},
                type: "POST",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  if(response.status == "200") {
                    /*$("#success-wish").modal('show');
                    setTimeout(function(){
                      location.reload();
                    },1000);*/
                    $("#return-place-success").modal('show');
                  }
                  else {
                    toastr.error(response.message);
                  }
                  $('.loader').css('visibility','hidden');
                }
              });
          });
      @endif
  /* ################# ORDER DETAIL :: SCRIPT ENDS ################# */

  /* ################# ORDER BOOK VIEW :: SCRIPT STARTS ################# */
      @if(\Request::route()->getName() == 'order_book_view')
          var order_item_id = $("#order_item_id").val();
          var book_id = $("#book_id").val();
          var language = "{{ app('request')->input('lang') }}"; 
          var order_item_info;
          function nl2br (str, is_xhtml) {   
              var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
              return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
          }
          get_order_item_info(order_item_id);
          function get_order_item_info(order_item_id)
          {
            $.ajax({
              url: BASE_URL+"order_item_details/"+order_item_id,
              data: { },
              type: "GET",
              beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                $('.loader').css('visibility','visible');

              },
              success: function(response) {

                if(response.data)
                {
                  order_item_info = response.data;
                   // var book = response.data;
                        $.each(order_item_info.cover_images,function(){
                          $('#book_images').slick('slickAdd','<div class="item"><div class="img"><img src="'+this.image+'" alt=""></div></div>');
                        });
                        setTimeout(function(){
                          $('.pro-slide').slick("unslick");
                          sliderInit();
                        },500);

                        $("#name").html(order_item_info.name);
                        $("#sub_heading").html(order_item_info.sub_heading);
                        //$("#description").html(book.description);
                        $("#description").html(nl2br(order_item_info.description));
                        if(!order_item_info.description)
                        {
                          $("#desc_heading").addClass('d-none');
                        }

                        $(".book-price-weight").html(`<ul>
                            <li><label><img class="rupee" src="{{asset('web_assets/images/book-price.svg')}}" alt=""> Book Price</label><div class="price">₹ `+order_item_info.sale_price+`<span>₹ `+order_item_info.mrp+`</span></div></li>                
                            <li><label>Weight</label><p>`+order_item_info.weight+` K.G</p></li>
                          </ul>`);


                        var return_product_btn = '';
                        if(order_item_info.order_status == 'completed')
                        {
                          if(order_item_info.added_to_return_cart == '0')
                          {
                            var CurrentDate = new Date();
                            var dateArr = order_item_info.last_returnable_date.split("-");
                            var last_returnable_date = new Date(dateArr[2]+'-'+dateArr[1]+'-'+dateArr[0]);
                            if(last_returnable_date > CurrentDate)
                            {
                              return_product_btn = `<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#return-prduct" class="btn secondary-btn return_product" data-order-item-id="`+order_item_info.order_item_id+`" data-total-qty="`+order_item_info.quantity+`" data-available-return-qty="`+order_item_info.max_returnable_quantity+`" data>Return Product <i class="icon-reply"></i></a>`;
                            }
                          }
                          else
                          {
                            return_product_btn = `<a href="{{route('return_cart')}}" class="btn green-btn">View Return Cart <i class="icon-bag"></i></a>`;
                          }
                        }
                        
                        $("#return_product_btn").html(return_product_btn);
                        $("#returnable_qty").html(order_item_info.returnable_qty+'% of The Product');
                        $("#last_returnable_date").html(order_item_info.last_returnable_date);

                        //$("#additional_info").html(book.additional_info);
                        $("#additional_info").html(nl2br(order_item_info.additional_info));
                        if(!order_item_info.additional_info)
                        {
                          $("#add_info_heading").addClass('d-none');
                        }
                }
              }
            });
          }
         /* setTimeout(function(){
            $.ajax({
                  url: BASE_URL+"book_details",
                  data: {"book_id" : book_id, "user_id" : userid, "language" : language },
                  type: "POST",
                  beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    $('.loader').css('visibility','visible');
                  },
                  error:function(response){
                   if(response.status == '401'){
                    auth_guard_route(response.status);
                   }
                  },
                  success: function(response) {
                      if(response.status == "200") {

                        var book = response.data;
                        $.each(book.cover_images,function(){
                          $('#book_images').slick('slickAdd','<div class="item"><div class="img"><img src="'+this.image+'" alt=""></div></div>');
                        });
                        setTimeout(function(){
                          $('.pro-slide').slick("unslick");
                          sliderInit();
                        },500);

                        $("#name").html(book.name);
                        $("#sub_heading").html(book.sub_heading);
                        //$("#description").html(book.description);
                        $("#description").html(nl2br(book.description));
                        if(!book.description)
                        {
                          $("#desc_heading").addClass('d-none');
                        }

                        $(".book-price-weight").html(`<ul>
                            <li><label><img class="rupee" src="{{asset('web_assets/images/book-price.svg')}}" alt=""> Book Price</label><div class="price">₹ `+book.sale_price+`<span>₹ `+book.mrp+`</span></div></li>                
                            <li><label>Weight</label><p>`+book.weight+` K.G</p></li>
                          </ul>`);


                        var return_product_btn = '';
                        if(order_item_info.order_status == 'completed')
                        {
                          if(order_item_info.added_to_return_cart == '0')
                          {
                            var CurrentDate = new Date();
                            var dateArr = order_item_info.last_returnable_date.split("-");
                            var last_returnable_date = new Date(dateArr[2]+'-'+dateArr[1]+'-'+dateArr[0]);
                            if(last_returnable_date > CurrentDate)
                            {
                              return_product_btn = `<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#return-prduct" class="btn secondary-btn return_product" data-order-item-id="`+order_item_info.order_item_id+`" data-total-qty="`+order_item_info.quantity+`" data-available-return-qty="`+order_item_info.max_returnable_quantity+`" data>Return Product <i class="icon-reply"></i></a>`;
                            }
                          }
                          else
                          {
                            return_product_btn = `<a href="{{route('return_cart')}}" class="btn green-btn">View Return Cart <i class="icon-bag"></i></a>`;
                          }
                        }
                        
                        $("#return_product_btn").html(return_product_btn);
                        $("#returnable_qty").html(book.returnable_qty+'% of The Product');
                        $("#last_returnable_date").html(book.last_returnable_date);

                        //$("#additional_info").html(book.additional_info);
                        $("#additional_info").html(nl2br(book.additional_info));
                        if(!book.additional_info)
                        {
                          $("#add_info_heading").addClass('d-none');
                        }
                      }
                      $('.loader').css('visibility','hidden');

                  }
            });
          }, 500)*/

          $(document).on('click','.return_product',function(){
            var total_qty = $(this).data('total-qty');
            if($(this).data('available-return-qty') == '')
            {
              var available_return_qty = '0';
            }
            else
            {
              var available_return_qty = parseInt($(this).data('available-return-qty'));
            }
            var order_item_id = $(this).data('order-item-id');
            $("#return_book_price_weight").html(`<ul>
              <li><label>Total Qty</label>`+total_qty+`</li>                
              <li><label>Available Return Qty</label><p class="theme-red-color">`+available_return_qty+`</p></li>
            </ul>`);
            $("#order_item_id").val(order_item_id);
          })

          $(document).on('click','.add_to_return_cart',function(){
              auth_guard_route(token);
              var order_item_id = $("#order_item_id").val();
              var return_qty = $(".return_qty").val();

              
              $.ajax({
                url: BASE_URL+"my_return/add_to_cart",
                data: {'order_item_id':order_item_id,'quantity':return_qty},
                type: "POST",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  if(response.status == "200") {
                    /*$("#success-wish").modal('show');
                    setTimeout(function(){
                      location.reload();
                    },1000);*/
                    $("#return-place-success").modal('show');
                  }
                  else {
                    toastr.error(response.message);
                  }
                  $('.loader').css('visibility','hidden');
                }
              });
          });
          
      @endif
  /* ################# ORDER BOOK VIEW :: SCRIPT ENDS ################# */

  /* ################# RETURN CART :: SCRIPT STARTS ################# */
      @if(\Request::route()->getName() == 'return_cart')
          var return_items = []
          $.ajax({
              url: BASE_URL+"my_return/my_cart",
              data: { },
              type: "GET",
              beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                $('.loader').css('visibility','visible');
              },
              error:function(response){
                if(response.status == '401'){
                    auth_guard_route(response.status);
                }
              },
              success: function(response) {
                  if(response.status == "200") {
                      var return_items_list = '';

                      if(response.data.length == 0)
                      {
                        $(".no-data-found").removeClass('d-none');
                      }
                      else
                      {
                        $(".no-data-found").addClass('d-none');
                        $(response.data).each(function(){
                          return_items_list += `<div class="return-pro-list white-bg">   
                              <div class="top-check-list">
                                <div class="common-check">
                                    <label class="checkbox">
                                       <input type="checkbox" class="checkout_return_items" data-order-return-id="`+this.return_item_id+`" checked><span class="checkmark"></span>
                                    </label>
                                </div> 
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete-confirm" id="`+this.return_item_id+`" class="delete"><i class="icon-close"></i></a>
                              </div>
                              <div class="return-product mb-3">
                                <div class="img"><img src="`+this.image+`" alt=""></div>
                                <div class="details">
                                  <h6 class="mb-4">`+this.name+`</h6>
                                  <div class="box">                  
                                    <div class="list">
                                      <div class="secondary-color">Price</div>
                                      <p>₹ `+this.total_sale_price+`</p> 
                                    </div>
                                    <div class="list">
                                      <div class="secondary-color">Return Qty</div>
                                      <div class="qty-items">
                                        <input type="button" value="-" class="qty-minus update_qty" id="`+this.return_item_id+`">
                                        <input type="number" value="`+this.quantity+`" class="qty" min="0" data-id="`+this.return_item_id+`" id="quantity_`+this.return_item_id+`">
                                        <input type="button" value="+" class="qty-plus update_qty" id="`+this.return_item_id+`">
                                      </div>               
                                    </div>
                                  </div> 
                                </div>  
                              </div> 
                            </div>`;
                          return_items.push(this.return_item_id);
                        })
                        $('#return_products').html(`<div class="section-title justify-content-center"><h2>Return Product Cart</h2></div> 
                            `+return_items_list+`
                            <div class="select-item">`+response.data.length+` Item Selected For Return Product</div>
                            <div class="text-center mt-4">
                              <a href="{{route('make_my_return')}}" class="btn secondary-btn me-1">Add More</a>
                              <a href="javascript:void(0);" id="place_return" class="btn primary-btn ms-1">Place Return</a>
                            </div>`);
                      }
                      
                  }
                  else
                  {
                    if(response.data.length == 0)
                    {
                      $(".no-data-found").removeClass('d-none');
                    }
                  }
                  $('.loader').css('visibility','hidden');

              }
          });
          
          $(document).on("change",".checkout_return_items",function(){
            var order_return_id = $(this).data('order-return-id');
            if(this.checked)
            {
              return_items.push(order_return_id);
            }
            else
            {
              return_items = jQuery.grep(return_items, function(value) {
                return value != order_return_id;
              });
            }
          });

          $(document).on('click','#place_return',function(){
            console.log(return_items)
            $.ajax({
                url: BASE_URL+"my_return/place_order_return",
                data: {"return_items": return_items},
                type: "POST",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  $('.loader').css('visibility','visible');
                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                    if(response.status == "200") {
                      toastr.success(response.message);
                      setTimeout(function(){
                        location.href = "{{route('return_products_list')}}";
                      },1000);
                    }
                    else {
                      toastr.error(response.message);
                    }
                    $('.loader').css('visibility','hidden');

                }
            });
          })

          $(document).on('click','.delete',function(){
             var order_return_id = $(this).attr('id');
             $("#id").val(order_return_id);
          });
        
          $(document).on('click','#remove_item',function(){
            var order_return_id = $("#id").val();
            var quantity = '0';  
            update_quantity(order_return_id,quantity);
          });

          $(document).on('click','.update_qty',function(e){
            e.preventDefault();
            e.stopPropagation();
            var return_item_id = $(this).attr('id');
            var quantity = $("#quantity_"+return_item_id).val(); 
            update_quantity(return_item_id,quantity);
          });

          $(document).on('input','.qty',function(e){
            e.preventDefault();
            e.stopPropagation();
            var return_item_id = $(this).attr('data-id');
            var quantity = $(this).val(); 
            if(quantity > 0)
            {
              update_quantity(return_item_id,quantity);
            }
          });

          function update_quantity(return_item_id,quantity){
            $.ajax({
                url: BASE_URL+"my_return/update_quantity",
                data: {'return_item_id':return_item_id,'quantity':quantity},
                type: "POST",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  if(response.status == "200") {
                    if(quantity == 0){
                      toastr.success('Item removed from the cart');
                    }else {
                      toastr.success(response.message);
                    }
                    setTimeout(function(){
                      location.reload();
                    },1000);

                  }
                  else {
                   toastr.error(response.message);
                   setTimeout(function(){
                      location.reload();
                    },1000);
                  }
                  $('.loader').css('visibility','hidden');
                }
            });
          }
          
      @endif
  /* ################# RETURN CART :: SCRIPT ENDS ################# */

  /* ################# WISHLIST :: SCRIPT STARTS ################# */
      @if(\Request::route()->getName() == 'wishlist')
          auth_guard_route(token);
          var page        = 1;
          var last_page   = '';
          var tab = "{{ app('request')->input('t') }}";

          var type = $("ul#myTab li a.active").attr('id'); // all, my_wish_list, available
          if(tab == 'my_wishlist')
          {
            $("#out_of_stock_wishlist").removeClass('active');
            $("#tab11").removeClass('active');
            $("#tab11").removeClass('show');

            $("#available").removeClass('active');
            $("#tab33").removeClass('active');
            $("#tab33").removeClass('show');

            $("#my_wishlist").addClass('active');
            $("#tab22").addClass('active');
            $("#tab22").addClass('show');

            type = 'my_wishlist';
          }
          else if(tab == 'available')
          {
            $("#out_of_stock_wishlist").removeClass('active');
            $("#tab11").removeClass('active');
            $("#tab11").removeClass('show');            

            $("#my_wishlist").removeClass('active');
            $("#tab22").removeClass('active');
            $("#tab22").removeClass('show');

            $("#available").addClass('active');
            $("#tab33").addClass('active');
            $("#tab33").addClass('show');

            type = 'available';
          }
          get_wishlist(page,type); 
          $("#out_of_stock_wishlist_data").html("");
          $("#my_wishlist_data").html("");
          $("#available_data").html("");
          $(window).scroll(function() { //detect page scroll
                if($(window).scrollTop() + $(window).height() >= $(document).height() - 250) { //if user scrolled from top to bottom of the page
                  if(page < last_page)
                  {
                    page++; //page number increment
                    var type = $("ul#myTab li a.active").attr('id');
                    get_wishlist(page,type); //load content
                  }
                }
          });

          function get_wishlist(page,type)
          {
            if(type == 'out_of_stock_wishlist')
            {
              var url = 'wishlist/out_of_stock_wishlist';
            }
            else
            {
              var url = 'wishlist/retailer_wishlist';
            }
            $.ajax({
                url: BASE_URL+url+"?page="+page,
                data: {"type":type },
                type: "GET",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  $('.loader').css('visibility','visible');
                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                    if(response.status == "200") {
                        if(page == 1)
                        {
                          $("#out_of_stock_wishlist_data").html("");
                          $("#my_wishlist_data").html("");
                          $("#available_data").html("");
                        }
                        if(response.data.length == 0){
                          $(".no-data-found").removeClass('d-none');
                        }
                        else
                        {
                          $(".no-data-found").addClass('d-none');
                          last_page = response.meta.last_page;
                          $.each(response.data,function(){
                            var create_wishlist_url = "{{route('create_wishlist',':book_id')}}";
                            create_wishlist_url = create_wishlist_url.replace(':book_id',this.book_id);

                            if(type == 'out_of_stock_wishlist')
                            {
                              var add_to_wishlist_btn = `<a href="`+create_wishlist_url+`" class="btn secondary-btn create_wishlist_btn">Add to Wishlist</a>`;

                              var wish_detail_img_link = `<a href="`+create_wishlist_url+`" ><img src="`+this.image+`" alt="" class="create_wishlist_btn"></a>`;
                              var wish_detail_name_link = `<a href="`+create_wishlist_url+`" class="create_wishlist_btn" >`+this.name+`</a>`;
                              if(this.in_wishlist == '1')
                              {
                                add_to_wishlist_btn = '';
                                wish_detail_img_link = `<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#wishlist-detail" data-id="`+this.wish_list_id+`" class="wishlist_detail" title=""><img src="`+this.image+`" alt=""></a>`;
                                wish_detail_name_link = `<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#wishlist-detail" class="wishlist_detail" data-id="`+this.wish_list_id+`" title="">`+this.name+`</a>`;
                              }
                              $("#out_of_stock_wishlist_data").append(`<div class="col-xl-4 col-lg-4 col-md-12 col-12"> 
                                  <div class="product-list-box">
                                    <div class="img">`+wish_detail_img_link+`</div>
                                    <div class="detail">
                                      <h6>`+wish_detail_name_link+`</h6> 
                                      <div class="sale-price">₹`+this.sale_price+`<span>₹`+this.mrp+`</span></div> 
                                      <div class="qty-items">
                                        <input type="button" value="-" data-id="`+this.wish_list_id+`" class="qty-minus update_qty">
                                        <input type="number" value="`+this.quantity+`" class="qty" data-id="`+this.wish_list_id+`" min="0" id="wl_quantity_`+this.wish_list_id+`"  >
                                        <input type="button" value="+" data-id="`+this.wish_list_id+`" class="qty-plus update_qty">
                                      </div>
                                      <div class="out-of-stock"> 
                                        <div class="theme-red-color out">`+this.status_label+`</div>
                                          `+add_to_wishlist_btn+`
                                      </div> 
                                    </div>
                                  </div>
                                </div> `);
                            }
                            else if(type == 'my_wishlist')
                            {
                              $("#my_wishlist_data").append(`<div class="col-xl-4 col-lg-4 col-md-12 col-12"> 
                                  <div class="product-list-box">
                                    <div class="img"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#wishlist-detail" class="wishlist_detail" data-id="`+this.wish_list_id+`"  title=""><img src="`+this.image+`" alt=""></a></div>
                                    <div class="detail">
                                      <h6><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#wishlist-detail" class="wishlist_detail" data-id="`+this.wish_list_id+`"  title="">`+this.name+`</a></h6> 
                                      <div class="sale-price">₹`+this.sale_price+`<span>₹`+this.mrp+`</span></div> 
                                      <div class="qty-items">
                                        <input type="button" value="-" data-id="`+this.wish_list_id+`" class="qty-minus update_qty">
                                        <input type="number" value="`+this.quantity+`" class="qty" data-id="`+this.wish_list_id+`" min="0" id="wl_quantity_`+this.wish_list_id+`"  >
                                        <input type="button" value="+" data-id="`+this.wish_list_id+`" class="qty-plus update_qty">
                                      </div> 
                                      <div class="out-of-stock"> 
                                        <div class="theme-red-color out"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#wishlist-detail" class="wishlist_detail theme-color" data-id="`+this.wish_list_id+`"><i class="far fa-edit"></i> Edit</a></div>
                                          
                                      </div> 
                                    </div>
                                  </div>
                                </div> `);
                            }
                            else
                            {
                              $("#available_data").append(`<div class="col-xl-4 col-lg-4 col-md-12 col-12"> 
                                    <div class="product-list-box">
                                      <div class="img"><a href="javascript:void(0);"  data-bs-toggle="modal" data-bs-target="#available-wish-list" class="available_wishlist_detail" data-id="`+this.wish_list_id+`"  title=""><img src="`+this.image+`" alt=""></a></div>
                                      <div class="detail">
                                        <h6><a href="javascript:void(0);"  data-bs-toggle="modal" data-bs-target="#available-wish-list" class="available_wishlist_detail" data-id="`+this.wish_list_id+`" title="">`+this.name+`</a></h6> 
                                        <div class="sale-price">₹`+this.sale_price+`<span>₹`+this.mrp+`</span></div> 
                                        <div class="qty-items">
                                          <input type="text" readonly value="Quantity `+this.quantity+`" class="qty" min="0"  >
                                        </div>
                                        <div class="out-of-stock">                     
                                          <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#confirm-remove" class="theme-red-color text-decoration-underline out mt-2 d-inline-block remove_wishlist" title="" data-id="`+this.wish_list_id+`">Remove</a>
                                        </div>
                                      </div>
                                    </div>
                                  </div> `);
                            }
                          });
                        }
                    }
                    $('.loader').css('visibility','hidden');

                }
            });
          }

          $(document).on('click',"ul#myTab li a",function(){
            type = $("ul#myTab li a.active").attr('id');
            page = 1;
            get_wishlist(page,type);
          });

          $(document).on('click',".wishlist_detail",function(){
            var wish_list_id = $(this).data('id');

            $.ajax({
                url: BASE_URL+"wishlist/wishlist_details",
                data: {"wish_list_id" : wish_list_id},
                type: "POST",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  $('.loader').css('visibility','visible');

                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                    if(response.status == "200") {
                      var wishlist = response.data;
                      
                      $("#book_info").html(`<div class="out-stock">`+wishlist.status_label+`</div>
                          <div class="img"><img src="`+wishlist.image+`" alt=""></div>
                          <div class="detail">
                            <h6>`+wishlist.name+`</h6>
                            <p class="secondary-color mb-0">`+wishlist.sub_heading+`</p>             
                            <div class="sale-price">₹`+wishlist.sale_price+`<span>₹`+wishlist.mrp+`</span></div>
                            <p><span class="secondary-color">Weight: </span>`+wishlist.weight+` K.G Per Book</p>
                          </div>`);
                      $("#wish_qty").html(wishlist.quantity);
                      $("#wish_qty_input").val(wishlist.quantity);
                      if(user_type == 'retailer')
                      {
                        if(wishlist.dealers.length == 0)
                        {
                          $("#wish_dealers_list").addClass('d-none');
                        }
                        else
                        {
                          $("#wish_dealers_list").html("");
                          $.each(wishlist.dealers,function(key, value){
                              var index = key+1;
                              $("#wish_dealers_list").append(`<div class="list">
                                  <div class="secondary-color">Dealer `+index+`</div>
                                  <div class="text"><img src="`+value.profile_image+`" alt="">`+value.first_name+`</div>            
                                </div>`);
                          });
                        }
                      }
                      else
                      {
                        $("#wish_dealers_list").addClass('d-none');
                      }
                      

                      $("#wish_update").attr('data-id',wishlist.wish_list_id);
                    }
                    $('.loader').css('visibility','hidden');

                }
            });
          });

          $(document).on('click',".available_wishlist_detail",function(){
            var wish_list_id = $(this).data('id');

            $.ajax({
                url: BASE_URL+"wishlist/wishlist_details",
                data: {"wish_list_id" : wish_list_id},
                type: "POST",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  $('.loader').css('visibility','visible');
                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                    if(response.status == "200") {
                      var wishlist = response.data;
                      
                      $("#available_book_info").html(`<div class="out-stock">`+wishlist.status_label+`</div>
                          <div class="img"><img src="`+wishlist.image+`" alt=""></div>
                          <div class="detail">
                            <h6>`+wishlist.name+`</h6>
                            <p class="secondary-color mb-0">`+wishlist.sub_heading+`</p>             
                            <div class="sale-price">₹`+wishlist.sale_price+`<span>₹`+wishlist.mrp+`</span></div>
                            <p><span class="secondary-color">Weight: </span>`+wishlist.weight+` K.G Per Book</p>
                          </div>`);
                      $("#available_wish_qty").html(wishlist.quantity);

                      if(user_type == 'retailer')
                      {
                        if(wishlist.dealers.length == 0)
                        {
                          $("#available_wish_dealers_list").addClass('d-none');
                        }
                        $("#available_wish_dealers_list").html("");
                        $.each(wishlist.dealers,function(key, value){
                            var index = key+1;
                            $("#available_wish_dealers_list").append(`<div class="list">
                                <div class="secondary-color">Dealer `+index+`</div>
                                <div class="text"><img src="`+value.profile_image+`" alt="">`+value.first_name+`</div>            
                              </div>`);
                        });
                      }
                      else
                      {
                        $("#available_wish_dealers_list").addClass('d-none');
                      }

                      $("#wish_remove").attr('data-id',wishlist.wish_list_id);
                    }
                    $('.loader').css('visibility','hidden');

                }
            });
          });
          
          
          $(document).on('click',".remove_wishlist",function(){
            var wishlist_id = $(this).data('id');
            $("#remove_wishlist_id").val(wishlist_id);
          });

          $(document).on('click',"#remove_from_wishlist_btn",function(){
            var wishlist_id = $("#remove_wishlist_id").val();
            remove_from_wishlist(wishlist_id)
          });

          $(document).on('click','.update_qty',function(){
             var wish_list_id = $(this).data('id');
             var quantity = $(this).parent().find('.qty').val(); 
             
             if(wish_list_id != '')
             {
               update_quantity(wish_list_id,quantity);
             }
             else
             {
                var create_wishlist_url = $(this).parent().parent().find('.create_wishlist_btn').attr('href').split('?')[0];
                create_wishlist_url = create_wishlist_url+"?qty="+quantity; 
                $(this).parent().parent().find('.create_wishlist_btn').attr('href',create_wishlist_url)
             }
          });

          $(document).on('input','.qty',function(){
             var wish_list_id = $(this).data('id');
             var quantity = $(this).val();
             if(quantity)
             {
                if(wish_list_id != '')
                {
                 update_quantity(wish_list_id,quantity);
                }
                else
                {
                  var create_wishlist_url = $(this).parent().parent().find('.create_wishlist_btn').attr('href').split('?')[0];
                  create_wishlist_url = create_wishlist_url+"?qty="+quantity; 
                  $(this).parent().parent().find('.create_wishlist_btn').attr('href',create_wishlist_url)
                }
             }
             
          });

          function update_quantity(wish_list_id,quantity){
            $.ajax({
                url: BASE_URL+"wishlist/update_quantity",
                data: {'wish_list_id':wish_list_id,'quantity':quantity},
                type: "POST",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  if(response.status == "200") {
                    toastr.success(response.message);
                    $("#wl_quantity_"+wish_list_id).val(quantity);
                  }
                  else {
                   toastr.error(response.message);
                  }
                  $('.loader').css('visibility','hidden');
                }
            });
          }

          function remove_from_wishlist(wish_list_id){
            $.ajax({
                url: BASE_URL+"wishlist/remove_from_wishlist",
                data: {'wish_list_id':wish_list_id},
                type: "POST",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  if(response.status == "200") {
                    toastr.success(response.message);
                    setTimeout(function(){
                      location.href = "{{route('wishlist')}}?t=available";
                    },1000);

                  }
                  else {
                   toastr.error(response.message);
                  }
                  $('.loader').css('visibility','hidden');
                }
            });
          }
      @endif

      @if(\Request::route()->getName() == 'create_wishlist')
          auth_guard_route(token);
          
          //get book details
          var book_id = $("#book_id").val();
          $.ajax({
                url: BASE_URL+"book_details",
                data: {"book_id" : book_id, "user_id" : userid },
                type: "POST",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  $('.loader').css('visibility','visible');

                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                    if(response.status == "200") {
                      var book = response.data;
                      var book_detail_url = "{{route('book_detail',':book_id')}}";
                      book_detail_url = book_detail_url.replace(':book_id',book.book_id);
                      $("#book_details").html(`<div class="out-stock">`+book.stock_status_label+`</div>
                              <div class="img"><a href="`+book_detail_url+`" title=""><img src="`+book.image+`" alt=""></a></div>
                              <div class="detail">
                                <h6><a href="`+book_detail_url+`" title="">`+book.name+`</a></h6>
                                <p class="secondary-color mb-0">`+book.sub_heading+`</p>             
                                <div class="sale-price">₹`+book.sale_price+`<span>₹`+book.mrp+`</span></div>
                                <p><span class="secondary-color">Weight: </span>`+book.weight+` K.G Per Book</p>
                              </div>`)
                    }
                    $('.loader').css('visibility','hidden');

                }
          });

          //get dealers list
          $.ajax({
                url: BASE_URL+"wishlist/retailer_dealer_list",
                data: { },
                type: "GET",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  $('.loader').css('visibility','visible');

                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                    if(response.status == "200") {
                      if(response.data.length > 0)
                      {
                        if(user_type == 'retailer')
                        {
                          $("#select_dealer_dropdown").removeClass('d-none');
                        }
                        $.each(response.data,function(){
                          $("#dealers_list").append(`<div class="list">
                              <label class="radio-box">                    
                                <input type="checkbox" name="dealers[]" data-name="`+this.first_name+`" data-image="`+this.profile_image+`" value="`+this.dealer_id+`"><span class="checkmark"></span>
                                <div class="text"><img src="`+this.profile_image+`" alt="">`+this.first_name+`</div>
                              </label>
                            </div>`);
                        });
                        $("#dealers_list").append(`<div class="text-center mt-4">
                              <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn primary-btn" id="select_dealer_btn">Done</a>
                            </div> `)
                      }
                    }
                    $('.loader').css('visibility','hidden');

                }
          });

          var dealer_ids = [];
          $(document).on('click','#select_dealer_btn',function(){
            var dealers_list = [];

            $("#selected_dealers_list").html("");

            $("input[name='dealers[]']:checked").each(function () {
                var dealer_detail = [];
                dealer_detail['id'] = this.value;
                dealer_detail['name'] = $(this).attr('data-name');
                dealer_detail['image'] = $(this).attr('data-image');
                dealers_list.push(dealer_detail);
            });

            if(dealers_list.length > 0)
            {
              $("#select_dealer_dropdown").addClass('d-none');
              $("#selected_dealers_list").removeClass('d-none');
              $("#add_more_btn").removeClass('d-none');
              dealer_ids = [];
              $.each(dealers_list,function(key, value){
                  var index = key+1;
                  $("#selected_dealers_list").append(`<div class="list">
                      <div class="secondary-color">Dealer `+index+`</div>
                      <div class="text"><img src="`+value.image+`" alt="">`+value.name+`</div>            
                    </div>`);
                  dealer_ids.push(value.id);
              });
            }
            else
            {
              dealer_ids = [];
              dealers_list = [];
              if(user_type == 'retailer')
              {
                $("#select_dealer_dropdown").removeClass('d-none');
              }
              $("#selected_dealers_list").addClass('d-none');
              $("#add_more_btn").addClass('d-none');
            }
          });

          $(document).on('click','#add_to_wishlist_btn',function(){
            var quantity = $("#quantity").val();
            $.ajax({
              url: BASE_URL+"wishlist/add_to_wishlist",
              data: {'book_id':book_id,'quantity':quantity,'dealers':dealer_ids},
              type: "POST",
              beforeSend: function(xhr){
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    $('.loader').css('visibility','visible');
              },
              error:function(response){
               if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
              },
              success: function(response) {
                if(response.status == "200") {
                  // toastr.success(response.message);
                  $("#success-wish").modal('show');
                  setTimeout(function(){
                    location.href = "{{route('wishlist')}}?t=my_wishlist";
                  },1000);
                }
                else {
                  toastr.error(response.message);
                }
                $('.loader').css('visibility','hidden');
              }
            });
          });
      @endif
  /* ################# WISHLIST :: SCRIPT ENDS ################# */

  /* ################# WISH RETURN :: SCRIPT STARTS ################# */
      @if(\Request::route()->getName() == 'wish_return')
          auth_guard_route(token);
          var page        = 1;
          var last_page   = '';
          var category_id = $("li.breadcrumb").last().attr('name');
         /* if(!category_id)
          {
            category_id = $("[name=main_category]:checked").attr('id');
          }*/
          var tab = "{{ app('request')->input('t') }}";
          var type = $("ul#myTab li a.active").attr('id'); // all_wish_return, my_wish_return
          var language = $("ul#myTabLang li a.active").attr('id'); // hindi, english

          if(tab == 'my_wish_return')
          {
            $("#my_wish_return").addClass('active');
            $("#tab22").addClass('active');
            $("#tab22").addClass('show');

            $("#all_wish_return").removeClass('active');
            $("#tab11").removeClass('active');
            $("#tab11").removeClass('show');

            type = 'my_wish_return';
          }
          else if(tab == 'available')
          {
            $("#all_wish_return").addClass('active');
            $("#tab11").addClass('active');
            $("#tab11").addClass('show');           

            $("#my_wish_return").removeClass('active');
            $("#tab22").removeClass('active');
            $("#tab22").removeClass('show');

            type = 'all_wish_return';
          }
          
          get_wish_return(page,type,language,category_id); 
          $("#all_wish_return_data_hindi").html("");
          $("#all_wish_return_data_english").html("");
          $("#my_wish_return_data").html("");
          $(window).scroll(function() { //detect page scroll
                if($(window).scrollTop() + $(window).height() >= $(document).height() - 250) { //if user scrolled from top to bottom of the page
                  if(page < last_page)
                  {
                    page++; //page number increment
                    var type = $("ul#myTab li a.active").attr('id'); // all_wish_return, my_wish_return
                    var language = $("ul#myTabLang li a.active").attr('id'); // hindi, english
                    get_wish_return(page,type,language,category_id);  //load content
                  }
                }
          });

          function get_wish_return(page,type,language,category_id = '')
          {
            
            if(type == 'all_wish_return')
            {
              var url = 'wish_return/all_wish_return_list';
              var request_method = "POST";
            }
            else
            {
              var url = 'wish_return/my_wish_return_list';
              var request_method = "GET";
            }
            $.ajax({
                url: BASE_URL+url+"?page="+page,
                data: {"category_id":category_id, "language":language},
                type: request_method,
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  $('.loader').css('visibility','visible');

                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                    if(response.status == "200") {
                        if(page == 1)
                        {
                          $("#all_wish_return_data_hindi").html("");
                          $("#all_wish_return_data_english").html("");
                          $("#my_wish_return_data").html("");
                        }
                        
                        if(response.data.length == 0){
                          
                          if(type == 'all_wish_return')
                          {
                            if(language == 'hindi')
                            {
                              $("#no_data_hindi").removeClass('d-none');
                            }else if(language == 'all')
                            {
                                $("#no_data_all").removeClass('d-none');
                            }else
                            {
                              $("#no_data_english").removeClass('d-none');
                            }
                          }
                          else
                          {
                            $("#no_data_my_wish_return").removeClass('d-none');
                          }
                        }
                        else
                        {
                          $("#no_data_hindi").addClass('d-none');
                          $("#no_data_english").addClass('d-none');
                          $("#no_data_all").addClass('d-none');
                          $("#no_data_my_wish_return").addClass('d-none');
                          last_page = response.meta.last_page;
                          $.each(response.data,function(){
                            

                            if(type == 'all_wish_return')
                            {
                              var wish_return_product_url = "{{route('wish_return_product',':book_id')}}";
                              wish_return_product_url = wish_return_product_url.replace(':book_id',this.book_id);

                              var wish_return_html = `<div class="col-xl-4 col-lg-4 col-md-12 col-12"> 
                                      <div class="product-list-box">
                                        <div class="img"><img src="`+this.image+`" alt=""></div>
                                        <div class="detail">
                                          <h6>`+this.name+`</h6> 
                                          <div class="sale-price">₹`+this.sale_price+`<span>₹`+this.mrp+`</span></div> 
                                          <div class="qty-items">
                                            <input type="button" value="-" class="qty-minus update_qty">
                                            <input type="number" value="0" class="qty" min="0" >
                                            <input type="button" value="+" class="qty-plus update_qty">
                                          </div> 
                                          <a href="`+wish_return_product_url+`" class="btn secondary-btn wish_return_product_btn">Wish Return <i class="icon-reply"></i></a>
                                        </div>
                                      </div>
                                    </div> `;
                                if(language == 'hindi')
                                {
                                  $("#all_wish_return_data_hindi").append(wish_return_html);
                                }
                                else
                                {
                                  $("#all_wish_return_data_english").append(wish_return_html);
                                }
                            }
                            else
                            {
                              var edit_wish_return_url = "{{route('edit_wish_return',':wish_return_id')}}";
                              edit_wish_return_url = edit_wish_return_url.replace(':wish_return_id',this.wish_return_id);
                              if(this.description != ''){
                                var description = `<div class="list">
                                        <div class="secondary-color">Description</div>
                                        <p>`+this.description+`</p>                
                                      </div>`;
                              }else {
                                var description = '';  
                              }

                              if(this.dealer_name != ''){
                                var dealer = `<div class="list">
                                        <div class="secondary-color">Dealer Name</div>
                                        <p>`+this.dealer_name+`</p>                
                                      </div>`;
                              }else {
                                var dealer = '';  
                              }
                              

                              $("#my_wish_return_data").append(`<div class="col-xl-4 col-lg-4 col-md-12 col-12"> 
                                    <div class="return-pro-list">  
                                      <div class="return-product mb-3">
                                        <div class="img"><img src="`+this.image+`" alt=""></div>
                                        <div class="details">
                                          <h6 class="mb-4">`+this.name+`</h6>
                                          <div class="box">
                                            <div class="list">
                                              <div class="secondary-color">Return Qty</div>
                                              <p>`+this.quantity+`</p>                
                                            </div>
                                            <div class="list">
                                              <div class="secondary-color">Price</div>
                                              <p>₹ `+this.sale_price+`</p> 
                                            </div>
                                          </div> 
                                        </div>  
                                      </div>
                                      `+description+`
                                      `+dealer+`
                                      <a href="`+edit_wish_return_url+`" class="edit"><i class="icon-edit-address"></i><span class="text-decoration-underline">Edit</span></a>  &nbsp;&nbsp;
                                      <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#confirm-remove" class="theme-red-color text-decoration-underline out mt-2 d-inline-block remove_wish_return" title="" data-id="`+this.wish_return_id+`">Remove</a>
                                    </div>
                                  </div> `);
                            }
                          });
                        }
                    }
                    $('.loader').css('visibility','hidden');

                }
            });
          }

          $(document).on('click',"ul#myTab li a",function(){
            type = $("ul#myTab li a.active").attr('id');
            language = $("ul#myTabLang li a.active").attr('id');
            page = 1;
            get_wish_return(page,type,language);
          });

          $(document).on('click',"ul#myTabLang li a",function(){
            type = $("ul#myTab li a.active").attr('id');
            language = $("ul#myTabLang li a.active").attr('id');
            page = 1;
            get_wish_return(page,type,language);
          });

          $(document).on('click','.update_qty',function(){
             var quantity = $(this).parent().find('.qty').val(); 
            var wish_return_product_url = $(this).parent().parent().find('.wish_return_product_btn').attr('href').split('?')[0];
            wish_return_product_url = wish_return_product_url+"?qty="+quantity; 
            $(this).parent().parent().find('.wish_return_product_btn').attr('href',wish_return_product_url)
          });

           $(document).on('input','.qty',function(e){
            e.preventDefault();
            e.stopPropagation();
            var quantity = $(this).parent().find('.qty').val(); 
            if(quantity >=0){
              var wish_return_product_url = $(this).parent().parent().find('.wish_return_product_btn').attr('href').split('?')[0];
              wish_return_product_url = wish_return_product_url+"?qty="+quantity; 
              $(this).parent().parent().find('.wish_return_product_btn').attr('href',wish_return_product_url);
            }
          });

          $(document).on('click',".remove_wish_return",function(){
            var wish_return_id = $(this).data('id');
            $("#remove_wish_return_id").val(wish_return_id);
          });

          $(document).on('click',"#remove_from_wish_return_btn",function(){
            var wish_return_id = $("#remove_wish_return_id").val();
            remove_from_wish_return(wish_return_id)
          });

          function remove_from_wish_return(wish_return_id){
            $.ajax({
                url: BASE_URL+"wish_return/remove_from_wishlist",
                data: {'wish_return_id':wish_return_id},
                type: "POST",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                success: function(response) {
                  if(response.status == "200") {
                    toastr.success(response.message);
                    setTimeout(function(){
                      location.href = "{{route('wish_return')}}?t=my_wish_return";
                    },1000);

                  }
                  else {
                   toastr.error(response.message);
                  }
                  $('.loader').css('visibility','hidden');
                }
            });
          }
      @endif

      @if(\Request::route()->getName() == 'wish_return_product')
          auth_guard_route(token);
          
          var book_id = "{{$book_id}}";
          //get dealers list
          $.ajax({
                url: BASE_URL+"wishlist/retailer_dealer_list",
                data: { },
                type: "GET",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  $('.loader').css('visibility','visible');

                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                    if(response.status == "200") {
                      if(response.data.length > 0)
                      {
                        if(user_type == 'retailer')
                        {
                          $("#select_dealer_dropdown").removeClass('d-none');
                          if(response.data.length == 0)
                          {
                            $("#select_dealer_dropdown").addClass('d-none');
                          }
                        }

                        $.each(response.data,function(){
                          $("#dealers_list").append(`<div class="list">
                              <label class="radio-box">                    
                                <input type="radio" name="dealer_id" data-name="`+this.first_name+`" data-image="`+this.profile_image+`" value="`+this.dealer_id+`"><span class="checkmark"></span>
                                <div class="text"><img src="`+this.profile_image+`" alt="">`+this.first_name+`</div>
                              </label>
                            </div>`);
                        });
                        $("#dealers_list").append(`<div class="text-center mt-4">
                              <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn primary-btn" id="select_dealer_btn">Done</a>
                            </div> `)
                      }
                    }
                    $('.loader').css('visibility','hidden');

                }
          });
          var dealer_id = ''
          $(document).on('click','#select_dealer_btn',function(){
            dealer_id = $('input[name="dealer_id"]:checked').val();
            var dealer_name = $('input[name="dealer_id"]:checked').attr('data-name');
            $("#selected_dealer").html(`<a href="#" title="" data-bs-toggle="modal" data-bs-target="#select-dealer">`+dealer_name+` <img src="{{asset('web_assets/images/right-arrow.svg')}}" alt=""></a>`)
          });

          $(document).on('click','#add_to_wish_return_btn',function(){
            var quantity = $("#quantity").val();
            var description = $("#description").val();
            $.ajax({
              url: BASE_URL+"wish_return/add_to_wish_return",
              data: {'book_id':book_id,'quantity':quantity,'dealer_id':dealer_id,'description':description},
              type: "POST",
              beforeSend: function(xhr){
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    $('.loader').css('visibility','visible');
              },
              error:function(response){
               if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
              },
              success: function(response) {
                if(response.status == "200") {
                  // toastr.success(response.message);
                  $("#success-wish").modal('show');
                  setTimeout(function(){
                    location.href = "{{route('wish_return')}}?t=my_wish_return";
                  },1000);
                }
                else if(response.status == '201'){
                  toastr.error(response.message);
                /*  setTimeout(function(){
                    location.href = "{{route('wish_return')}}?t=my_wish_return";
                  },1000);*/
                }
                else {
                  toastr.error(response.message);
                }
                $('.loader').css('visibility','hidden');
              }
            });
          });
      @endif

      @if(\Request::route()->getName() == 'edit_wish_return')
          auth_guard_route(token);
          
          var wish_return_id = "{{$wish_return_id}}";
          //get dealers list
          $.ajax({
                url: BASE_URL+"wish_return/wish_return_details",
                data: { 'wish_return_id':wish_return_id },
                type: "POST",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  $('.loader').css('visibility','visible');

                },
                error:function(response){
                 if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                    if(response.status == "200") {
                      $("#quantity").val(response.data.quantity);
                      $("#description").html(response.data.description);
                    }
                    $('.loader').css('visibility','hidden');

                }
          });
          

          $(document).on('click','#update_wish_return_btn',function(){
            var quantity = $("#quantity").val();
            var description = $("#description").val();
            var wish_return_id = "{{$wish_return_id}}";
            $.ajax({
              url: BASE_URL+"wish_return/edit_wish_return_item",
              data: {'wish_return_id':wish_return_id,'quantity':quantity,'description':description},
              type: "POST",
              beforeSend: function(xhr){
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    $('.loader').css('visibility','visible');
              },
              error:function(response){
               if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
              },
              success: function(response) {
                if(response.status == "200") {
                  // toastr.success(response.message);
                  $("#success-wish").modal('show');
                  setTimeout(function(){
                    location.href = "{{route('wish_return')}}?t=my_wish_return";
                  },1000);
                }
                else {
                  toastr.error(response.message);
                }
                $('.loader').css('visibility','hidden');
              }
            });
          });
      @endif
  /* ################# WISH RETURN :: SCRIPT ENDS ################# */
   
  /* ################# RETAILER WISH LIST (FOR DEALER) :: SCRIPT STARTS ################# */
  @if(\Request::route()->getName() == 'retailer_wish_list')
      var page = 1;
      var last_page   = '';
      load_more(page); 
      $("#request_wishlist").html('');
      $(window).scroll(function() { //detect page scroll
            if($(window).scrollTop() + $(window).height() >= $(document).height() - 250) { //if user scrolled from top to bottom of the page
                if(page < last_page)
                {
                  page++; //page number increment
              		load_more(page); //load content
              	}
            }
      });
      function load_more(page,search)
      {
          $.ajax({
            url: BASE_URL+"wishlist/dealer_wishlist_requests?page="+page,
            data: { },
            type: "POST",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
            },
            error:function(response){
               if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
            },
            success: function(response) {
                if(response.status == "200") {
                 var html ='';
                 $.each(response.data,function(){
                    var show_page = "{{route('request_wishlist_detail',':wish_list_request_id')}}";
                    show_page = show_page.replace(':wish_list_request_id',this.wish_list_request_id);
                    html+= `<div class="col-xl-6 col-lg-6 col-md-12 col-12">
                            <a href="`+show_page+`" title="">
                              <div class="order-list">              
                                <div class="head">
                                  <p><label>Retailer Name:</label>`+this.retailer_name+`</p>                                
                                  <p><label>Book Name:</label>`+this.product_name+`</p>
                                </div>
                                <div class="details"> 
                                  <ul>
                                    <li><label>Date</label>`+this.date+`</li>
                                    <li><label>Time</label>`+this.time+`</li>
                                    <li><label>Wish Quantity</label>`+this.quantity+`</li>
                                  </ul>
                                </div>
                              </div>
                            </a>
                          </div>`;
                 });
                 if(html == ''){
                  $(".no-data-found").removeClass('d-none');
                 }else {
                  $(".no-data-found").addClass('d-none');
                  $("#request_wishlist").append(html);
                 }
                  
                } 
                else {
                  toastr.error(response.message);
                }
            }
          });
      }

      $(document).on('keydown','.searchbox',function(){
        var searchQuery = $(".searchbox").val();
           $.ajax({
              url: BASE_URL+"wishlist/dealer_wishlist_requests",
              data: { 'search':searchQuery },
              type: "POST",
              beforeSend: function(xhr){
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              },
              success: function(response) {
                  if(response.status == "200") {
                   var html ='';
                   $.each(response.data,function(){
                      var show_page = "{{route('request_wishlist_detail',':wish_list_request_id')}}";
                      show_page = show_page.replace(':wish_list_request_id',this.wish_list_request_id);
                      html+= `<div class="col-xl-6 col-lg-6 col-md-12 col-12">
                              <a href="`+show_page+`" title="">
                                <div class="order-list">              
                                  <div class="head">
                                    <p><label>Retailer Name:</label>`+this.retailer_name+`</p>                                
                                    <p><label>Book Name:</label>`+this.product_name+`</p>
                                  </div>
                                  <div class="details"> 
                                    <ul>
                                      <li><label>Date</label>`+this.date+`</li>
                                      <li><label>Time</label>`+this.time+`</li>
                                      <li><label>Wish Quantity</label>`+this.quantity+`</li>
                                    </ul>
                                  </div>
                                </div>
                              </a>
                            </div>`;
                   });
                   if(html == ''){
                     $(".no-data-found").removeClass('d-none');
                  }else {
                    $(".no-data-found").addClass('d-none');
                    $("#request_wishlist").html(html);
                  }
                  } 
                  else {
                    toastr.error(response.message);
                  }
              }
          });
        
      });
  @endif

  @if(\Request::route()->getName() == 'request_wishlist_detail')

      var wish_list_request_id = $("#hdn_wishlist_request_id").val();
      $.ajax({
        url: BASE_URL+"wishlist/dealer_wishlist_request_details",
        data: { 'wish_list_request_id':wish_list_request_id },
            type: "POST",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              xhr.setRequestHeader('Authorization', 'Bearer '+token);
              $('.loader').css('visibility','visible');
        },
        error:function(response){
          if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
        },
        success: function(response) {
           if(response.status == "200") {
            $("#retailer_name").text(response.data.retailer_name);
            $("#date").text(response.data.date);
            $("#time").text(response.data.time);
            $("#full_name").text(response.data.full_name);
            $("#contact_number").text(response.data.contact_number);
            $("#product_name").text(response.data.product_name);
            $("#sale_price").text(response.data.sale_price);
            $("#product_img").attr('src',response.data.product_image);
            $("#qty").text(response.data.quantity);
            $("#wish_quantity").text(response.data.quantity);

            var address = '-'
            if(response.data.postcode)
            {
              address = response.data.house_no+", "+response.data.street+", "+response.data.landmark+", "+response.data.area+", "+response.data.city+", "+response.data.state+" - "+response.data.postcode;
            }
            $("#address").text(address);

           }
           else {
            toastr.error(response.message);
           }
            $('.loader').css('visibility','hidden');
          }
      });
  @endif
  /* ################# RETAILER WISH LIST (FOR DEALER) :: SCRIPT ENDS ################# */

  /*################ RETAILER WISH RETURN (FOR DEALER) :: SCRIPT STARTS ################ */
  @if(\Request::route()->getName() == 'retailer_wish_return')
      var page = 1;
      var last_page = '';
      load_more(page); 
      $("#request_wishreturn").html('');
      $(window).scroll(function() { //detect page scroll
            if($(window).scrollTop() + $(window).height() >= $(document).height() - 250) { //if user scrolled from top to bottom of the page
                if(page < last_page)
                {
                  page++; //page number increment
              		load_more(page); //load content
              	}
            }
      });
      function load_more(page,search)
      {
          $.ajax({
            url: BASE_URL+"wish_return/dealer_wish_return_requests?page="+page,
            data: {},
            type: "POST",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
            },
            error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
            },
            success: function(response) {
                if(response.status == "200") {
                 var html ='';
                 $.each(response.data,function(){
                    var show_page = "{{route('request_wishreturn_detail',':wishreturn_request_id')}}";
                    show_page = show_page.replace(':wishreturn_request_id',this.wish_return_id);
                    html+= ` <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                              <a href="`+show_page+`" title="">
                                <div class="order-list">              
                                  <div class="head">
                                    <p><label>Retailer Name:</label>`+this.retailer_name+`</p>
                                    <p><label>Book Name:</label>`+this.product_name+`</p>          
                                  </div>
                                  <div class="details"> 
                                    <ul>
                                      <li><label>Date</label>`+this.date+`</li>
                                      <li><label>Time</label>`+this.time+`</li>
                                      <li><label>Wish Quantity</label>`+this.quantity+`</li>
                                    </ul>
                                  </div>
                                </div>
                              </a>
                            </div>`;
                 });
                  if(html == ''){
                     $(".no-data-found").removeClass('d-none');
                  }else {
                    $(".no-data-found").addClass('d-none');
                    $("#request_wishreturn").append(html);
                  }
                } 
                else {
                  toastr.error(response.message);
                }
            }
          });
      }

      $(document).on('keydown','.searchbox',function(){
        var searchQuery = $(".searchbox").val();
        $.ajax({
            url: BASE_URL+"wish_return/dealer_wish_return_requests",
            data: { 'search':searchQuery },
            type: "POST",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
            },
            error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
            },
            success: function(response) {
                if(response.status == "200") {
                 var html ='';
                 $.each(response.data,function(){
                    var show_page = "{{route('request_wishreturn_detail',':wishreturn_request_id')}}";
                    show_page = show_page.replace(':wishreturn_request_id',this.wish_return_id);
                    html+= ` <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                              <a href="`+show_page+`" title="">
                                <div class="order-list">              
                                  <div class="head">
                                    <p><label>Retailer Name:</label>`+this.retailer_name+`</p>
                                    <p><label>Book Name:</label>`+this.product_name+`</p>          
                                  </div>
                                  <div class="details"> 
                                    <ul>
                                      <li><label>Date</label>`+this.date+`</li>
                                      <li><label>Time</label>`+this.time+`</li>
                                      <li><label>Wish Quantity</label>`+this.quantity+`</li>
                                    </ul>
                                  </div>
                                </div>
                              </a>
                            </div>`;
                 });

                if(html == ''){
                     $(".no-data-found").removeClass('d-none');
                  }else {
                    $(".no-data-found").addClass('d-none');
                    $("#request_wishreturn").html(html);
                  }
                } 
                else {
                  toastr.error(response.message);
                }
            }
          });
      }); 
  @endif

  @if(\Request::route()->getName() == 'request_wishreturn_detail')

      var wishreturn_request_id = $("#hdn_wishreturn_request_id").val();
      $.ajax({
        url: BASE_URL+"wish_return/dealer_wishlist_request_details",
        data: { 'wish_return_id':wishreturn_request_id },
            type: "POST",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              xhr.setRequestHeader('Authorization', 'Bearer '+token);
              $('.loader').css('visibility','visible');
        },
        error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
        },
        success: function(response) {
          console.log(response);
           if(response.status == "200") {
            $("#retailer_name").text(response.data.retailer_name);
            $("#date").text(response.data.date);
            $("#wish_quantity").text(response.data.quantity);
            $("#time").text(response.data.time);
            $("#full_name").text(response.data.full_name);
            $("#contact_number").text(response.data.contact_number);
            $("#product_name").text(response.data.product_name);
            $("#sale_price").text(response.data.sale_price);
            $("#product_img").attr('src',response.data.product_image);
            $("#qty").text(response.data.quantity);
            var address = '-'
            if(response.data.postcode)
            {
              address = response.data.house_no+", "+response.data.street+", "+response.data.landmark+", "+response.data.area+", "+response.data.city+", "+response.data.state+" - "+response.data.postcode;
            }
            $("#address").text(address);

           }
           else {
            toastr.error(response.message);
           }
            $('.loader').css('visibility','hidden');
          }
      });
  @endif
  /*################ RETAILER WISH RETURN (FOR DEALER) :: SCRIPT ENDS ################ */

  /*################  RETURN PRODUCT :: SCRIPT STARTS ################ */
  @if(\Request::route()->getName() == 'return_products_list')

	    var page = 1;
      var last_page = '';
	    load_more(page);
	    $("#return_products_list").html('');
	    $(window).scroll(function() { //detect page scroll
	            if($(window).scrollTop() + $(window).height() >= $(document).height() - 250) { //if user scrolled from top to bottom of the page
	                if(page < last_page)
	                {
    	              page++; //page number increment
	              		load_more(page); //load content
	              	}
	            }
	    });

	    function load_more(page,status)
	    {
        $.ajax({
	        url: BASE_URL+"my_return/return_orders_list?page="+page,
	        data: { 'status':status },
	            type: "POST",
	            beforeSend: function(xhr){
	              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
	              xhr.setRequestHeader('Authorization', 'Bearer '+token);
	              $('.loader').css('visibility','visible');
	        },
          error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
          },
	        success: function(response) {
	          console.log(response);
	           if(response.status == "200") {
	            var span_class = '';
	            var html ='';
	            var show_page = '';
              if(page == 1)
              {
                $("#return_products_list").html("");
              }
              if(response.data.length == 0)
              {
                $(".no-data-found").removeClass('d-none');
                //$("#return_filter").addClass('d-none');
              }
              else
              {
                $(".no-data-found").addClass('d-none');
                //$("#return_filter").removeClass('d-none');
                $.each(response.data, function(){
                  if(this.status == 'in_review')
                  { 
                    span_class ='orange-color';  
                    show_page = "{{route('return_placed',':id')}}";
                    show_page = show_page.replace(':id',this.order_return_id);
                  }
                  else if(this.status == 'return_placed')
                  { 
                    span_class ='theme-color';  
                    show_page = "{{route('return_placed',':id')}}";
                    show_page = show_page.replace(':id',this.order_return_id);
                  }
                  else if(this.status == 'dispatched')
                  {  
                    span_class ='text-warning';  
                    show_page = "{{route('return_dispatched',':id')}}";
                    show_page = show_page.replace(':id',this.order_return_id);
                  }
                  else if(this.status == 'rejected')
                  { 
                    span_class ='theme-red-color';  
                    show_page = "{{route('return_rejected',':id')}}";
                    show_page = show_page.replace(':id',this.order_return_id);
                  }
                  else if(this.status == 'accepted')
                  {
                    span_class ='green-color';  
                    show_page = "{{route('return_accepted',':id')}}";
                    show_page = show_page.replace(':id',this.order_return_id);
                  }
                  //else  {  span_class ='theme-color';  }
                  html += `  <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                            <a href="`+show_page+`" title="">
                              <div class="order-list">
                                <div class="head">
                                  <p><label>Order ID:</label>`+this.order_return_id+`</p>
                                  <div><span class="`+span_class+`">`+this.status_label+`</span></div>
                                </div>    
                                <div class="details"> 
                                  <ul>
                                    <li><label>Return Date</label>`+this.return_date+`</li>
                                    <li><label>Total Return Qty</label>`+this.total_return_quantity+`</li>
                                    <li><label>Total Price</label>₹`+this.total_sale_price+`</li>
                                  </ul>
                                </div>
                              </div>
                            </a>
                          </div>`;
                });
                $("#return_products_list").append(html);
              }
	            
	           }
	           else {
	            toastr.error(response.message);
	           }
	            $('.loader').css('visibility','hidden');
	          }
	      });
	    }

	    $(document).on('click','#clear_all',function(){
	    		$('input[name="order_type"]:checked').removeAttr('checked');
          page = 1;
          load_more(page);
          $("#filter-modal").modal("hide");
	    	/*	setTimeout(function(){
	    			location.reload();
	    		},1000);*/
	    });

	    $(document).on('click','#apply_filter',function(){
	      var status = [];
	      $("input[name=order_type]:checked").each(function(i){
	        status[i] = $(this).attr('value');
	      });
	      if(status !='undefined' || status !='') {
              load_more('1',status);
              $("#filter-modal").modal("hide");
        }
       
	    });
  @endif

  @if(\Request::route()->getName() == 'return_dispatched')

      var order_return_id = $("#hdn_order_return_id").val();
      $.ajax({
        url: BASE_URL+"my_return/order_return_details",
        data: { 'order_return_id':order_return_id },
            type: "POST",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              xhr.setRequestHeader('Authorization', 'Bearer '+token);
              $('.loader').css('visibility','visible');
        },
        error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
        },
        success: function(response) {
           console.log(response);

           if(response.status == "200") {
            $("#order_id").text(response.data.order_return_id);
            $("#date").text(response.data.return_date);
            $("#status").text(response.data.status_label);
            $("#return_qty").text(response.data.total_return_quantity);
            $("#total_sale_price").text(response.data.total_sale_price);
            var html = '';
            $.each(response.data.return_items,function(){
              var returnable_price = this.total_sale_price;

              if(this.return_sale_price > 0)
              {
                returnable_price = this.return_sale_price
              }
              html+=`<div class="return-product mb-3">
                      <div class="img"><img src="`+this.image+`" alt=""></div>
                      <div class="details">
                        <h6 class="mb-4">`+this.name+`</h6>
                        <div class="box">
                          <div class="list">
                            <div class="secondary-color">Return Qty</div>
                            <p>`+this.total_quantity+`</p>                
                          </div>
                          <div class="list">
                            <div class="secondary-color">Price</div>
                            <p>₹ `+ returnable_price +`</p> 
                          </div>
                        </div> 
                      </div>  
                    </div>`;
              });
              $(".return-pro-list").append(html);
           }
           else {
            toastr.error(response.message);
           }
           $('.loader').css('visibility','hidden');
        }
      });
  @endif

  @if(\Request::route()->getName() == 'return_accepted')

      var order_return_id = $("#hdn_order_return_id").val();
      $.ajax({
        url: BASE_URL+"my_return/order_return_details",
        data: { 'order_return_id':order_return_id },
            type: "POST",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              xhr.setRequestHeader('Authorization', 'Bearer '+token);
              $('.loader').css('visibility','visible');
        /*      $('#preloader').removeClass('d-none');
              $('#preloader').addClass('d-flex');
        */},
        error:function(response){
          if(response.status == '401'){
                    auth_guard_route(response.status);
          }
        },
        success: function(response) {
           console.log(response);

           if(response.status == "200") {
            $("#order_id").text(response.data.order_return_id);
            $("#date").text(response.data.return_date);
            $("#status").text(response.data.status_label);
            $("#return_qty").text(response.data.total_return_quantity);
            $("#return_time").text(response.data.return_time);
            var html = '';
            $.each(response.data.return_items,function(){
              html+=`  <div class="return-product mb-3">
                        <div class="img"><img src="`+this.image+`" alt=""></div>
                        <div class="details">
                          <h6 class="mb-4">`+this.name+`</h6>
                          <div class="box">
                            <div class="list">
                              <div class="secondary-color">Return Qty</div>
                              <p>`+this.total_quantity+`</p>                
                            </div>
                            <div class="list">
                              <div class="secondary-color">Price</div>
                              <p>₹ `+this.total_sale_price+`</p> 
                            </div>
                          </div> 
                        </div>  
                      </div> 
                      <div class="item-accepte">
                      <ul>
                        <li><label>Accepted Items</label><div>`+this.accepted_quantity+`</div></li>
                        <li><label>Refused Items</label><div class="theme-red-color">`+this.rejected_quantity+`</div></li>
                        <li><label>Returnable Price</label><div class="green-color">₹ `+this.return_sale_price+`</div></li>
                      </ul>
                    </div>`;
              });
              $(".return-pro-list").append(html);
           }
           else {
            toastr.error(response.message);
           }
            $('.loader').css('visibility','hidden');
          /*$('#preloader').addClass('d-none');
          $('#preloader').removeClass('d-flex');*/
        }
      });
  @endif

  @if(\Request::route()->getName() == 'return_rejected')

      var order_return_id = $("#hdn_order_return_id").val();
      $.ajax({
        url: BASE_URL+"my_return/order_return_details",
        data: { 'order_return_id':order_return_id },
            type: "POST",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              xhr.setRequestHeader('Authorization', 'Bearer '+token);
              $('.loader').css('visibility','visible');
        },
        error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
        },
        success: function(response) {
           console.log(response);

           if(response.status == "200") {
            $("#order_id").text(response.data.order_return_id);
            $("#date").text(response.data.return_date);
            $("#status").text(response.data.status_label);
            $("#return_qty").text(response.data.total_return_quantity);
            $("#total_sale_price").text(response.data.total_sale_price);
            var html = '';
            $.each(response.data.return_items,function(){
              html+=`<div class="return-product mb-3">
                      <div class="img"><img src="`+this.image+`" alt=""></div>
                      <div class="details">
                        <h6 class="mb-4">`+this.name+`</h6>
                        <div class="box">
                          <div class="list">
                            <div class="secondary-color">Return Qty</div>
                            <p>`+this.total_quantity+`</p>                
                          </div>
                          <div class="list">
                            <div class="secondary-color">Price</div>
                            <p>₹ `+this.total_sale_price+`</p> 
                          </div>
                        </div> 
                      </div>  
                    </div> 
                    <div class="item-accepte">
                    <ul>
                      <li><label>Accepted Items</label><div>`+this.accepted_quantity+`</div></li>
                      <li><label>Refused Items</label><div class="theme-red-color">`+this.rejected_quantity+`</div></li>
                      <li><label>Returnable Price</label><div class="green-color">₹ `+this.return_sale_price+`</div></li>
                    </ul>
                  </div>`;
              });
              $(".return-pro-list").append(html);
           }
           else {
            toastr.error(response.message);
           }
            $('.loader').css('visibility','hidden');
        }
      });
  @endif

  @if(\Request::route()->getName() == 'return_placed')

      var order_return_id = $("#hdn_order_return_id").val();
      $.ajax({
        url: BASE_URL+"my_return/order_return_details",
        data: { 'order_return_id':order_return_id },
            type: "POST",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              xhr.setRequestHeader('Authorization', 'Bearer '+token);
              $('.loader').css('visibility','visible');
        },
        error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
        },
        success: function(response) {
           console.log(response);

           if(response.status == "200") {
            $("#order_id").text(response.data.order_return_id);
            $("#date").text(response.data.return_date);
            $("#status").text(response.data.status_label);
            $("#return_qty").text(response.data.total_return_quantity);
            $("#total_sale_price").text(response.data.total_sale_price);
            var html = '';
            $.each(response.data.return_items,function(){
              html+=`<div class="return-product mb-3">
                      <div class="img"><img src="`+this.image+`" alt=""></div>
                      <div class="details">
                        <h6 class="mb-4">`+this.name+`</h6>
                        <div class="box">
                          <div class="list">
                            <div class="secondary-color">Return Qty</div>
                            <p>`+this.total_quantity+`</p>                
                          </div>
                          <div class="list">
                            <div class="secondary-color">Price</div>
                            <p>₹ `+this.total_sale_price+`</p> 
                          </div>
                        </div> 
                      </div>  
                    </div>`;
              });
              $(".return-pro-list").append(html);
           }
           else {
            toastr.error(response.message);
           }
            $('.loader').css('visibility','hidden');
        }
      });

      $(document).on("click",".dispatch",function(){
        var id = $(this).attr('id');
        $("#order_return_id").val(id);
      });


    $("#receipt_image").on('change',function(){
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
               $("#receipt_image_preview").html('<div class="img"><img src="'+e.target.result+'"/><a href="javascript:void(0)" class="close remove_preview">×</a>Image 1</div>');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
    $(document).on('click','.remove_preview',function(){
      $("#receipt_image_preview").html('');
       $("#receipt_image_preview").attr('src','');
    });



      $("#orderReturnDispatchForm").on('submit',function(e){
          e.preventDefault();
          //var form = $("#orderReturnDispatchForm")[0];
          var formData = new FormData($(this)[0]);
         // formData = formData.replaceAll("<\/script>", "");
          $.ajax({
          url: BASE_URL+"my_return/dispatch_order_return",
          data: formData,
          type: "POST",
          processData:false,
          cache:false,
          contentType:false,
          // async:false,
          beforeSend: function(xhr){
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              xhr.setRequestHeader('Authorization', 'Bearer '+token);
              $('.loader').css('visibility','visible');
              $('#preloader').removeClass('d-none');
              $('#preloader').addClass('d-flex');
          },
          error:function(response){
            if(response.status == '401'){
              auth_guard_route(response.status);
            }
          },
          success: function(response) {
            if(response.status == "200") {
              toastr.success(response.message);
              setTimeout(function(){
                location.href = "{{route('return_products_list')}}";
              },1000);
            }
            else {
              toastr.error(response.message);
            }
               $('#preloader').addClass('d-none');
              $('#preloader').removeClass('d-flex');
            
          }
        });
      });
  @endif
  /*################ RETURN PRODUCT :: SCRIPT ENDS ################ */
  
  /*################ NESTED CATEGORIES ::SCRIPT STARTS ##############*/
  @if(\Request::route()->getName()=='suggestions' || \Request::route()->getName() =='latest_digital_coupons' || \Request::route()->getName() =='books_list' || \Request::route()->getName() =='wish_return')
      var active_tab = '';
      var tab_changed = 0;
      active_tab = $("ul#myTabLang li a.active").attr('id');
      if($("#business_category_id").val() != undefined)
      {
         var business_category = $("#business_category_id").val();   
      }
      else {
        var business_category = '';
      }
      var call_action = '';
      @if(\Request::route()->getName()=='suggestions')
        $("#product_list").html("");
        call_action = 'suggestions';
      @elseif(\Request::route()->getName()=='latest_digital_coupons')
        $("#digital_coupons_list").html('');
        call_action = 'latest_digital_coupons';
      @elseif(\Request::route()->getName()=='books_list' || \Request::route()->getName()=='make_my_return')
        $('#books_list').html("");
        $('#books_list_hindi').html("");
        $('#books_list_all').html("");
        call_action = 'books_list';
      @elseif(\Request::route()->getName()=='wish_return')
        var tab = "{{ app('request')->input('t') }}";
        if(tab != 'my_wish_return')
        {
          $('#all_wish_return_data_hindi').html("");
          $('#all_wish_return_data_english').html("");
          call_action = 'wish_return';
        }
        
      @endif

      //get_main_categories('',active_tab);
      get_main_categories();

      $(document).on('change',"#myTabContent ul.main-cat li",function(){
         var type = $("ul#myTab li a.active").attr('id');
           var language = $("ul#myTabLang li a.active").attr('id');
          /*  var category_id = $("li.breadcrumb").last().attr('name');
            tab_changed = 1;
            $("[class*=sub_cat_]").remove();
           // $("[class*=selected_cat_]").remove();
            get_main_categories();
            if(!category_id)
            {
              category_id = $("li.breadcrumb").first().attr('id');
            }
            page = 1;*/
            var language = $("ul#myTabLang li a.active").attr('id');
            var category_id = $("li.breadcrumb").last().attr('name');
            $("[class*=sub_cat_]").remove();
           // $("[class*=selected_cat_]").remove();
           // get_main_categories();
            if(!category_id)
            {
              category_id = $("li.breadcrumb").first().attr('id');
            }
            page = 1;

            if(call_action == 'suggestions') { 
              load_more(page,category_id,language);
            }
            else if(call_action == 'latest_digital_coupons'){
              load_more_coupons(page,category_id);
            }
            else if(call_action == 'books_list'){
              load_more_books(page,category_id,language);
            }
            else if(call_action == 'wish_return'){
              get_wish_return(page,'all_wish_return',language,category_id);
            }
      });

      $(document).on('click',"ul#myTabLang li a",function(){
            var type = $("ul#myTab li a.active").attr('id');
            var language = $("ul#myTabLang li a.active").attr('id');
            var category_id = $("li.breadcrumb").last().attr('name');
            tab_changed = 1;
            $("[class*=sub_cat_]").remove();
           // $("[class*=selected_cat_]").remove();
            get_main_categories();
            if(!category_id)
            {
              category_id = $("li.breadcrumb").first().attr('id');
            }
            page = 1;
            if(call_action == 'suggestions') { 
              load_more(page,category_id,language);
            }
            else if(call_action == 'latest_digital_coupons'){
              load_more_coupons(page,category_id);
            }
            else if(call_action == 'books_list'){
              load_more_books(page,category_id,language);
            }
            else if(call_action == 'wish_return'){
              get_wish_return(page,'all_wish_return',language,category_id);
            }
      });

      function get_breadcrumb()
      {
            //active_tab = $("ul#myTab li a.active").attr('id');
            
            var category_id = $("input[name='category']:checked").attr('id');
            var category_name = $("input[name='category']:checked").closest('li').find('.checkmark').text();
            var class_name = $("input[name='category']:checked").closest('li').find('.change-text').attr('class');
            if(class_name){
              class_name = class_name.replace('change-text ','');
            }
            var breadcrumb = '';
            breadcrumb += `<li class="breadcrumb selected_cat_`+category_id+`" id="`+class_name+`" name="`+category_id+`"><div class="tag-box"><label class="slot-box"><input class="change-text selected_cat_`+category_id+`"  name="category" id="`+category_id+`" type="radio" selected="true"><span class="checkmark" style="border-color: #E8E8E8;  background: #E8E8E8;  color: #000;">`+category_name+` <i class="fa fa-window-close close_level" id="`+category_id+`"></i></span></label></div></li>`;

            if($(':radio[name=category][class*=selected_cat_'+category_id+']', '.selected_category').length)
            {
              $("li.selected_cat_"+category_id).nextAll().remove();
              $('.selected_category').find("li.selected_cat_"+category_id).remove();
              $('.selected_category').append(breadcrumb);
            }
            else
            {
              $('.selected_category').append(breadcrumb);
            } 
      }

      $(document).on('click','.close_level',function(e){
            var id = $(this).attr('id');
            var language = $("ul#myTabLang li a.active").attr('id');
            // e.preventDefault();
            var prev_id_class = $("li.selected_cat_"+id).prev().attr('id');
            var prev_id = $("li.selected_cat_"+id).prev().attr('name');
	   	     // var cat_id = $("input[name='main_category']:checked").attr('id');
            if(prev_id_class == undefined){
              $("[class*=sub_cat_]").remove();
              tab_changed = 0;
              get_main_categories();
            }else {
              eval(prev_id_class+'('+prev_id+')');
            }
         
            $("li.selected_cat_"+id).nextAll().remove();
            $('.selected_category').find("li.selected_cat_"+id).remove();
      });
  
      // get main categories
      function get_main_categories(category_id) {
         var token =  localStorage.getItem('user_token');
          //var language  = active_tab;
          var language = $("ul#myTabLang li a.active").attr('id');
       
           $.ajax({
              url: BASE_URL+"nested_categories/"+business_category+"?lang="+language,
              data: { },
              type: "get",
              beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              },
              success: function(response) {
                if(response.status == "200") {
                          var category = "";
                          category = '<ul class="main-cat">';
                              $.each( response.data, function( index, value ){
                              category +=
                                  `<li>
                                      <div class="tag-box">
                                          <label class="slot-box">
                                          <input class="change-text sub_category_1" name="main_category" id="`+value.category_id+`" type="radio"><span class="checkmark">`+value.category_name+`</span>
                                          </label>
                                      </div>
                                  </li>`
                              });
                              category += `</ul>`;
                              $("#main_tag_list").html(category);
                             //  var active_tab = $("ul#myTab li a.active").attr('id');
                            if(!tab_changed){
                              if(call_action == 'suggestions') { 
                                language = $("ul#myTabLang li a.active").attr('id');
                                load_more('1',category_id,language);
                              }
                              else if(call_action == 'latest_digital_coupons') {
                                load_more_coupons('1',category_id);
                              }
                              else if(call_action == 'books_list'){
                                language = $("ul#myTabLang li a.active").attr('id');
                                load_more_books('1',category_id,language);
                              }
                              else if(call_action == 'wish_return'){
                                language = $("ul#myTabLang li a.active").attr('id');
                                get_wish_return(1,'all_wish_return',language,category_id);
                              }
                            }
                          //}
                } 
                else {
                    toastr.error(response.message);
                }
              }
          });
      } 
      
      // get sub categories level 2
      $(document).on('click','.sub_category_1',function(){
           // get_breadcrumb();
           var category_id = $("input[name='main_category']:checked").attr('id');
            var category_name = $("input[name='main_category']:checked").closest('li').find('.checkmark').text();
            var class_name = $("input[name='main_category']:checked").closest('li').find('.change-text').attr('class');
             if(class_name){
              class_name = class_name.replace('change-text ','');
            }

            var breadcrumb = `<li class="breadcrumb selected_cat_`+category_id+`" id="`+class_name+`" name="`+category_id+`"><div class="tag-box"><label class="slot-box"><input class="selected_cat_`+category_id+`"  name="main_category" id="`+category_id+`" type="radio" selected="true"><span class="checkmark" style="border-color: #E8E8E8;  background: #E8E8E8;  color: #000;">`+category_name+` <i class="fa fa-window-close close_level" id="`+category_id+`"></i></span></label></div></li>`;
            $(".selected_category").html(breadcrumb);
            sub_category_1(this.id);
      })
  
      function sub_category_1(id)
      {
            var parent_cat_id = id;
            var language  = active_tab;
            if(call_action == 'suggestions') {                          
                language    = $("ul#myTabLang li a.active").attr('id');
                load_more('1',parent_cat_id,language);
            }
            else if(call_action == 'latest_digital_coupons'){
               load_more_coupons('1',parent_cat_id);
            }
            else if(call_action == 'books_list'){
              language = $("ul#myTabLang li a.active").attr('id');
              load_more_books('1',parent_cat_id,language);
            }
            else if(call_action == 'wish_return'){
              language = $("ul#myTabLang li a.active").attr('id');
              get_wish_return(1,'all_wish_return',language,parent_cat_id);
            }
          
            for(var i=1; i<=7; i++) {
              $(".sub_cat_"+i).remove();
            }
            $.ajax({
                    url: BASE_URL+"nested_categories/"+business_category+"?lang="+language,
                    data: { },
                    type: "get",
                    beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    },
                    error:function(response){
                      if(response.status == '401'){
                        auth_guard_route(response.status);
                      }
                    },
                    success: function(response) {
                    if(response.status == "200") {
                        //console.log(response);
                            var sub_category = '';
                            $('.sub_cat_1').html('');
                            $.each( response.data, function( index, value ){
                             if(parent_cat_id == this.category_id){
                              if(this.sub_cat.length==0){ $(".sub_cat_2").remove(); }
                              else {
                                sub_category = '<ul class="sub_cat_2">';
                              $.each( this.sub_cat, function( index, value ){
                              sub_category+=
                               `<li>
                                    <div class="tag-box">
                                        <label class="slot-box">
                                        <input class="change-text sub_category_2" name="category" id="`+this.category_id+`" type="radio"><span class="checkmark">`+this.category_name+`</span>
                                        </label>
                                    </div>
                                </li>`;
                               });
                              }
                              sub_category+= '</ul>';
                              if($(':radio[name=category][class=sub_category_2]', '#tag-list').length ==0) {
                                $('#tag_list').html(sub_category);
                              }
                              }
                                });

                    } else {
                        toastr.error(response.message);
                    }
                    }
            });     
      }
      
      // get sub categories level 3
      $(document).on('click','.sub_category_2',function(){
            //console.log("sub_category_1("+this.id+")");
             get_breadcrumb();
             sub_category_2(this.id);
             $(this.id).attr('checked',true);
      })

      function sub_category_2(id)
      {
        for(var i=2; i<=7; i++) {
            $(".sub_cat_"+i).remove();
        }
        var parent_cat_id = id;
        var language = active_tab;
        if(call_action == 'suggestions') {                          
          language    = $("ul#myTabLang li a.active").attr('id');
          load_more('1',parent_cat_id,language);
        }
        else if(call_action == 'latest_digital_coupons'){
          load_more_coupons('1',parent_cat_id);
        }
        else if(call_action == 'books_list'){
          language = $("ul#myTabLang li a.active").attr('id');
          load_more_books('1',parent_cat_id,language);
        }
        else if(call_action == 'wish_return'){
          language = $("ul#myTabLang li a.active").attr('id');
          get_wish_return(1,'all_wish_return',language,parent_cat_id);
        }
        $.ajax({
                    url: BASE_URL+"nested_categories/"+business_category+"?lang="+language,
                    data: { },
                    type: "get",
                    beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    },
                    error:function(response){
                      if(response.status == '401'){
                        auth_guard_route(response.status);
                      }
                    },
                    success: function(response) {
                    if(response.status == "200") {
                          var sub_category = '';
                           $('.sub_cat').html('');
                            $.each( response.data, function( index, value ){
                              $.each( this.sub_cat, function( index, value ){
                             // console.log(parent_cat_id+"/"+this.category_id);
                                   if(parent_cat_id == this.category_id){
                                   // console.log(this.sub_cat.length);
                                    if(this.sub_cat.length==0){ $('.sub_cat_3').remove(); }
                                    else {
                                        sub_category = '<ul class="sub_cat_3">';
                                      $.each(this.sub_cat, function( index, value ){
                                     sub_category+=
                                     `<li>
                                          <div class="tag-box">
                                              <label class="slot-box">
                                              <input class="change-text sub_category_3" name="category" id="`+this.category_id+`" type="radio"><span class="checkmark">`+this.category_name+`</span>
                                              </label>
                                          </div>
                                      </li>`;
                                     });
                                      sub_category += '</ul>';
                                    }
                                     if($(':radio[name=category][class=sub_category_3]', '#tag-list').length ==0) {
                                     $('#tag_list').html(sub_category);
                                        }
                                    }
                                });
                              });
                    } else {
                        toastr.error(response.message);
                    }
                    }
        });
      }
      
      // get sub categories level 4
      $(document).on('click','.sub_category_3',function(){
             get_breadcrumb();
             sub_category_3(this.id);
      })

      function sub_category_3(id)
      {
              for(var i=3; i<=7; i++) {
              $(".sub_cat_"+i).remove();
              }
              var parent_cat_id = id;
              var language = active_tab;
              if(call_action == 'suggestions') {                          
                language    = $("ul#myTabLang li a.active").attr('id');
                load_more('1',parent_cat_id,language);
              }
              else if(call_action == 'latest_digital_coupons'){
                load_more_coupons('1',parent_cat_id);
              }
              else if(call_action == 'books_list'){
                language = $("ul#myTabLang li a.active").attr('id');
                load_more_books('1',parent_cat_id,language);
              }
              else if(call_action == 'wish_return'){
                language = $("ul#myTabLang li a.active").attr('id');
                get_wish_return(1,'all_wish_return',language,parent_cat_id);
              }
              $.ajax({
                    url: BASE_URL+"nested_categories/"+business_category+"?lang="+language,
                    data: { },
                    type: "get",
                    beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    },
                    error:function(response){
                      if(response.status == '401'){
                        auth_guard_route(response.status);
                      }
                    },
                    success: function(response) {
                    if(response.status == "200") {
                          var sub_category = '';
                           $('.sub_cat_3').html('');
                           //console.log(response.data);
                            $.each( response.data, function( index, value ){
                              $.each( this.sub_cat, function( index, value ){
                              $.each( value.sub_cat, function( index, value ){
                             // console.log(parent_cat_id+"/"+this.category_id);
                             
                             if(parent_cat_id == this.category_id){
                             // console.log(this.sub_cat.length);
                              if(this.sub_cat.length==0){ $('.sub_cat_4').remove(); }
                              else {
                                  sub_category = '<ul class="sub_cat_4">';
                                $.each(this.sub_cat, function( index, value ){
                               sub_category+=
                               `<li>
                                    <div class="tag-box">
                                        <label class="slot-box">
                                        <input class="change-text sub_category_4" name="category" id="`+this.category_id+`" type="radio"><span class="checkmark">`+this.category_name+`</span>
                                        </label>
                                    </div>
                                </li>`;
                               });
                                sub_category += '</ul>';
                              }
                                 if($(':radio[name=category][class=sub_category_4]', '#tag-list').length ==0) {
                                $('#tag_list').html(sub_category);   
                                  }
                              }
                                });
                               });
                            });
                    } else {
                        toastr.error(response.message);
                    }
                    }
              });
      }   

      // get sub categories level 5
      $(document).on('click','.sub_category_4',function(){
          get_breadcrumb();
          sub_category_4(this.id);
      })

      function sub_category_4(id)
      {
          for(var i=4; i<=7; i++) {
            $(".sub_cat_"+i).remove();
          }
          var parent_cat_id = id;
          var language = active_tab;
          if(call_action == 'suggestions') {                          
              language    = $("ul#myTabLang li a.active").attr('id');
              load_more('1',parent_cat_id,language);
          }
          else if(call_action == 'latest_digital_coupons'){
              load_more_coupons('1',parent_cat_id);
          }
          else if(call_action == 'books_list'){
            language = $("ul#myTabLang li a.active").attr('id');
            load_more_books('1',parent_cat_id,language);
          }
          else if(call_action == 'wish_return'){
            language = $("ul#myTabLang li a.active").attr('id');
            get_wish_return(1,'all_wish_return',language,parent_cat_id);
          }
          $.ajax({
                    url: BASE_URL+"nested_categories/"+business_category+"?lang="+language,
                    data: { },
                    type: "get",
                    beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    },
                    error:function(response){
                      if(response.status == '401'){
                        auth_guard_route(response.status);
                      }
                    },
                    success: function(response) {
                    if(response.status == "200") {
                          var sub_category = '';
                           //console.log(response.data);
                            $.each( response.data, function( index, value ){
                              $.each( this.sub_cat, function( index, value ){
                              $.each( value.sub_cat, function( index, value ){
                              $.each( value.sub_cat, function( index, value ){
                         
                             // console.log(parent_cat_id+"/"+this.category_id);
                             
                             if(parent_cat_id == this.category_id){
                             // console.log(this.sub_cat.length);
                              if(this.sub_cat.length==0){ $(".sub_cat_5").remove(); }
                              else {
                                   sub_category = '<ul class="sub_cat_5">';
                                $.each(this.sub_cat, function( index, value ){
                               sub_category+=
                               `<li>
                                    <div class="tag-box">
                                        <label class="slot-box">
                                        <input class="change-text sub_category_5" name="category" id="`+this.category_id+`" type="radio"><span class="checkmark">`+this.category_name+`</span>
                                        </label>
                                    </div>
                                </li>`;
                               });
                                sub_category += '</ul>';
                              }
                                 if($(':radio[name=category][class=sub_category_5]', '#tag-list').length ==0) {
                                  $('#tag_list').html(sub_category);
                                      
                               }
                              }
                                });
                               });
                            });
                            });
                    } else {
                        toastr.error(response.message);
                    }
                    }
          });
      }

      // get sub categories level 6
      $(document).on('click','.sub_category_5',function(){
        get_breadcrumb();
        sub_category_5(this.id);
      })

      function sub_category_5(id)
      {
              $(".sub_cat_5").remove();
              $(".sub_cat_6").remove();
              $(".sub_cat_7").remove();
              var parent_cat_id = id;
              var language = active_tab;
              if(call_action == 'suggestions') {                          
                language    = $("ul#myTabLang li a.active").attr('id');
                load_more('1',parent_cat_id,language);
              }
              else if(call_action == 'latest_digital_coupons'){
                load_more_coupons('1',parent_cat_id);
              }
              else if(call_action == 'books_list'){
                language = $("ul#myTabLang li a.active").attr('id');
                load_more_books('1',parent_cat_id,language);
              }
              else if(call_action == 'wish_return'){
                language = $("ul#myTabLang li a.active").attr('id');
                get_wish_return(1,'all_wish_return',language,parent_cat_id);
              }
              $.ajax({
                    url: BASE_URL+"nested_categories/"+business_category+"?lang="+language,
                    data: { },
                    type: "get",
                    beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    },
                    error:function(response){
                      if(response.status == '401'){
                        auth_guard_route(response.status);
                      }
                    },
                    success: function(response) {
                    if(response.status == "200") {
                          var sub_category = '';
                           //console.log(response.data);
                            $.each( response.data, function( index, value ){
                              $.each( this.sub_cat, function( index, value ){
                              $.each( value.sub_cat, function( index, value ){
                              $.each( value.sub_cat, function( index, value ){
                                $.each( value.sub_cat, function( index, value ){
                         
                              //console.log(parent_cat_id+"/"+this.category_id);
                             
                             if(parent_cat_id == this.category_id){
                             // console.log(this.sub_cat.length);
                              if(this.sub_cat.length==0){ $(".sub_cat_6").remove(); }
                              else {
                                  sub_category = '<ul class="sub_cat_6">';
                                $.each(this.sub_cat, function( index, value ){
                               sub_category+=
                               `<li>
                                    <div class="tag-box">
                                        <label class="slot-box">
                                        <input class="change-text sub_category_6" name="category" id="`+this.category_id+`" type="radio"><span class="checkmark">`+this.category_name+`</span>
                                        </label>
                                    </div>
                                </li>`;
                               });
                                sub_category += '</ul>';
                              }
                                 if($(':radio[name=category][class=sub_category_6]', '#tag-list').length ==0) {
                                 $('#tag_list').html(sub_category);
                                      
                               }
                              }
                                });
                               });
                            });
                            });
                            });
                    } else {
                        toastr.error(response.message);
                    }
                    }
              });
      }

      $(document).on('click','.sub_category_6',function(){
            get_breadcrumb();
            sub_category_6(this.id);
      })

      function sub_category_6(id)
      {
                $(".sub_cat_6").remove();
                $(".sub_cat_7").remove();
                var parent_cat_id = id;
                var language = active_tab;
                if(call_action == 'suggestions') {  
                  language    = $("ul#myTabLang li a.active").attr('id');
                  load_more('1',parent_cat_id,language);
                }
                else if(call_action == 'latest_digital_coupons'){
                  load_more_coupons('1',parent_cat_id);
                }
                else if(call_action == 'books_list'){
                  language = $("ul#myTabLang li a.active").attr('id');
                  load_more_books('1',parent_cat_id,language);
                }
                else if(call_action == 'wish_return'){
                  language = $("ul#myTabLang li a.active").attr('id');
                  get_wish_return(1,'all_wish_return',language,parent_cat_id);
                }
                $.ajax({
                    url: BASE_URL+"nested_categories/"+business_category+"?lang="+language,
                    data: { },
                    type: "get",
                    beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    },
                    error:function(response){
                      if(response.status == '401'){
                        auth_guard_route(response.status);
                      }
                    },
                    success: function(response) {
                    if(response.status == "200") {
                          var sub_category = '';
                           //console.log(response.data);
                            $.each( response.data, function( index, value ){
                              $.each( this.sub_cat, function( index, value ){
                              $.each( value.sub_cat, function( index, value ){
                              $.each( value.sub_cat, function( index, value ){
                              $.each( value.sub_cat, function( index, value ){
                              $.each( value.sub_cat, function( index, value ){
 
                              //console.log(parent_cat_id+"/"+this.category_id);
                             
                             if(parent_cat_id == this.category_id){
                             // console.log(this.sub_cat.length);
                              if(this.sub_cat.length==0){ $(".sub_cat_7").remove() }
                              else {
                                  sub_category = '<ul class="sub_cat_7">';
                                $.each(this.sub_cat, function( index, value ){
                               sub_category+=
                               `<li>
                                    <div class="tag-box">
                                        <label class="slot-box">
                                        <input class="change-text sub_category_7" name="category" id="`+this.category_id+`" type="radio"><span class="checkmark">`+this.category_name+`</span>
                                        </label>
                                    </div>
                                </li>`;
                               });
                                sub_category += '</ul>';
                              }
                                  if($(':radio[name=category][class=sub_category_7]', '#tag-list').length ==0) {
                                 $('#tag_list').html(sub_category);
                                      
                               }
                              }
                                });
                               });
                            });
                            });
                            });
                            });
                    } else {
                        toastr.error(response.message);
                    }
                    }
                });
      }

      $(document).on('click','.sub_category_7',function(){
        get_breadcrumb();
        sub_category_7(this.id)
      });

      // get sub categories level 7
      function sub_category_7(id)
      {
        $(".sub_cat_7").remove();
        var parent_cat_id = id;
        var language = active_tab;
        if(call_action == 'suggestions') {  
          language    = $("ul#myTabLang li a.active").attr('id');
          load_more('1',parent_cat_id,language);
        }
        else if(call_action == 'latest_digital_coupons'){
          load_more_coupons('1',parent_cat_id);
        }
        else if(call_action == 'books_list'){
          language = $("ul#myTabLang li a.active").attr('id');
          load_more_books('1',parent_cat_id,language);
        }
        else if(call_action == 'wish_return'){
          language = $("ul#myTabLang li a.active").attr('id');
          get_wish_return(1,'all_wish_return',language,parent_cat_id);
        }
      }  
  @endif
  /*################ NESTED CATEFGORIES :: SCRIPT ENDS ################ */

  /*################ SUGGESTION :: SCRIPT STARTS ################ */
  @if(\Request::route()->getName() == 'suggestions')
      var page = 1;
      var last_page = '';
      //var category_id = '';
      //load_more(page);
      var category_id = $("li.breadcrumb").last().attr('name');

      if(!category_id)
      {
        //category_id = $("[name=main_category]:checked").attr('id');
        category_id = $("li.breadcrumb").first().attr('id');
      }
      var language    = $("ul#myTabLang li a.active").attr('id');
      $("#product_list").html("");
      $(window).scroll(function() { //detect page scroll
            if($(window).scrollTop() + $(window).height() >= $(document).height() - 250) { //if user scrolled from top to bottom of the page
                if(page < last_page)
                {
                  page++; //page number increment
                  var language = $("ul#myTabLang li a.active").attr('id');
               		load_more(page,category_id,language); //load content
          		}
            }
      });

      function load_more(page,category_id,language) {
        $.ajax({
            url: BASE_URL+"suggestion_book_list?page="+page,
            data: {'language':language,'category_id':category_id},
                type: "POST",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
            },
            error:function(response){
              if(response.status == '401'){
                auth_guard_route(response.status);
              }
            },
            success: function(response) {
              console.log(response);
              if(response.status == "200") {
                if(page == 1)
                {
                  $("#product_list").html("");
                }
                
                var html = '';
                $.each(response.data,function(){
                  var description = this.description.replace(/(<([^>]+)>)/ig,"");
                  var description = "";
                  for(i=0;i<7;i++){
                    if(this.description.split(' ')[i]){
                      description += this.description.split(' ')[i]+" ";
                    }
                  }
                   html += `<div class="col-xl-4 col-lg-4 col-md-12 col-12"> 
                            <div class="product-list-box">
                              <div class="img"><img src="`+this.image+`" alt=""></div>
                              <div class="detail">
                                <h6>`+this.name+`</h6> 
                                <p class="secondary-color">`+description+`</p>
                                <a href="javascript:void(0)" id="`+this.book_id+`" name="`+this.name+`" title="`+description+`,`+this.image+`" class="btn secondary-btn add_ssgc_suggestion">Add Suggestion</a>
                              </div>
                            </div>
                          </div>`;
                });
                if(html == '') {
                  $("#no_data").removeClass("d-none");
                  $("#product_list").empty();
                }else {
                  last_page = response.meta.last_page;
                  $("#no_data").addClass("d-none");
                  $("#product_list").append(html); 
                }
              }
              else {
               // toastr.error(response.message);
              }
                $('.loader').css('visibility','hidden');
            }
          });
      }
    var cnt = '1';  
    var cnt1='1';
    $("#images").on('change',function(){
      if (this.files) {
            for(var j=0; j< $(this)[0].files.length; j++){
              var reader = new FileReader();
              reader.onload = function (e) {
                  console.log(reader);
                  $('<div class="img" id="image_'+cnt+'"><img src="'+e.target.result+'" /><a href="javascript:void(0)" class="close remove_preview_images" id="'+cnt+'">×</a>Image '+cnt+'</div>', {
                  }).appendTo("#book_images_div");
                  cnt++;
              }
                 
              reader.readAsDataURL(this.files[j]);
            }
        }
    });

    $(document).on('click','.remove_preview_images',function(){
      var id = $(this).attr('id');
      $("#image_"+id).remove();
      $(this).parent('div').remove();
    });

    $("#pdf").on('change',function(e){
      if (this.files) {
            for(var j=0; j< $(this)[0].files.length; j++){
                  $('<div class="img" id="pdf_'+cnt1+'"><i class="fa fa-file-pdf"><a href="javascript:void(0)" class="close remove_preview_pdf" id="'+cnt1+'">×</a></i>'+this.files[j].name+'</div>', {
                  }).appendTo("#book_pdf_div");
            }
        }
        $("#pdf_div").addClass('d-none');
    });

    $(document).on('click','.remove_preview_pdf',function(){
      var id = $(this).attr('id');
      $("#pdf_"+id).remove();
      $("#pdf_div").removeClass('d-none');
      $(this).parent('div').remove();
    });
   
     
    $(document).on("click",".add_ssgc_suggestion",function(){
       var product_id =  $(this).attr('id');
       var product_name = $(this).attr('name');
       var data = $(this).attr('title');
       data = data.split(",");
       var description = data[0];
       var img = data[1];
       $("#product_id").val(product_id);
       $("#product_name").text(product_name);
       $("#product_description").text(description);
       $("#product_img").attr('src',img);
       $("#add-suggestion").modal("show");
    });

    $("#ssgcSuggestionForm").on('submit',function(event){
          event.preventDefault();
          var formData = new FormData($(this)[0]);
         // formData = formData.replaceAll("<\/script>", "");
          $.ajax({
            url: BASE_URL+"add_ssgc_suggestion",
            data: formData,
                type: "POST",
                processData:false,
                cache:false,
                contentType:false,
                // async:false,
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
            },
            error:function(response){
              if(response.status == '401'){
                auth_guard_route(response.status);
              }
            },
            success: function(response) {
              if(response.status == "200") {
               // toastr.success(response.message);
                $("#add-suggestion").modal("hide");
                $("#success-suggestion").modal("show");
                setTimeout(function(){
                  location.reload();
                },1000);
              }
              else {
                toastr.error(response.message);
              }
                $('.loader').css('visibility','hidden');
            }
          });
      });
     
      $.ajax({
        url: BASE_URL+"profile",
        data: { },
            type: "GET",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              xhr.setRequestHeader('Authorization', 'Bearer '+token);
              $('.loader').css('visibility','visible');
        },
        error:function(response){
            if(response.status == '401'){
              auth_guard_route(response.status);
            }
        },
        success: function(response) {
          // console.log(response);
          if(response.status == "200") {
           	$("input[name='mobile_number']").val(response.data.mobile_number);
           	$("input[name='email']").val(response.data.email);
            $('.loader').css('visibility','hidden');
          }
        }
    });

      $("#wishSuggestionForm").on('submit',function(event){
          event.preventDefault();
          var formData = new FormData($(this)[0]);
          //formData = formData.replaceAll("<\/script>", "");
           for(var j=0; j< $('#images')[0].files.length; j++){
              formData.append('images[]',$('#images')[0].files[j]);
            }
            for(var i=0; i< $('#pdf')[0].files.length; i++){
              formData.append('pdf[]',$('#pdf')[0].files[i]);
            }
          $.ajax({
            url: BASE_URL+"add_wish_suggestion",
            data: formData,
                type: "POST",
                processData:false,
                cache:false,
                contentType:false,
                // async:false,
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
                },
            success: function(response) {
              if(response.status == "200") {
                //toastr.success(response.message);
                $("#success-suggestion").modal("show");
                setTimeout(function(){
                  location.reload();
                },1000);
              }
              else {
                toastr.error(response.message);
              }
                $('.loader').css('visibility','hidden');
            }
          });
      });
  @endif
  /*################ SUGGESTION :: SCRIPT ENDS #########		####### */

  /*################## LATEST DIGITAL COUPONS::SCRIPT STARTS ############*/
  @if(\Request::route()->getName() == 'latest_digital_coupons')
      var page = 1;
      var last_page = '';
      //load_more_coupons(page);
      $("#digital_coupons_list").html('');
      $(window).scroll(function() { //detect page scroll
            if($(window).scrollTop() + $(window).height() >= $(document).height() - 250) { 
              //if user scrolled from top to bottom of the page
              if(page < last_page)
              {
                page++; //page number increment
              	load_more_coupons(page); //load content
              }
            }
      });

      function load_more_coupons(page,category_id = '') {
        var business_category_id = $("#business_category_id").val();
        $.ajax({
            url: BASE_URL+"coupon_list?page="+page,
            data: {'category_id':category_id,'business_category_id':business_category_id},
                type: "POST",
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
            },
            success: function(response) {
            	console.log(response);
              if(response.status == "200") {
                if(page == 1)
                {
                  $("#digital_coupons_list").html("");
                   $("#no_data").removeClass('d-none');
                }
                var html = '';
                $.each(response.data,function(){
                  last_page = response.meta.last_page;
                  if(this.added_to_cart == '0'){
                    var link = `  <a href="javascript:void(0);" id="`+this.sub_coupon_id+`" class="btn secondary-btn add_to_cart">Add to Cart <i class="icon-bag"></i></a>`;

                    var qty_input = `<div class="qty-items">
                                  <input type="button" value="-" class="qty-minus">
                                  <input type="number" id="quantity_`+this.sub_coupon_id+`" name="quantity" value="`+this.quantity+`" class="qty" min="0">
                                  <input type="button" value="+" class="qty-plus">
                                </div>`;
                  }else {
                    var view_cart = "{{route('my_cart')}}";
                    var link = `<a href="`+view_cart+`" id="`+this.sub_coupon_id+`" class="btn green-btn">View Cart <i class="icon-bag"></i></a>`;
                    var qty_input = `<div class="qty-bulk">Qty.`+this.quantity+`</div>`;
                  }
                   var show_page = "{{route('digital_coupon_details',':id')}}";
                   show_page = show_page.replace(':id',this.sub_coupon_id);
                   html += `<div class="col-xl-4 col-lg-4 col-md-12 col-12"> 
                            <div class="product-list-box">
                              <div class="img"><a href="`+show_page+`" title=""><img src="`+this.image+`" alt=""></a></div>
                              <div class="detail">
                                <h6><a href="`+show_page+`" title="">`+this.name+`</a></h6> 
                                <div class="sale-price">₹`+this.sale_price+`<span>₹`+this.mrp+`</span></div>
                                <div class="type">Type: <span>`+this.type+`</span></div>
                                `+qty_input+`
                                <div class="date">Expiry Date: `+this.expiry_date+`</div> 
                                  `+link+`
                              </div>
                            </div>
                          </div>`;
                });
                if(html == '') {
                  $("#no_data").removeClass('d-none');
                  $("#digital_coupons_list").empty();
                }else {
                  $("#no_data").addClass('d-none');
                  $("#digital_coupons_list").append(html); 
                }
              }
              else {
               // toastr.error(response.message);
              }
                $('.loader').css('visibility','hidden');
            }
        });
      }

    
  @endif
  @if(\Request::route()->getName() == 'digital_coupon_details')
        var sub_coupon_id = $("#hdn_sub_coupon_id").val();
        $.ajax({
            url: BASE_URL+"coupon_detail",
            data: {'sub_coupon_id':sub_coupon_id},
            type: "POST",
            beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
            },
            success: function(response) {
              console.log(response);
              if(response.status == "200") {
                if(response.data.added_to_cart == '0'){
                  var link = `<a href="javascript:void(0)" id="`+response.data.sub_coupon_id+`" class="btn secondary-btn add_to_cart">Add to Cart <i class="icon-bag me-2"></i></a>`;

                }else {
                  var view_cart = "{{route('my_cart')}}";
                  var link = `<a href="javascript:void(0)" class="btn green-btn update_qty" id="`+response.data.cart_item_id+`">Update <i class="icon-bag me-2"></i></a>`;
                }
                get_images(response.data.cover_image);
                $("#heading").text(response.data.name);
                $("#description").text(response.data.description);
                if(!response.data.description)
                {
                  $("#desc_heading").addClass('d-none');
                }
                $("#item_type").text(response.data.type);
                $("#expiry_date").text(response.data.expiry_date);
                $(".price").html("₹ "+response.data.sale_price+"<span>₹ "+response.data.mrp+"</span>");
                $("#quantity").val(response.data.quantity);
                $("#buttons").append(link);
              }
              else {
                toastr.error(response.message);
              }
                $('.loader').css('visibility','hidden');
            }
        });

        $(document).on('click','.update_qty',function(e){
            e.preventDefault();
            e.stopPropagation();
            var cart_item_id = $(this).attr('id');
            var quantity = $("#quantity").val();
            // console.log(quantity)  
            update_quantity(cart_item_id,quantity);
        });

        function update_quantity(cart_item_id,quantity){
          $.ajax({
              url: BASE_URL+"books/update_quantity",
              data: {'cart_item_id':cart_item_id,'quantity':quantity},
              type: "POST",
              beforeSend: function(xhr){
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    $('.loader').css('visibility','visible');
              },
              error:function(response){
                if(response.status == '401'){
                  auth_guard_route(response.status);
                }
              },
              success: function(response) {
                if(response.status == "200") {
                  toastr.success(response.message);
                }
                else {
                 toastr.error(response.message);
                }
                setTimeout(function(){
                  location.reload();
                },1000);

                $('.loader').css('visibility','hidden');
              }
          });
        }
        function get_images(data){
           var imgList = '';
           $.each(data,function(){
            imgList +=`<div class="item">
                      <div class="img"><img src="`+this.image+`" alt="Demo">
                      </div></div>`;
           }); 
           $("#slider").slick('slickAdd',imgList);
        }
  @endif
  
  @if(\Request::route()->getName() == 'latest_digital_coupons' || \Request::route()->getName() == 'digital_coupon_details')
    $(document).on('click','.add_to_cart',function(){
          auth_guard_route(token);
          var coupon_id = $(this).attr('id');
          @if(\Request::route()->getName() == 'latest_digital_coupons')
            var quantity = $("#quantity_"+coupon_id).val();
          @else
            var quantity = $("#quantity").val();
          @endif
          $.ajax({
            url: BASE_URL+"coupon/add_to_cart",
            data: {'coupon_id':coupon_id,'quantity':quantity},
            type: "POST",
            beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
            },
            error:function(response){
              if(response.status == '401'){
                auth_guard_route(response.status);
              }
            },
            success: function(response) {
              console.log(response);
              if(response.status == "200") {
               toastr.success(response.message);
                setTimeout(function(){
               //   location.reload();
               location.href ="{{route('my_cart')}}";
                },1000);
              }
              else {
                toastr.error(response.message);
              }
              $('.loader').css('visibility','hidden');
            }
          });
    });
  @endif
  
  @if(\Request::route()->getName() == 'my_cart' || \Request::route()->getName() == 'coupons_checkout' || \Request::route()->getName() == 'books_checkout')

    auth_guard_route(token);
    var checkout_items = [];

    @if(\Request::route()->getName() == 'my_cart')
      var urlParams = new URLSearchParams(window.location.search);
      var is_no_btn = urlParams.get('no_btn'); //success
      if(is_no_btn == '1')
      {
        $(window).load(function(){
          location.href="{{route('my_cart')}}";
          setTimeout(function(){ location.reload(); },1000);
        });
      }
    @endif
    
    $.ajax({
        url: BASE_URL+"books/my_cart",
        data: {},
        type: "GET",
        beforeSend: function(xhr){
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              xhr.setRequestHeader('Authorization', 'Bearer '+token);
              $('.loader').css('visibility','visible');
        },
        error:function(response){
          if(response.status == '401'){
            auth_guard_route(response.status);
          }
        },
        success: function(response) {
          if(response.status == "200") {
            console.log(response.data);
            if(response.data.coupon_items.length > 0)
            {
              $(".no-data-found").addClass('d-none');
              $("#cart_data").removeClass('d-none');
              set_coupon_items(response.data.coupon_items);
              set_payment_method(response.data.payment_methods);
              if(response.data.order_summary.total_payable == 0)
              {
                $("#payment_method").addClass('d-none');
              }
              $("#earned_points").text(response.data.earned_points);
              $("#points").text(response.data.points_formula);

              if(response.data.earned_points == '0' && response.data.points_redeemed == '0')
              {
                $("#earned_points_div").addClass('d-none');
              }
              if(response.data.points_redeemed == '1'){
                $("#redeem").attr('checked',true);
              }
              var total_items = response.data.coupon_items.length;
              var order_type = 'coupon';
              cart_summary(response.data.order_summary,total_items,order_type,user_type);
              $("#add_more_btn").attr('href',"{{route('search')}}?type=coupons");
            }
            else if(response.data.book_items.length > 0)
            {
              $(".no-data-found").addClass('d-none');
              $("#cart_data").removeClass('d-none');
              @if(\Request::route()->getName() == 'books_checkout')
                if(response.data.payment_methods.length > 0)
                {
                  $("#payment_method").removeClass('d-none');
                }
                else
                {
                  $("#payment_method").addClass('d-none');
                }
                set_book_items_for_checkout(response.data.book_items,checkout_items);
              @else
                set_book_items(response.data.book_items);
              @endif
              var total_items = response.data.book_items.length;
              var order_type = 'book';
              var order_summary = response.data.order_summary;
              var user_type = response.data.user_type;
               <?php   $check_items = app('request')->input('chk_items'); ?>
              var check_items = "[{{$check_items}}]";
              check_items = JSON.parse(check_items);
              $.ajax({
                url: BASE_URL+"books/update_cart_summary",
                data: {"checkout_items" : check_items},
                type: "POST",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  if(response.status == "200") {
                   //cart_summary(response.data,checkout_items.length,'book');
                    cart_summary(response.data,check_items.length,order_type,user_type);
                  }
                  else {
                    //toastr.error(response.message);
                   // cart_summary(order_summary,check_items,'book');
                    cart_summary(order_summary,total_items,order_type,user_type);
                  }
                  $('.loader').css('visibility','hidden');
                }
              });
              $("#add_more_btn").attr('href',"{{route('search')}}?type=books");
            }
            else {
            	$("#cart_data").addClass('d-none');
          	 	$(".no-data-found").removeClass('d-none');
            }
            
          }
          else {
            //toastr.error(response.message);
            $("#cart_data").addClass('d-none');
            $(".no-data-found").removeClass('d-none');
          }
          $('.loader').css('visibility','hidden');
        }
    });
     
    function set_coupon_items(data)
    {
    	    var items = '';
          $.each(data,function(){
	          var show_page = "{{route('digital_coupon_details',':id')}}";
	          show_page = show_page.replace(':id',this.coupon_id);
	          items += `<div class="cart-list white-bg"><!--<div class="top-check-list">
	                    <div class="common-check">
	                        <label class="checkbox">
	                           <input type="checkbox" checked><span class="checkmark"></span>
	                        </label>
	                    </div> 
	                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#delete-confirm" class="delete" id="`+this.cart_item_id+`"><i class="icon-close" ></i></a>
	                  </div>  -->
	                  <div class="box">
	                    <div class="img">
	                      <a href="`+show_page+`"><img src="`+this.cover_image+`" alt=""></a> 
	                    </div>
	                    <div class="details">
	                    
	                      <div class="head">
	                    <a href="`+show_page+`"><h5>`+this.name+`</h5></a>
	                        <p class="secondary-color">`+this.description+`</p>                
	                      </div>`
                          @if(\Request::route()->getName() =='coupons_checkout') 
                            items += `<div class="price-qty">
                          <div class="sale-price">₹`+this.sale_price+`<span>₹`+this.mrp+`</span></div></div><p><span class="secondary-color">Qty: </span>`+this.quantity+`</p>`;
                          @else 
                            items += `<div class="price-qty">
                          <div class="sale-price">₹`+this.sale_price+`<span>₹`+this.mrp+`</span></div><div class="qty-items">
                            <input type="button" value="-" id="`+this.cart_item_id+`" class="qty-minus update_qty">
                            <input type="number" value="`+this.quantity+`" class="qty" min="0"  id="quantity_`+this.cart_item_id+`" data-id="`+this.cart_item_id+`">
                            <input type="button" value="+" id="`+this.cart_item_id+`" class="qty-plus update_qty">
                          </div></div>`;
                          @endif
	                      items+=`<p><span class="secondary-color">Type: </span>`+this.type+`</p> <div class="theme-red-color">Expiry Date: `+this.expiry_date+`</div>
	                    </div> 
	                  </div></div>`;
	        });
	        //$(".cart-list").html(items);
	        $("#cart_items").html(items);
    }

    function set_payment_method(data)
    {
            var html = '';
            $.each(data,function(key,value){
              html += `<label class="radio-box">                    
                    <input type="radio" name="payment" class="payment_gateway" value="`+value+`"><span class="checkmark"></span>
                    <div class="text">`+value.toUpperCase()+`</div>
                  </label> `;
            });
            $("#payment_method").append(html);
    }

    $(document).on('click','.update_qty',function(e){
        e.preventDefault();
        e.stopPropagation();
    	  var cart_item_id = $(this).attr('id');
    	  var quantity = $("#quantity_"+cart_item_id).val();
        // console.log(quantity)	
    	  update_quantity(cart_item_id,quantity);
    });

    $(document).on('click','.qty',function(e){
      e.preventDefault();
      e.stopPropagation();
    });

    var timer;
    var timeout = 1000;
    $(document).on('input','.qty',function(e){
      e.preventDefault();
      e.stopPropagation();
      var cart_item_id = $(this).attr('data-id');
      var quantity = $(this).val();
      if(quantity >= 0)
      {
        clearTimeout(timer);
        timer = setTimeout(function() {
          update_quantity(cart_item_id,quantity);
        },timeout);
      }
    });

 	  $(document).on('click','.delete',function(){
    	 var cart_item_id = $(this).attr('id');
    	 $("#id").val(cart_item_id);
    });
	
    $(document).on('click','#remove_item',function(){
    	 var cart_item_id = $("#id").val();
    	 var quantity = '0';	
    	 update_quantity(cart_item_id,quantity);
    });

    function update_quantity(cart_item_id,quantity){
    	$.ajax({
          url: BASE_URL+"books/update_quantity",
          data: {'cart_item_id':cart_item_id,'quantity':quantity},
          type: "POST",
          beforeSend: function(xhr){
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                $('.loader').css('visibility','visible');
          },
          error:function(response){
            if(response.status == '401'){
              auth_guard_route(response.status);
            }
          },
          success: function(response) {
            if(response.status == "200") {
                if(quantity == '0')
                {
                  toastr.success('Item removed from the cart');
                  location.href = "{{route('my_cart')}}";
                }
                else
                {
                  toastr.success(response.message);
                  setTimeout(function(){
                  location.reload();},1000);
                }
            }
            else {
             toastr.error(response.message);
              setTimeout(function(){
                  location.reload();},1000);
            }
            $('.loader').css('visibility','hidden');
          }
      });
    }

    $(document).on('click','#redeem',function(){
        if($(this).is(':checked')==true){
          var operation = 'apply';
        }else {
          var operation = 'remove';
        }
        $.ajax({
            url: BASE_URL+"redeemed_points/"+operation,
            data: {},
            type: "GET",
            beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
            },
            error:function(response){
              if(response.status == '401'){
                auth_guard_route(response.status);
              }
            },
            success: function(response) {
              if(response.status == "200") {
                toastr.success(response.message);
                setTimeout(function(){
                  location.reload();
                },1000);
              }
              else {
                toastr.error(response.message);
              }
              $('.loader').css('visibility','hidden');
          }
      	});
    });
     
    $(document).on("change",".checkout_book_items",function(){
        var cart_item_id = $(this).data('cart-item-id');
        if(this.checked)
        {
          checkout_items.push(cart_item_id);
        }
        else
        {
          checkout_items = jQuery.grep(checkout_items, function(value) {
            return value != cart_item_id;
          });
        }
        $.ajax({
          url: BASE_URL+"books/update_cart_summary",
          data: {"checkout_items" : checkout_items},
          type: "POST",
          beforeSend: function(xhr){
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                $('.loader').css('visibility','visible');
          },
          error:function(response){
            if(response.status == '401'){
              auth_guard_route(response.status);
            }
          },
          success: function(response) {
            if(response.status == "200") {
              cart_summary(response.data,checkout_items.length,'book',user_type)
            }
            else {
              toastr.error(response.message);
              setTimeout(function(){ location.reload(true); },1000);
              cart_summary(response.data,checkout_items,'book',user_type);
            }
            $('.loader').css('visibility','hidden');
          }
        });
    });

    function set_book_items(data){
      var items = '';
      $.each(data,function(){
        checkout_items.push(this.cart_item_id);
        var show_page = "{{route('book_detail',':id')}}";
        show_page = show_page.replace(':id',this.product_id);
        items += `<div class="cart-list white-bg">
            <div class="top-check-list">
              <div class="common-check">
                  <label class="checkbox">
                     <input type="checkbox" data-cart-item-id="`+this.cart_item_id+`" checked class="checkout_book_items"><span class="checkmark"></span>
                  </label>
              </div> 
              <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete-confirm" class="delete" id="`+this.cart_item_id+`"><i class="icon-close"></i></a>
            </div>  
            <div class="box">
              <div class="img">
                <a href="`+show_page+`"><img src="`+this.cover_image+`" alt=""></a> 
              </div>
              <div class="details">
              <a href="`+show_page+`">
                <div class="head">
                  <h5>`+this.name+`</h5>
                  <p class="secondary-color">`+this.description+`</p>                
                </div>
                <div class="price-qty">
                  <div class="sale-price">₹`+this.sale_price+`<span>₹`+this.mrp+`</span></div>
                  <div class="qty-items">
                    <input type="button" value="-" class="qty-minus update_qty" id="`+this.cart_item_id+`">
                    <input type="number" value="`+this.quantity+`" class="qty" min="0" id="quantity_`+this.cart_item_id+`" data-id="`+this.cart_item_id+`" autocomplete="off">
                    <input type="button" value="+" class="qty-plus update_qty" id="`+this.cart_item_id+`">
                  </div>
                </div>
                <p><span class="secondary-color">Weight: </span>`+this.weight+` K.G Per Book</p> 
                <div class="secondary-color">Returnable:</div>
              <!--  <div class="green-color">You Can Return Only `+this.returnable_qty+`% of The Product Within `+this.last_returnable_days+` Days After Placing The Order</div> -->
              <div class="green-color">You Can Return Only `+this.returnable_qty+`% of The Product By `+this.last_returnable_date +`</div> 
              </div> 
              </a>
            </div>              
          </div>`;
      });
      $("#cart_items").html(items);
    }

    function set_book_items_for_checkout(data){
      <?php
        $checkout_items = app('request')->input('chk_items');
      ?>
      var checkout_items = "[{{$checkout_items}}]";
      checkout_items = JSON.parse(checkout_items);
      var items = '';
      $.each(data,function(){
        if(jQuery.inArray(parseInt(this.cart_item_id), checkout_items) !== -1)
        {
          var show_page = "{{route('book_detail',':id')}}";
          show_page = show_page.replace(':id',this.product_id);
          items += `<div class="box white-bg">
              <div class="img"><img src="`+this.cover_image+`" alt=""></div>
              <div class="detail">
                <h6>`+this.name+`</h6>
                <div class="sale-price">₹`+this.sale_price+`<span>₹`+this.mrp+`</span></div>
                <div class="qty">Qty. `+this.quantity+`</div>
              </div>
            </div>`;
        }
        
      });
      $("#cart_items").html(items);
    }

    function cart_summary(data,total_items,order_type,user_type){

      var coin_discount = '';
      var delivery_charges = '';
      if(total_items == '')
      {
        var total_items = 0;
      }
      if(data)
      {
        if(order_type == 'coupon')
        {
          coin_discount = `<li><div class="title secondary-color">Coin Discount</div><div class="price">- ₹ `+data.coin_point_discount+`</div></li> `;

          var checkout_url = "{{route('coupons_checkout')}}";
        }
        var selected_item_count = '';
        if(order_type == 'book')
        {
          if(user_type == 'dealer'){
            if(data.delivery_charges =='0') {
              delivery_charges = `<li><div class="title secondary-color">Delivery Charges</div><div class="price">Freight Charges</div></li> `;  
            }else {
              delivery_charges = `<li><div class="title secondary-color">Delivery Charges</div><div class="price">`+data.delivery_charges+`</div></li> `;
            }
            
          }else if(user_type == 'retailer'){
          delivery_charges = `<li><div class="title secondary-color">Delivery Charges</div><div class="price">₹ `+data.delivery_charges+`</div></li> `;  
          }
          
          var selected_items = checkout_items.toString();
          var checkout_url = "{{route('books_checkout')}}?chk_items="+selected_items;
          var selected_item_count = `<div class="select-item">`+total_items+` Item Selected For Order</div>`;
        }
        var summary = ` <div class="priceHeader">Price Details (`+total_items+` Item)</div>
                        <ul>
                          <li><div class="title secondary-color">Total MRP</div><div class="price">₹ `+data.total_mrp+`</div></li>              
                           `+coin_discount+` 
                            <li><div class="title secondary-color">Discount on MRP</div><div class="price">- ₹ `+data.discount_on_mrp+`</div></li> 
                             `+delivery_charges+`                                      
                          <li><div class="title"><b>Total Payable Amount</b></div><div class="price"><b id="total_payable_amount">₹ `+data.total_payable+`</b></div></li>
                        </ul>`+selected_item_count;
        $("#pay_now").text("Pay ₹"+data.total_payable);
        $(".cart-summary").html(summary);
        $("#checkout_btn").attr('href',checkout_url);
        // $("#checkout_btn").css('pointer-events','');
        $("#checkout_btn").css('cursor','');
      }
      else
      {
        if(order_type == 'coupon')
        {
          coin_discount = `<li><div class="title secondary-color">Coin Discount</div><div class="price">₹ 0</div></li>`;
        }
        if(order_type == 'book')
        {
          if(user_type == 'dealer'){
            //delivery_charges = `<li><div class="title secondary-color">Delivery Charges</div><div class="price">Freight Charges</div></li> `;
             if(data.delivery_charges =='0') {
              delivery_charges = `<li><div class="title secondary-color">Delivery Charges</div><div class="price">Freight Charges</div></li> `;  
            }else {
              delivery_charges = `<li><div class="title secondary-color">Delivery Charges</div><div class="price">`+data.delivery_charges+`</div></li> `;
            }
          }else {
          delivery_charges = `<li><div class="title secondary-color">Delivery Charges</div><div class="price">₹ 0</div></li> `;  
          }
          
        }
        var summary = ` <div class="priceHeader">Price Details (`+total_items+` Item)</div>
                        <ul>
                          <li><div class="title secondary-color">Total MRP</div><div class="price">₹ 0</div></li>              
                           `+coin_discount+` 
                            <li><div class="title secondary-color">Discount on MRP</div><div class="price">₹ 0</div></li> 
                             `+delivery_charges+`                                      
                          <li><div class="title"><b>Total Payable Amount</b></div><div class="price"><b>₹ 0</b></div></li>
                        </ul>
                        <div class="select-item">`+total_items+` Item Selected For Order</div>`;
        $(".cart-summary").html(summary);
        $("#checkout_btn").attr('href','javascript:void(0)');
        // $("#checkout_btn").css('pointer-events','none');
        $("#checkout_btn").css('cursor','default');
        
      }
    }

    $(document).on('click','#checkout_btn',function(){
      if($(this).attr('href') == 'javascript:void(0)')
      {
        toastr.error('Please Select at least one item to checkout');
      }
    });

    $(document).on('click','#pay_now',function(){
    	var payment_method = $("input[name=payment]:checked").val();
      localStorage.setItem("payout",payment_method);
      if($("#total_payable_amount").text() == '₹ 0')
      {
        payment_method = '0-amount';
      }
      $.ajax({
          url: BASE_URL+"coupon/checkout",
          data: {payment_method : payment_method},
          type: "POST",
          beforeSend: function(xhr){
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                $('.loader').css('visibility','visible');
          },
          error:function(response){
            if(response.status == '401'){
              auth_guard_route(response.status);
            }
          },
          success: function(response) {
            if(response.status == "200") {
             // toastr.success(response.message);
              location.href= response.data.url;
             /* setTimeout(function(){
                location.reload();
              },1000);*/
            }
            else {
              toastr.error(response.message);
            }
            $('.loader').css('visibility','hidden');
          }
        });
    });

    //checkout address
    @if(\Request::route()->getName() == 'books_checkout')
      $.ajax({
            url: BASE_URL+"addresses",
            data: {},
            type: "GET",
            beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
            },
            error:function(response){
              if(response.status == '401'){
                auth_guard_route(response.status);
              }
            },
            success: function(response) {
              if(response.status == "200") {
                if(response.data.length == 0)
                {
                  $("#add_address").html(`<a class="theme-color saved_checkout_address" href="javascript:void(0)">Add New</a>`);
                  $("#edit_address").addClass('d-none');
                }
                else
                {
                  $(response.data).each(function(){
                    if(this.is_delivery_address == 'yes')
                    {
                      set_billing_address(this);
                      set_shipping_address(this);
                      return false;
                    }
                    else
                    {
                      $("#add_address").html(`<a class="theme-color saved_checkout_address new_billing_address" href="javascript:void(0)" style="text-align:right">Add New</a>`);
                        $(".new_billing_address").parent('div').css('text-align','right');
                      $("#edit_address").addClass('d-none');
                    }
                  })
                }
                
                
              }
              else {
                toastr.error(response.message);
              }
              $('.loader').css('visibility','hidden');
            }
      });

      $(document).on("click",".saved_checkout_address",function(){
         $.ajax({
              url: BASE_URL+"addresses",
              data: {  },
              type: "GET",
              beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
              },
              error:function(response){
                if(response.status == '401'){
                  auth_guard_route(response.status);
                }
              },
              success: function(response) {
                if(response.status == "200") {
                    var html = '';
                    var is_delivery_address;
                    $.each(response.data,function(){
                       if(this.address_type == 'Home'){
                      var icon = `<i class="icon-home"></i>`;
                    }else if(this.address_type == 'Office'){
                      var icon = `<i class="fas fa-building"></i>`;
                    }else if(this.address_type == 'Other'){
                      var icon = `<i class="fas fa-map-pin"></i>`;
                    }
                      var contact_name =  this.contact_name.charAt(0).toUpperCase() +""+this.contact_name.slice(1);
                      if(this.is_delivery_address == "yes") { is_delivery_address ="checked";  }
                      else { is_delivery_address = ''; }
                      html += ` <div class="form-group white-bg"> <label class="radio-box">
                              <input type="radio" name="sendoption" id="`+this.id+`" `+is_delivery_address+` ><span class="checkmark"></span>
                                        <h5>`+icon+` `+this.address_type+`</h5> <div class="row">
                                          <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                            <label class="secondary-color">Full Name</label>
                                            <p>`+this.contact_name+`</p> </div> <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                            <label class="secondary-color">Company Name</label><p>`+this.company_name+`</p>
                                          </div>
                                          <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                            <label class="secondary-color">Phone Number</label>
                                            <p>+91 `+this.contact_number+`</p>
                                          </div>
                                          <div class="col-xl-6 col-lg-6 col-md-6 col-6">
                                            <label class="secondary-color">Email Address</label>
                                            <p>`+this.email+`</p>
                                          </div>
                                          <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                                            <label class="secondary-color">Address</label>
                                            <p>`+this.house_no+`, `+this.street+`, `+this.landmark+`, `+this.area+`, `+this.city_name+`, `+this.state_name+` - `+this.postcode+`</p>
                                          </div>
                                        </div>
                                      </label></div>`;
                    });
                    
                    $("#addresses_list").html(html);
                } 
                else {
                  toastr.error(response.message);
                }
                $('.loader').css('visibility','hidden');
              }
         });
         $("#type").val('billing');
         $("#save-address").modal("show");
      });

      function set_billing_address(address)
      {
        $("#default_address").html(`<h5>`+address.address_type+`</h5>                              
            <div class="row">
              <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                <label class="secondary-color">Full Name</label>
                <p>`+address.contact_name+`</p>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                <label class="secondary-color">Company Name</label>
                <p>`+address.company_name+`</p>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                <label class="secondary-color">Phone Number</label>
                <p>`+address.country_code+` `+address.contact_number+`</p>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                <label class="secondary-color">Email Address</label>
                <p>`+address.email+`</p>
              </div>
              <div class="col-xl-8 col-lg-8 col-md-12 col-12">
                <label class="secondary-color">Address</label>
                <p>`+address.house_no+`, `+address.street+`, `+address.landmark+`, `+address.area+`, `+address.city_name+`, `+address.state_name+` - `+address.postcode+`</p>
              </div>
            </div>`);
        $("#add_address").html(`<a class="theme-color text-center text-decoration-underline change_billing" href="javascript:void(0);">Change Address</a>`);
        $(".change_billing").parent('div').css('text-align','center');
        $(".edit_address").removeClass('d-none');
        $(".edit_address").attr('id',address.id);
        $("#billing_address_id").val(address.id);
      }

      function set_shipping_address(address)
      {
        $("#shipping_address").html(`<h5>`+address.address_type+`</h5>                              
            <div class="row">
              <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                <label class="secondary-color">Full Name</label>
                <p>`+address.contact_name+`</p>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                <label class="secondary-color">Company Name</label>
                <p>`+address.company_name+`</p>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                <label class="secondary-color">Phone Number</label>
                <p>`+address.country_code+` `+address.contact_number+`</p>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                <label class="secondary-color">Email Address</label>
                <p>`+address.email+`</p>
              </div>
              <div class="col-xl-8 col-lg-8 col-md-12 col-12">
                <label class="secondary-color">Address</label>
                <p>`+address.house_no+`, `+address.street+`, `+address.landmark+`, `+address.area+`, `+address.city_name+`, `+address.state_name+` - `+address.postcode+`</p>
              </div>
            </div>`);
        $("#change_address").html(`<a class="theme-color text-decoration-underline change_shipping" href="javascript:void(0);">Change Address</a>`);
        $(".edit_shipping_address").removeClass('d-none');
        $(".edit_shipping_address").attr('id',address.id);
        $("#shipping_address_id").val(address.id);
        $("#shipping_btns").addClass('d-none');
      }

      $(document).on('click',".change_billing",function(){
         $.ajax({
              url: BASE_URL+"addresses",
              data: {  },
              type: "GET",
              beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
              },
              error:function(response){
                if(response.status == '401'){
                  auth_guard_route(response.status);
                }
              },
              success: function(response) {
                if(response.status == "200") {
                    var html = '';
                    var is_delivery_address;
                    $.each(response.data,function(){
                      if(this.address_type == 'Home'){
                      var icon = `<i class="icon-home"></i>`;
                    }else if(this.address_type == 'Office'){
                      var icon = `<i class="fas fa-building"></i>`;
                    }else if(this.address_type == 'Other'){
                      var icon = `<i class="fas fa-map-pin"></i>`;
                    }
                      var contact_name =  this.contact_name.charAt(0).toUpperCase() +""+this.contact_name.slice(1);
                      if(this.is_delivery_address == "yes") { is_delivery_address ="checked";  }
                      else { is_delivery_address = ''; }
                      html += ` <div class="form-group white-bg"> <label class="radio-box">
                              <input type="radio" name="sendoption" id="`+this.id+`" `+is_delivery_address+` ><span class="checkmark"></span>
                                        <h5>`+icon+` `+this.address_type+`</h5> <div class="row">
                                          <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                            <label class="secondary-color">Full Name</label>
                                            <p>`+this.contact_name+`</p> </div> <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                            <label class="secondary-color">Company Name</label><p>`+this.company_name+`</p>
                                          </div>
                                          <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                            <label class="secondary-color">Phone Number</label>
                                            <p>+91 `+this.contact_number+`</p>
                                          </div>
                                          <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                            <label class="secondary-color">Email Address</label>
                                            <p>`+this.email+`</p>
                                          </div>
                                          <div class="col-xl-8 col-lg-8 col-md-12 col-12">
                                            <label class="secondary-color">Address</label>
                                            <p>`+this.house_no+`, `+this.street+`, `+this.landmark+`, `+this.area+`, `+this.city_name+`, `+this.state_name+` - `+this.postcode+`</p>
                                          </div>
                                        </div>
                                      </label></div>`;
                    });
                    $("#type").val('billing');
                    $("#addresses_list").html(html);
                } 
                else {
                  toastr.error(response.message);
                }
                $('.loader').css('visibility','hidden');
              }
         });

         $("#save-address").modal("show");
      });

      $(document).on('click',".change_shipping",function(){
         $.ajax({
              url: BASE_URL+"addresses",
              data: {  },
              type: "GET",
              beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
              },
              error:function(response){
                if(response.status == '401'){
                  auth_guard_route(response.status);
                }
              },
              success: function(response) {
                if(response.status == "200") {
                    var html = '';
                    var is_delivery_address;
                    $.each(response.data,function(){
                      if(this.address_type == 'Home'){
                      var icon = `<i class="icon-home"></i>`;
                    }else if(this.address_type == 'Office'){
                      var icon = `<i class="fas fa-building"></i>`;
                    }else if(this.address_type == 'Other'){
                      var icon = `<i class="fas fa-map-pin"></i>`;
                    }
                      var contact_name =  this.contact_name.charAt(0).toUpperCase() +""+this.contact_name.slice(1);
                      if(this.is_delivery_address == "yes") { is_delivery_address ="checked";  }
                      else { is_delivery_address = ''; }
                      html += ` <div class="form-group white-bg"> <label class="radio-box">
                              <input type="radio" name="sendoption" id="`+this.id+`" `+is_delivery_address+` ><span class="checkmark"></span>
                                        <h5>`+icon+`  `+this.address_type+`</h5> <div class="row">
                                          <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                            <label class="secondary-color">Full Name</label>
                                            <p>`+this.contact_name+`</p> </div> <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                            <label class="secondary-color">Company Name</label><p>`+this.company_name+`</p>
                                          </div>
                                          <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                            <label class="secondary-color">Phone Number</label>
                                            <p>+91 `+this.contact_number+`</p>
                                          </div>
                                          <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                            <label class="secondary-color">Email Address</label>
                                            <p>`+this.email+`</p>
                                          </div>
                                          <div class="col-xl-8 col-lg-8 col-md-12 col-12">
                                            <label class="secondary-color">Address</label>
                                            <p>`+this.house_no+`, `+this.street+`, `+this.landmark+`, `+this.area+`, `+this.city_name+`, `+this.state_name+` - `+this.postcode+`</p>
                                          </div>
                                        </div>
                                      </label></div>`;
                    });
                    $("#type").val('shipping');
                    $("#addresses_list").html(html);
                } 
                else {
                  toastr.error(response.message);
                }
                $('.loader').css('visibility','hidden');
              }
         });
         $("#save-address").modal("show");
      });

      $(document).on('click','#select_address',function(){
        var type = $("#type").val();
        var address_id = $("input[name='sendoption']:checked").attr('id');
        $.ajax({
            url: BASE_URL+"address/edit",
            data: {"address_id":address_id},
            type: "POST",
            beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
            },
            error:function(response){
                if(response.status == '401'){
                  auth_guard_route(response.status);
                }
            },
            success: function(response) {
              if(response.status == "200") {
                if(type == 'billing')
                {
                  set_billing_address(response.data);
                }
                else
                {
                  set_shipping_address(response.data);
                }
                $("#save-address").modal('hide');
              }
              else {
                toastr.error(response.message);
              }
              $('.loader').css('visibility','hidden');
            }
        });
      })

      $(document).on('click','#same_as_billing',function(e){
        e.preventDefault();
        var address_id = $("#billing_address_id").val();

        if(address_id != ''){
          $.ajax({
              url: BASE_URL+"address/edit",
              data: {"address_id":address_id},
              type: "POST",
              beforeSend: function(xhr){
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                    xhr.setRequestHeader('Authorization', 'Bearer '+token);
                    $('.loader').css('visibility','visible');
              },
              error:function(response){
                if(response.status == '401'){
                  auth_guard_route(response.status);
                }
              },
              success: function(response) {
                if(response.status == "200") {
                    set_shipping_address(response.data);
                }
                else {
                  toastr.error(response.message);
                }
                $('.loader').css('visibility','hidden');
              }
          });
        }
        else{
          toastr.error("Please select the billing address first");
          return false;
        }
      });

      
      $(document).on('click','#place_order',function(){
        
        var billing_address_id = $("#billing_address_id").val();
        var shipping_address_id = $("#shipping_address_id").val();
        var payment_method = $("input[name='payment_method']:checked").val();
        localStorage.setItem("payout",payment_method);
        <?php
          $checkout_items = app('request')->input('chk_items');
        ?>
        var checkout_items = "[{{$checkout_items}}]";
        checkout_items = JSON.parse(checkout_items);
        if(billing_address_id == ''){
          toastr.error("Please select billing address first");
          return false;
        }

        if(shipping_address_id == ''){
          toastr.error("Please select shipping address first");
          return false;
        }

        if(user_type == 'retailer')
        {
          $.ajax({
            url: BASE_URL+"books/checkout",
            data: {"payment_method" : payment_method, "checkout_items" : checkout_items, "billing_address_id" : billing_address_id, "shipping_address_id": shipping_address_id},
            type: "POST",
            beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
            },
            error:function(response){
                if(response.status == '401'){
                  auth_guard_route(response.status);
                }
            },
            success: function(response) {
              console.log(response);
              if(response.status == "200") {
               // toastr.success(response.message);
                location.href= response.data.url;
              }
              else if(response.status == '202') {
                $("#usertype_change_modal").modal("show");
               // toastr.error(response.message);
              }
              else if(response.message == 'item_invalid'){
                setTimeout(function(){ location.href="{{route('my_cart')}}" },1000);
              }
              else {
                toastr.error(response.message);
              }
              $('.loader').css('visibility','hidden');
            }
          });
        }
        else
        {
          $.ajax({
            url: BASE_URL+"books/dealer_checkout",
            data: {"checkout_items" : checkout_items, "billing_address_id" : billing_address_id, "shipping_address_id": shipping_address_id},
            type: "POST",
            beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
            },
            error:function(response){
                if(response.status == '401'){
                  auth_guard_route(response.status);
                }
            },
            success: function(response) {
              if(response.status == "200") {
                location.href = "{{route('order_payment_success')}}";
              }
              else if(response.status == "201") {
                //location.href = "{{route('order_payment_success')}}";
                toastr.error(response.message);
              }
              else if(response.status == '202') {
                $("#usertype_change_modal").modal("show");
               // toastr.error(response.message);
              }
              else {
                location.href = "{{route('order_payment_error')}}";
              }
              $('.loader').css('visibility','hidden');
            }
          });
        }
      });
     /*
      $(document).on('click','.add_new_address',function(){
        location.href = "{{route('customer.profile')}}?add_address=true";
      });*/
    @endif
  @endif
  @if(Request::is('order_payment_success'))
    var type = "{{ app('request')->input('type') }}";
    setTimeout(function() {
      if(type == 'coupon')
      {
        $("#redirect_url").attr('href',"{{route('digital_coupons_list')}}")
      }
      $("#place-order").modal('show');
    },500);
    
  @endif
  @if(Request::is('order_payment_error'))
    //redirect to cart
    location.href = "{{route('my_cart')}}"
  @endif
  /*################## LATEST DIGITAL COUPONS::SCRIPT ENDS ############*/

  /*################# NOTIFICATIONS :: SCRIPT STARTS #################*/
    $(document).on('click',"#notification",function(e){
      e.preventDefault();
      auth_guard_route(token);
      if(token)
      {
        location.href = "{{route('notifications')}}";
      }
    });
    @if(\Request::route()->getName() == 'notifications')
          auth_guard_route(token)
          var page = 1;
          var last_page ;
          load_more(page);
          $("#notifications_data").html('');
          $(window).scroll(function() { //detect page scroll
                if($(window).scrollTop() + $(window).height() >= $(document).height() - 250) { //if user scrolled from top to bottom of the page
                    if(page < last_page)
                    {
                      page++; //page number increment
                      load_more(page); //load content
                    }
                }
          });
          function load_more() {
            // auth_guard_route(token);
            $.ajax({
                url: BASE_URL+"notification_list?page="+page,
                data: {},
                type: "GET",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  console.log(response);
                  if(response.status == "200") {
                    var html = '';
                    if(response.data.length == 0)
                    {
                      $("#notifications_data").addClass('d-none');
                      $(".no-data-found").removeClass('d-none');
                      $("#clear_all").addClass('d-none');
                    }
                    else
                    {
                      $("#clear_all").removeClass('d-none');
                      $(".no-data-found").addClass('d-none');
                      last_page = response.meta.last_page;
                      var route = '';
                      $.each(response.data,function(){
                        if(this.slug == 'customer_ticket_resolved' || this.slug == 'customer_ticket_acknowledged')
                        {
                          var route  = "{{route('ticket_history')}}?ticket_id="+this.data_id;
                        }
                        else if(this.slug == 'customer_order_placed' || this.slug == 'customer_order_delivered'  ||this.slug == 'customer_order_refunded'|| this.slug == 'customer_order_cancelled' || this.slug =='order_update_notify'){

                          var route  = "{{route('order_details',':id')}}";
                          route = route.replace(':id',this.data_id);
                        }else if(this.slug == 'customer_coupon_order_placed' ||this.slug == 'customer_coupon_order_refunded'|| this.slug == 'customer_coupon_order_cancelled'){

                          var route  = "{{route('digital_coupon_detail_purchased',':id')}}";
                          route = route.replace(':id',this.data_id);
                        }else {
                          var route = this.url;
                        }
                        if(route != ''){
                          html += `<li>
                            <div class="box">
                                <a href="#delete-confirm" data-bs-toggle="modal" class="delete" data-id="`+this.id+`"><i class="far fa-trash-alt"></i></a>
                                <a href="`+route+`" class="link-color"><p>`+this.content+`</p>
                                <div class="date secondary-color">`+this.date+`</div> </a>                         
                            </div>
                          </li>`;
                        }else {
                          html += `<li>
                            <div class="box">
                                <a href="#delete-confirm" data-bs-toggle="modal" class="delete" data-id="`+this.id+`"><i class="far fa-trash-alt"></i></a>
                                 <p>`+this.content+`</p>
                                <div class="date secondary-color">`+this.date+`</div> 
                            </div>
                          </li>`;
                        }
                        
                      });
                      $("#notifications_data").append(html);
                      $(".no-data-found").addClass('d-none');
                      $("#notifications_data").removeClass('d-none');
                    }
                    
                  }
                  else {
                    $("#notifications_data").addClass('d-none');
                    $(".no-data-found").removeClass('d-none');
                    toastr.error(response.message);
                  }
                  $('.loader').css('visibility','hidden');
                }
            });
          }

          $(document).on('click','.delete',function(){
             var notification_id = $(this).data('id');
             $("#id").val(notification_id);
          });
        
          $(document).on('click','#remove_item',function(){
             var notification_id = $("#id").val();
             $.ajax({
                url: BASE_URL+"delete_notification",
                data: {'id':notification_id},
                type: "POST",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  if(response.status == "200") {
                    toastr.success(response.message);
                    setTimeout(function(){
                      location.reload();
                    },1000);
                  }
                  else {
                    toastr.error(response.message);
                  }
                  $('.loader').css('visibility','hidden');
                }
              });
          });

          $(document).on('click','#clear_all_btn',function(){
             $.ajax({
                url: BASE_URL+"clear_all_notifications",
                data: { },
                type: "GET",
                beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      xhr.setRequestHeader('Authorization', 'Bearer '+token);
                      $('.loader').css('visibility','visible');
                },
                error:function(response){
                  if(response.status == '401'){
                    auth_guard_route(response.status);
                  }
                },
                success: function(response) {
                  if(response.status == "200") {
                    toastr.success(response.message);
                    setTimeout(function(){
                      location.reload();
                    },1000);
                  }
                  else {
                    toastr.error(response.message);
                  }
                  $('.loader').css('visibility','hidden');
                }
              });
          });
    @endif
  /*################# NOTIFICATIONS :: SCRIPT ENDS #################*/

  /*################# CONTACT US :: SCRIPT STARTS #################*/
  @if(\Request::route()->getName() == 'contact_us' || \Request::route()->getName() == 'ticket_history')
        $.ajax({
            url: BASE_URL+"reason_list",
            data: {},
            type: "GET",
            beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
            },
            error:function(response){
                if(response.status == '401'){
                  auth_guard_route(response.status);
                }
            },
            success: function(response) {
              if(response.status == "200") {
                //toastr.success(response.message);
                var option = '';
                option = `<option>Select</option>`;
                $.each(response.data,function(){
                    option += `<option value=`+this.id+`>`+this.reason+`</option>`;
                });
                $("#reason_id").append(option);
              }
              else {
                toastr.error(response.message);
              }
              $('.loader').css('visibility','hidden');
            }
        });

        $.ajax({
        url: BASE_URL+"profile",
        data: { },
            type: "GET",
            beforeSend: function(xhr){
              xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
              xhr.setRequestHeader('Authorization', 'Bearer '+token);
              $('.loader').css('visibility','visible');
        },
        error:function(response){
          if(response.status == '401'){
            auth_guard_route(response.status);
          }
        },
        success: function(response) {
          // console.log(response);
          if(response.status == "200") {
          	$("#full_name").val(response.data.first_name +" "+response.data.last_name);
           	$("#mobile_number").val(response.data.mobile_number);
           	$("#email").val(response.data.email);
            $('.loader').css('visibility','hidden');
          }
        }
    });
       
        $("#contactSupportForm").on('submit',function(evt){
            evt.preventDefault();
            var formData = new FormData($(this)[0]);
           // formData = formData.replaceAll("<\/script>", "");
            $.ajax({
                url: BASE_URL+"send_ticket",
                data: formData,
                type: "POST",
                processData:false,
                contentType:false,
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
                  $("#submit_btn").prop("disabled",true);
                },
                success: function(response) {
                  if(response.status == "200") {
                   // toastr.success(response.message);
                   $("#add-ticket-modal").modal("hide");
                   $("#ticket-send").modal("show");
                    setTimeout(function(){
                      if(token)
                      {
                        location.href = "{{route('ticket_history')}}";
                      }
                      else
                      {
                        location.href = "{{route('web_home')}}"
                      }
                    },2000);
                  } else {
                    toastr.error(response.message);
                  }
                   $("#submit_btn").prop("disabled",false);
                   $('.loader').css('visibility','hidden');
                }
            });
        });
  @endif
  /*################# CONTACT US :: SCRIPT ENDS #################*/

  /*################# CONTACT US :: SCRIPT STARTS #################*/
  @if(\Request::route()->getName() == 'ticket_history')
      var page = 1;
      load_more(page);
      var last_page = '';
      $(".ticket-list").html('');
      
      $(window).scroll(function() { //detect page scroll
            if($(window).scrollTop() + $(window).height() >= $(document).height() - 250) { //if user scrolled from top to bottom of the page
                if(page < last_page)
                {
                  page++; //page number increment
              		load_more(page); //load content
              	}
            }
      });
      function load_more() {
      	auth_guard_route(token);
        $.ajax({
            url: BASE_URL+"ticket_list?page="+page,
            data: {},
            type: "GET",
            beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
            },
            success: function(response) {
              if(response.status == "200") {
                //toastr.success(response.message);
                console.log(response);
                var html = '';
                var style_class = '';
                if(response.data.length == 0)
                {
                  $(".no-data-found").removeClass('d-none');
                  $("#add_new_ticket_btn").addClass('d-none');
                }
                else
                {
                  $(".no-data-found").addClass('d-none');
                  $("#add_new_ticket_btn").removeClass('d-none');

                  $.each(response.data,function(){
                    if(this.status_original == 'pending') {
                      style_class = 'text-warning';
                    }else if(this.status_original == 'acknowledged') {
                      style_class = 'green-color';
                    }else if(this.status_original == 'resolved') {
                      style_class = 'theme-color';
                    }
                    if(this.reason == null || this.reason == ''){
                      var reason = "";
                    }else {
                      var reason = this.reason.reason;
                    }

                    html += `<div class="ticket-box">
                            <a href="javascript:void(0)" class="ticket_info" id="`+this.ticket_number+`" title="">
                              <div class="head">            
                                <div class="list">
                                  <label class="secondary-color">Ticket ID:</label> 
                                  <p class="mb-0">`+this.ticket_number+`</p>                
                                </div>            
                                <div class="list">
                                  <label class="secondary-color">Ticket Date:</label> 
                                  <p class="mb-0">`+this.date+`</p>   
                                </div>
                                <div class="list">
                                  <label class="secondary-color">Ticket Status:</label>
                                  <p><span class="`+style_class+`">`+this.status+`</span></p>  
                                </div>
                              </div>
                              <div class="details"> 
                                <label class="secondary-color">Ticket Reason</label>
                                <p>`+reason+`</p> 
                              </div>
                            </a>
                             </div>`;
                  });
                  $(".ticket-list").append(html);
                  $(".ticket-list").removeClass('d-none');
                }
              }
              else {
              	$(".ticket-list").addClass('d-none');
              	$(".no-data-found").removeClass('d-none');
                toastr.error(response.message);
              }
              $('.loader').css('visibility','hidden');
            }
        });
      }

      if($("#ticket_id").val() != undefined){
        var ticket_id = $("#ticket_id").val();
        get_ticket_info(ticket_id);
      }
    
      $(document).on('click','.ticket_info',function(){
        var id = $(this).attr('id');
        get_ticket_info(id);
       
      });
      function get_ticket_info(id){
         $.ajax({
            url: BASE_URL+"ticket/"+id,
            data: {},
            type: "GET",
            beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
            },
            success: function(response) {
              if(response.status == "200") {
                //toastr.success(response.message);
                console.log(response);
                var html = '';
                var style_class = '';
                if(response.data.status_original == 'pending') {
                    style_class = 'text-warning';
                  }else if(response.data.status_original == 'acknowledged') {
                    style_class = 'green-color';
                  }else if(response.data.status_original == 'resolved') {
                    style_class = 'theme-color';
                  }
                   if(response.data.reason == null || response.data.reason == ''){
                      var reason = "";
                    }else {
                      var reason = response.data.reason.reason;
                    }

                  html = `<div class="head">            
                            <div class="list">
                              <label class="secondary-color">Ticket ID:</label> 
                              <p class="mb-0">`+response.data.ticket_number+`</p>                
                            </div>            
                            <div class="list">
                              <label class="secondary-color">Ticket Date:</label> 
                              <p class="mb-0">`+response.data.date+`</p>   
                            </div>
                            <div class="list">
                              <label class="secondary-color">Ticket Status:</label>
                              <p><span class="`+style_class+`">`+response.data.status+`</span></p>  
                            </div>
                          </div>
                          <div class="details"> 
                            <label class="secondary-color">Ticket Reason</label>
                            <p>`+reason+`</p> 
                          </div> 
                        </div> 
                        <div class="description">
                          <p class="secondary-color mb-1">Description</p>
                          <p class="mb-0">`+response.data.message+`</p>          
                        </div>`;
                          if(response.data.acknowledged_comment !='') {
                          html+=`<div class="admin-comment">
                          <p class="theme-color mb-1">Acknowledgement</p>
                          <p>`+response.data.acknowledged_comment+`</p>
                        </div>`;
                      }
                        if(response.data.admin_comment !='') {
                          html+=`<div class="admin-comment">
                          <p class="theme-color mb-1">Sam Samyik Ghatna Chakra Response</p>
                          <p>`+response.data.admin_comment+`</p>
                        </div>`;
                      }
                      
                $("#ticket-detail").modal("show");
                $("#ticket_info").html(html);
              }
              else {
                toastr.error(response.message);
              }
              $('.loader').css('visibility','hidden');
            }
        });
      }
  @endif
  /*################# CONTACT US :: SCRIPT ENDS #################*/



  /*################ CMS PAGES ::SCRIPT START #####################*/
      @if(\Request::route()->getName() == 'about_us' || \Request::route()->getName() == 'terms_and_condition' || \Request::route()->getName() == 'privacy_policy')

      cms_pages();
      function cms_pages() {
          $.ajax({
                 url: BASE_URL+"cms",
                 data: {  },
                 type: "GET",
                 beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                       $('.loader').css('visibility','visible');

                  },
                 success: function(response) {
                    if(response.status == "200") {
                          @if(\Request::route()->getName() == 'about_us')
                             about_us_page(response.data.about_us);
                          @endif
                          @if(\Request::route()->getName() == 'terms_and_condition')
                            terms_page(response.data.terms_page_url);
                          @endif
                           @if(\Request::route()->getName() == 'privacy_policy')
                            privacy_policy_page(response.data.privacy_policy);
                          @endif
                    } else {
                     toastr.error(response.message);
                    }
                     $('.loader').css('visibility','hidden');

                 }
          });
      }

      function about_us_page(url)
      {
            $.ajax({
                 url: url,
                 data: {  },
                 type: "GET",
                 beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                       $('.loader').css('visibility','visible');

                  },
                 success: function(response) {
                  console.log(response);
                  setTimeout(function(){
                       $('#about_us').append(response);
                     },1000);
                   $('.loader').css('visibility','hidden');
                 }
            });
      }

      function terms_page(url)
      {
              $.ajax({
                 url: url,
                 data: {  },
                 type: "GET",
                 beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      $('.loader').css('visibility','visible');
                  },
                 success: function(response) {
                  setTimeout(function(){
                       $('#terms_condition').html(response);
                     },1000);
                   $('.loader').css('visibility','hidden');
                 }
              });
      }


      function privacy_policy_page(url)
      {
              $.ajax({
                 url: url,
                 data: {  },
                 type: "GET",
                 beforeSend: function(xhr){
                      xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                      $('.loader').css('visibility','visible');
                  },
                 success: function(response) {
                    setTimeout(function(){
                       $('#privacy_policy').html(response);
                     },1000);
                    $('.loader').css('visibility','hidden');
                 }
              });
      }
    @endif
    /*################ CMS PAGES ::SCRIPT START #####################*/


    /*################ Global Script #####################*/
        function updateCartCount() {
            $.ajax({
                url: BASE_URL + 'books/my_cart_item_count',
                type: 'GET',
                dataType: 'json',
                beforeSend: function(xhr){
                  xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                  xhr.setRequestHeader('Authorization', 'Bearer '+token);
                  $('.loader').css('visibility','visible');
                },
                success: function(response) {
                    $('#cart_item_count').text(response.data.cart_item_count);
                },
                error: function(error) {
                    console.error('Error fetching cart count:', error);
                }
            });
        }

        // Call the function initially to get the cart count
        updateCartCount();


    @if(\Request::route()->getName() == 'books_checkout' || \Request::route()->getName() == 'coupons_checkout')
      // call api to cancel the transaction and marked as failed and create duplicate order 
      $("#cancel_transaction").click(function(){
        $.ajax({
              url: BASE_URL+"markOrderFailed",
              data: { },
              type: "GET",
              beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer '+token);
                xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                $('.loader').css('visibility','visible');
              },
              error:function(response){
                if(response.status == '401'){
                    auth_guard_route(response.status);
                }
              },
              success: function(response) {
                  if(response.status == "200") {
                    console.log(response.success);
                    location.href = '{{route("my_cart")}}';
                  }
                  else
                  {
                     toastr.error(response.message);
                    location.href = '{{route("my_cart")}}';
                  }
                  $('.loader').css('visibility','hidden');

              }
          });
      });

      $("#no_btn").click(function(){
        $("#order-cancel-confirm").modal("hide");
        location.href = "{{route('my_cart')}}?no_btn=1";
       });
    @endif
});
</script>