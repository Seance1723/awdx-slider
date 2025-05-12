<?php
// includes/class-metabox.php

class AWDX_MetaBox {

    public static function init() {
        add_action( 'add_meta_boxes',       [ __CLASS__, 'add_meta_box' ] );
        add_action( 'save_post_awdx_slider', [ __CLASS__, 'save_meta' ] );
    }

    public static function add_meta_box() {
        add_meta_box(
            'awdx_slider_meta',
            __( 'Slider Images & Settings', 'awdx-slider' ),
            [ __CLASS__, 'render_meta_box' ],
            'awdx_slider',
            'normal',
            'high'
        );
    }

    public static function render_meta_box( $post ) {
        $images    = get_post_meta( $post->ID, '_awdx_images',    true ) ?: [];
        $captions  = get_post_meta( $post->ID, '_awdx_captions',  true ) ?: [];
        $columns   = intval( get_post_meta( $post->ID, '_awdx_columns', true ) ?: 3 );
        $columns   = min( $columns, 4 );    // clamp to max 4
        $rows      = intval( get_post_meta( $post->ID, '_awdx_rows',    true ) ?: 1 );
        $classname = get_post_meta( $post->ID, '_awdx_classname',     true ) ?: '';

        wp_nonce_field( 'awdx_slider_nonce', 'awdx_slider_nonce_field' );
        ?>
        <p>
          <label for="awdx_columns"><?php _e( 'Images per row:', 'awdx-slider' ); ?></label>
          <input type="number"
                 id="awdx_columns"
                 name="awdx_columns"
                 value="<?php echo esc_attr( $columns ); ?>"
                 min="1"
                 max="4">
        </p>

        <p>
          <label for="awdx_rows"><?php _e( 'Rows per slide:', 'awdx-slider' ); ?></label>
          <input type="number"
                 id="awdx_rows"
                 name="awdx_rows"
                 value="<?php echo esc_attr( $rows ); ?>"
                 min="1"
                 max="3">
        </p>

        <p>
          <label for="awdx_classname"><?php _e( 'Custom Class Name:', 'awdx-slider' ); ?></label>
          <input type="text"
                 id="awdx_classname"
                 name="awdx_classname"
                 value="<?php echo esc_attr( $classname ); ?>">
        </p>

        <p>
          <button type="button" class="button awdx-upload-images">
            <?php _e( 'Add Images', 'awdx-slider' ); ?>
          </button>
          <ul class="awdx-image-list">
            <?php foreach ( $images as $i => $url ) : ?>
              <li>
                <img src="<?php echo esc_url( $url ); ?>" width="80">
                <input type="hidden" name="awdx_images[]"   value="<?php echo esc_url( $url ); ?>">
                <input type="text"
                       name="awdx_captions[]"
                       placeholder="<?php esc_attr_e( 'Caption', 'awdx-slider' ); ?>"
                       value="<?php echo esc_attr( $captions[ $i ] ?? '' ); ?>">
                <button type="button" class="button awdx-remove-image">
                  <?php _e( 'Remove', 'awdx-slider' ); ?>
                </button>
              </li>
            <?php endforeach; ?>
          </ul>
        </p>

        <p>
          <strong><?php _e( 'Shortcode:', 'awdx-slider' ); ?></strong>
          <code>[awdx_slider id="<?php echo $post->ID; ?>"]</code>
        </p>
        <?php
    }

    public static function save_meta( $post_id ) {
        if (
            ! isset( $_POST['awdx_slider_nonce_field'] ) ||
            ! wp_verify_nonce( $_POST['awdx_slider_nonce_field'], 'awdx_slider_nonce' )
        ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Clamp columns to 4
        $cols = isset( $_POST['awdx_columns'] )
              ? min( intval( $_POST['awdx_columns'] ), 4 )
              : 3;
        update_post_meta( $post_id, '_awdx_columns', $cols );

        if ( isset( $_POST['awdx_rows'] ) ) {
            update_post_meta( $post_id, '_awdx_rows', intval( $_POST['awdx_rows'] ) );
        }
        if ( isset( $_POST['awdx_classname'] ) ) {
            update_post_meta( $post_id, '_awdx_classname', sanitize_text_field( $_POST['awdx_classname'] ) );
        }

        if ( isset( $_POST['awdx_images'] ) ) {
            $images = array_map( 'esc_url', $_POST['awdx_images'] );
            update_post_meta( $post_id, '_awdx_images', $images );
        } else {
            delete_post_meta( $post_id, '_awdx_images' );
        }

        if ( isset( $_POST['awdx_captions'] ) ) {
            $captions = array_map( 'sanitize_text_field', $_POST['awdx_captions'] );
            update_post_meta( $post_id, '_awdx_captions', $captions );
        } else {
            delete_post_meta( $post_id, '_awdx_captions' );
        }
    }
}

AWDX_MetaBox::init();
