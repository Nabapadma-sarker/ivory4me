<?php
/**
 * All functions and hooks for woocommerce plugin
 *
 * @package WordPress
 * @subpackage YIW Themes
 * @since 1.4
 */

global $woocommerce;

define( 'WC_LATEST_VERSION', '2.6' );

/* fix 2.4 */
if( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.4', '>=' ) ) {

    remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
    remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );

    if( ! function_exists( 'yit_woocommerce_single_variation_quantity' ) ) {
        function yit_woocommerce_single_variation_quantity() {
            ?>
            <div class="variations_button for_quantity">
                <?php if ( is_shop_enabled() ) {
                    woocommerce_quantity_input();
                } ?>
            </div>
            <?php
        }
    }

    if( ! function_exists( 'yit_woocommerce_single_variation_add_button' ) ){
        function yit_woocommerce_single_variation_add_button(){
            global $product;

            $show_add_to_cart = (is_shop_enabled() && yit_get_option('shop-detail-add-to-cart')) ;

            if( yit_get_option('shop-detail-show-price') || $show_add_to_cart ) : ?>
                <div class="woocommerce-price-and-add group">

                    <?php if( yit_get_option('shop-detail-show-price') ): ?>
                        <div class="single_variation"></div>
                    <?php endif ?>

                    <?php if( $show_add_to_cart ) : ?>
                        <div class="variations_button">
                            <input type="hidden" name="add-to-cart" value="<?php echo $product->id; ?>" />
                            <input type="hidden" name="product_id" value="<?php echo esc_attr( $product->id ); ?>" />
                            <input type="hidden" name="variation_id" value="" />
                            <button type="submit" class="single_add_to_cart_button button alt"><?php echo apply_filters('single_add_to_cart_text', __('Add to cart', 'yit'), $product->product_type); ?></button>
                        </div>
                    <?php endif ?>
                    <!--/div-->
                </div>
            <?php endif;
        }

        add_action( 'woocommerce_single_variation', 'yit_woocommerce_single_variation_quantity', 10 );
        add_action( 'woocommerce_single_variation', 'yit_woocommerce_single_variation_add_button', 20 );
    }
}

/* fix 2.1 */
global $woo_shop_folder;


if ( ! defined( 'YIT_DEBUG' ) || ! YIT_DEBUG ) {
    $message = get_option( 'woocommerce_admin_notices', array() );
    $message = array_diff( $message, array( 'template_files' ) );
    update_option( 'woocommerce_admin_notices', $message );
}

/* woocommerce 2.0.x */
if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.1', '<' ) ) {

    add_filter( 'woocommerce_template_url', create_function( "", "return 'woocommerce_2.0.x/';" ) );
    add_action( 'wp_enqueue_scripts', 'yit_enqueue_woocommerce_styles', 11 );
    $woo_shop_folder = 'shop';

} else {

    /* woocommerce 2.1.x */
    if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.2', '<' ) ) {
        add_filter( 'WC_TEMPLATE_PATH', create_function( "", "return 'woocommerce_2.1.x/';" ) );
    }
    /* woocommerce 2.2.x */
    else if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.3', '<' ) ) {
        add_filter( 'woocommerce_template_path', create_function( "", "return 'woocommerce_2.2.x/';" ) );
    }/* woocommerce 2.3.x */
    else if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.4', '<' ) ) {
        add_filter( 'woocommerce_template_path', create_function( "", "return 'woocommerce_2.3.x/';" ) );
    }/* woocommerce 2.4.x */
    else if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.5', '<' ) ) {
        add_filter( 'woocommerce_template_path', create_function( "", "return 'woocommerce_2.4.x/';" ) );
    }/* woocommerce 2.5.x */
    else if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.6', '<' ) ) {
        add_filter( 'woocommerce_template_path', create_function( "", "return 'woocommerce_2.5.x/';" ) );
    }

    if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.2', '>' ) ) {
        add_action( 'wp_enqueue_scripts', 'yit_enqueue_woocommerce_2_3_assets' );
    }

    add_filter( 'woocommerce_enqueue_styles', 'yit_enqueue_wc_styles' );
    $woo_shop_folder = 'global';
    add_action( 'admin_init', 'yit_check_version', 8 );

    if ( ! is_active_widget( false, false, 'woocommerce_price_filter', true ) ) {
        add_filter( 'loop_shop_post_in', array( WC()->query, 'price_filter' ) );
    }

}

function yit_enqueue_woocommerce_2_3_assets() {
    wp_enqueue_script( 'yit-woocommerce-2-3', yit_remove_protocol_url(YIT_THEME_ASSETS_URL) . '/js/woocommerce_2.3.js', array( 'jquery', 'jquery-cookie' ), '1.0', true );
}


function yit_check_version() {
    if ( get_option( 'yit_theme_version_1.2.0' ) || ! isset( $_GET['do_update_woocommerce'] ) ) {
        return;
    }
    clear_menu_from_old_woo_pages();
    update_option( 'yit_theme_version_1.2.0', true );
}

function clear_menu_from_old_woo_pages() {
    $locations = get_nav_menu_locations();
    $logout    = get_page_by_path( 'my-account/logout' );
    $parent    = get_page_by_path( 'my-account' );
    $permalink = get_option( 'permalink_structure' );

    $pages_deleted = array(
        get_option( 'woocommerce_pay_page_id' ), get_option( 'woocommerce_thanks_page_id' ), get_option( 'woocommerce_view_order_page_id' ), get_option( 'woocommerce_view_order_page_id' ),
        get_option( 'woocommerce_change_password_page_id' ), get_option( 'woocommerce_edit_address_page_id' ), get_option( 'woocommerce_lost_password_page_id' )
    );


    foreach ( (array) $locations as $name => $menu_ID ) {
        $items = wp_get_nav_menu_items( $menu_ID );
        foreach ( (array) $items as $item ) {

            if ( ! is_null( $logout ) && ! is_null( $parent ) && $item->object_id == $logout->ID ) {
                update_post_meta( $item->ID, '_menu_item_object', 'custom' );
                update_post_meta( $item->ID, '_menu_item_type', 'custom' );
                if ( $permalink == '' ) {
                    $new_url = get_permalink( $parent->ID ) . '&customer-logout';
                }
                else {
                    wp_update_post( array(
                            'ID'        => $logout->ID,
                            'post_name' => 'customer-logout', )
                    );
                    $new_url = get_permalink( $logout->ID );
                }
                update_post_meta( $item->ID, '_menu_item_url', $new_url );
                wp_update_post( array(
                        'ID'         => $item->ID,
                        'post_title' => $logout->post_title, )
                );
            }

            foreach ( $pages_deleted as $page ) {

                if ( $page && $item->object_id == $page && $item->object == 'page' ) {

                    wp_delete_post( $item->ID );

                }

            }
        }

    }
}

/* end fix 2.1 */

// global flag to know that woocommerce is active
$yiw_is_woocommerce = true;

/* === GENERAL SETTINGS === */
register_sidebar( yit_sidebar_args( 'Shop Sidebar' ) );

// add support to woocommerce
add_theme_support( 'woocommerce' );


/* === HOOKS === */
add_action( 'wp_enqueue_scripts', 'yit_enqueue_woocommerce_assets' );
add_action( 'woocommerce_before_main_content', 'yit_shop_page_meta' );
if ( yit_get_option( 'shop-show-breadcrumb' ) ) add_action( 'shop_page_meta', 'woocommerce_breadcrumb' );
add_action( 'shop_page_meta'     , 'yit_woocommerce_list_or_grid' );
add_action( 'shop_page_meta'     , 'yit_woocommerce_catalog_ordering' );
add_filter( 'woocommerce_page_settings', 'yit_woocommerce_deactive_logout_link' );
add_action( 'wp_head', 'yit_size_images_style' );
add_filter( 'loop_shop_per_page' , 'yit_set_posts_per_page');
//add_filter( 'woocommerce_catalog_settings', 'yit_add_responsive_image_option' );
add_filter( 'wp_redirect', 'yit_remove_add_to_cart_query', 10, 2 );

add_action( 'yit_activated', 'yit_woocommerce_default_image_dimensions');
add_action( 'admin_init', 'yit_woocommerce_update' ); //update image names after woocommerce update

add_filter( 'yit_sample_data_tables',  'yit_save_woocommerce_tables' );
add_filter( 'yit_sample_data_options', 'yit_save_woocommerce_options' );
add_filter( 'yit_sample_data_options', 'yit_save_wishlist_options' );
add_filter( 'yit_sample_data_options', 'yit_add_plugins_options' );

