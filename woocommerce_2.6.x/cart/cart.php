<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

<form id="cart-table" action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>

<table class="shop_table cart" cellspacing="0">
    <thead>
        <tr>
            <th class="product-remove">&nbsp;</th>
            <th class="product-thumbnail">&nbsp;</th>
            <th class="product-name"><?php     _e('Product', 'yit'); ?></th>
            <th class="product-price"><?php    _e('Price',   'yit'); ?></th>
            <th class="product-quantity"><?php _e('Quantity','yit'); ?></th>
            <th class="product-subtotal"><?php _e('Total',   'yit'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php do_action( 'woocommerce_before_cart_contents' ); ?>

        <?php
        if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
            foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
                $_product = apply_filters( 'woocommerce_cart_item_product', $values['data'], $values, $cart_item_key );

                    if ( $_product && $_product->exists() && $values['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $values, $cart_item_key ) ) {
                    ?>
                    <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                        <!-- Remove from cart link -->
                        <td class="product-remove">
                            <?php
                                echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __('Remove this item', 'yit') ), $cart_item_key );
                            ?>
                        </td>

                        <!-- The thumbnail -->
                        <td class="product-thumbnail">
                            <?php
                                $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $values, $cart_item_key );

                                if ( ! $_product->is_visible() )
                                    echo $thumbnail;
                                else
                                    printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
                            ?>
                        </td>

                        <!-- Product Name -->
                        <td class="product-name">
                            <?php
                                if ( ! $_product->is_visible() )
                                    echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $values, $cart_item_key );
                                else
                                    echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() ), $values, $cart_item_key );

                                // Meta data
                                echo WC()->cart->get_item_data( $values );

                                // Backorder notification
                                if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $values['quantity'] ) )
                                    echo '<p class="backorder_notification">' . __( 'Available on backorder', 'yit' ) . '</p>';
                            ?>
                        </td>

                        <!-- Product price -->
                        <td class="product-price">
                            <?php
                                echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $values, $cart_item_key );
                            ?>
                        </td>

                        <!-- Quantity inputs -->
                        <td class="product-quantity">
                            <?php
                                if ( $_product->is_sold_individually() ) {
                                    $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                } else {
                                    $product_quantity = woocommerce_quantity_input( array(
                                        'input_name'  => "cart[{$cart_item_key}][qty]",
                                        'input_value' => $values['quantity'],
                                        'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                                        'min_value'   => '0'
                                    ), $_product, false );
                                }

                                echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
                            ?>
                        </td>

                        <!-- Product subtotal -->
                        <td class="product-subtotal">
                            <?php
                                echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $values['quantity'] ), $values, $cart_item_key );
                            ?>
                        </td>
                    </tr>
                    <?php
                }
            }
        }

        do_action( 'woocommerce_cart_contents' );
        ?>
        <tr>
            <td colspan="6" class="actions">

                <?php if ( wc_coupons_enabled() ) { ?>
                    <div class="coupon">

                        <label for="coupon_code"><?php _e('Coupon', 'yit'); ?>:</label> <input name="coupon_code" class="input-text" id="coupon_code" value="" /> <input type="submit" class="button" name="apply_coupon" value="<?php _e('Apply Coupon', 'yit'); ?>" />

                        <?php do_action('woocommerce_cart_coupon'); ?>

                    </div>
                <?php } ?>

                <input type="submit" class="button" name="update_cart" value="<?php _e('Update Cart', 'yit'); ?>" /> <input type="submit" class="checkout-button button alt" name="proceed" value="<?php _e('Proceed to Checkout &rarr;', 'yit'); ?>" />

                <?php //do_action( 'woocommerce_proceed_to_checkout' ); ?>

                <?php wp_nonce_field( 'woocommerce-cart' ); ?>
            </td>
        </tr>

        <?php do_action( 'woocommerce_after_cart_contents' ); ?>
    </tbody>
</table>

<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>
<div class="cart-collaterals row-fluid">

    <?php do_action( 'woocommerce_cart_collaterals' ); ?>


    <?php woocommerce_shipping_calculator(); ?>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
