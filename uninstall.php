<?php
/**
 * AWDX Slider Uninstall
 *
 * @package AWDX_Slider
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Delete all awdx_slider posts and their meta
$slides = get_posts( array(
    'post_type'      => 'awdx_slider',
    'numberposts'    => -1,
    'post_status'    => 'any',
) );
foreach ( $slides as $slide ) {
    wp_delete_post( $slide->ID, true );
}

// Clean up orphaned postmeta
global $wpdb;
$wpdb->query(
    "
    DELETE pm 
    FROM {$wpdb->postmeta} pm
    LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
    WHERE p.ID IS NULL
    "
);
