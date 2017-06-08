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

//define area
define( 'YIT_THEME_NAME', 'boemia' );
define( 'YIT_THEME_NOTIFIER_URL', 'http://update.yithemes.com/' . YIT_THEME_NAME . '.xml');
define( 'YIT_DOCUMENTATION_URL', 'http://yithemes.com/docs/' . YIT_THEME_NAME . '/');
//define( 'YIT_SUPPORT_URL', 'http://support.yithemes.com' );
define( 'YIT_FREE_THEMES_URL', 'http://yithemes.com/' );
define( 'YIT_MARKETPLACE', 'yit' ); // ( tf | yit | free )
define( 'YIT_EXTERNAL_PLUGINS', 1);
define( 'YIT_IS_SHOP', 1);
define( 'YIT_SAMPLE_IMAGES_URL', 'https://dl.dropbox.com/s/09mhq7zv8ln069g/boemia.zip' );

define( 'YIT_SHOW_PANEL_HEADER', 1 );
define( 'YIT_SHOW_PANEL_HEADER_LINKS', 1 );

if( !defined( 'YIT_DEBUG' ) ) { define( 'YIT_DEBUG', false ); }
if( !defined( 'YIT_SHOW_UPDATES' ) ) { define( 'YIT_SHOW_UPDATES', true ); }

if( is_admin() && in_array( 'theme-check/theme-check.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    set_time_limit( 0 );
}

function yit_theme_fonts() {
    
}
add_action( 'yit_loaded', 'yit_theme_fonts' );

/**
 * Add recommended jetpack modules
 */
function yit_recommended_jetpack_modules( $modules ){

    $modules= array();

    $modules[] = 'yith-woocommerce-zoom-magnifier';
    $modules[] = 'yith-woocommerce-wishlist';
    $modules[] = 'yith-woocommerce-compare';
    $modules[] = 'yith-woocommerce-ajax-navigation';

    return $modules;
}
add_filter( 'yith_jetpack_recommended_list', 'yit_recommended_jetpack_modules' );