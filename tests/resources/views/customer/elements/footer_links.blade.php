

<!-- Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="{{asset('web_assets/js/bootstrap.min.js')}}"></script>
<script src="{{asset('web_assets/js/bootstrap.bundle.js')}}"></script>

<!-- Date Time Picker -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>


<!-- Prograss Circle -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/easy-pie-chart/2.1.6/jquery.easypiechart.min.js"></script>

<!-- Bar Chart -->
<script src="https://www.google.com/jsapi" type="text/javascript" ></script>

<!-- Fancy Box -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
<!-- Custom Script -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.slick/1.4.1/slick.min.js"></script>
<!-- <script src="{{asset('web_assets/js/page-scroll.js')}}"></script> -->
<script src="{{asset('web_assets/js/intlTelInput.min.js')}}"></script>
<!-- <script src="js/utils.js"></script> -->
<script src="{{asset('web_assets/js/main.js')}}"></script>

<!-- toastr js -->
<script src="{{asset('web_assets/js/toastr.min.js')}}"></script>



<script type="text/javascript">
   
    function getOtpCodeElement(index) {
      return document.getElementById('otp_code' + index);
    }

   function onKeyUpEvent(index, event) {
      const eventCode = event.which || event.keyCode;
      if (getOtpCodeElement(index).value.length === 1) {
       if (index !== 6) {
        getOtpCodeElement(index+ 1).focus();
       } else {
        getOtpCodeElement(index).blur();
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