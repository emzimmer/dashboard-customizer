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
define( 'EEDC_CLASSES_URI', EE_DASHBOARD_CUSTOMIZER_URI . 'framework/classes/' );

define( 'EEDC_INCLUDES', EE_DASHBOARD_CUSTOMIZER_ROOT . 'framework/includes/' );
define( 'EEDC_INCLUDES_URI', EE_DASHBOARD_CUSTOMIZER_URI . 'framework/includes/' );

define( 'EEDC_SCRIPTS', EE_DASHBOARD_CUSTOMIZER_URI . 'framework/scripts/' );
define( 'EEDC_STYLES', EE_DASHBOARD_CUSTOMIZER_URI . 'framework/stylesheets/' );


/**
 * Begin the startup process.
 *
 * @since 1.0.0
 */
require_once EEDC_CLASSES . 'config.php';
require_once EEDC_CLASSES . 'admin.php';
new EditorEnhancer_Dashboard_Customizer;
