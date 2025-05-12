<?php
class AWDX_CPT {
    public static function register() {
        add_action( 'init', [ __CLASS__, 'register_cpt' ] );
    }

    public static function register_cpt() {
        $svg = get_svg_base64( get_template_directory() . '/assets/img/admin/logo-slider.svg' );
        $labels = [
            'name'               => __( 'ADWX Sliders', 'awdx-slider' ),
            'singular_name'      => __( 'ADWX Slider',  'awdx-slider' ),
            'menu_name'          => __( 'ADWX Sliders', 'awdx-slider' ),
            'add_new'            => __( 'Add New Slider',     'awdx-slider' ),
            'add_new_item'       => __( 'Add New Image Slider','awdx-slider' ),
            'edit_item'          => __( 'Edit Image Slider',  'awdx-slider' ),
            'new_item'           => __( 'New Image Slider',   'awdx-slider' ),
            'view_item'          => __( 'View Slider',        'awdx-slider' ),
            'all_items'          => __( 'All Sliders',        'awdx-slider' ),
        ];
        register_post_type( 'awdx_slider', [
            'labels'    => $labels,
            'public'    => false,
            'show_ui'   => true,
            'supports'  => [ 'title' ],
            'menu_icon' => $svg,
        ] );
    }
}
