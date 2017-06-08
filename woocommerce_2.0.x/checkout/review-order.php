<?php
/**
 * Review order form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

global $woocommerce;

$available_methods = $woocommerce->shipping->get_available_shipping_methods();
?>
<div id="order_review">

	<table class="shop_table">
		<thead>
			<tr>
				<th class="product-name"><?php _e('Product', 'yit'); ?></th>
				<th class="product-total"><?php _e('Totals', 'yit'); ?></th>
			</tr>
		</thead>
		<tfoot>
			
			<tr class="cart-subtotal">
				<th><?php _e('Cart Subtotal', 'yit'); ?></th>
				<td><?php echo $woocommerce->cart->get_cart_subtotal(); ?></td>
			</tr>

			<?php if ($woocommerce->cart->get_discounts_before_tax()) : ?>

			<tr class="discount">
				<th><?php _e('Cart Discount', 'yit'); ?></th>
				<td>-<?php echo $woocommerce->cart->get_discounts_before_tax(); ?></td>
			</tr>

			<?php endif; ?>

			<?php if ( $woocommerce->cart->needs_shipping() && $woocommerce->cart->show_shipping() ) : ?>

			<?php do_action('woocommerce_review_order_before_shipping'); ?>

				<tr class="shipping">
					<th><?php _e( 'Shipping', 'yit' ); ?></th>
					<td><?php woocommerce_get_template( 'cart/shipping-methods.php', array( 'available_methods' => $available_methods ) ); ?></td>
				</tr>

				<?php do_action('woocommerce_review_order_after_shipping'); ?>

			<?php endif; ?>

			<?php foreach ( $woocommerce->cart->get_fees() as $fee ) : ?>

				<tr class="fee fee-<?php echo $fee->id ?>">
					<th><?php echo $fee->name ?></th>
					<td><?php
						if ( $woocommerce->cart->tax_display_cart == 'excl' )
							echo woocommerce_price( $fee->amount );
						else
							echo woocommerce_price( $fee->amount + $fee->tax );
					?></td>
				</tr>

			<?php endforeach; ?>

			<?php
				// Show the tax row if showing prices exlcusive of tax only
				if ( $woocommerce->cart->tax_display_cart == 'excl' ) {

					$taxes = $woocommerce->cart->get_formatted_taxes();

					if ( sizeof( $taxes ) > 0 ) {

						$has_compound_tax = false;

						foreach ( $taxes as $key => $tax ) {
							if ( $woocommerce->cart->tax->is_compound( $key ) ) {
								$has_compound_tax = true;
								continue;
							}
							?>
							<tr class="tax-rate tax-rate-<?php echo $key; ?>">
								<th><?php echo $woocommerce->cart->tax->get_rate_label( $key ); ?></th>
								<td><?php echo $tax; ?></td>
							</tr>
							<?php
						}

						if ( $has_compound_tax ) {
							?>
							<tr class="order-subtotal">
								<th><?php _e( 'Subtotal', 'yit' ); ?></th>
								<td><?php echo $woocommerce->cart->get_cart_subtotal( true ); ?></td>
							</tr>
							<?php
						}

						foreach ( $taxes as $key => $tax ) {
							if ( ! $woocommerce->cart->tax->is_compound( $key ) )
								continue;
							?>
							<tr class="tax-rate tax-rate-<?php echo $key; ?>">
								<th><?php echo $woocommerce->cart->tax->get_rate_label( $key ); ?></th>
								<td><?php echo $tax; ?></td>
							</tr>
							<?php
						}

					} elseif ( $woocommerce->cart->get_cart_tax() ) {
						?>
						<tr class="tax">
							<th><?php _e( 'Tax', 'yit' ); ?></th>
							<td><?php echo $woocommerce->cart->get_cart_tax(); ?></td>
						</tr>
						<?php
					}
				}
			?>

			<?php if ($woocommerce->cart->get_discounts_after_tax()) : ?>

			<tr class="discount">
				<th><?php _e('Order Discount', 'yit'); ?></th>
				<td>-<?php echo $woocommerce->cart->get_discounts_after_tax(); ?></td>
			</tr>

			<?php endif; ?>

			<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

			<tr class="total">
				<th><strong><?php _e( 'Order Total', 'yit' ); ?></strong></th>
				<td>
					<strong><?php echo $woocommerce->cart->get_total(); ?></strong>
					<?php
						// If prices are tax inclusive, show taxes here
						if ( $woocommerce->cart->tax_display_cart == 'incl' ) {
							$tax_string_array = array();
							$taxes = $woocommerce->cart->get_formatted_taxes();

							if ( sizeof( $taxes ) > 0 ) {
								foreach ( $taxes as $key => $tax ) {
									$tax_string_array[] = sprintf( '%s %s', $tax, $woocommerce->cart->tax->get_rate_label( $key ) );
								}
							} elseif ( $woocommerce->cart->get_cart_tax() ) {
								$tax_string_array[] = sprintf( '%s tax', $tax );
							}

							if ( ! empty( $tax_string_array ) ) {
								?><small class="includes_tax"><?php printf( __( '(Includes %s)', 'yit' ), implode( ', ', $tax_string_array ) ); ?></small><?php
							}
						}
					?>
				</td>
			</tr>

			<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

		</tfoot>
		<tbody>
			<?php
				do_action( 'woocommerce_review_order_before_cart_contents' );

				if (sizeof($woocommerce->cart->get_cart())>0) :
					foreach ($woocommerce->cart->get_cart() as $item_id => $values) :
						$_product = $values['data'];
						if ($_product->exists() && $values['quantity']>0) :
							echo '
								<tr class="' . esc_attr( apply_filters('woocommerce_checkout_table_item_class', 'checkout_table_item', $values, $item_id ) ) . '">
									<td class="product-name">' . $_product->get_title().$woocommerce->cart->get_item_data( $values ) . ' <strong class="product-quantity">&times; ' . $values['quantity'] . '</strong></td>
									<td class="product-total">' . apply_filters( 'woocommerce_checkout_item_subtotal', $woocommerce->cart->get_product_subtotal( $_product, $values['quantity'] ), $values, $item_id ) . '</td>
								</tr>';
						endif;
					endforeach;
				endif;

				do_action( 'woocommerce_review_order_after_cart_contents' );
			?>
		</tbody>
	</table>
    
	<?php if( !yit_get_option('shop-checkout-multistep') ): ?>
		<?php woocommerce_get_template('checkout/form-payment.php', array('woocommerce' => $woocommerce)); ?>
	<?php else: ?>
		<?php $checkout = $woocommerce->checkout(); ?>
		<?php do_action('woocommerce_before_order_notes', $checkout); ?>
		
		<?php if (get_option('woocommerce_enable_order_comments')!='no') : ?>
		
			<h3><?php _e('Additional Information', 'yit'); ?></h3>
		
			<?php foreach ($checkout->checkout_fields['order'] as $key => $field) : ?>
		
				<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
		
			<?php endforeach; ?>
		
		<?php endif; ?>
		
		<?php do_action('woocommerce_after_order_notes', $checkout); ?>

		<?php woocommerce_get_template('checkout/form-place-order.php', array('woocommerce' => $woocommerce)); ?>
	<?php endif ?>

</div>