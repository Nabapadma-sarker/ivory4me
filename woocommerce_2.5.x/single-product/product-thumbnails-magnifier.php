<?php
/**
 * Single Product Image
 *
 * @author 		YIThemes
 * @package 	YITH_Magnifier/Templates
 * @version     1.0.0
 */

global $post, $product, $woocommerce;
?>

<div class="thumbnails">
<?php
    /*
    $attachments = get_posts( array(
    	'post_type' 	=> 'attachment',
    	'numberposts' 	=> -1,
    	'post_status' 	=> null,
    	'post_parent' 	=> $post->ID,
    	//'post__not_in'	=> array( get_post_thumbnail_id() ),
    	'post_mime_type'=> 'image',
    	'orderby'		=> 'menu_order',
    	'order'			=> 'ASC'
    ) );
    */


$attachment_ids = $product->get_gallery_attachment_ids();

// add featured image
if ( apply_filters( 'yit_single_product_thumbnails_show_featured', false ) ) {
    if ( ! empty( $attachment_ids ) && !in_array( get_post_thumbnail_id(), $attachment_ids ) ) array_unshift( $attachment_ids, get_post_thumbnail_id() );
}

if ($attachment_ids) {

	if( yith_wcmg_is_enabled() ) {
		echo '<ul class="yith_magnifier_gallery">';
	}

	$loop = 0;
	$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );

	foreach ( $attachment_ids as $id ) {

		if ( get_post_meta( $id, '_woocommerce_exclude_image', true ) == 1 )
			continue;

		if( !yith_wcmg_is_enabled() ) {
			$classes = array( 'zoom' );
			$rel = 'prettyPhoto[product-gallery]';
		} else {
			$classes = array();
			$rel = '';
		}

		if ( $loop == 0 || $loop % $columns == 0 )
			$classes[] = 'first';

		if ( ( $loop + 1 ) % $columns == 0 )
			$classes[] = 'last';


		if( yith_wcmg_is_enabled() ) {
			list( $thumbnail_url, $thumbnail_width, $thumbnail_height ) = wp_get_attachment_image_src( $id, "shop_single" );
			list( $magnifier_url, $magnifier_width, $magnifier_height ) = wp_get_attachment_image_src( $id, "shop_magnifier" );

			printf( '<li><a href="%s" title="%s" rel="%s" class="%s" data-small="%s">%s</a></li>', $magnifier_url, esc_attr( get_the_title( $id ) ), $rel, implode(' ', $classes), $thumbnail_url, wp_get_attachment_image( $id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) ) );
		} else {
			printf( '<a href="%s" title="%s" rel="%s" class="%s">%s</a></li>', wp_get_attachment_url( $id ), esc_attr( get_the_title( $id ) ), $rel, implode(' ', $classes), wp_get_attachment_image( $id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) ) );
		}
		$loop++;

	}

	if( yith_wcmg_is_enabled() ) {
		echo '</ul>';
	}
}
?>
    <div id="slider-prev"></div>
    <div id="slider-next"></div>
</div>