function yit_woocommerce_hooks() {
    global $woocommerce;

    if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.3', '<' ) ) {
        add_filter( 'add_to_cart_fragments', 'yit_add_to_cart_success_ajax' );
    } else {
        add_filter( 'woocommerce_add_to_cart_fragments', 'yit_add_to_cart_success_ajax' );
    }
}
add_action( 'after_setup_theme', 'yit_woocommerce_hooks' );

if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.1', '<=' ) ) {
    add_filter( 'woocommerce_get_image_size_shop_featured', 'yit_get_featured_image_size' );
} else {

    add_filter( 'yit_add_image_size', 'yit_add_featured_image_size' );

}

/* shop */
//add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 10 );
add_action( 'yith_after_thumbnail_product', 'woocommerce_template_loop_add_to_cart' ); // fix hover iphone
if ( yit_get_option('shop-view-show-description') )
    add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_single_excerpt', 5 );
remove_action( 'woocommerce_before_main_content' , 'woocommerce_breadcrumb', 20, 0 );
remove_action( 'woocommerce_pagination'          , 'woocommerce_catalog_ordering', 20 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
if ( yit_get_option('shop-view-show-rating') ) add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 15 );
add_filter( 'yith-wcan-frontend-args', 'yit_wcan_change_pagination_class' );


remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
/* single */
add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
add_filter( 'yith_wcwl_add_to_wishlist_std_icon', create_function( '', 'return "icon-heart-empty";' ) );
if ( ! has_action( 'woocommerce_share' ) )
    add_action( 'woocommerce_share', 'yit_woocommerce_share' );
//add_action( 'woocommerce_single_product_summary', 'yit_woocommerce_compare_link', 35 );

function yit_add_to_cart_success_ajax( $datas ) {
    global $woocommerce;

    // quantity
    $qty = 0;
    if (sizeof($woocommerce->cart->get_cart())>0) : foreach ($woocommerce->cart->get_cart() as $item_id => $values) :

        $qty += $values['quantity'];

    endforeach; endif;

    $datas['#topbar .wrap .cart_control span.count'] = '<span class="count">' . $qty . '</span>';

    return $datas;
}


// upsell rich snippet fix
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display', 15 );


/* related */
if ( yit_get_option('shop-show-related') ) {
    add_action( 'woocommerce_related_products_args', 'yit_related_posts_per_page', 1 );
}

if ( ! yit_get_option( 'shop-show-related-view' ) ) {
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
}
else {
    // rich snippet fix
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
    add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 20 );
}

function yit_related_posts_per_page() {
    global $product;
    $related = $product->get_related(yit_get_option('shop-number-related'));

    return array(
        'posts_per_page' 		=> yit_get_option('shop-number-related'),
        'post_type'				=> 'product',
        'ignore_sticky_posts'	=> 1,
        'no_found_rows' 		=> 1,
        'post__in' 				=> $related
    );
}

/* tabs */
add_action( 'woocommerce_product_tabs', 'yit_woocommerce_add_tabs' );  // Woo 2
//add_action( 'woocommerce_product_tabs', 'yit_woocommerce_add_info_tab', 40 );
add_action( 'woocommerce_product_tab_panels', 'yit_woocommerce_add_info_panel', 40 );
//add_action( 'woocommerce_product_tabs', 'yit_woocommerce_add_custom_tab', 50 );
add_action( 'woocommerce_product_tab_panels', 'yit_woocommerce_add_custom_panel', 50 );


/* cart */

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart' , 'woocommerce_cross_sell_display');


//if ( yit_get_option( 'shop-show-breadcrumb' ) ) add_action( 'shop_page_meta', 'woocommerce_breadcrumb' );
// active the price filter
global $woocommerce;

if( version_compare($woocommerce->version,"2.0.0",'<') ) {
    add_action('init', 'woocommerce_price_filter_init');
}


/* fix woo2*/
if( yit_get_option('shop-fields-order') ) {
    add_filter( 'woocommerce_billing_fields' , 'woocommerce_restore_billing_fields_order' );
    function woocommerce_restore_billing_fields_order( $fields ) {
        global $woocommerce;

        $fields['billing_city']['class'][0] = 'form-row-last';
        $fields['billing_country']['class'][0] = 'form-row-first';
        $fields['billing_address_1']['class'][0] = 'form-row-first';
        $fields['billing_address_2']['class'][0] = 'form-row-last';
        $fields['billing_state'][0] = 'form-row-wide';

        /* FIX WOO 2.1.x */
        if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.1', '>=' ) ) {
            $fields['billing_country']['class'][0] = 'form-row-wide';
        }

        $country = $fields['billing_country'];
        unset( $fields['billing_country'] );
        yit_array_splice_assoc( $fields, array('billing_country' => $country), 'billing_postcode' );

        return $fields;
    }

    add_filter( 'woocommerce_shipping_fields' , 'woocommerce_restore_shipping_fields_order' );
    function woocommerce_restore_shipping_fields_order( $fields ) {
        global $woocommerce;
        if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.1', '<' ) ) {
            $fields['shipping_city']['class'][0] = 'form-row-last';
        }
        $fields['shipping_country']['class'][0] = 'form-row-first';
        $fields['shipping_address_1']['class'][0] = 'form-row-first';
        $fields['shipping_address_2']['class'][0] = 'form-row-last';
        $fields['shipping_state'][0] = 'form-row-wide';

        /* FIX WOO 2.1.x */
        if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.1', '>=' ) ) {
            $fields['shipping_country']['class'][0] = 'form-row-wide';
        }

        $country = $fields['shipping_country'];
        unset( $fields['shipping_country'] );
        yit_array_splice_assoc( $fields, array('shipping_country' => $country), 'shipping_postcode' );

        return $fields;
    }
}

/* compare */
global $yith_woocompare;
if ( isset($yith_woocompare) ) {
    remove_action( 'woocommerce_after_shop_loop_item', array( $yith_woocompare->obj, 'add_compare_link' ), 20 );
    if ( get_option( 'yith_woocompare_compare_button_in_products_list' ) == 'yes' ) add_action( 'woocommerce_after_shop_loop_item_title', array( $yith_woocompare->obj, 'add_compare_link' ), 20 );
}
/* === FUNCTIONS === */
function yit_remove_add_to_cart_query( $location, $status ) {
    return esc_url_raw( remove_query_arg( 'add-to-cart', $location ) );
}

function yit_woocommerce_catalog_ordering() {
    if ( ! is_single() ) woocommerce_catalog_ordering();
}

function yit_set_posts_per_page( $cols ) {
    $items = yit_get_option( 'shop-products-per-page', $cols );
    return $items == 0 ? -1 : $items;
}

function yit_woocommerce_list_or_grid() {
    global $woo_shop_folder;
    yith_wc_get_template( $woo_shop_folder . '/list-or-grid.php' );
}

function yit_shop_page_meta() {
    global $woo_shop_folder;
    //if ( is_single() ) return;
    yith_wc_get_template( $woo_shop_folder.'/page-meta.php' );
}

function yit_woocommerce_show_product_thumbnails() {
    yith_wc_get_template( 'single-product/thumbs.php' );
}

function yit_woocommerce_compare_link() {
    if(function_exists('woo_add_compare_button')) echo woo_add_compare_button(), '<a class="woo_compare_button_go"></a>';
}

function yit_wcan_change_pagination_class( $args ) {
    $args['pagination'] = '.general-pagination';
    return $args;
}

/* Woo >= 2 */
function yit_woocommerce_add_tabs( $tabs ) {

    if ( yit_get_post_meta( yit_post_id(), '_use_ask_info' ) == 1 ) {
        $tabs['info'] = array(
            'title'    => apply_filters( 'yit_ask_info_label', __('Product Inquiry', 'yit') ),
            'priority' => 30,
            'callback' => 'yit_woocommerce_add_info_panel'
        );
    }

    $custom_tabs = yit_get_post_meta( yit_post_id(), '_custom_tabs');
    if( !empty( $custom_tabs ) ) {
        foreach( $custom_tabs as $tab ) {
            $tabs['custom-'.$tab["position"]] = array(
                'title'    => $tab["name"],
                'priority' => 30 + $tab["position"],
                'callback' => 'yit_woocommerce_add_custom_panel',
                'custom_tab' => $tab
            );
        }
    }

    return $tabs;
}

/* custom and info tabs */
function yit_woocommerce_add_info_tab() {
    yith_wc_get_template( 'single-product/tabs/tab-info.php' );
}

