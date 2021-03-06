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

/**
 * Add specific fields to the tab Colors -> Navigation
 * 
 * @param array $fields
 * @return array
 */ 
function yit_tab_colors_navigation( $fields ) {
	
	unset( $fields[10] );
	unset( $fields[20] );
	
	
	return array_merge( $fields, array(
        	10 => array(
                'id' => 'navigation-background',
                'type' => 'colorpicker',
                'name' => __( 'Navigation background color', 'yit' ),
                'desc' => __( 'Select the background color of the navigation.', 'yit' ),
                'std' => apply_filters( 'yit_navigation-background_std', '#ffffff' ),
                'style' => array(
                	'selectors' => '#nav',
                	'properties' => 'background-color'
				)
            ),
            20 => array(
                'id' => 'sub-navigation-background',
                'type' => 'colorpicker',
                'name' => __( 'Sub navigation background color', 'yit' ),
                'desc' => __( 'Select the background color of the sub navigation.', 'yit' ),
                'std' => apply_filters( 'yit_sub-navigation-background_std', '#ffffff' ),
                'style' => array(
                	'selectors' => '#nav ul.sub-menu, #nav ul.children, #topbar #lang_sel ul ul',
                	'properties' => 'background-color'
				)
            ),
	        30 => array(
	            'id' => 'nav-border',
	            'type' => 'colorpicker',
	            'name' => __( 'Border color', 'yit' ),
	            'desc' => __( 'Select the color of the border.', 'yit' ),
	            'std' => apply_filters( 'yit_nav-border_std', '#dfdcdc' ),
	            'style' => array(
	               	'selectors' => '#nav, #nav > ul li a, #nav .menu > ul li a, #nav .megamenu ul.sub-menu li, #nav ul.sub-menu li, #nav ul.children li',
	               	'properties' => 'border-color'
				)
	        ),
	        40 => array(
	            'id' => 'nav-custom-text',
	            'type' => 'colorpicker',
	            'name' => __( 'Custom text color', 'yit' ),
	            'desc' => __( 'Select the color of the custom text.', 'yit' ),
	            'std' => apply_filters( 'yit_custom-text-highlight_std', '#121212' ),
	            'style' => array(
	               	'selectors' => '#nav .megamenu ul.sub-menu li.menu-item-custom-content p',
	               	'properties' => 'color'
				)
	        ),
	        50 => array(
	            'id' => 'nav-custom-text-highlight',
	            'type' => 'colorpicker',
	            'name' => __( 'Highlight custom text color', 'yit' ),
	            'desc' => __( 'Select the color of the custom text highlight.', 'yit' ),
	            'std' => apply_filters( 'yit_custom-text-highlight_std', '#0D0D0D' ),
	            'style' => array(
	               	'selectors' => '#nav .megamenu ul.sub-menu li.menu-item-custom-content span.highlight',
	               	'properties' => 'color'
				)
	        ),
        ) );
}
add_filter( 'yit_submenu_tabs_theme_option_colors_navigation', 'yit_tab_colors_navigation' );