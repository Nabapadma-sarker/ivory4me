<?php
/**
 * Single Product Meta
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $post, $product;
?>
<style>
    .form-product-offer {
        border: solid 1px grey; 
        margin-top: 10px;
        padding: 10px;
    }
    .form-product-offer table {
        width: 100%;
        background: white;
    }
    p.text-error {
      margin-top: 0px;
    }
    .btn-inline {
        margin-bottom: 9px;
        height: 30px;
    }
    .table-make-offer td {
        vertical-align: baseline;
    }
    .btn-product-offer-submit {
        background: red;
        border: none;
        color: white;
    }
    #contact-form input { display: none;}
</style>

<!--
  <div id="contact-form" style="display:inline-block; float: right">
  <a href="javascript:void(0);" class="button contact wpi-button">Product enquiry</a>
  </div>
 --> 
  
  <div id="contact-form" title="<?php _e("Product Enquiry", "product-enquiry-for-woocommerce");?>" style="display:none;">
    <form id="enquiry-form" action="#" method="POST">
    <label id="wdm_product_name" for='product_name'> <?php echo get_the_title();?> </label>
	    <div class="wdm-pef-form-row">
		<label for='contact-name'>*<?php _e("Name", "product-enquiry-for-woocommerce");?>:</label>
        <input type='hidden' name='author_email' id='author_email' value='<?php echo $authorEmail ?>'>
		<input type='text' id='contact-name' class='contact-input' name='wdm_customer_name' value=""/>
	    </div>
	    <div class="wdm-pef-form-row">
		<label for='contact-email'>*<?php _e("Email", "product-enquiry-for-woocommerce");?>:</label>
		<input type='text' id='contact-email' class='contact-input' name='wdm_customer_email'  />
	    </div>
	    <div class="wdm-pef-form-row">
		<label for='contact-subject'><?php _e("Subject", "product-enquiry-for-woocommerce");?>:</label>
		<input type='text' id='contact-subject' class='contact-input' name='wdm_subject' value=''  />
	    </div>
	    <div class="wdm-pef-form-row">
		<label for='contact-message'>*<?php _e("Enquiry", "product-enquiry-for-woocommerce");?>:</label>
		<textarea id='contact-message' class='contact-input' name='wdm_enquiry' cols='40' rows='4' style="resize:none"></textarea>
	    </div>
	    <?php if (!empty($form_data['enable_send_mail_copy'])) {?>
	    <div class="wdm-pef-send-copy">
		<input type='checkbox' id='contact-cc' name='cc' value='1' /> <span class='contact-cc'>
		<?php _e("Send me a copy", "product-enquiry-for-woocommerce");?></span>
	    </div>
	    <?php }?>
	    <div id="errors"></div>
	    <div class="wdm-enquiry-action-btns">
		<button id="send-btn" type='submit' class='contact-send contact-button' ><?php _e("Send", "product-enquiry-for-woocommerce");?></button>
		<button id="cancel" type='button' class='contact-cancel contact-button' ><?php _e("Cancel", "product-enquiry-for-woocommerce");?></button>
	    </div>
	    <?php echo wp_nonce_field('enquiry_action', 'product_enquiry', true, false); ?>
	    
  </form>
    <?php
 
    $site_url=site_url();
    $domain_name=htmlspecialchars(url_to_domain($site_url));
    $domain_name_value=ord($domain_name);
    if ($domain_name_value>=97 && $domain_name_value<=102) {
        $display_url="https://wisdmlabs.com/";
        $display_message = 'WordPress Development Experts';
        $prefix = "Brought to you by WisdmLabs: ";
        $suffix = "";
    } else if ($domain_name_value>=103 && $domain_name_value<=108) {
        $display_url="https://wisdmlabs.com/wordpress-development-services/plugin-development/";
        $display_message = 'Expert WordPress Plugin Developer';
        $prefix = "Brought to you by WisdmLabs: ";
        $suffix = "";
    } elseif ($domain_name_value>=109 && $domain_name_value<=114) {
        $display_url="https://wisdmlabs.com/woocommerce-extension-development-customization-services/";
        $display_message = 'Expert WooCommerce Developer';
        $prefix = "Brought to you by WisdmLabs: ";
        $suffix = "";
    } else {
        $display_url="https://wisdmlabs.com/woocommerce-product-enquiry-pro/";
        $display_message = 'WooCommerce Enquiry Plugin';
        $prefix = "";
        $suffix = " by WisdmLabs";
    }
?>
<div class='contact-bottom'><a href='#' onclick="return false;"><?php// echo $prefix; ?></a><a href='<?php echo $display_url ?>' target='_blank' rel='nofollow'><?php// echo $display_message;?></a><a href='#' onclick="return false;"><?php echo $suffix; ?></a></div>
  </div>
  
  <style>
  input.contact.wpi-button {margin-left: 243px;background: #fff;
    border: 1px solid #8b8989;
    font-size: 13px;
    color: #212223;
	height:36px;
    padding: 8px 12px;
    font-weight: 700;
    margin-top: 20px;
    display: inline-block;
    line-height: normal;
    border-radius: 0;font-family: 'Noticia Text', sans-serif;}
	
	input.wpi-button:hover, input.wpi-button:active, input.wpi-button:focus {padding: 8px 12px !important; background: #212223 !important; color: #ffffff !important;border:none !important;font-family: 'Noticia Text', sans-serif;}
  #enquiry {/*margin-top: 20px;*/
    /* clear: both; */
    width: 0px;
    float: left;padding-bottom: 0px;}
	a.compare.button {margin-left:-10px}
  </style>
  