function yit_woocommerce_add_info_panel() {
    yith_wc_get_template( 'single-product/tabs/info.php' );
}

function yit_woocommerce_add_custom_tab() {
    yith_wc_get_template( 'single-product/tabs/tab-custom.php' );
}

function yit_woocommerce_add_custom_panel( $key, $tab ) {
    yith_wc_get_template( 'single-product/tabs/custom.php', array( 'key' => $key, 'tab' => $tab ) );
}

function woocommerce_template_loop_product_thumbnail() {
    global $product;

    echo '<a href="' . get_permalink() . '" class="thumb">' . woocommerce_get_product_thumbnail();

    // add another image for hover
    $attachments = $product->get_gallery_attachment_ids();
    if ( ! empty( $attachments ) && isset( $attachments[0] ) ) {
        yit_image( "id=$attachments[0]&size=shop_catalog&class=image-hover" );
    }

    echo  '</a>';
}

/* share */
function yit_woocommerce_share() {
    if( !yit_get_option( 'shop-share' ) )
        { return; }

    echo do_shortcode('[share class="product-share" title="' . yit_get_option( 'shop-share-title' ) . '" socials="' . yit_get_option( 'shop-share-socials' ) . '"]');
}

/* logout link */
function yit_woocommerce_deactive_logout_link( $options ) {
    foreach ( $options as $option ) {
        if ( isset( $option['id'] ) && $option['id'] != 'yit_woocommerce_deactive_logout_link' ) continue;

        $option['std'] = 'no';
        break;
    }

    return $options;
}

/* checkout */
add_filter('wp_redirect', 'yit_woocommerce_checkout_registration_redirect');
function yit_woocommerce_checkout_registration_redirect( $location ) {
    if ( isset($_POST['register']) && $_POST['register'] && isset($_POST['yit_checkout']) && $location == get_permalink(yith_wc_get_page_id('myaccount')) ) {
        $location = get_permalink(yith_wc_get_page_id('checkout'));
    }

    return $location;
}

// Detect the span to use for the products list
function yit_detect_span_catalog_image( $classes ) {
    global $woocommerce_loop, $yit_is_feature_tab;

    $content_width = yit_get_sidebar_layout() == 'sidebar-no' ? 1170 : 870;
    if ( isset( $yit_is_feature_tab ) && $yit_is_feature_tab ) $content_width -= 300;
    $product_width = yit_shop_catalog_w() + ( $woocommerce_loop['layout'] == 'classic' ? 6 : 10 ) + 2;  // 10 = padding & 2 = border
    $is_span = false;
    if ( get_option('woocommerce_responsive_images') == 'yes' ) {
        $is_span = true;
        if ( yit_get_sidebar_layout() == 'sidebar-no' ) {
            if ( $product_width >= 0   && $product_width < 120 ) { $classes[] = 'span1'; $woocommerce_loop['columns'] = 12; }
            elseif ( $product_width >= 120 && $product_width < 220 ) { $classes[] = 'span2'; $woocommerce_loop['columns'] = 6;  }
            elseif ( $product_width >= 220 && $product_width < 320 ) { $classes[] = 'span3'; $woocommerce_loop['columns'] = 4;  }
            elseif ( $product_width >= 320 && $product_width < 470 ) { $classes[] = 'span4'; $woocommerce_loop['columns'] = 3;  }
            elseif ( $product_width >= 470 && $product_width < 620 ) { $classes[] = 'span6'; $woocommerce_loop['columns'] = 2;  }
            else $is_span = false;

        } else {
            if ( $product_width >= 0   && $product_width < 150 ) { $classes[] = 'span1'; $woocommerce_loop['columns'] = 12; }
            elseif ( $product_width >= 150 && $product_width < 620 ) { $classes[] = 'span3'; $woocommerce_loop['columns'] = 3;  }
            else $is_span = false;

        }

    } else {
        $grid = yit_get_span_from_width( $product_width );
        $classes[] = 'span' . $grid;
        $product_width = yit_width_of_span( $grid );
    }
    if ( $yit_is_feature_tab || ! $is_span ) $woocommerce_loop['columns'] = floor( ( $content_width + 30 ) / ( $product_width + 30 ) );

    return $classes;
}


/**
 * SIZES
 */

if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.1', '<' ) ) {
   // shop small
    if ( ! function_exists('yit_shop_catalog_w') ) : function yit_shop_catalog_w()      { global $woocommerce; $size = $woocommerce->get_image_size('shop_catalog'); return $size['width']; } endif;
    if ( ! function_exists('yit_shop_catalog_h') ) : function yit_shop_catalog_h()      { global $woocommerce; $size = $woocommerce->get_image_size('shop_catalog'); return $size['height']; } endif;
    if ( ! function_exists('yit_shop_catalog_c') ) : function yit_shop_catalog_c()      { global $woocommerce; $size = $woocommerce->get_image_size('shop_catalog'); return $size['crop']; } endif;
    // shop thumbnail
    if ( ! function_exists('yit_shop_thumbnail_w') ) : function yit_shop_thumbnail_w()  { global $woocommerce; $size = $woocommerce->get_image_size('shop_thumbnail'); return $size['width']; } endif;
    if ( ! function_exists('yit_shop_thumbnail_h') ) : function yit_shop_thumbnail_h()  { global $woocommerce; $size = $woocommerce->get_image_size('shop_thumbnail'); return $size['height']; } endif;
    if ( ! function_exists('yit_shop_thumbnail_c') ) : function yit_shop_thumbnail_c()  { global $woocommerce; $size = $woocommerce->get_image_size('shop_thumbnail'); return $size['crop']; } endif;
    // shop large
    if ( ! function_exists('yit_shop_single_w') ) : function yit_shop_single_w()        { global $woocommerce; $size = $woocommerce->get_image_size('shop_single'); return $size['width']; } endif;
    if ( ! function_exists('yit_shop_single_h') ) : function yit_shop_single_h()        { global $woocommerce; $size = $woocommerce->get_image_size('shop_single'); return $size['height']; } endif;
    if ( ! function_exists('yit_shop_single_c') ) : function yit_shop_single_c()        { global $woocommerce; $size = $woocommerce->get_image_size('shop_single'); return $size['crop']; } endif;
    // shop featured
    if ( ! function_exists('yit_shop_featured_w') ) : function yit_shop_featured_w()    { global $woocommerce; $size = $woocommerce->get_image_size('shop_featured'); return $size['width']; } endif;
    if ( ! function_exists('yit_shop_featured_h') ) : function yit_shop_featured_h()    { global $woocommerce; $size = $woocommerce->get_image_size('shop_featured'); return $size['height']; } endif;
    if ( ! function_exists('yit_shop_featured_c') ) : function yit_shop_featured_c()    { global $woocommerce; $size = $woocommerce->get_image_size('shop_featured'); return $size['crop']; } endif;
}else {
    // shop small
    if ( ! function_exists('yit_shop_catalog_w') ) : function yit_shop_catalog_w()      { $size = wc_get_image_size('shop_catalog'); return $size['width']; } endif;
    if ( ! function_exists('yit_shop_catalog_h') ) : function yit_shop_catalog_h()      { $size = wc_get_image_size('shop_catalog'); return $size['height']; } endif;
    if ( ! function_exists('yit_shop_catalog_c') ) : function yit_shop_catalog_c()      { $size = wc_get_image_size('shop_catalog'); return $size['crop']; } endif;
    // shop thumbnail
    if ( ! function_exists('yit_shop_thumbnail_w') ) : function yit_shop_thumbnail_w()  { $size = wc_get_image_size('shop_thumbnail'); return $size['width']; } endif;
    if ( ! function_exists('yit_shop_thumbnail_h') ) : function yit_shop_thumbnail_h()  { $size = wc_get_image_size('shop_thumbnail'); return $size['height']; } endif;
    if ( ! function_exists('yit_shop_thumbnail_c') ) : function yit_shop_thumbnail_c()  { $size = wc_get_image_size('shop_thumbnail'); return $size['crop']; } endif;
    // shop large
    if ( ! function_exists('yit_shop_single_w') ) : function yit_shop_single_w()        { $size = wc_get_image_size('shop_single'); return $size['width']; } endif;
    if ( ! function_exists('yit_shop_single_h') ) : function yit_shop_single_h()        { $size = wc_get_image_size('shop_single'); return $size['height']; } endif;
    if ( ! function_exists('yit_shop_single_c') ) : function yit_shop_single_c()        { $size = wc_get_image_size('shop_single'); return $size['crop']; } endif;
    // shop featured
    if ( ! function_exists('yit_shop_featured_w') ) : function yit_shop_featured_w()    { $size = wc_get_image_size('shop_featured'); return $size['width']; } endif;
    if ( ! function_exists('yit_shop_featured_h') ) : function yit_shop_featured_h()    { $size = wc_get_image_size('shop_featured'); return $size['height']; } endif;
    if ( ! function_exists('yit_shop_featured_c') ) : function yit_shop_featured_c()    { $size = wc_get_image_size('shop_featured'); return $size['crop']; } endif;
}


