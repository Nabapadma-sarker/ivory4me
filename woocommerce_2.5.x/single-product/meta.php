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
</style>
<?php 
    $list = get_the_terms( $post->ID, 'product_cat' ); 
    $fromCategory = false;
    foreach ($list as $value) {
        if ($value->name == "Mammoth Ivory") {
            $fromCategory = true;
            break;
        }
    }
?>
<?php if ($fromCategory) : ?>
<a href="<?php echo site_url(); ?>">
<img src="<?php echo get_template_directory_uri(); ?>/images/right-image.jpg" />
</a>
<?php endif; ?>
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
                console.log(12);
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