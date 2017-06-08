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


 function yit_tab_popup_settings( $fields ) {

			$fields[5] = array(
                'id'   => 'popup_title_font',
                'type' => 'typography',
                'name' => __( 'Title font', 'yit' ),
                'desc' => __( 'Select the font used in the Popup Title. ', 'yit' ),
                'min'  => 1,
                'max'  => 30,
                'std'  => apply_filters( 'yit_popup-title_std', array(
                    'size'   => 21,
                    'unit'   => 'px',
                    'family' => 'Noticia Text',
                    'style'  => 'regular',
                    'color'  => '#666767'
                ) ),
            	'style' => apply_filters( 'yit_popup-title_style', array(
					'selectors' => 'div.popup .title',
					'properties' => 'font-size, font-family, color, font-style, font-weight'
				) ),
            );

            $fields[6] = array(
                'id'   => 'popup_content_font',
                'type' => 'typography',
                'name' => __( 'Content Font', 'yit' ),
                'desc' => __( 'Select the font used in the Popup Content. ', 'yit' ),
                'min'  => 1,
                'max'  => 18,
                'std'  => apply_filters( 'yit_popup-content_std', array(
                    'size'   => 12,
                    'unit'   => 'px',
                    'family' => 'Noticia Text',
                    'style'  => 'regular',
                    'color'  => '#666767'
                ) ),
            	'style' => apply_filters( 'yit_popup-content_style', array(
					'selectors' => 'div.popup, div.popup_message, div.popup_message p, div.popup_message span',
					'properties' => 'font-size, font-family, color, font-style, font-weight'
				) ),
            );

     return $fields;
 }

add_filter( 'yit_submenu_tabs_theme_option_popup_settings', 'yit_tab_popup_settings' );