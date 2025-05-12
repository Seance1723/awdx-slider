<?php
class AWDX_Slider {
    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self;
            self::$instance->setup_hooks();
        }
        return self::$instance;
    }

    private function setup_hooks() {
        require_once AWDX_SLIDER_DIR . 'includes/class-cpt.php';
        AWDX_CPT::register();

        require_once AWDX_SLIDER_DIR . 'includes/class-metabox.php';
        AWDX_MetaBox::init();

        require_once AWDX_SLIDER_DIR . 'includes/class-shortcode.php';
        AWDX_Shortcode::init();

        require_once AWDX_SLIDER_DIR . 'includes/class-assets.php';
        AWDX_Assets::init();
    }
}
