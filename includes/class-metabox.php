<?php
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
        $columns   = get_post_meta( $post->ID, '_awdx_columns',   true ) ?: 3;
        $rows      = get_post_meta( $post->ID, '_awdx_rows',      true ) ?: 1;
        $classname = get_post_meta( $post->ID, '_awdx_classname', true ) ?: '';

        wp_nonce_field( 'awdx_slider_nonce', 'awdx_slider_nonce_field' );
        ?>
        <p>
          <label><?php _e( 'Images per row:', 'awdx-slider' ); ?></label>
          <input type="number" name="awdx_columns" value="<?php echo esc_attr( $columns ); ?>" min="1" max="6">
        </p>
        <p>
          <label><?php _e( 'Rows per slide:', 'awdx-slider' ); ?></label>
          <input type="number" name="awdx_rows" value="<?php echo esc_attr( $rows ); ?>" min="1" max="3">
        </p>
        <p>
          <label><?php _e( 'Custom Class:', 'awdx-slider' ); ?></label>
          <input type="text" name="awdx_classname" value="<?php echo esc_attr( $classname ); ?>">
        </p>
        <p>
          <button type="button" class="button awdx-upload-images"><?php _e( 'Add Images', 'awdx-slider' ); ?></button>
          <ul class="awdx-image-list">
            <?php foreach ( $images as $i => $url ): ?>
              <li>
                <img src="<?php echo esc_url( $url ); ?>" width="80">
                <input type="hidden" name="awdx_images[]" value="<?php echo esc_url( $url ); ?>">
                <input type="text"   name="awdx_captions[]" placeholder="<?php esc_attr_e( 'Caption', 'awdx-slider' ); ?>"
                       value="<?php echo esc_attr( $captions[ $i ] ?? '' ); ?>">
                <button type="button" class="button awdx-remove-image"><?php _e( 'Remove', 'awdx-slider' ); ?></button>
              </li>
            <?php endforeach; ?>
          </ul>
        </p>
        <p><strong><?php _e( 'Shortcode:', 'awdx-slider' ); ?></strong>
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

        update_post_meta( $post_id, '_awdx_columns',   intval( $_POST['awdx_columns']   ?? 3 ) );
        update_post_meta( $post_id, '_awdx_rows',      intval( $_POST['awdx_rows']      ?? 1 ) );
        update_post_meta( $post_id, '_awdx_classname', sanitize_text_field( $_POST['awdx_classname'] ?? '' ) );

        if ( ! empty( $_POST['awdx_images'] ) ) {
            $imgs = array_map( 'esc_url',            $_POST['awdx_images']   );
            $caps = array_map( 'sanitize_text_field', $_POST['awdx_captions'] );
            update_post_meta( $post_id, '_awdx_images',   $imgs );
            update_post_meta( $post_id, '_awdx_captions', $caps );
        } else {
            delete_post_meta( $post_id, '_awdx_images' );
            delete_post_meta( $post_id, '_awdx_captions' );
        }
    }
}
