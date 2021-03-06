<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see         http://docs.woothemes.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $product;

$details = sprintf('<a href="%s" rel="nofollow" title="%s" class="details">%s</a>', get_permalink( $product->id ), __( 'Details', 'yit' ), __( 'Details', 'yit' ));
if ( ! yit_get_option('shop-view-show-details') )
    { $details = ''; }

if ( ! is_shop_enabled() || ! yit_get_option('shop-view-show-add-to-cart') || ! $product->is_purchasable() ) :
    $add_to_cart = '';

$out_of_stock = '';
?>

<?php elseif ( ! $product->is_in_stock() ) : $add_to_cart = ''; $label = apply_filters( 'out_of_stock_add_to_cart_text', __( 'Out of stock', 'yit' ) ); ?>

    <?php $out_of_stock = sprintf( '<a class="button out-of-stock" title="%s">%s</a>', $label, $label ); ?>

<?php else : ?>

    <?php

       $add_to_cart = '';

        switch ( $product->product_type ) {
            case "variable" :
                $link 	= apply_filters( 'variable_add_to_cart_url', get_permalink( $product->id ) );
                $label 	= apply_filters( 'variable_add_to_cart_text', __('Select options', 'yit') );
                $class       .= ' view-options';
            break;
            case "grouped" :
                $link 	= apply_filters( 'grouped_add_to_cart_url', get_permalink( $product->id ) );
                $label 	= apply_filters( 'grouped_add_to_cart_text', __('View options', 'yit') );
                $class       .= ' view-options';
            break;
            case "external" :
                $link 	= apply_filters( 'external_add_to_cart_url', get_permalink( $product->id ) );
                $label 	= apply_filters( 'external_add_to_cart_text', __('Read More', 'yit') );
                $class       .= ' view-options';
            break;
            default :
                $link 	= apply_filters( 'add_to_cart_url', esc_url( $product->add_to_cart_url() ) );
                $label 	= apply_filters( 'add_to_cart_text', __('Add to cart', 'yit') );
                $quantity = apply_filters( 'add_to_cart_quantity', ( get_post_meta( $product->id, 'minimum_allowed_quantity', true ) ? get_post_meta( $product->id, 'minimum_allowed_quantity', true ) : 1 ) );
            break;
        }

        $add_to_cart = apply_filters( 'woocommerce_loop_add_to_cart_link',
            sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',
                esc_url( $product->add_to_cart_url() ),
                esc_attr( isset( $quantity ) ? $quantity : 1 ),
                esc_attr( $product->id ),
                esc_attr( $product->get_sku() ),
                esc_attr( isset( $class ) ? $class : '' ),
                esc_html( $label )
            ),
            $product );

    ?>

<?php endif; ?>

<?php if ( ! empty( $add_to_cart ) || ! empty( $details ) ) : ?>
<div class="product-actions">
    <?php echo $details; ?>
    <?php echo $add_to_cart; ?>
    <?php if (isset($out_of_stock) && $out_of_stock != '') : echo $out_of_stock; endif ?>
</div>
<?php endif; ?>