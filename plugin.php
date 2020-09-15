<?php
/**
 * Plugin Name: GIT Dashboard Customizer
 * Plugin URI: https://editorenhancer.com
 * Description: Design beautiful dashboards for yourself and your clients with Oxygen Builder.
 * Version: 1.2.0
 * Author: Editor Enhancer
 * Author URI: https://editorenhancer.com
 * Text Domain: editor_enhancerdc
 */

defined( 'ABSPATH' ) || exit;


/**
 * Cool definitions.
 *
 * @since 1.0.0
 */
define( 'EE_DASHBOARD_CUSTOMIZER', true );
define( 'EE_DASHBOARD_CUSTOMIZER_VERSION', '1.2.0' );
define( 'EE_DASHBOARD_CUSTOMIZER_INDEX', __FILE__ );
define( 'EE_DASHBOARD_CUSTOMIZER_ROOT', plugin_dir_path( EE_DASHBOARD_CUSTOMIZER_INDEX ) );
define( 'EE_DASHBOARD_CUSTOMIZER_URI', plugin_dir_url( EE_DASHBOARD_CUSTOMIZER_INDEX ) );


/**
 * Helper definitions.
 *
 * @since 1.0.0
 */
define( 'EEDC_ADMIN', EE_DASHBOARD_CUSTOMIZER_ROOT . 'framework/admin/' );
define( 'EEDC_ADMIN_URI', EE_DASHBOARD_CUSTOMIZER_URI . 'framework/admin/' );
define( 'EEDC_CLASSES', EE_DASHBOARD_CUSTOMIZER_ROOT . 'framework/classes/' );
define( 'EEDC_COMPONENTS', EE_DASHBOARD_CUSTOMIZER_ROOT . 'framework/components/' );
define( 'EEDC_COMPONENTS_URI', EE_DASHBOARD_CUSTOMIZER_URI . 'framework/components/' );


/**
 * Init Dashboard Customizer
 */
add_action( 'plugins_loaded', 'ee_dashboard_customizer_init' );
function ee_dashboard_customizer_init() {

	// check if Oxygen is installed and active, and running on front end
	if ( ! class_exists( 'OxygenElement' ) ) return;

	// Get the configuration class.
	require_once EEDC_CLASSES . 'config.php';

	if ( is_admin() ) :
		require_once EEDC_CLASSES . 'admin.php';
		$EEDashboardCustomizerAdmin = new EEDashboardCustomizer_Admin;
	endif;

	require_once EEDC_CLASSES . 'interface.php';
	$EEDashboardCustomizer = new EEDashboardCustomizer_Interface;

}


/**
 * Adds scripts for the iframe
 */
add_action( 'admin_enqueue_scripts', 'enqueue_dashboard_customizer_iframe_scripts' );
function enqueue_dashboard_customizer_iframe_scripts() {
	//if ( isset( $_GET['custom_dashboard_active'] ) && $_GET['custom_dashboard_active'] == true ) :
		wp_enqueue_script(
			'eedc-dash',
			EEDC_ADMIN_URI . 'dash.js',
			[],
			EE_DASHBOARD_CUSTOMIZER_VERSION,
			true
		);
	//endif;
}


/**
 * Sets links to open in the parent, rather than the iframe
 */
add_action( 'wp_head', 'enqueue_dashboard_customizer_special_head_scripts');
function enqueue_dashboard_customizer_special_head_scripts() {
	if ( isset( $_GET['custom_dashboard_active'] ) && $_GET['custom_dashboard_active'] == true ) :
		?>
		<base target="_parent">
		<?php
	endif;
}
