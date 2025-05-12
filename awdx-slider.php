<?php
/**
 * Plugin Name:     AWDX Slider
 * Plugin URI:      https://example.com/awdx-slider
 * Description:     A CPT-based logo slider with meta fields & Owl Carousel (CDN).
 * Version:         1.1.2
 * Author:          You
 * Author URI:      https://example.com
 * Text Domain:     awdx-slider
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'AWDX_SLIDER_VERSION', '1.1.2' );
define( 'AWDX_SLIDER_DIR',     plugin_dir_path( __FILE__ ) );
define( 'AWDX_SLIDER_URL',     plugin_dir_url(  __FILE__ ) );

// Boot the plugin
require_once AWDX_SLIDER_DIR . 'includes/class-awdx-slider.php';
AWDX_Slider::instance();
