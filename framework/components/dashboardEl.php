<?php

if ( class_exists( 'DashboardCustomizerEl' ) ){
	return;
}

class DashboardCustomizerEl extends OxyEl {

	function init() {
		$this->El->useAJAXControls();
	}

	function class_names() {
		return '';
	}

	function button_place() {

		$dashboard_customizer_button_place = $this->dashboard_customizer_button_place();

		if ( $dashboard_customizer_button_place ) {
			return 'dashboard_customizer::' . $dashboard_customizer_button_place;
		}

	}

	function button_priority() {
		return '';
	}

	function doContent($content) {
		if ($content) {
			echo do_shortcode($content);
		}
	}

	/**
	 * Custom functions
	 */
	function slug() {
		return 'dashboard_customizer-' . $this->name2slug( $this->name() );
	}

	function optionEquals( $option, $value ) {
		return isset( $option ) && $option === $value;
	}

	// Shortcut
	function inlineJS($js) {
		$this->El->inlineJS($js);
	}

	public $includeJSOnce = false;

	function includeScriptOnce( $callback ) {
		if ( ! $this->includeJSOnce ) {
			add_action( 'wp_footer', [ $this, $callback ] );
			//$this->includeJSOnce = true;
		}
	}

	function getCustomOption( $options, $optionName ) {
		return $options['oxy-' . $this->slug() . '_' . $optionName];
	}

}
