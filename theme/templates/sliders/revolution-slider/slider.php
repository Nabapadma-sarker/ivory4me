<?php
/**
 * @package WordPress
 * @subpackage Your Inspiration Themes
 */                                                          

$slider_class = '';
$slider_class .= yit_slide_get('align') != '' ? ' align' . yit_slide_get('align') : '';

$show_text = yit_slide_get( 'show_text' );
$wrapper_class  = $show_text ? ' with-text' : '';
$wrapper_class .= $show_text ? ' ' . yit_slide_get( 'text_position' ) : '';

$slider_class .= $show_text ? ' span9' : ' span12';
$text = $show_text ? yit_slide_get( 'text' ) : '';
?>
 
<!-- START SLIDER -->
<div class="container slider-revolution<?php echo $wrapper_class ?>">
    <div class="row">

        <?php if ( $show_text && ! empty( $text ) ) : ?>
            <div class="text span3">
                <?php echo wpautop( $text ) ?>
            </div>
        <?php endif; ?>

        <div id="<?php echo $slider_id ?>"<?php yit_slider_class($slider_class) ?>>
            <div class="shadowWrapper">
                <?php echo do_shortcode('[rev_slider ' . yit_slide_get( 'slider_name' ) . ']'); ?>
            </div>
        </div>

    </div>
</div>

<!-- END SLIDER -->