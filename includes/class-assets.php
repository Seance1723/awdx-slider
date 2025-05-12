<?php
class AWDX_Assets {
    public static function init() {
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_assets' ] );
        add_action( 'wp_enqueue_scripts',   [ __CLASS__, 'frontend_assets' ] );
    }

    public static function admin_assets() {
        wp_enqueue_media();
        wp_enqueue_script(
            'awdx-admin-js',
            AWDX_SLIDER_URL . 'assets/js/admin.js',
            [ 'jquery' ],
            AWDX_SLIDER_VERSION,
            true
        );
    }

    public static function frontend_assets() {
        if (
            ! wp_script_is( 'owl-carousel', 'registered' ) &&
            ! wp_script_is( 'owl-carousel', 'enqueued' )
        ) {
            wp_register_style(
                'owl-carousel',
                'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css',
                [],
                '2.3.4'
            );
            wp_register_style(
                'owl-theme',
                'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css',
                [ 'owl-carousel' ],
                '2.3.4'
            );
            wp_register_script(
                'owl-carousel',
                'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js',
                [ 'jquery' ],
                '2.3.4',
                true
            );
            wp_enqueue_style( 'owl-carousel' );
            wp_enqueue_style( 'owl-theme' );
            wp_enqueue_script( 'owl-carousel' );
        }

        wp_enqueue_style(
            'awdx-slider-css',
            AWDX_SLIDER_URL . 'assets/css/frontend.css',
            [],
            AWDX_SLIDER_VERSION
        );

        wp_register_script(
            'awdx-frontend-js',
            AWDX_SLIDER_URL . 'assets/js/frontend.js',
            [ 'jquery', 'owl-carousel' ],
            AWDX_SLIDER_VERSION,
            true
        );
        wp_enqueue_script( 'awdx-frontend-js' );

        $fallback = <<<'JS'
(function($){
    $(window).on('load', function(){
        if (typeof $.fn.owlCarousel !== 'function') {
            $('.awdx-slider').each(function(){
                $(this).empty().append(
                  '<div class="awdx-slider-error" ' +
                  'style="color:red;padding:1em;border:1px solid red;">' +
                  'AWDX Slider Error: Owl Carousel not loaded.' +
                  '</div>'
                );
            });
        }
    });
})(jQuery);
JS;
        wp_add_inline_script( 'awdx-frontend-js', $fallback );
    }
}
