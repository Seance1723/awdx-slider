<?php
// includes/class-shortcode.php

class AWDX_Shortcode {

    public static function init() {
        add_shortcode( 'awdx_slider', [ __CLASS__, 'render_slider' ] );
    }

    public static function render_slider( $atts ) {
        $atts      = shortcode_atts( [ 'id' => '' ], $atts, 'awdx_slider' );
        $slider_id = intval( $atts['id'] );

        if ( ! $slider_id || get_post_type( $slider_id ) !== 'awdx_slider' ) {
            return '';
        }

        $images    = get_post_meta( $slider_id, '_awdx_images',   true ) ?: [];
        $captions  = get_post_meta( $slider_id, '_awdx_captions', true ) ?: [];
        $cols_raw  = intval( get_post_meta( $slider_id, '_awdx_columns', true ) ?: 3 );
        $cols      = min( $cols_raw, 4 );    // clamp to max 4
        $rows      = intval( get_post_meta( $slider_id, '_awdx_rows',    true ) ?: 1 );
        $classname = get_post_meta( $slider_id, '_awdx_classname',     true ) ?: '';

        if ( empty( $images ) ) {
            return '';
        }

        // Build slides of cols × rows
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
        <div id="awdx-slider-<?php echo esc_attr( $slider_id ); ?>"
             class="awdx-slider owl-carousel owl-theme <?php echo esc_attr( $classname ); ?>"
             data-cols="<?php echo esc_attr( $cols ); ?>">

          <?php foreach ( $chunks as $chunk_idx => $chunk ) : ?>
            <div class="awdx-slide-item">
              <div class="awdx-slider-grid"
                   style="--grid-cols:repeat(<?php echo esc_attr( $cols ); ?>,1fr)">

                <?php foreach ( $chunk as $i => $img ) :
                    $cap = $captions[ $chunk_idx * $per_slide + $i ] ?? '';
                ?>
                  <div class="awdx-slider-cell">
                    <img src="<?php echo esc_url( $img ); ?>" alt="">
                    <?php if ( $cap !== '' ) : ?>
                      <p class="awdxc-caption"><?php echo esc_html( $cap ); ?></p>
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

AWDX_Shortcode::init();
