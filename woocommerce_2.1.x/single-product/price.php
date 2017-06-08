<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product, $price;

if ( ! isset( $price ) ) $price = $product->get_price_html();

if ( empty( $price ) ) return;
?>
<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">

    <p class="price"><span class="price-label"><?php _e('Price', 'yit') ?>:</span><span><?php echo $price; ?></span></p>

    <meta itemprop="price" content="<?php echo $product->get_price(); ?>" />
    <meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
    <link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />

</div>