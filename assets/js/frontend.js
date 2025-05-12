jQuery(function($){
    if ( typeof $.fn.owlCarousel !== 'function' ) return;

    $('.awdx-slider').each(function(){
        var $el  = $(this)

        $el.owlCarousel({
            loop: true,
    margin: 30,
    dots: true,
    nav: true,
    items: 2,
        });
    });
});
