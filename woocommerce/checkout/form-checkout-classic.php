<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

global $woocommerce; $woocommerce_checkout = $woocommerce->checkout();
?>

<?php wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'yit' ) );
	return;
}

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', wc_get_checkout_url() ); ?>
<style>
#order_review .shop_table tfoot td { background-color:#eee!important; }
.coupon_form { display:none!important; }
</style>
<form name="checkout" method="post" class="checkout" action="<?php echo esc_url( $get_checkout_url ); ?>">

	<?php if ( sizeof( $woocommerce_checkout->checkout_fields ) > 0 ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details'); ?>
 
		<div class="row-fluid" id="customer_details">
			
            <div class="span6">
            
                <div class="col-1">
    
                    <div class="billing-fields"><?php do_action('woocommerce_checkout_billing'); ?></div>
                    
                    <div class="shipping-fields"><?php do_action('woocommerce_checkout_shipping'); ?></div>
    
                </div>                                
                
            </div>
            
           <div class="span6" style="padding:10px;background-color:#eeeeee;">
              
			    <div class="col-2">                                    <?php do_action( 'woocommerce_checkout_after_customer_details'); ?>                    <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>                    <div id="order_review">                        <h3 id="order_review_heading"><?php _e('Your order', 'yit'); ?></h3>                        <?php do_action('woocommerce_checkout_order_review'); ?>                    </div>                    <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>                    </div>

            
            </div>

		</div>


	<?php endif; ?>

	
</form>

<?php do_action( 'woocommerce_after_checkout_form', $woocommerce_checkout ); ?>