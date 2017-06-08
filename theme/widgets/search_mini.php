<?php
/**
 * Your Inspiration Themes 
 * 
 * @package WordPress
 * @subpackage Your Inspiration Themes
 * @author Your Inspiration Themes Team <info@yithemes.com>
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if( !class_exists( 'search_mini' ) ) :
/**
 * Search widget class
 *
 * @since 2.8.0
 */
class search_mini extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_search_mini', 'description' => __( "A mini search form for your site", 'yit' ) );
		parent::__construct('search_mini', __( 'Mini Search', 'yit' ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
        
        $value = is_shop_installed() ? 'product' : 'post';
        $value = apply_filters( 'yit_searchform_post_type', $value );
        $value = apply_filters( 'yit_mini_searchform_post_type', $value );
        
		echo $before_widget;

        if ( defined( 'YITH_WCAS' ) ): ?>
            <?php echo do_shortcode( '[yith_woocommerce_ajax_search]' ) ?>
        <?php else: ?>

<style>
  .search_mini input {
      margin-bottom: 0px;
      height: 32px; 
      width: initial !important;
      border-radius: 0px;
  }
 </style>
		<form action="<?php echo home_url( '/' ); ?>" method="get" class="search_mini">
      <?php echo woocommerce_product_search();  ?>
			<!--<input type="text" name="s" id="product-search-field-851969839" value="<?php the_search_query(); ?>" placeholder="<?php _e( 'search for...', 'yit' ) ?>" />
			<input type="hidden" name="post_type" value="<?php echo $value ?>" />
            <input type="submit" id="submit" name="submit" value="" />-->
		</form>
       <?php endif ?>
<?php		echo $after_widget;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array() );
?>
<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array());
		return $instance;
	}

}
endif;