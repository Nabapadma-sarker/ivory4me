<?php 
	global $woocommerce_loop;
	
	$ids = '';
	if ( isset( $category ) && $category != '' ) {
		$ids = explode( ',', $category );
	  	$ids = array_map( 'trim', $ids );
		if (in_array('0', $ids)) $ids = '';
	}	
	
	if ($per_page == -1) $per_page = 0;
	
	if (!isset($style) || $style == '' || strcmp($style, 'traditional') == 0) $style = '';
	
	$hide_empty = ( $hide_empty == true || strcmp($hide_empty, 'yes') == 1 ) ? 1 : 0;
	
  	$args = array(
  		'number'     => $per_page,
  		'orderby'    => $orderby,
  		'order'      => $order,
  		'hide_empty' => $hide_empty,
		'include'    => $ids,
	);
	
  	$terms = get_terms( 'product_cat', $args );
  	
	if ( $terms ) : ?>
		<div class="show-category <?php echo $style ?>">
  			<ul class="products">		
			<?php foreach ( $terms as $category ) {			
				yith_wc_get_template( 'content-product_cat.php', array(
					'category' => $category
				) );			
			} ?>
			</ul>
		</div>
	<?php endif;
	wp_reset_query();
?>