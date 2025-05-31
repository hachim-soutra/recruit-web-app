

/*m-n*/

$(document).ready(function(){

    $(".show-btn-block").click(function(){
        if($(".show-sector").css('display')=='block'){
        $(".show-sector").slideUp(1000);
    }

    if($(".show-sector").css('display')=='none'){
       $(".show-sector").slideDown(1000);
    }

    });

     $(".show-more-btn").click(function(){
        $(".show-more-btn").toggleClass("hide-block");
    });

});




/*m-n*/

// seller-slider

jQuery("#logo-slider").owlCarousel( {

    margin:15,
    autoplay: true,
    responsiveClass: true,
    autoplayTimeout: 5000,
    smartSpeed: 1500,
    center: false,
    loop:true,
    nav:false,
    dots:false,
     responsive: {
      0: {
        items: 1
      },
      575: {
        items:2
      },

       991: {
        items:4
      },

      1200: {
        items:6
      }
    }
});

jQuery("#gllery-slide").owlCarousel( {

    margin: 0,
    autoplay: true,
    responsiveClass: true,
    autoplayTimeout: 5000,
    smartSpeed: 1500,
    center: true,
    loop:true,
    nav:true,
    dots:false,
     responsive: {
      0: {
        items: 1
      },
      600: {
        items:2
      },

       800: {
        items:3
      },

      1200: {
        items:3
      }
    }
});

// seller-slider



// seller-slider

jQuery("#clients-slider").owlCarousel( {

    autoplay: true,
    responsiveClass: true,
    autoplayTimeout: 5000,
    smartSpeed: 1500,
    center: true,
    loop:true,
    nav:false,
    dots:false,
    items:1,
    // animateIn: 'fadeIn',
    // animateOut: 'fadeOut'

});

// seller-slider


// ----

$(document).ready(function(){
    $(".dotted-sec").click(function(){
      $(".open-sec").toggle();
    });
  });

// ----


// equalheight script start
   $(function() {
          $('.equalheight').matchHeight();
        });
// equalheight script end