// print style for small thumb size
function yit_size_images_style() {
    ?>
    <style type="text/css">
        ul.products li.product.list .product-thumbnail { margin-left:<?php echo yit_shop_catalog_w() + 30 + 10 + 2; ?>px; }
        ul.products li.product.list .product-thumbnail .thumbnail-wrapper { margin-left:-<?php echo yit_shop_catalog_w() + 30 + 10 + 2; ?>px; }

        /* IE8, Portrait tablet to landscape and desktop till 1024px */
        .single-product .sidebar-no div.images,
        .single-product .sidebar-no div.images { width:<?php echo ( yit_shop_single_w() - 30 + 2 ) / 1200 * 100 ?>%; }
        .single-product .sidebar-no div.summary,
        .single-product .sidebar-no div.summary { width:<?php echo ( 1200 - yit_shop_single_w() - 30 + 2 ) / 1200 * 100 ?>%; }

        .single-product .sidebar-right .span10 div.images,
        .single-product .sidebar-left .span10 div.images { width:<?php echo ( yit_shop_single_w() - 30 + 2 ) / 970 * 100 ?>%; }
        .single-product .sidebar-right .span10 div.summary,
        .single-product .sidebar-left .span10 div.summary { width:<?php echo ( 970 - yit_shop_single_w() - 30 + 2 ) / 970 * 100 ?>%; }

        .single-product .sidebar-right .span9 div.images,
        .single-product .sidebar-left .span9 div.images { width:<?php echo ( yit_shop_single_w() - 30 + 2 ) / 870 * 100 ?>%; }
        .single-product .sidebar-right .span9 div.summary,
        .single-product .sidebar-left .span9 div.summary { width:<?php echo ( 870 - yit_shop_single_w() - 30 + 2 ) / 870 * 100 ?>%; }
        /* WooCommerce standard images */
        .single-product .images .thumbnails > a { width:<?php echo min( yit_shop_thumbnail_w(), 80 ) ?>px !important; height:<?php echo min( yit_shop_thumbnail_h(), 80 ) ?>px !important; }
        /* Slider images */
        .single-product .images .thumbnails li img { max-width:<?php echo min( yit_shop_thumbnail_w(), 80 ) ?>px !important; }

        /* Desktop above 1200px */
        @media (min-width:1200px) {
            /* WooCommerce standard images */
            .single-product .images .thumbnails > a { width:<?php echo min( yit_shop_thumbnail_w(), 100 ) ?>px !important; height:<?php echo min( yit_shop_thumbnail_h(), 100 ) ?>px !important; }
            /* Slider images */
            .single-product .images .thumbnails li img { max-width:<?php echo min( yit_shop_thumbnail_w(), 100 ) ?>px !important; }
        }

        /* Desktop above 1200px */
        @media (max-width: 979px) and (min-width: 768px) {
            /* WooCommerce standard images */
            .single-product .images .thumbnails > a { width:<?php echo min( yit_shop_thumbnail_w(), 63 ) ?>px !important; height:<?php echo min( yit_shop_thumbnail_h(), 63 ) ?>px !important; }
            /* Slider images */
            .single-product .images .thumbnails li img { max-width:<?php echo min( yit_shop_thumbnail_w(), 63 ) ?>px !important; }
        }

        <?php if( yit_get_option( 'responsive-enabled' ) ) : ?>
        /* Below 767px, mobiles included */
        @media (max-width: 767px) {
            .single-product div.images,
            .single-product div.summary { float:none;margin-left:0px !important;width:100% !important; }

            .single-product div.images { margin-bottom:20px; }

            /* WooCommerce standard images */
            .single-product .images .thumbnails > a { width:<?php echo min( yit_shop_thumbnail_w(), 65 ) ?>px !important; height:<?php echo min( yit_shop_thumbnail_h(), 65 ) ?>px !important; }
            /* Slider images */
            .single-product .images .thumbnails li img { max-width:<?php echo min( yit_shop_thumbnail_w(), 65 ) ?>px !important; }
        }
        <?php endif ?>
    </style>
    <?php
}

function yit_get_featured_image_size() {

    $element['width']   = "160";
    $element['height'] = "160";
    $element['crop']    = 1;

    $element = get_option( 'shop_featured_image_size', $element );

    $crop            = WC_Admin_Settings::get_option( 'shop_featured_image_size' . '[crop]' );
    $element['crop'] = ( $crop == 'on' || $crop == '1' ) ? 1 : 0;

    return  $element;
}

function yit_add_featured_image_size($images) {

    $element = yit_get_option('shop-featured-image-size');

    if ( ! is_array( $element ) ) {
        $element['width']   = "160";
        $element['height'] = "160";
        $element['crop']    = 1;
    }

    $image_sizes = array(
        'shop_featured_image_size' => array( intval( $element['width'] ), intval( $element['height'] ), ( isset( $element['crop'] ) && $element['crop'] ) ? true : false ),
    );

    return array_merge( $image_sizes , $images );
}

// ADD IMAGE CATEGORY OPTION

// add image size for the images of widget featured product slider
function yit_add_featured_products_slider_size( $image_sizes ) {
    $size = yit_get_option('shop-featured-image-size');
    $width  = $size['width'];
    $height = $size['height'];
    $crop = ( isset( $element['crop'] ) && $element['crop'] ) ? true : false;
    $image_sizes['featured_products_slider'] = array( $width, $height, $crop );
    return $image_sizes;
}
add_filter( 'yit_add_image_size', 'yit_add_featured_products_slider_size' );

// ADD IMAGE RESPONSIVE OPTION
function yit_add_responsive_image_option( $options ) {
    $tmp = $options[ count($options)-1 ];
    unset( $options[ count($options)-1 ] );

    $options[] = array(
        'name'		=> __( 'Active responsive images', 'yit' ),
        'desc' 		=> __( 'Active this to make the images responsive and adaptable to the layout grid.', 'yit' ),
        'id' 		=> 'woocommerce_responsive_images',
        'std' 		=> 'yes',
        'default'   => 'yes',
        'type' 		=> 'checkbox'
    );

    $options[] = $tmp;

    return $options;
}



/** NAV MENU
-------------------------------------------------------------------- */

add_action('admin_init', array('yitProductsPricesFilter', 'admin_init'));

class yitProductsPricesFilter {
    // We cannot call #add_meta_box yet as it has not been defined,
    // therefore we will call it in the admin_init hook
    static function admin_init() {

        global $woocommerce;

        if ( ! isset($woocommerce) || basename($_SERVER['PHP_SELF']) != 'nav-menus.php' )
            return;

        wp_enqueue_script('nav-menu-query', YIT_THEME_ASSETS_URL . '/js/metabox_nav_menu.js', 'nav-menu', false, true);
        add_meta_box('products-by-prices', 'Prices Filter', array(__CLASS__, 'nav_menu_meta_box'), 'nav-menus', 'side', 'low');
    }

    function nav_menu_meta_box() { ?>
    <div class="prices">
        <input type="hidden" name="woocommerce_currency" id="woocommerce_currency" value="<?php echo get_woocommerce_currency_symbol( get_option('woocommerce_currency') ) ?>" />
        <input type="hidden" name="woocommerce_shop_url" id="woocommerce_shop_url" value="<?php echo get_option('permalink_structure') == '' ? site_url() . '/?post_type=product' : get_permalink( get_option('woocommerce_shop_page_id') ) ?>" />
        <input type="hidden" name="menu-item[-1][menu-item-url]" value="" />
        <input type="hidden" name="menu-item[-1][menu-item-title]" value="" />
        <input type="hidden" name="menu-item[-1][menu-item-type]" value="custom" />

        <p>
            <?php _e( sprintf( 'The values are already expressed in %s', get_woocommerce_currency_symbol( get_option('woocommerce_currency') ) ), 'yiw' ) ?>
        </p>

        <p>
            <label class="howto" for="prices_filter_from">
                <span><?php _e('From', 'yit'); ?></span>
                <input id="prices_filter_from" name="prices_filter_from" type="text" class="regular-text menu-item-textbox input-with-default-title" title="<?php esc_attr_e('From', 'yit'); ?>" />
            </label>
        </p>

        <p style="display: block; margin: 1em 0; clear: both;">
            <label class="howto" for="prices_filter_to">
                <span><?php _e('To', 'yit'); ?></span>
                <input id="prices_filter_to" name="prices_filter_to" type="text" class="regular-text menu-item-textbox input-with-default-title" title="<?php esc_attr_e('To'); ?>" />
            </label>
        </p>

        <p class="button-controls">
            <span class="add-to-menu">
                <img class="waiting" src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" alt="" />
                <input type="submit" class="button-secondary submit-add-to-menu" value="<?php esc_attr_e('Add to Menu'); ?>" name="add-custom-menu-item" />
            </span>
        </p>

    </div>
<?php
    }
}

