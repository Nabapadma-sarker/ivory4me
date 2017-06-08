<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $product, $woocommerce_loop, $yit_is_page, $yit_is_feature_tab;

// the classes for the <li> tag
$woocommerce_loop['li_class'] = array();

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ){
    $woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ){
    $woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
    return;
}

// Increase loop count
$woocommerce_loop['loop']++;

if ( !( isset( $woocommerce_loop['layout'] ) && ! empty( $woocommerce_loop['layout'] ) ) )
    $woocommerce_loop['layout'] = 'with-hover';

if ( !( isset( $woocommerce_loop['view'] ) && ! empty( $woocommerce_loop['view'] ) ) )
    $woocommerce_loop['view'] = yit_get_option( 'shop-view', 'grid' );

// remove the shortcode from the short description, in list view
remove_filter( 'woocommerce_short_description', 'do_shortcode', 11 );
add_filter( 'woocommerce_short_description', 'strip_shortcodes' );

// changes for the "classic" layout
if ( $woocommerce_loop['layout'] == 'classic' ) {
    //remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart' );
    add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
}

//product countdown compatibility
global $ywpc_loop;
if ( $ywpc_loop && $ywpc_loop == 'ywpc_widget' ) {
    $woocommerce_loop['view'] = 'grid';
}

// li classes
$woocommerce_loop['li_class'][] = 'product';
$woocommerce_loop['li_class'][] = 'group';
$woocommerce_loop['li_class'][] = $woocommerce_loop['view'];
$woocommerce_loop['li_class'][] = $woocommerce_loop['layout'];
if ( yit_get_option('shop-view-show-border') ) {
    $woocommerce_loop['li_class'][] = 'with-border';
}

// width of each product for the grid
yit_detect_span_catalog_image();

$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', $woocommerce_loop['columns'] );

// put the percentual width
// if ( ! $is_span ) {
//     $woocommerce_loop['li_class'][] = 'no-span';
//     $perc = ( 100 - $woocommerce_loop['columns'] * 3 ) / $woocommerce_loop['columns'];
//     $style_attr = " style='width:$perc%;'";
// }

// first and last
    if ( $woocommerce_loop['loop'] % $woocommerce_loop['columns'] == 0 )         $woocommerce_loop['li_class'][] = 'last';
elseif ( ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] == 0 ) $woocommerce_loop['li_class'][] = 'first';

// configuration
if ( ! yit_get_option('shop-view-show-price') ) remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price' );
?>
<li <?php post_class( $woocommerce_loop['li_class'] ); ?> >

    <?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

    <div class="product-thumbnail group">

        <div class="thumbnail-wrapper">
            <?php
                /**
                 * woocommerce_before_shop_loop_item_title hook
                 *
                 * @hooked woocommerce_show_product_loop_sale_flash - 10
                 * @hooked woocommerce_template_loop_product_thumbnail - 10
                 */
                do_action( 'woocommerce_before_shop_loop_item_title' );
            ?>
        </div>

        <?php do_action( 'yith_after_thumbnail_product' ); //fix hover ?>

        <?php if ( $woocommerce_loop['layout'] == 'classic' && yit_get_option('shop-view-show-shadow') ) : ?>
        <div class="product-shadow"></div>
        <?php endif; ?>

        <div class="product-meta">
            <?php
	            /**
	             * woocommerce_shop_loop_item_title hook
	             *
	             * @hooked woocommerce_template_loop_product_title - 10
	             */
	            do_action( 'woocommerce_shop_loop_item_title' );

                /**
                 * woocommerce_after_shop_loop_item_title hook
                 *
                 * @hooked woocommerce_template_loop_price - 10
                 */
                do_action( 'woocommerce_after_shop_loop_item_title' );
            ?>
        </div>

    </div>

    <?php

	    /**
	     * woocommerce_after_shop_loop_item hook
	     *
	     * @hooked woocommerce_template_loop_add_to_cart - 10
	     */
	    do_action( 'woocommerce_after_shop_loop_item' );

    ?>

</li>