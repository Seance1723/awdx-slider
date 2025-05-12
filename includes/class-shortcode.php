<?php
class AWDX_Shortcode {
    public static function init() {
        add_shortcode( 'awdx_slider', [ __CLASS__, 'render_slider' ] );
    }

    public static function render_slider( $atts ) {
        $atts    = shortcode_atts( [ 'id' => '' ], $atts, 'awdx_slider' );
        $post_id = intval( $atts['id'] );
        if ( get_post_type( $post_id ) !== 'awdx_slider' ) {
            return '';
        }

        $images   = get_post_meta( $post_id, '_awdx_images',    true ) ?: [];
        $captions = get_post_meta( $post_id, '_awdx_captions',  true ) ?: [];
        $cols     = get_post_meta( $post_id, '_awdx_columns',   true ) ?: 3;
        $rows     = get_post_meta( $post_id, '_awdx_rows',      true ) ?: 1;
        $cls      = get_post_meta( $post_id, '_awdx_classname', true ) ?: '';

        if ( empty( $images ) ) {
            return '';
        }

        $per_slide = $cols * $rows;
        $total     = count( $images );
        $missing   = $per_slide - ( $total % $per_slide );
        if ( $missing < $per_slide ) {
            for ( $i = 0; $i < $missing; $i++ ) {
                $images[]   = $images[ $i % $total ];
                $captions[] = $captions[ $i % $total ] ?? '';
            }
        }
        $chunks = array_chunk( $images, $per_slide );

        ob_start(); ?>
        <div id="awdx-slider-<?php echo esc_attr( $post_id ); ?>"
             class="awdx-slider owl-carousel owl-theme <?php echo esc_attr( $cls ); ?>"
             data-cols="<?php echo esc_attr( $cols ); ?>">
          <?php foreach ( $chunks as $chunk_idx => $chunk ) : ?>
            <div class="awdx-slide-item">
              <div class="awdx-slider-grid" style="--grid-cols:repeat(<?php echo esc_attr( $cols ); ?>,1fr)">
                <?php foreach ( $chunk as $i => $img ) :
                  $cap = esc_html( $captions[ $chunk_idx * $per_slide + $i ] ?? '' );
                ?>
                  <div class="awdx-slider-cell">
                    <img src="<?php echo esc_url( $img ); ?>" alt="">
                    <?php if ( $cap ) : ?>
                      <p class="awdxc-caption"><?php echo $cap; ?></p>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
