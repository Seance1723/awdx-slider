jQuery(function($){
    if (typeof $.fn.owlCarousel !== 'function') return;

    $('.awdx-slider').each(function(){
        var $el  = $(this),
            cols = parseInt($el.data('cols'),10) || 3;

        $el.owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            slideBy: 1,
            responsive: {
                0:   { items: 1 },
                576: { items: 2 },
                768: { items: cols }
            }
        });
    });
});
