<?php

/**
 * Slider Revolution
 *
 * @package   Essential_Grid
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/revolution/
 * @copyright 2014 ThemePunch
 */

/**
 * @package RevSliderExtension
 * @author  ThemePunch <info@themepunch.com>
 */
class RevSliderExtension {

	public function __construct() {

		$this->init_essential_grid_extensions();

	}


	/***************************
	 * Setup part for Revslider inclusion into Essential Grid
	 ***************************/

	/**
	 * Do all initializations for RevSlider integration
	 */
	public function init_essential_grid_extensions(){

		add_filter('essgrid_set_ajax_source_order', array($this, 'add_slider_to_eg_ajax'));
		add_filter('essgrid_handle_ajax_content', array($this, 'set_slider_values_to_eg_ajax'), 10, 4);
		add_action('essgrid_add_meta_options', array($this, 'add_eg_additional_meta_field'));
		add_action('essgrid_save_meta_options', array($this, 'save_eg_additional_meta_field'), 10, 2);

		//only do on frontend

		add_action('admin_head', array($this, 'add_eg_additional_inline_javascript'));
		add_action('wp_head', array($this, 'add_eg_additional_inline_javascript'));

	}


	/**
	 * Add Slider to the List of choosable media
	 */
	public function add_slider_to_eg_ajax($media){

		$media['revslider'] = array('name' => __('Slider Revolution', REVSLIDER_TEXTDOMAIN), 'type' => 'ccw');

		return $media;
	}


	/**
	 * Add Slider to the List of choosable media
	 */
	public function set_slider_values_to_eg_ajax($handle, $media_sources, $post, $grid_id){

		if($handle !== 'revslider') return false;

		$slider_source = '';

		$values = get_post_custom($post['ID']);

		if(isset($values['eg_sources_revslider'])){
			if(isset($values['eg_sources_revslider'][0]))
				$slider_source = @$values['eg_sources_revslider'][0];
			else
				$slider_source = @$values['eg_sources_revslider'];
		}

		if($slider_source === ''){
			return false;
		}else{
			return ' data-ajaxtype="'.$handle.'" data-ajaxsource="'.$slider_source.'"';
		}

	}


	/**
	 * Adds custom meta field into the essential grid meta box for post/pages
	 */
	public function add_eg_additional_meta_field($values){

		$sld = new RevSlider();
		$sliders = $sld->getArrSliders();
		$shortcodes = array();
		if(!empty($sliders)){
			$first = true;
			foreach($sliders as $slider){
				$name = $slider->getParam('shortcode','false');
				if($name != 'false'){
					$shortcodes[$slider->getID()] = $name;
					$first = false;
				}
			}
		}

		$selected_slider = (isset($values['eg_sources_revslider'])) ? $values['eg_sources_revslider'] : '';
		if($selected_slider == '') $selected_slider[0] = '';
		?>
		<p>
			<strong style="font-size:14px"><?php _e('Choose Revolution Slider', REVSLIDER_TEXTDOMAIN); ?></strong>
		</p>
		<p>
			<select name="eg_sources_revslider" id="eg_sources_revslider">
				<option value=""<?php selected($selected_slider[0], ''); ?>><?php _e('--- Choose Slider ---', REVSLIDER_TEXTDOMAIN); ?></option>
				<?php
				if(!empty($shortcodes)){
					foreach($shortcodes as $id => $name){
						?>
						<option value="<?php echo $id; ?>"<?php selected($selected_slider[0], $id); ?>><?php echo $name; ?></option>
						<?php
					}
				}
				?>
			</select>
		</p>
		<?php

	}

	/**
	 * Adds custom meta field into the essential grid meta box for post/pages
	 */
	public function save_eg_additional_meta_field($metas, $post_id){

		if(isset($metas['eg_sources_revslider']))
			update_post_meta($post_id, 'eg_sources_revslider', $metas['eg_sources_revslider']);

	}


	/**
	 * Adds needed javascript to the DOM
	 */
	public function add_eg_additional_inline_javascript(){
		?>

		<?php
	}

}
?>
