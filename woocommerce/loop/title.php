<?php
/**
 * Product loop title
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php if ( yit_get_option('shop-view-show-title') ) : ?><h3><?php the_title(); ?></h3><?php endif ?>