<div class="form-product-offer">
  <input type="hidden" id="user_id" value="<?php echo get_current_user_id(); ?>"
  <table class="table-make-offer">
    <tr>
      <td valign="top">Your Offer:</td>
      <td valign="top">$<input type="text" id="product_offer_offer_value" style="width: 100px"/></td>
      <td valign="top"><input type="button" class="btn-inline btn-product-offer-submit" id="submit-offer-form" value="Make An Offer" /></td>     
    </tr>
  </table>
  <input type="hidden" id="product_offer_user_id" value="<?php echo get_current_user_id(); ?>"/>
  <input type="hidden" id="product_offer_product_id" value="<?php echo $product->id; ?>" />
  <table style="display:none" id="table-make-offer">
    <tr>
      <td>Full Name:*</td>
      <td><input type="text" id="product_offer_full_name" /></td>
    </tr>
    <tr>
      <td>Email:*</td>
      <td><input type="text" id="product_offer_email" /></td>
    </tr>
    <tr>
      <td>Phone Number*</td>
      <td><input type="text" id="product_offer_phone" /></td>
    </tr>
    <tr>
      <td colspan="2">
        <input type="button" class="btn-product-offer-submit" value="Make An Offer" />
      </td>
    </tr>
  </table>
</div>
<script>
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    var openOfferFormDetail = false;
    jQuery(document).ready(function() {
        jQuery('.btn-product-offer-submit').on('click', function(event) {
            jQuery('.text-error').remove();
            userId = parseInt(jQuery('#user_id').val());
           
            if (userId==0 && !openOfferFormDetail) {
                openOfferFormDetail = true;
                jQuery('#table-make-offer').css('display', 'block');
                jQuery('#submit-offer-form').css('display', 'none');
                return;
            }
            event.preventDefault();
            
            jQuery.post(ajaxurl, {
                'action': 'add_product_offer',
                'product_id': jQuery('#product_offer_product_id').val(),
                'offer_value': jQuery('#product_offer_offer_value').val(),
                'user_id': jQuery('#product_offer_user_id').val(),
                'full_name': jQuery('#product_offer_full_name').val(),
                'email': jQuery('#product_offer_email').val(),
                'phone': jQuery('#product_offer_phone').val()    
            }, function (response) {
                if (!response.success) {
                    jQuery.each(response.data.error, function (key, val) {
                        var textError = "<p class='text-error'>"+val[0]+"</p>";
                        jQuery(textError).insertAfter(jQuery('#product_offer_'+key));
                    })
                } else {
                    jQuery('.form-product-offer').html(response.data.message);
                }
            })
        })
    });
</script>
<div class="product_meta">
   
  
  
    <?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( $product->is_type( array( 'simple', 'variable' ) ) && get_option('woocommerce_enable_sku') == 'yes' && $product->get_sku() ) : ?>
		<span itemprop="productID" class="sku"><?php _e('SKU:', 'yit'); ?> <?php echo $product->get_sku(); ?>.</span>
	<?php endif; ?>

    <?php
    $size = count( get_the_terms( $post->ID, 'product_cat' ) );
    echo $product->get_categories( ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', $size, 'yit' ) . ' ', '.</span>' );
    ?>

    <?php
    $size = count( get_the_terms( $post->ID, 'product_tag' ) );
    echo $product->get_tags( ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', $size, 'yit' ) . ' ', '.</span>' );
    ?>

    <?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>