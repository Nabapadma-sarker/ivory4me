<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="woocommerce-order">

    <?php if ( $order ) : ?>

        <?php if ( $order->has_status( 'failed' ) ) : ?>

            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php _e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'yit'); ?></p>

            <p><?php
                if (is_user_logged_in()) :
                    _e('Please attempt your purchase again or go to your account page.', 'yit');
                else :
                    _e('Please attempt your purchase again.', 'yit');
                endif;
            ?></p>

            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
                <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e('Pay', 'yit') ?></a>
                <?php if ( is_user_logged_in() ) : ?>
                <a href="<?php echo esc_url( get_permalink(wc_get_page_id('myaccount')) ); ?>" class="button pay"><?php _e('My Account', 'yit'); ?></a>
                <?php endif; ?>
            </p>

        <?php else : ?>

            <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'yit' ), $order ); ?></p>

            <table class="shop_table thankyou">
                <thead>
                    <tr>
                        <th><?php _e('Order:', 'yit'); ?></th>
                        <th><?php _e('Date:', 'yit'); ?></th>
                        <th><?php _e('Total:', 'yit'); ?></th>
                        <?php if ($order->get_payment_method_title()) : ?>
                        <th><?php _e('Payment method:', 'yit'); ?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $order->get_order_number(); ?></td>
                        <td><?php echo wc_format_datetime( $order->get_date_created() ); ?></td>
                        <td><?php echo $order->get_formatted_order_total(); ?></td>
                        <?php if ($order->get_payment_method_title()) : ?>
                        <td><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></td>
                        <?php endif; ?>
                    </tr>
                </tbody>
            </table>
            <div class="clear"></div>            

        <?php endif; ?>

        <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
        <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

    <?php else : ?>

        <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'yit' ), null ); ?></p>

    <?php endif; ?>
</div>
