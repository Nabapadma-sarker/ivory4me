<?php
  	if (!isset($show) || $show == '') $show = 'all';
  	if (!isset($per_page) || $per_page == '') $per_page = '-1';
	if (!isset($orderby) || $orderby == '') $orderby = 'menu_order';
	if (!isset($order) || $order == '') $order = 'desc';
	
	$args = array(
		'post_type'	=> 'product',
		'post_status' => 'publish',
		'posts_per_page' => $per_page,
		'ignore_sticky_posts'	=> 1,
		'orderby' => $orderby,
		'order' => $order,
		'meta_query' => '',
	);

    //Ordering by price
    if( $orderby == 'meta_value_num' ) {
        $args['meta_key'] = '_price';
    }

    if ( $pagination == 'yes' ) {

        $page_var_name = is_front_page() ? 'page' : 'paged';

        $paged               = get_query_var( $page_var_name ) ? get_query_var( $page_var_name ) : 1;
        $args['paged'] = $paged;

    }
	
	if (strcmp($show, 'all') == 0) { // show all products

		$args = yit_product_visibility_meta( $args );
		
	}elseif (strcmp($show, 'featured') == 0) { // show featured products
  		if( IS_PRIOR_3_0 ){
			$args['meta_query'][] = array(
				'key' 		=> '_featured',
				'value' 	=> 'yes'
			);
		}else{
			$tax_query   = WC()->query->get_tax_query();
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => 'IN',
			);
			$args['tax_query'] = $tax_query;
		}

		
	}elseif (strcmp($show, 'best_sellers') == 0) { // show best sellers products
  		
  		$args['meta_key'] = 'total_sales';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
		
	}elseif (strcmp($show, 'onsale') == 0) { // show onsale products
  		
  		$args['meta_query'][] = array(
	    	'key' => '_sale_price',
	        'value' 	=> 0,
			'compare' 	=> '>'
        );
		
	}
	
	if(isset($skus)){
		$skus = explode(',', $skus);
	  	$skus = array_map('trim', $skus);
    	$args['meta_query'][] = array(
      		'key' 		=> '_sku',
      		'value' 	=> $skus,
      		'compare' 	=> 'IN'
    	);
  	}
	
	if(isset($ids)){
		$ids = explode(',', $ids);
	  	$ids = array_map('trim', $ids);
    	$args['post__in'] = $ids;
	}
    
    if(!empty( $category )) {
        $tax = 'product_cat';
        $category = array_map( 'trim', explode( ',', $category ) );
        if ( count($category) == 1 ) $category = $category[0];
        $args['tax_query'] = array(
            array(
                'taxonomy' => $tax,
                'field' => 'slug',
                'terms' => $category
            )
        );
    }	
	
	if ( $show == 'best_sellers' ) { // show best sellers products
        $args['meta_key'] = 'total_sales';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
    }

	$products = new WP_Query( $args );
	
	global $woocommerce_loop;
	$woocommerce_loop['loop'] = 0;
	if ( isset( $layout ) && $layout != 'default' ) $woocommerce_loop['layout'] = $layout;
	//$woocommerce_loop['columns'] = $columns;

	if ( $products->have_posts() ) : ?>
		
		<ul class="products">
			
			<?php while ( $products->have_posts() ) : $products->the_post(); ?>
		
				<?php ( function_exists( 'wc_get_template_part' ) ) ? wc_get_template_part( 'content', 'product' ) : woocommerce_get_template_part( 'content', 'product' ); ?>
	
			<?php endwhile; // end of the loop. ?>
				
		</ul>
        
        <div class="clear"></div>

		<?php
        if( $pagination == 'yes' ) {
			yit_pagination( $products->max_num_pages );
        }
		?> 
		
	<?php endif; 

	wp_reset_query();       
	                       
	$woocommerce_loop['loop'] = 0;
	
?>
