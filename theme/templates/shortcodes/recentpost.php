<?php	
	global $icons_name;
    
    remove_filter( 'the_content_more_link', 'yit_sc_more_link', 10, 3 );  //shortcode in more links

    $defaults = array(
        'show_thumb' => 'no',
        'more_text' => __( 'Read more...', 'yit' ),
        'show_comments' => 'no',
        'show_author' => 'yes'
    );

    foreach ( $defaults as $arg => $value ) {
        if ( ! isset( ${$arg} ) ) ${$arg} = $value;
    }
    
    $args = array(
       'posts_per_page' => $items,
       'orderby' => 'date',
       'ignore_sticky_posts' => 1
    );                            
    
    if( isset($popular) ) $args['orderby'] = 'comment_count';
	if( isset( $cat_name ) && !empty( $cat_name ) ) $args['category_name'] = $cat_name;
    
    $args['order'] = 'DESC'; 
    
    $myposts = new WP_Query( $args );
	
    $html = "\n";       
    $html .= '<div class="recent-post group">'."\n";
    
    if( $myposts->have_posts() ) : while( $myposts->have_posts() ) : $myposts->the_post();

            $img = '';
            if(has_post_thumbnail())
                { $img = get_the_post_thumbnail( get_the_ID(), 'blog_thumb' ); }
            else
                { $img = '<img src="'.get_template_directory_uri().'/images/no_image_recentposts.jpg" alt="No Image" />'; }

            $html .= '<div class="hentry-post group">'."\n";
            if ( $show_thumb == 'yes' ) {
                $html .= "<div class=\"thumb-img\">$img</div>\n";
                $html .= '<div class="text">';
            } else {
                $html .= '<div class="text without-thumbnail">';
            }

            if( strpos( $more_text, "href='#'" ) ) {
                $post_readmore = str_replace( "href='#'", "href='" . get_permalink() . "'", str_replace( '"', "'", do_shortcode( $more_text ) ) );
            } else {
            	$post_readmore = $more_text;
            }
            if( $date == "yes" ) {
                $html .= '<p class="post-date">';
                $html .= '<span class="month">' . get_the_time('M') . '</span>';
                $html .= '<span class="day">' . get_the_time('d') . '</span>';
                $html .= '</p>';
            } else {
                $excerpt = '' . yit_content( 'excerpt', $excerpt_length, $post_readmore ) . '';
            }

            $html .= the_title( '<a href="'.get_permalink().'" title="'.get_the_title().'" class="title">', '</a>', false );
            if(isset($excerpt) && $excerpt!=''){
                $html .= $excerpt;
            }

            if( $show_comments == 'yes' ) {
                    $html .= '<span class="num-comments">' . get_comments_number() . ( get_comments_number() == 1 ? __( ' comment', 'yit' ) : __( ' comments', 'yit' ) ) . '</span>';
                }

            if($show_author=="yes"){
                $html .= '<span class="author">by '.'<a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'">'.get_the_author_link().'</a></span>';
            }
            $html .= '</div>'."\n";
    		$html .= '</div>'."\n";

        endwhile; endif;
    
    wp_reset_query();
    $html .= '</div>';
    
    add_filter( 'the_content_more_link', 'yit_sc_more_link', 10, 3 );  //shortcode in more links
?>
<?php echo $html; ?>