(function( $ ) {
	'use strict';

    $(document).ready(function($){
        var timerSpeed = 2500;
        if(public_obj.timer != ''){
          timerSpeed = public_obj.timer;
        }
        $('.promotions-slider').slick({
        centerMode: true,
        centerPadding: '260px',
        slidesToShow: 1,
        autoplay: true,
        autoplaySpeed: timerSpeed,
        infinite: true,
        responsive: [
          {
            breakpoint: 768,
            settings: {
              arrows: false,
              centerMode: true,
              centerPadding: '40px',
              slidesToShow: 3
            }
          },
          {
            breakpoint: 480,
            settings: {
              arrows: false,
              centerMode: true,
              centerPadding: '40px',
              slidesToShow: 1
            }
          }
        ]
      });
      $('.arrows .prev').click(function() {
          $('.promotions-slider').slick('slickPrev');
      });
  
      
      $('.arrows .next').click(function() {
          $('.promotions-slider').slick('slickNext');
      });
    });
	
})( jQuery );