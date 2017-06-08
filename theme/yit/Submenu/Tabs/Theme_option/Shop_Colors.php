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
 * Class to print fields in the tab Shop -> Colors
 * 
 * @since 1.0.0
 */
class YIT_Submenu_Tabs_Theme_option_Shop_Colors extends YIT_Submenu_Tabs_Abstract {
    /**
     * Default fields
     * 
     * @var array
     * @since 1.0.0
     */
    public $fields;
    
    /**
     * Merge default fields with theme specific fields using the filter yit_submenu_tabs_theme_option_shop_colors
     * 
     * @param array $fields
     * @since 1.0.0
     */
    public function __construct() {
        $fields = $this->init();
        $this->fields = apply_filters( strtolower( __CLASS__ ), $fields );
    }
    
    /**
     * Set default values
     * 
     * @return array
     * @since 1.0.0
     */
    public function init() {  
        return array(
        	/* === START COLORS === */
        	10 => array(
                'id' => 'shop-cart-background',
                'type' => 'colorpicker',
                'name' => __( 'Shopping cart background', 'yit' ),
                'desc' => __( 'Select the color of shop cart on topbar background.', 'yit' ),
                'std' => '#484848',
                'style' => array(
                	'selectors' => '#header-sidebar .widget_shopping_cart .cart_control',
                	'properties' => 'background-color'
				)
            ),
            20 => array(
                'id' => 'shop-cart-border',
                'type' => 'colorpicker',
                'name' => __( 'Shopping cart button border', 'yit' ),
                'desc' => __( 'Select the color of border of shop cart on topbar.', 'yit' ),
                'std' => '#474747',
                'style' => array(
                	'selectors' => '#header-sidebar .widget_shopping_cart .cart_control',
                	'properties' => 'border-color'
				)
            ),
            30 => array(
                'id' => 'shop-cart-list-background',
                'type' => 'colorpicker',
                'name' => __( 'Shopping cart list background', 'yit' ),
                'desc' => __( 'Select the color of shop cart list on topbar background.', 'yit' ),
                'std' => '#494949',
                'style' => array(
                	'selectors' => '#header-sidebar .widget_shopping_cart .cart_wrapper',
                	'properties' => 'background-color'
				)
            ),
            35 => array(
                'id' => 'shop-cart-button-color',
                'type' => 'colorpicker',
                'name' => __( 'Shopping cart button color', 'yit' ),
                'desc' => __( 'Select the color of shop cart button.', 'yit' ),
                'std' => '#393737',
                'style' => array(
                	'selectors' => '#header-sidebar .widget_shopping_cart .cart_wrapper .buttons .button',
                	'properties' => 'background-color'
				)
            ),
            36 => array(
                'id' => 'shop-cart-button-hover',
                'type' => 'colorpicker',
                'name' => __( 'Shopping cart button hover', 'yit' ),
                'desc' => __( 'Select the color of shop cart button hover.', 'yit' ),
                'std' => '#302e2e',
                'style' => array(
                	'selectors' => '#header-sidebar .widget_shopping_cart .cart_wrapper .buttons .button:hover',
                	'properties' => 'background-color'
				)
            ),
            40 => array(
                'type' => 'title',
                'name' => __( 'Common Shop settings', 'yit' ),
                'desc' => __( 'Common settings for the shop page.', 'yit' )
            ),
            50 => array(
                'id' => 'shop-sale-bg-color',
                'type' => 'colorpicker',
                'name' => __( 'OnSale icon background color', 'yit' ),
                'desc' => __( 'Select the background color of sale icon in the products list.', 'yit' ),
                'std' => '#c16604',
                'style' => array(
                	'selectors' => 'span.onsale',
                	'properties' => 'background-color'
				)
            ),
            60 => array(
                'id' => 'shop-sale-color',
                'type' => 'colorpicker',
                'name' => __( 'OnSale icon text color', 'yit' ),
                'desc' => __( 'Select the text color of sale icon in the products list.', 'yit' ),
                'std' => '#ffffff',
                'style' => array(
                	'selectors' => 'span.onsale',
                	'properties' => 'color'
				)
            ),
            70 => array(
                'type' => 'title',
                'name' => __( 'Grid View', 'yit' ),
                'desc' => __( 'Colors for the grid view.', 'yit' )
            ),
            80 => array(
                'id' => 'shop-grid-button',
                'type' => 'colorpicker',
                'name' => __( 'Color of button details and add to cart', 'yit' ),
                'desc' => __( 'Select the background color of details and add to cart buttons.', 'yit' ),
                'std' => '#fff',
                'style' => array(
                	'selectors' => 'ul.products li.product .product-actions a',
                	'properties' => 'background-color'
				)
            ),  
            90 => array(
                'id' => 'shop-grid-button-hover',
                'type' => 'colorpicker',
                'name' => __( 'Color of button details and add to cart, on hover', 'yit' ),
                'desc' => __( 'Select the background color of details and add to cart buttons, on hover.', 'yit' ),
                'std' => '#f5f4f2',
                'style' => array(
                	'selectors' => 'ul.products li.product .product-actions a:hover',
                	'properties' => 'background-color'
				)
            ),
            100 => array(
                'id' => 'shop-grid-out-of-stock',
                'type' => 'colorpicker',
                'name' => __( 'Background color out of stock message', 'yit' ),
                'desc' => __( 'Select the background color of "out of stock" message.', 'yit' ),
                'std' => '#8e0404',
                'opacity' => 0.69,
                'style' => array(
                	'selectors' => 'ul.products li.product .product-actions a.out_of_stock',
                	'properties' => 'background-color'
				)
            ),
            110 => array(
                'type' => 'title',
                'name' => __( 'List View', 'yit' ),
                'desc' => __( 'Colors for the list view.', 'yit' )
            ),
            120 => array(
                'id' => 'shop-list-button-bg',
                'type' => 'colorpicker',
                'name' => __( 'Color background read more button', 'yit' ),
                'desc' => __( 'Select the background color of read more button.', 'yit' ),
                'std' => '#fff',
                'style' => array(
                	'selectors' => 'ul.products li.product.list a.read-more',
                	'properties' => 'background-color'
				)
            ), 
            130 => array(
                'id' => 'shop-list-button-text',
                'type' => 'colorpicker',
                'name' => __( 'Color text read more button', 'yit' ),
                'desc' => __( 'Select the text color of read more button.', 'yit' ),
                'std' => '#212223',
                'style' => array(
                	'selectors' => 'ul.products li.product.list a.read-more',
                	'properties' => 'color'
				)
            ),  
            140 => array(
                'id' => 'shop-list-button-bg-hover',
                'type' => 'colorpicker',
                'name' => __( 'Color background read more button (hover)', 'yit' ),
                'desc' => __( 'Select the background color of read more button, in hover.', 'yit' ),
                'std' => '#1f1f1f',
                'style' => array(
                	'selectors' => 'ul.products li.product.list a.read-more:hover',
                	'properties' => 'background-color'
				)
            ), 
            150 => array(
                'id' => 'shop-list-button-text-hover',
                'type' => 'colorpicker',
                'name' => __( 'Color text read more button (hover)', 'yit' ),
                'desc' => __( 'Select the text color of read more button, in hover.', 'yit' ),
                'std' => '#ffffff',
                'style' => array(
                	'selectors' => 'ul.products li.product.list a.read-more:hover',
                	'properties' => 'color'
				)
            ),
            270 => array(
                'type' => 'title',
                'name' => __( 'Button colors', 'yit' ),
                'desc' => __( 'Colors for the buttons.', 'yit' )
            ),
            280 => array(
                'id' => 'shop-buttons-background',
                'type' => 'colorpicker',
                'name' => __( 'Color background of buttons', 'yit' ),
                'desc' => __( 'Select the background color of buttons.', 'yit' ),
                'std' => '#fff',
                'style' => array(
                	'selectors' => '.status-publish .button, .button[name="change_password"], table.my_account_orders .button, .woocommerce_message .button, .product .summary .single_add_to_cart_button, .cart .button, input.checkout-button.alt.button, .shipping-calculator-form .button, .multistep_step .button, #place_order.button, .yith-woo-ajax-navigation a.yith-wcan-reset-navigation.button, .summary a.add_to_wishlist',
                	'properties' => 'background-color'
				)
            ),
            290 => array(
                'id' => 'shop-buttons-hover-background',
                'type' => 'colorpicker',
                'name' => __( 'Color background of buttons on hover', 'yit' ),
                'desc' => __( 'Select the background color of Add to cart button on hover.', 'yit' ),
                'std' => '#1f1f1f',
                'style' => array(
                	'selectors' => '.status-publish .button:hover, .button[name="change_password"]:hover, table.my_account_orders .button:hover, .woocommerce_message .button:hover, div.product form.cart .button:hover, #content div.product form.cart .button:hover, .cart .button:hover, input.checkout-button.alt.button:hover, .shipping-calculator-form .button:hover, .multistep_step .button:hover, #place_order.button:hover, .yith-woo-ajax-navigation a.yith-wcan-reset-navigation.button:hover, .summary a.add_to_wishlist:hover',
                	'properties' => 'background-color'
				)
            ),
            300 => array(
                'id' => 'shop-buttons-text',
                'type' => 'colorpicker',
                'name' => __( 'Color text of buttons', 'yit' ),
                'desc' => __( 'Select the text color of buttons.', 'yit' ),
                'std' => '#212223',
                'style' => array(
                	'selectors' => '.status-publish .button, .button[name="change_password"], table.my_account_orders .button, .woocommerce_message .button, .product .summary .single_add_to_cart_button, .cart .button, input.checkout-button.alt.button, .shipping-calculator-form .button, .multistep_step .button, #place_order.button, .yith-woo-ajax-navigation a.yith-wcan-reset-navigation.button',
                	'properties' => 'color'
				)
            ),
            310 => array(
                'id' => 'shop-buttons-text-hover',
                'type' => 'colorpicker',
                'name' => __( 'Color text hover of buttons', 'yit' ),
                'desc' => __( 'Select the text color of buttons on hover.', 'yit' ),
                'std' => '#FFFFFF',
                'style' => array(
                	'selectors' => '.status-publish .button:hover, .button[name="change_password"]:hover, table.my_account_orders .button:hover, .woocommerce_message .button:hover, div.product form.cart .button:hover, #content div.product form.cart .button:hover, .cart .button:hover, input.checkout-button.alt.button:hover, .shipping-calculator-form .button:hover, .multistep_step .button:hover, #place_order.button:hover, .yith-woo-ajax-navigation a.yith-wcan-reset-navigation.button:hover',
                	'properties' => 'color'
				)
            ),
        );
    }
}