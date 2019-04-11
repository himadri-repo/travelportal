(function($) {
 "use strict";

    $(document).ready(function() {
        //jQuery to collapse the navbar on scroll
        $(window).scroll(function() {
            if ($("#navigation").offset().top > 160) {
                $(".navbar-fixed-top").addClass("header-collapse");
            } else {
                $(".navbar-fixed-top").removeClass("header-collapse");
            }
        });

        //header sticky
        if( $( window ).width() >= "768" ) {
            $(".header-fixed").sticky({topSpacing:0});
        }

        //st- slider
        $(window).load(function() {
            $('.st-slider-carousel').each(function () {
                var nav = $(this).attr('data-nav');
                var dots = $(this).attr('data-dots');
                $('.st-slider-carousel').owlCarousel({
                    loop: true,
                    margin: 0,
                    items: 1,
                    autoplay: true,
                    singleItem:true,
                    nav:nav,
                    dots:dots,
                    autoplayTimeout: 4000,
                    animateOut: 'fadeOut',
                    animateIn: 'fadeIn',
                    smartSpeed: 450,
                    navText : ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
                    responsive: {
                        0: {
                            items: 1
                        },
                    }
                });
            })
        });

        $(window).load(function(){
            if (typeof $('.tour-gallery').fotorama == 'function') {
                $('.tour-gallery').each(function () {
                    $('.tour-gallery').fotorama({
                        thumbmargin: 15,
                        thumbborderwidth: 3
                    });
                });
            }
        })


    });

})(jQuery);