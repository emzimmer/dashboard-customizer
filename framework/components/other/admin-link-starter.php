<?php

class DC_Other_Admin_Link_Starter extends DashboardCustomizerEl {

 //   var $js_added = false;

	function name() {
		return __('Admin Link Starter');
	}

/*
	function enableFullPresets() {
	   // return true;
	}
	function icon() {
		return CT_FW_URI . '/toolbar/UI/oxygen-icons/add-icons/pro-menu.svg';
	}
*/
	function dashboard_customizer_button_place() {
		return 'other';
	}

	function button_priority() {
		return 11;
	}

	function init() {

		$this->enableNesting();

	   // add_action("oxygen_default_classes_output", array( $this->El, "generate_defaults_css" ) );

		// include only for builder
	   // if (isset( $_GET['oxygen_iframe'] )) {
	   //     add_action( 'wp_footer', array( $this, 'output_js' ) );
	   // }
//
	 //   add_filter("oxy_allowed_empty_options_list", array( $this, "allowedEmptyOptions") );
	 //   add_filter("oxygen_vsb_element_presets_defaults", array( $this, "presetsDefaults") );

	}


	function afterInit() {
		$this->removeApplyParamsButton();
	}

	/*
		function tag() {
			return array( 'default' => 'div' );
		}
	*/
	function class_names() {
		return '';
	}

	function optionCSS($slug) {
		return 'oxy-' . $this->slug() . '_' . $slug;
	}

	
	function customCSS($options, $selector) {
		return '';
	}


	function tag() {
		return 'div';
	}


	function controls() {

		$this->addOptionControl(
			array(
				'type' => 'textfield',
				'name' => 'URL',
				'slug' => 'url'
			)
		)->rebuildElementOnChange();
	}


	function render($options, $defaults, $content) {

		echo '<a href="' . get_admin_url() . $options['url'] . '">';

		echo ct_do_shortcode( $content );

		echo '</a>';
	}

	function defaultCSS() {

	}

	/**
	 * Output JS for toggle menu in responsive mode
	 *
	 * @since 2.0
	 * @author Ilya K.
	 */
	
	function output_js() {}


}

new DC_Other_Admin_Link_Starter();