/**
 * Add 'On Sale Filter to Product list in Admin
 */
add_filter( 'parse_query', 'on_sale_filter' );
function on_sale_filter( $query ) {
    global $pagenow, $typenow, $wp_query;

    if ( $typenow=='product' && isset($_GET['onsale_check']) && $_GET['onsale_check'] ) :

        if ( $_GET['onsale_check'] == 'yes' ) :
            $query->query_vars['meta_compare']  =  '>';
            $query->query_vars['meta_value']    =  0;
            $query->query_vars['meta_key']      =  '_sale_price';
        endif;

        if ( $_GET['onsale_check'] == 'no' ) :
            $query->query_vars['meta_value']    = '';
            $query->query_vars['meta_key']      =  '_sale_price';
        endif;

    endif;
}

add_action('restrict_manage_posts','woocommerce_products_by_on_sale');
function woocommerce_products_by_on_sale() {
    global $typenow, $wp_query;
    if ( $typenow=='product' ) :

        $onsale_check_yes = '';
        $onsale_check_no  = '';

        if ( isset( $_GET['onsale_check'] ) && $_GET['onsale_check'] == 'yes' ) :
            $onsale_check_yes = ' selected="selected"';
        endif;

        if ( isset( $_GET['onsale_check'] ) && $_GET['onsale_check'] == 'no' ) :
            $onsale_check_no = ' selected="selected"';
        endif;

        $output  = "<select name='onsale_check' id='dropdown_onsale_check'>";
        $output .= '<option value="">'.__('Show all products (Sale Filter)', 'yit').'</option>';
        $output .= '<option value="yes"'.$onsale_check_yes.'>'.__('Show products on sale', 'yit').'</option>';
        $output .= '<option value="no"'.$onsale_check_no.'>'.__('Show products not on sale', 'yit').'</option>';
        $output .= '</select>';

        echo $output;

    endif;
}


if( yit_get_option('shop-customer-vat' ) && yit_get_option('shop-customer-ssn' ) ) {

    add_filter( 'woocommerce_billing_fields' , 'woocommerce_add_billing_fields' );
    function woocommerce_add_billing_fields( $fields ) {
        //$fields['billing_country']['clear'] = true;
        $field = array('billing_ssn' => array(
            'label'       => apply_filters( 'yit_ssn_label', __('SSN', 'yit') ),
            'placeholder' => apply_filters( 'yit_ssn_label_x', _x('SSN', 'placeholder', 'yit') ),
            'required'    => false,
            'class'       => array('form-row-first'),
            'clear'       => false
         ));

        yit_array_splice_assoc( $fields, $field, 'billing_address_1');

        $field = array('billing_vat' => array(
            'label'       => apply_filters( 'yit_vatssn_label', __('VAT', 'yit') ),
            'placeholder' => apply_filters( 'yit_vatssn_label_x', _x('VAT', 'placeholder', 'yit') ),
            'required'    => false,
            'class'       => array('form-row-last'),
            'clear'       => true
         ));

        yit_array_splice_assoc( $fields, $field, 'billing_address_1');

        return $fields;
    }


    add_filter( 'woocommerce_shipping_fields' , 'woocommerce_add_shipping_fields' );
    function woocommerce_add_shipping_fields( $fields ) {
        $field = array('shipping_ssn' => array(
            'label'       => apply_filters( 'yit_ssn_label', __('SSN', 'yit') ),
            'placeholder' => apply_filters( 'yit_ssn_label_x', _x('SSN', 'placeholder', 'yit') ),
            'required'    => false,
            'class'       => array('form-row-first'),
            'clear'       => false
         ));

        yit_array_splice_assoc( $fields, $field, 'shipping_address_1');

        $field = array('shipping_vat' => array(
            'label'       => apply_filters( 'yit_vatssn_label', __('VAT', 'yit') ),
            'placeholder' => apply_filters( 'yit_vatssn_label_x', _x('VAT', 'placeholder', 'yit') ),
            'required'    => false,
            'class'       => array('form-row-last'),
            'clear'       => true
         ));

        yit_array_splice_assoc( $fields, $field, 'shipping_address_1');
        return $fields;
    }


    add_filter( 'woocommerce_admin_billing_fields', 'woocommerce_add_billing_shipping_fields_admin' );
    add_filter( 'woocommerce_admin_shipping_fields', 'woocommerce_add_billing_shipping_fields_admin' );
    function woocommerce_add_billing_shipping_fields_admin( $fields ) {
        $fields['vat'] = array(
            'label' => apply_filters( 'yit_vatssn_label', __('VAT', 'yit') )
        );
        $fields['ssn'] = array(
            'label' => apply_filters( 'yit_ssn_label', __('SSN', 'yit') )
        );

        return $fields;
    }

    add_filter( 'woocommerce_load_order_data', 'woocommerce_add_var_load_order_data' );
    function woocommerce_add_var_load_order_data( $fields ) {
        $fields['billing_vat'] = '';
        $fields['shipping_vat'] = '';
        $fields['billing_ssn'] = '';
        $fields['shipping_ssn'] = '';
        return $fields;
    }



} elseif( yit_get_option('shop-customer-vat' ) ) {
    add_filter( 'woocommerce_billing_fields' , 'woocommerce_add_billing_fields' );
    function woocommerce_add_billing_fields( $fields ) {
        $fields['billing_company']['class'] = array('form-row-first');
        $fields['billing_company']['clear'] = false;
        //$fields['billing_country']['clear'] = true;
        $field = array('billing_vat' => array(
            'label'       => apply_filters( 'yit_vatssn_label', __('VAT/SSN', 'yit') ),
            'placeholder' => apply_filters( 'yit_vatssn_label_x', _x('VAT or SSN', 'placeholder', 'yit') ),
            'required'    => false,
            'class'       => array('form-row-last'),
            'clear'       => true
         ));

        yit_array_splice_assoc( $fields, $field, 'billing_address_1');
        return $fields;
    }

    add_filter( 'woocommerce_shipping_fields' , 'woocommerce_add_shipping_fields' );
    function woocommerce_add_shipping_fields( $fields ) {
        $fields['shipping_company']['class'] = array('form-row-first');
        $fields['shipping_company']['clear'] = false;
        //$fields['shipping_country']['clear'] = true;
        $field = array('shipping_vat' => array(
            'label'       => apply_filters( 'yit_vatssn_label', __('VAT/SSN', 'yit') ),
            'placeholder' => apply_filters( 'yit_vatssn_label_x', _x('VAT or SSN', 'placeholder', 'yit') ),
            'required'    => false,
            'class'       => array('form-row-last'),
            'clear'       => true
         ));

        yit_array_splice_assoc( $fields, $field, 'shipping_address_1');
        return $fields;
    }

    add_filter( 'woocommerce_admin_billing_fields', 'woocommerce_add_billing_shipping_fields_admin' );
    add_filter( 'woocommerce_admin_shipping_fields', 'woocommerce_add_billing_shipping_fields_admin' );
    function woocommerce_add_billing_shipping_fields_admin( $fields ) {
        $fields['vat'] = array(
            'label' => apply_filters( 'yit_vatssn_label', __('VAT/SSN', 'yit') )
        );

        return $fields;
    }

    add_filter( 'woocommerce_load_order_data', 'woocommerce_add_var_load_order_data' );
    function woocommerce_add_var_load_order_data( $fields ) {
        $fields['billing_vat'] = '';
        $fields['shipping_vat'] = '';
        return $fields;
    }
}
elseif( yit_get_option('shop-customer-ssn' ) ) {
    add_filter( 'woocommerce_billing_fields' , 'woocommerce_add_billing_ssn_fields' );
    function woocommerce_add_billing_ssn_fields( $fields ) {
        $fields['billing_company']['class'] = array('form-row-first');
        $fields['billing_company']['clear'] = false;
        $field = array('billing_ssn' => array(
            'label'       => apply_filters( 'yit_ssn_label', __('SSN', 'yit') ),
            'placeholder' => apply_filters( 'yit_ssn_label_x', _x('SSN', 'placeholder', 'yit') ),
            'required'    => false,
            'class'       => array('form-row-last'),
            'clear'       => true
         ));

        yit_array_splice_assoc( $fields, $field, 'billing_address_1');
        return $fields;
    }

    add_filter( 'woocommerce_shipping_fields' , 'woocommerce_add_shipping_ssn_fields' );
    function woocommerce_add_shipping_ssn_fields( $fields ) {
        $fields['shipping_company']['class'] = array('form-row-first');
        $fields['shipping_company']['clear'] = false;
        $field = array('shipping_ssn' => array(
            'label'       => apply_filters( 'yit_ssn_label', __('SSN', 'yit') ),
            'placeholder' => apply_filters( 'yit_ssn_label_x', _x('SSN', 'placeholder', 'yit') ),
            'required'    => false,
            'class'       => array('form-row-last'),
            'clear'       => true
         ));

        yit_array_splice_assoc( $fields, $field, 'shipping_address_1');
        return $fields;
    }

    add_filter( 'woocommerce_admin_billing_fields', 'woocommerce_add_billing_shipping_ssn_fields_admin' );
    add_filter( 'woocommerce_admin_shipping_fields', 'woocommerce_add_billing_shipping_ssn_fields_admin' );
    function woocommerce_add_billing_shipping_ssn_fields_admin( $fields ) {
        $fields['ssn'] = array(
            'label' => apply_filters( 'yit_ssn_label', __('SSN', 'yit') )
        );

        return $fields;
    }

    add_filter( 'woocommerce_load_order_data', 'woocommerce_add_var_load_order_ssn_data' );
    function woocommerce_add_var_load_order_ssn_data( $fields ) {
        $fields['billing_ssn'] = '';
        $fields['shipping_ssn'] = '';
        return $fields;
    }
}
// custom field for two different layout
add_image_size( 'shop_catalog_3cols', 258, 180, true );
add_image_size( 'shop_catalog_5cols', 158, 158, true );

