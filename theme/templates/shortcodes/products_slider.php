<?php
	wp_enqueue_script( 'caroufredsel' );
	wp_enqueue_script( 'touch-swipe' );
	wp_enqueue_script( 'mousewheel' );

  	global $wpdb, $woocommerce, $woocommerce_loop;
  	
  	if ( isset( $latest ) && $latest == 'yes' ) {
        $orderby = 'date';
        $order = 'desc'; 
    }
	
  	$args = array(
		'post_type'	=> array( 'product', 'product_variation' ),
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'ignore_sticky_posts'	=> 1,
		'meta_query' => '',
        'fields' => 'id=>parent',
        'tax_query' => ''
	);

	if(isset( $featured) && $featured == 'yes' ){

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


  	}
	
	if(isset( $best_sellers) && $best_sellers == 'yes' ){
		$args['meta_key'] = 'total_sales';
    	$args['orderby'] = 'meta_value';
    	$args['order'] = 'desc';
  	}

    $query_args = array(
        'posts_per_page' 	=> $per_page,
        'no_found_rows' => 1,
        'post_status' 	=> 'publish',
        'post_type' 	=> 'product',
        'orderby' 		=> $orderby,
        'order' 		=> $order,
        'meta_query' 	=> $args['meta_query'],
        'tax_query' => $args['tax_query']
    );
	
	if(isset($atts['skus'])){
		$skus = explode(',', $atts['skus']);
	  	$skus = array_map('trim', $skus);
        $query_args['meta_query'][] = array(
      		'key' 		=> '_sku',
      		'value' 	=> $skus,
      		'compare' 	=> 'IN'
    	);
  	}
	
	if(isset($atts['ids'])){
		$ids = explode(',', $atts['ids']);
	  	$ids = array_map('trim', $ids);
        $query_args['post__in'] = $ids;
	}           
    
  if ( isset( $category ) && $category!= 'null' && $category != 'a:0:{}' && $category != '0' && $category!="0, ") {
      $query_args['product_cat'] = $category;
  }
    
  $woocommerce_loop['setLast'] = true;

	$products = new WP_Query( $query_args );
	
	$woocommerce_loop['view'] = 'grid';
    $woocommerce_loop['layout'] =  ( isset( $layout ) && $layout != 'default' ) ? $layout : '';
    $i = 0;
	if ( $products->have_posts() ) :
	    echo '<div class="woocommerce">';
		echo '<div class="products-slider-wrapper"><div class="products-slider">';
		if (isset($title) && $title != '')
			echo '<h4>'.$title.'</h4>';
		else
			echo '<h4>&nbsp;</h4>';
		echo '<ul class="products row">';
        while ( $products->have_posts() ) : $products->the_post();
            ( function_exists( 'wc_get_template_part' ) ) ? wc_get_template_part( 'content', 'product' ) : woocommerce_get_template_part( 'content', 'product' );
	        $i++;
		endwhile; // end of the loop.
		echo '</ul>';
		echo '<div class="es-nav"><span class="es-nav-prev">Previous</span><span class="es-nav-next">Next</span></div>';
		echo '</div></div><div class="es-carousel-clear"></div>';
		echo '</div>';
	endif;

    echo do_shortcode('[clear]');

	wp_reset_query();
	                         
	$woocommerce_loop['loop'] = 0;        
	unset( $woocommerce_loop['setLast'] );
	
?>