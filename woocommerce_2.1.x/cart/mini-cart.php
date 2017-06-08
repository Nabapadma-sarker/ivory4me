<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $yit_topbar;
?>

<?php if( $yit_topbar ): ?>
    <a href="<?php echo WC()->cart->get_cart_url(); ?>" class="cart_control">Cart</a>
    <div class="cart_wrapper">
<?php endif ?>

<?php do_action( 'woocommerce_before_mini_cart' ); ?>

<ul class="cart_list product_list_widget <?php echo $args['list_class']; ?>">

    <?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

        <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :

            $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

            // Only display if allowed
            if ( ! apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) || ! $_product->exists() || $cart_item['quantity'] == 0 )
                continue;

            $product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
            $thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
            $product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
            ?>

            <li>
                <a href="<?php echo get_permalink( $cart_item['product_id'] ); ?>">
                    <?php echo $thumbnail ?>
                </a>

                <a href="<?php echo get_permalink( $cart_item['product_id'] ); ?>">
                    <?php echo  $product_name ?>
                </a>

                <?php
                    echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove_item" title="%s">%s</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __('Remove this item', 'yit'), __( '&times; remove', 'yit' ) ), $cart_item_key );
                ?>

                <?php echo WC()->cart->get_item_data( $cart_item ); ?>

                <span class="quantity"><?php printf( '%s &times; %s', $cart_item['quantity'], $product_price ); ?></span>

            </li>

        <?php endforeach; ?>

    <?php else : ?>

        <li class="empty"><?php _e('No products in the cart.', 'yit'); ?></li>

    <?php endif; ?>

</ul><!-- end product list -->

<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

    <p class="total"><strong><?php _e('Subtotal', 'yit'); ?>:</strong> <?php echo WC()->cart->get_cart_subtotal(); ?></p>

    <?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

    <p class="buttons">
        <a href="<?php echo WC()->cart->get_cart_url(); ?>" class="button"><?php _e('View Cart', 'yit'); ?></a>
        <a href="<?php echo WC()->cart->get_checkout_url(); ?>" class="button checkout"><?php _e('Checkout', 'yit'); ?></a>
    </p>

<?php endif; ?>


<?php do_action( 'woocommerce_after_mini_cart' ); ?>