function yit_change_shop_image_size_init() {
    if ( ! function_exists( 'is_shop' ) || ! function_exists( 'is_product_category' ) || ! function_exists( 'is_product_tag' ) ) return;

    global $post, $_wp_additional_image_sizes, $yit_live_post_id;

    if ( is_shop() || is_product_category() || is_product_tag() )
        $yit_live_post_id = yith_wc_get_page_id( 'shop' );
    else if ( isset( $post->ID ) )
        $yit_live_post_id = $post->ID;
    else
        $yit_live_post_id = 0;

    $post_id = $yit_live_post_id;

    if ( $post_id == 0 ) return;

    $postmeta = get_post_meta( $post_id, 'shop_layout', true );
    $new_size = yit_get_model('image')->get_size( "shop_catalog_$postmeta" );

    if ( empty( $new_size ) ) return;

    $new_width  = $new_size['width'];
    $new_height = $new_size['height'];
    $crop       = $new_size['crop'];

    yit_get_model('image')->set_size( 'shop_catalog', array( $new_width, $new_height, $crop ) );

    add_filter( 'woocommerce_get_image_size_shop_catalog', create_function( '', "return array( 'width' => $new_width, 'height' => $new_height, 'crop' => true );" ) );
    add_filter( 'post_thumbnail_size', 'yit_change_shop_image_size' );
}
add_action( 'wp_head', 'yit_change_shop_image_size_init', 1 );

function yit_change_shop_image_size( $size ) {
    global $post, $yit_live_post_id;

    $post_id = $yit_live_post_id;

    if ( !( $size == 'shop_catalog' && $post_id != 0 ) ) return $size;

    $postmeta = get_post_meta( $post_id, 'shop_layout', true );
    if ( ! empty( $postmeta ) && in_array( $postmeta, array( '3cols', '5cols' ) ) ) {
        $size .= '_' . $postmeta;
    }

    return $size;
}



/* is image responsive enabled? */
function yit_print_image_responsive_enabled_variables() {return;
?>
<script type="text/javascript">
var elastislide_defaults = {
    imageW: <?php echo get_option('woocommerce_responsive_images') == 'no' || ! get_option('woocommerce_responsive_images') ? yit_shop_catalog_w() + 6 + 2 : '"100%"'; ?>,
    border		: 0,
    margin      : 0,
    preventDefaultEvents: false,
    infinite : true,
    slideshowSpeed : 3500
};
</script>
<?php
}
add_action( 'wp_head', 'yit_print_image_responsive_enabled_variables', 1 );
add_action( 'yit_after_import', create_function( '', 'update_option("woocommerce_responsive_images", "yes");' ) );

/**
 * Add default images dimensions to woocommerce options
 *
 */
function yit_woocommerce_default_image_dimensions() {
    $field = 'yit_woocommerce_image_dimensions_' . get_template();

    if( get_option($field) == false ) {
        update_option($field, time());

        update_option( 'woocommerce_thumbnail_image_width', '90' );
        update_option( 'woocommerce_thumbnail_image_height', '90' );
        update_option( 'woocommerce_single_image_width', '470' );
        update_option( 'woocommerce_single_image_height', '365' );
        update_option( 'woocommerce_catalog_image_width', '258' );
        update_option( 'woocommerce_catalog_image_height', '180' );
        update_option( 'woocommerce_magnifier_image_width', '940' );
        update_option( 'woocommerce_magnifier_image_height', '730' );
        update_option( 'woocommerce_featured_products_slider_image_width', '160' );
        update_option( 'woocommerce_featured_products_slider_image_height', '124' );

        update_option( 'woocommerce_thumbnail_image_crop', 1 );
        update_option( 'woocommerce_single_image_crop', 1 );
        update_option( 'woocommerce_catalog_image_crop', 1 );
        update_option( 'woocommerce_magnifier_image_crop', 1 );
        update_option( 'woocommerce_featured_products_slider_image_crop', 1 );

        //woocommerce 2.0
        update_option( 'shop_thumbnail_image_size', array( 'width' => 90, 'height' => 90, 'crop' => true ) );
        update_option( 'shop_single_image_size', array( 'width' => 470, 'height' => 365, 'crop' => true ) );
        update_option( 'shop_catalog_image_size', array( 'width' => 258, 'height' => 180, 'crop' => true ) );
        update_option( 'woocommerce_magnifier_image', array( 'width' => 940, 'height' => 730, 'crop' => true ) );
        update_option( 'woocommerce_featured_products_slider_image', array( 'width' => 160, 'height' => 124, 'crop' => true ) );
    }
}

/**
 * Update woocommerce options after update from 1.6 to 2.0
 */
function yit_woocommerce_update() {
    global $woocommerce;

    $field = 'yit_woocommerce_update_' . get_template();

    if( get_option($field) == false && version_compare($woocommerce->version,"2.0.0",'>=') ) {
        update_option($field, time());

        //woocommerce 2.0
        update_option(
            'shop_thumbnail_image_size',
            array(
                'width' => get_option('woocommerce_thumbnail_image_width', 90),
                'height' => get_option('woocommerce_thumbnail_image_height', 90),
                'crop' => get_option('woocommerce_thumbnail_image_crop', 1)
            )
        );

        update_option(
            'shop_single_image_size',
            array(
                'width' => get_option('woocommerce_single_image_width', 300 ),
                'height' => get_option('woocommerce_single_image_height', 300 ),
                'crop' => get_option('woocommerce_single_image_crop', 1)
            )
        );

        update_option(
            'shop_catalog_image_size',
            array(
                'width' => get_option('woocommerce_catalog_image_width', 150 ),
                'height' => get_option('woocommerce_catalog_image_height', 150 ),
                'crop' => get_option('woocommerce_catalog_image_crop', 1)
            )
        );

        update_option(
            'woocommerce_magnifier_image',
            array(
                'width' => get_option('woocommerce_magnifier_image_width', 600 ),
                'height' => get_option('woocommerce_magnifier_image_height', 600 ),
                'crop' => get_option('woocommerce_magnifier_image_crop', 1)
            )
        );

        update_option(
            'woocommerce_featured_products_slider_image',
            array(
                'width' => get_option('woocommerce_featured_products_slider_image_width', 160 ),
                'height' => get_option('woocommerce_featured_products_slider_image_height', 124 ),
                'crop' => get_option('woocommerce_featured_products_slider_image_crop', 1)
            )
        );
    }
}

