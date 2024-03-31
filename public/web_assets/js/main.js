$(document).ready(function () {
	
	// var input = document.querySelector("#phone");
 //    window.intlTelInput(input, { 
 //    	onlyCountries: ["al", "ad", "at", "by", "be",],
 //      utilsScript: "js/utils.js",      
	// });

 //  var input = document.querySelector(".countrycode");
 //    window.intlTelInput(input, {      	
 //      utilsScript: "js/utils.js",
	// });
 	   

	/* ======== Preloader ======== */
    var loader = $(".loader");

    if (loader.length) {
      // show Preloader until the website ist loaded
      $(window).on("load", function () {
        loader.addClass('fade-out');
        setTimeout(function () {
          loader.hide();
        }, 1000);
      });
    }

 		/* Product List Slider */
    /*$(".book-slider").slick({
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
            adaptiveHeight: true,
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
  	});*/



  	/* Related Slider */
    $(".related-slider").slick({
	   slidesToShow: 6,
	   infinite:false,
	   slidesToScroll: 1,
	   autoplay: true,
	   autoplaySpeed: 2000,
	   dots: false,
	   arrows: false,
	   responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 5,
            slidesToScroll: 1,
            adaptiveHeight: true,
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

   //  /* ======== Slick Slider ======== */
   //  $(".banner-slider").slick({
	  //  slidesToShow: 1,
	  //  infinite:true,
	  //  slidesToScroll: 1,
	  //  autoplay: true,
	  //  autoplaySpeed: 2000,
	  //  dots: true,
	  //  arrows: false
	  //    // dots: false, Boolean
	  //   // arrows: false, Boolean
  	// });

  	/* ======== Product Slider ======== */
  	sliderInit();
  	


   
 
    /* QTY Minus & Plus */			
		$(document).on('click','.qty-plus',function () {
				// if ($(this).prev().val() < 500) {
		    	$(this).prev().val(+$(this).prev().val() + 1);
				// }
		});
		$(document).on('click','.qty-minus',function () {
				// if ($(this).next().val() > 1) {
		    	// if ($(this).next().val() > 1) 
		    		$(this).next().val(+$(this).next().val() - 1);
				// }
		});
    
      
		/* OTP Second */
		/*jQuery(function($) {
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
			      // if (timer < 20) {
			      //     display.addClass('countdown__time--orange')
			      //   };
			      //   if (timer < 5) {
			      //     display.addClass('countdown__time--red')
			      //   };
			      }, 1000);
			      // this.$reset.on('click', function() {
			      //     clearInterval(interval);
			      //   countdownTimer.render();  
			      //   }); 
			    },
			};
			countdownTimer.init();
			});
*/

		/* Time Picker */ 
		$(".timepicker").datetimepicker({
	    format: "LT",
	    icons: {
	      up: "icon-chevron-up",
	      down: "icon-chevron-down"
	    }
	  });
	   

		// let dropdowns = document.querySelectorAll('.dropdown-toggle')
		// dropdowns.forEach((dd)=>{
		//     dd.addEventListener('click', function (e) {
		//         var el = this.nextElementSibling
		//         el.style.display = el.style.display==='block'?'none':'block'
		//     })
		// })


});

function sliderInit(){
	$(".pro-slide").slick({
	   slidesToShow: 1,
	   infinite:true,
	   slidesToScroll: 1,
	   autoplay: true,
	   autoplaySpeed: 2000,
	   dots: true,
	   arrows: false
	     // dots: false, Boolean
	    // arrows: false, Boolean
		});
}
