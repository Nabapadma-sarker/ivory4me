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

add_filter('yit_plugins', 'yit_add_plugins');
function yit_add_plugins( $plugins ) {
    return array_merge( $plugins, array(

        array(
            'name' 		=> 'WooCommerce',
            'slug' 		=> 'woocommerce',
            'required' 	=> true,
            'version'=> '2.0.0',
        ),

        array(
            'name'               => 'Revolution Slider',
            'slug'               => 'revslider',
            'source'             => YIT_THEME_PLUGINS_DIR . '/revslider.zip',
            'required'           => false,
            'version'            => '4.6.93',
            'force_activation'   => false,
            'force_deactivation' => true,
            'external_url'       => '',
        ),

        array(
            'name'         => 'YITH Essential Kit for WooCommerce #1',
            'slug'         => 'yith-essential-kit-for-woocommerce-1',
            'required'     => false,
            'version'      => '1.0.0',
        ),


        defined( 'YITH_YWPI_PREMIUM' ) ? array() : array(
            'name'      => 'YITH WooCommerce PDF Invoice and Shipping List',
            'slug'      => 'yith-woocommerce-pdf-invoice',
            'required'  => false,
            'version'   => '1.0.0'
        ),


    ));
}