/**
 * Backup woocoomerce options when create the export gz
 *
 */
function yit_save_woocommerce_tables( $tables ) {
    $tables[] = 'woocommerce_termmeta';
    $tables[] = 'woocommerce_attribute_taxonomies';
    return $tables;
}

/**
 * Backup woocoomerce options when create the export gz
 *
 */
function yit_save_woocommerce_options( $options ) {
    $options[] = '%woocommerce%';
    $options[] = '%wc_average_rating%';
    $options[] = '%wc_product_children_ids%';
    $options[] = '%wc_term_counts%';
    $options[] = '%wc_products_onsale%';
    $options[] = '%wc_needs_pages%';
    $options[] = '%wc_needs_update%';
    $options[] = '%wc_activation_redirect%';
    $options[] = '%wc_hidden_product_ids%';
    $options[] = '%shop_catalog_image_size%';
    $options[] = '%shop_featured_image_size%';
    $options[] = '%shop_single_image_size%';
    $options[] = '%shop_thumbnail_image_size%';

    return $options;
}

/**
 * Backup woocoomerce wishlist when create the export gz
 *
 */
function yit_save_wishlist_options( $options ) {
    $options[] = 'yith\_wcwl\_%';
    $options[] = 'yith-wcwl-%';
    return $options;
}

/**
 * Backup options of plugins when create the export gz
 *
 */
function yit_add_plugins_options( $options ) {
    $options[] = 'yith_woocompare_%';
    $options[] = 'yith_wcmg_%';

    return $options;
}

/* Function to add compatibility with WC 2.1 */
function yit_woocommerce_primary_start() {
    global $woo_shop_folder;
    yith_wc_get_template( $woo_shop_folder . '/primary-start.php' );
}

function yit_rating_singleproduct() {
    yith_wc_get_template( 'single-product/rating.php' );
}

function yit_woocommerce_primary_end() {
    global $woo_shop_folder;
    yith_wc_get_template( $woo_shop_folder . '/primary-end.php' );
}


if ( ! function_exists( 'yith_wc_get_page_id' ) ) {

    function yith_wc_get_page_id( $page ) {

        global $woocommerce;

        if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.1', '<' ) ) {
            return woocommerce_get_page_id( $page );
        }
        else {

            if ( $page == 'pay' || $page == 'thanks' ) {
                $wc_order = wc_get_order();
                $page     = $wc_order->get_checkout_order_received_url();
            }
            return wc_get_page_id( $page );
        }

    }
}

if ( ! function_exists( 'yith_wc_get_template' ) ) {
    function yith_wc_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
        if ( function_exists( 'wc_get_template' ) ) {
            wc_get_template( $template_name, $args, $template_path, $default_path );
        }
        else {
            woocommerce_get_template( $template_name, $args, $template_path, $default_path );
        }
    }
}

function yit_enqueue_woocommerce_styles() {
    wp_deregister_style( 'woocommerce_frontend_styles' );
    wp_enqueue_style( 'woocommerce_frontend_styles', get_stylesheet_directory_uri() . '/woocommerce_2.0.x/style.css' );
}


function yit_enqueue_wc_styles( $styles ) {

    global $woocommerce;

    unset( $styles['woocommerce-layout'], $styles['woocommerce-smallscreen'], $styles['woocommerce-general'] );

    $style_version = '';
    if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.6', '<' ) ) {
        $style_version = '_' . substr( $woocommerce->version, 0, 3 ) . '.x';
    }

    $styles ['yit-layout'] = array(
        'src'     => get_stylesheet_directory_uri() . '/woocommerce' . $style_version . '/style.css',
        'deps'    => '',
        'version' => '1.0',
        'media'   => ''
    );
    return $styles;
}

/**
 * Get a coupon label
 *
 * This code replace the original woocommerce function call wc_cart_totals_coupon_label().
 * The override is because of the textdomain.
 *
 * @access public
 * @param  string $coupon
 * @return void
 */
function yit_cart_totals_coupon_label( $coupon ) {
    if ( is_string( $coupon ) )
        $coupon = new WC_Coupon( $coupon );

    echo apply_filters( 'woocommerce_cart_totals_coupon_label', esc_html( __( 'Coupon:', 'yit' ) . ' ' . $coupon->code ), $coupon );
}

/**
 * Get order total html including inc tax if needed
 *
 * This function override the original woocommerce wc_cart_totals_order_total_html()
 *
 * @access public
 * @return void
 */
function yit_cart_totals_order_total_html() {
    echo '<strong>' . WC()->cart->get_total() . '</strong> ';

    // If prices are tax inclusive, show taxes here
    if ( get_option( 'woocommerce_calc_taxes' ) == 'yes' && WC()->cart->tax_display_cart == 'incl' ) {
        $tax_string_array = array();

        if ( get_option( 'woocommerce_tax_total_display' ) == 'itemized' ) {
            foreach ( WC()->cart->get_tax_totals() as $code => $tax )
                $tax_string_array[] = sprintf( '%s %s', $tax->formatted_amount, $tax->label );
        } else {
            $tax_string_array[] = sprintf( '%s %s', wc_price( WC()->cart->get_taxes_total( true, true ) ), WC()->countries->tax_or_vat() );
        }

        if ( ! empty( $tax_string_array ) )
            echo '<small class="includes_tax">' . sprintf( __( '(Includes %s)', 'yit' ), implode( ', ', $tax_string_array ) ) . '</small>';
    }
}

function yit_enqueue_woocommerce_assets() {
    //yit_wp_enqueue_style( 1000, 'woocommerce-custom-style', YIT_THEME_URL . '/woocommerce/custom.css' );
    wp_enqueue_script( 'yit-woocommerce', YIT_THEME_ASSETS_URL . '/js/woocommerce.js', array( 'jquery', 'jquery-cookie' ), '1.0', true );
}

//--------------------------------------------------------------------------------------------
if ( ! function_exists( 'yit_woocommerce_default_shiptobilling' ) ) {

    function yit_woocommerce_default_shiptobilling() {
        return ( get_option( 'woocommerce_ship_to_destination' ) == 'billing' || get_option( 'woocommerce_ship_to_destination' ) == 'billing_only' );
    }

}

if ( ! function_exists( 'yit_woocommerce_default_shiptoaddress' ) ) {

    function yit_woocommerce_default_shiptoaddress() {
        return ( get_option( 'woocommerce_ship_to_destination' ) == 'shipping' );
    }
}

if ( ! function_exists( 'yit_woocommerce_default_shiptobilling_only' ) ) {

    function yit_woocommerce_default_shiptobilling_only() {
        return ( get_option( 'woocommerce_ship_to_destination' ) == 'billing_only' );
    }
}
//-------------------------------------------------------------------------


// Redirect to checkout page after add to cart
if ( yit_get_option( 'shop-redirect-to-checkout' ) ) {
    add_filter ( 'add_to_cart_redirect', 'yit_redirect_to_checkout' );
    add_filter ( 'wc_add_to_cart_params', 'yit_redirect_cart_to_checkout' );
}

if ( ! function_exists( 'yit_redirect_to_checkout' ) ) {
    /**
     * Redirect to checkout page after add to cart
     *
     * @since 1.6.0
     *
     * @return string
     */
    function yit_redirect_to_checkout() {
        global $woocommerce;
        $checkout_url = $woocommerce->cart->get_checkout_url();
        return $checkout_url;
    }
}

if ( ! function_exists( 'yit_redirect_cart_to_checkout' ) ) {
    /**
     * Redirect to checkout after product is added to cart
     *
     * This function is called only on shop pages and shortcodes.
     *
     * @since 1.6.0
     *
     * @param $params
     * @return null
     */
    function yit_redirect_cart_to_checkout( $params ) {
        return null;
    }
}

//-----------------------------------------------------------------------------------

if ( ! function_exists( 'yit_woocommerce_object' ) ) {
    function yit_woocommerce_object() {

        if ( function_exists( 'WC' ) ) {
            $version = WC()->version;
        }
        else {
            global $woocommerce;
            $version = $woocommerce->version;
        }

        wp_localize_script( 'jquery', 'yit_woocommerce', array(
            'load_gif' => yit_get_ajax_loader_gif_url(),
            'version'  => $version,
        ) );

    }
}

//---------------------------------------------------------------------

