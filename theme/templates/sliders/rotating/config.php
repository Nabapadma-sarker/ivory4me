<?php
/**
 * @package WordPress
 * @subpackage Your Inspiration Themes
 */ 
 
yit_register_slider_style(  $slider_type, 'slider-rotating', 'css/slider-rotating.css' );
yit_register_slider_script( $slider_type, 'slider-rotating', 'js/jquery.RotateImageMenu.js' );

// set here if the slider is reponsive or not
$this->responsive_sliders[ $slider_type ] = true;

// add support to slide
yit_add_slide_support( $slider_type, 'link' );
 
// add the slider fields for the admin
yit_add_slider_config( $slider_type, array(
    array(
        'name' => __( 'Width content', 'yit' ),
        'id' => 'width',
        'type' => 'number',        
        'desc' => __( 'Select the width of container (select 0 if you want to have the same width of site container).', 'yit' ),
        'min' => 0,
        'max' => 2000,
        'std' => 0
    ),
    array(
        'name' => __( 'Height content', 'yit' ),
        'id' => 'height',
        'type' => 'number',        
        'desc' => __( 'Select the height of container.', 'yit' ),
        'min' => 10,
        'max' => 2000,
        'std' => 320
    ),                
    array( 'name' => __('Slider Title', 'yit'),
    	   'desc' => __("The title that appears above slides (leave empty if you don't want this title).", 'yit'),
    	   'id' => 'slider_title',
    	   'type' => 'text',
		   'std' => ''
    ),                 
    array( 'name' => __('Number of panel', 'yit'),
    	   'desc' => __('Number of panels for each slide.', 'yit'),
    	   'id' => 'n_panels',
    	   'type' => 'slider',
    	   'min' => 2,
    	   'max' => 5,
		   'std' => 3
    ),                		   
    array(
        'name' => __( 'Pause between slides (s)', 'yit' ),
        'id' => 'interval',
        'type' => 'slider',        
        'desc' => __( 'Select the delay between slides, expressed in seconds.', 'yit' ),
        'min' => 0.1,
        'max' => 20,
        'step' => 0.1,
        'std' => 7
    ),
    array(
        'name' => __( 'Animation speed (s)', 'yit' ),
        'id' => 'speed',
        'type' => 'slider',
        'desc' => __( 'The speed of the animation between two slide, expressed in seconds.', 'yit' ),
        'min' => 0.1,
        'max' => 20,
        'step' => 0.1,
        'std' => 0.2
    ),
    array(
        'type' => 'sep',
    ),
    array( 'name' => __('Show text near slider', 'yit'),
           'desc' => __("Define if you want to show a text near the slider.", 'yit'),
           'id' => 'show_text',
           'type' => 'onoff',
           'std' => 0
    ),
    array( 'name' => __('Text Position', 'yit'),
           'desc' => __("Define position of the text to show a text near the slider.", 'yit'),
           'id' => 'text_position',
           'type' => 'select',
           'options' => array(
               'left' => __( 'Left', 'yit' ),
               'right' => __( 'Right', 'yit' ),
           ),
           'std' => 'left'
    ),
    array( 'name' => __('Text', 'yit'),
           'desc' => __("Define the text to show a text near the slider.", 'yit'),
           'id' => 'text',
           'type' => 'textarea-editor',
           'std' => ''
    ),
) );        