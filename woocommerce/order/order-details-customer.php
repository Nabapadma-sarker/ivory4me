<?php
/**
 * Order Customer Details
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$span_size = 'span' . ( yit_get_sidebar_layout() == 'sidebar-no' ? 4 : 3 );
?>

<div class="row my-account-order-details">
	<div class="<?php echo $span_size ?>">
		<dl class="customer_details">
			<header class="title">
				<h2><?php _e('Customer details', 'yit'); ?></h2>
			</header>

			<?php if ( $order->billing_email ): ?>
				<dt><?php _e( 'Email:', 'yit' ); ?></dt>
				<dd><?php echo $order->billing_email; ?></dd>
			<?php endif; ?>

			<?php if ($order->billing_phone) : ?>
				<dt><?php _e( 'Telephone:', 'yit' ); ?></dt>
				<dd><?php echo $order->billing_phone; ?></dd>
			<?php endif; ?>

			<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>
		</dl>
	</div>

	<?php if ( yit_woocommerce_default_shiptobilling() ) : ?>

		<div class="<?php echo $span_size ?> addresses">
			<div>
				<header class="title">
					<h2><?php _e('Billing Address', 'yit'); ?></h2>
				</header>
				<address>
					<p><?php echo ( $address = $order->get_formatted_billing_address() ) ? $address : __('N/A', 'yit'); ?></p>
				</address>
			</div>
		</div>

	<?php endif; ?>
	<?php if ( ! yit_woocommerce_default_shiptobilling_only() ) : ?>
		<div class="<?php echo $span_size ?> addresses">
			<div>
				<header class="title">
					<h2><?php _e('Shipping Address', 'yit'); ?></h2>
				</header>
				<address>
					<p><?php echo ( $address = $order->get_formatted_shipping_address() ) ? $address : __('N/A', 'yit'); ?></p>
				</address>
			</div>
		</div>

	<?php endif; ?>
</div>