if( defined('YITH_YWAR_PREMIUM') ) {

    add_filter( 'yith_advanced_reviews_loader_gif', 'yit_get_ajax_loader_gif_url' );

}

/* ===== MULTI VENDOR ====== */

if ( function_exists( 'YITH_Vendors' ) ) {

    if( ! function_exists( 'yit_contact_form_to_vendor' ) ) {
        function yit_contact_form_to_vendor( $to ){
            $vendor_email = false;
            if( ! empty( $_POST['yit_contact']['product_id'] ) && yit_get_option( 'send-email-to-vendor' ) ){
                $vendor = yith_get_vendor( $_POST['yit_contact']['product_id'], 'product' );
                if( $vendor->is_valid() ){
                    $vendor_email = $vendor->store_email;
                    if( empty( $vendor_email ) ){
                        $vendor_owner = get_user_by( 'id', absint( $vendor->get_owner() ) );
                        $vendor_email = $vendor_owner instanceof WP_User ? $vendor_owner->user_email : false;
                    }
                }
            }
            return $vendor_email ? $vendor_email : $to;
        }
    }

    add_filter( 'yit_contact_form_email_to', 'yit_contact_form_to_vendor' );
}

/* =============================' */





/* ********************** */
/* ***** WC 2.6 FIX ***** */
/* ********************** */

if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', WC()->version ), '2.6', '>=' ) ) {

    // add_action( 'woocommerce_share', 'yit_woocommerce_share');

    // My Account
    add_filter( 'woocommerce_account_menu_items' , 'yit_woocommerce_account_menu_items' );
    
    // Loop
    add_filter( 'post_class', 'yit_wc_product_post_class', 30, 3 );
    add_filter( 'product_cat_class', 'yit_wc_product_product_cat_class', 30, 3 );

    // remove unused template
    yit_wc_2_6_removed_unused_template() ;
    
}
if ( ! function_exists( 'yit_woocommerce_share' ) ) {

    function yit_woocommerce_share () {

        if ( yit_get_option('shop-single-share') == 'yes' ) {

            echo '<div class="product-share"><h3>'. __( "Share","yit"). '</h3>';
               yit_get_social_share( 'text' );
            echo '</div><div class="clearfix"></div>';
        }

    }

}

/**
 * @author Andre Frascaspata
 */
function yit_wc_2_6_removed_unused_template () {

    if( function_exists( 'yit_remove_unused_template' ) ) {

        $option = 'yit_wc_2_6_template_remove';

        $files = array(
            'single-product/review.php',
            'myaccount/form-edit-address.php',
            'myaccount/form-login.php',
            'myaccount/my-account.php',
            'myaccount/my-address.php',
            'order/order-details.php',
        );

        yit_remove_unused_template( 'woocommerce' , $option , $files );

    }

}

if ( ! function_exists( 'yit_woocommerce_account_menu_items' ) ) {
    /**
     * @author Andrea Frascaspata
     */
    function yit_woocommerce_account_menu_items( $menu_list ) {

        unset( $menu_list['customer-logout'] );

        $menu_list['orders']       = __( 'My Orders', 'yit' );
        $menu_list['downloads']    = __( 'My Downloads', 'yit' );
        $menu_list['edit-address'] = __( 'Edit Address', 'yit' );
        $menu_list['edit-account'] = __( 'Edit Account', 'yit' );

        return $menu_list;

    }
}

if ( ! function_exists( 'yit_get_myaccount_menu_icon' ) ) {

    /**
     * @param $endpoint
     * @return mixed|string
     * @author Andrea Frascaspata
     */
    function yit_get_myaccount_menu_icon( $endpoint ) {

        $icon_list = apply_filters( 'yit_get_myaccount_menu_icon_list_fa' , array(
                'dashboard'       => 'fa-book',
                'orders'          => 'fa-folder-open',
                'downloads'       => 'fa-download',
                'edit-address'    => 'fa-pencil-square-o',
                'payment-methods' => 'fa-credit-card',
                'edit-account'    => 'fa-pencil-square-o',
            )
        );

        if( isset( $icon_list[ $endpoint ] ) ) {
            return $icon_list[ $endpoint ];
        } else {
            return '';
        }

    }

}

if ( ! function_exists( 'yit_wc_product_post_class' ) ) {
    /**
     * @param        $classes
     * @param string $class
     * @param string $post_id
     *
     * @return array
     */
    function yit_wc_product_post_class( $classes, $class = '', $post_id = '' ) {

        global $product, $woocommerce_loop, $yit_is_page, $yit_is_feature_tab;

        if ( ( !isset( $woocommerce_loop['name'] ) || empty( $woocommerce_loop['name'] ) ) && !isset( $woocommerce_loop['view'] ) ) {
            return $classes;
        }

        // remove the shortcode from the short description, in list view
        remove_filter( 'woocommerce_short_description', 'do_shortcode', 11 );
        add_filter( 'woocommerce_short_description', 'strip_shortcodes' );

        // changes for the "classic" layout
        if ( $woocommerce_loop['layout'] == 'classic' ) {
            //remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart' );
            add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
        }

        //product countdown compatibility
        global $ywpc_loop;
        if ( $ywpc_loop && $ywpc_loop == 'ywpc_widget' ) {
            $woocommerce_loop['view'] = 'grid';
        }

        // li classes
        $classes[] = 'product';
        $classes[] = 'group';
        $classes[] = $woocommerce_loop['view'];
        $classes[] = $woocommerce_loop['layout'];
        if ( yit_get_option('shop-view-show-border') ) {
            $classes[] = 'with-border';
        }

        // width of each product for the grid
        $classes = yit_detect_span_catalog_image( $classes );

        $woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', $woocommerce_loop['columns'] );

        // configuration
        if ( ! yit_get_option('shop-view-show-price') ) remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price' );

        return $classes;

    }
    
}

if ( ! function_exists( 'yit_wc_product_product_cat_class' ) ) {
    function yit_wc_product_product_cat_class( $classes, $class, $category ) {

        global $woocommerce_loop;

        // remove the shortcode from the short description, in list view
        remove_filter( 'woocommerce_short_description', 'do_shortcode', 11 );
        add_filter( 'woocommerce_short_description', 'strip_shortcodes' );

        // li classes
        $classes = array();
        $classes[] = 'product category';
        $classes[] = 'group';
        $classes[] = 'grid';
        $classes[] = $woocommerce_loop['layout'];
        if ( yit_get_option('shop-view-show-border') ) {
            $classes[] = 'with-border';
        }

        // width of each product for the grid
        $content_width = yit_get_sidebar_layout() == 'sidebar-no' ? 1170 : 870;
        if ( isset( $yit_is_feature_tab ) && $yit_is_feature_tab ) $content_width -= 300;
        $product_width = yit_shop_catalog_w() + 10 + 2;  // 10 = padding & 2 = border
        $is_span = false;
        if ( get_option('woocommerce_responsive_images') == 'yes' ) {
            $is_span = true;
            if ( yit_get_sidebar_layout() == 'sidebar-no' ) {
                    if ( $product_width >= 0   && $product_width < 120 ) { $classes[] = 'span1'; $woocommerce_loop['columns'] = 12; }
                elseif ( $product_width >= 120 && $product_width < 220 ) { $classes[] = 'span2'; $woocommerce_loop['columns'] = 6;  }
                elseif ( $product_width >= 220 && $product_width < 320 ) { $classes[] = 'span3'; $woocommerce_loop['columns'] = 4;  }
                elseif ( $product_width >= 320 && $product_width < 470 ) { $classes[] = 'span4'; $woocommerce_loop['columns'] = 3;  }
                elseif ( $product_width >= 470 && $product_width < 620 ) { $classes[] = 'span6'; $woocommerce_loop['columns'] = 2;  }
                else $is_span = false;

            } else {
                    if ( $product_width >= 0   && $product_width < 150 ) { $classes[] = 'span1'; $woocommerce_loop['columns'] = 12; }
                elseif ( $product_width >= 150 && $product_width < 620 ) { $classes[] = 'span3'; $woocommerce_loop['columns'] = 3;  }
                else $is_span = false;

            }
        }
        if ( isset($yit_is_feature_tab) && ($yit_is_feature_tab || ! $is_span) ) $woocommerce_loop['columns'] = floor( ( $content_width + 30 ) / ( $product_width + 30 ) );

        $woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', $woocommerce_loop['columns'] );

        return $classes;

    }
}

/* ********************** */
/* ***** END WC 2.6 ***** */
/* ********************** */
