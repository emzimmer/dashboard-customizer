<?php

defined( 'ABSPATH' ) || exit;


/**
 * Dashboard Customizer Config.
 *
 * @since 1.0.0
 */
class EditorEnhancer_Dashboard_Customizer_Config {

	/**
	 * Debug.
	 *
	 * @since 1.0.0
	 */
	protected $debug = false;


	/**
	 * Prefix.
	 *
	 * @since 1.0.0
	 */
	protected $prefix = 'eedc';


	/**
	 * General variable prototypes.
	 *
	 * @since 1.0.0
	 */
	protected $name,
			  $version,
			  $plugin_url,
			  $website_url,
			  $admin_url,
			  $poke;


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	*/
	public function __construct() {

		// Set up general information
		$this->name        = 'Dashboard Customizer';
		$this->version     = EE_DASHBOARD_CUSTOMIZER_VERSION;
		$this->plugin_url  = 'http://editorenhancer.com';
		$this->website_url = get_site_url();
		$this->admin_url   = get_admin_url();
		$this->poke        = 5600;

		// Check for updates
		add_action( 'admin_init', [ $this, 'runUpdater' ], 0 );

		// Load assets
		$this->preferences = $this->getOption( 'settings' );

		
		/**
		 * Do universal stuff before initialization
		 */

		// Post type
		add_action( 'init', [ $this, 'create_posttype' ] );


		// Init
		$this->init();
	}


	/**
	 * Create the post type
	 */
	public function create_posttype() {
		if ( is_user_logged_in() ) :
			$labels = array(
		        'name'                  => _x( 'Dashboards', 'Post type general name', 'editor_enhancerdc' ),
		        'singular_name'         => _x( 'Dashboard', 'Post type singular name', 'editor_enhancerdc' ),
		        'menu_name'             => _x( 'Dashboards', 'Admin Menu text', 'editor_enhancerdc' ),
		        'name_admin_bar'        => _x( 'Dashboard', 'Add New on Toolbar', 'editor_enhancerdc' ),
		        'add_new'               => __( 'Add New', 'editor_enhancerdc' ),
		        'add_new_item'          => __( 'Add New Dashboard', 'editor_enhancerdc' ),
		        'new_item'              => __( 'New Dashboard', 'editor_enhancerdc' ),
		        'edit_item'             => __( 'Edit Dashboard', 'editor_enhancerdc' ),
		        'view_item'             => __( 'View Dashboard', 'editor_enhancerdc' ),
		        'all_items'             => __( 'All Dashboards', 'editor_enhancerdc' ),
		        'search_items'          => __( 'Search Dashboards', 'editor_enhancerdc' ),
		        'parent_item_colon'     => __( 'Parent Dashboards:', 'editor_enhancerdc' ),
		        'not_found'             => __( 'No dashboards found.', 'editor_enhancerdc' ),
		        'not_found_in_trash'    => __( 'No dashboards found in Trash.', 'editor_enhancerdc' ),
		        'featured_image'        => _x( 'Dashboard Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'editor_enhancerdc' ),
		        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'editor_enhancerdc' ),
		        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'editor_enhancerdc' ),
		        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'editor_enhancerdc' ),
		        'archives'              => _x( 'Dashboard archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'editor_enhancerdc' ),
		        'insert_into_item'      => _x( 'Insert into dashboard', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'editor_enhancerdc' ),
		        'uploaded_to_this_item' => _x( 'Uploaded to this dashboard', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'editor_enhancerdc' ),
		        'filter_items_list'     => _x( 'Filter dashboards list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'editor_enhancerdc' ),
		        'items_list_navigation' => _x( 'Dashboards list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'editor_enhancerdc' ),
		        'items_list'            => _x( 'Dashboards list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'editor_enhancerdc' ),
		    );
		 
		    $args = array(
				'labels'             => $labels,
				'public'             => false,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => 'admin.php?page=eedc_home',
				'show_in_nav_menus'  => false,
				'show_in_admin_bar'  => false,
				'show_in_rest'       => false,
				'capability_type'    => 'post',
				//'capabilities' => '',
				'query_var'          => true,
				'rewrite'            => array( 'slug' => 'eedashboard' ),
				'has_archive'        => false,
				'hierarchical'       => false,
				//'menu_position'      => null,
				'supports'           => array( 'title' ),
		    );
		 
		    register_post_type( 'eedashboard', $args );

		endif;

	}


	/**
	 * Get option data.
	 *
	 * @since 1.0.0
	 */
	protected function getOption( $option ) {
		return get_option( $this->prefix . '_' . $option );
	}


	/**
	 * Preferences checkbox checker.
	 *
	 * @since 1.0.0
	 */
	protected function preferenceExists( $preferenceName ) {
		if ( isset( $this->preferences[$preferenceName] ) && $this->preferences[$preferenceName] )
			return true;

		return false;
	}


	/**
	 * Run updater functions.
	 *
	 * @since 1.0.0
	 */
	public function runUpdater() {

		if ( $this->getOption( 'license_key' ) && $license = trim( $this->getOption( 'license_key' ) ) ) :
			require_once 'edd.php';

			$edd_updater = new EditorEnhancer_DC_EDD_SL_Plugin_Updater( $this->plugin_url, EE_DASHBOARD_CUSTOMIZER_INDEX,
				array(
					'version' => $this->version,
					'license' => $license,
					'item_id' => $this->poke,
					'author'  => 'Ukuwi',
					'beta'    => false
				)
			);

		else :
			$this->debug( 'Updater license key not found.' );

		endif;
	}


	/**
	 * Check license.
	 *
	 * @since 1.0.0
	 */
	protected function isValid( $test = false ) {
		
		if ( ! $test ) :

			return ( $this->getOption( 'license_key' )
						&& $this->getOption( 'license_status' )
						&& $this->getOption( 'license_status' ) == 'valid'
						//&& $this->getOption( 'version' )
						//&& $this->getOption( 'version' ) === $this->version
					) ? true : false;

		else :
			global $wp_version;

			$license = trim( $this->getOption( 'license_key' ) );

			$api_params = array(
				'edd_action' => 'check_license',
				'license'    => $license,
				'item_name'  => $this->name,
				'item_id'    => $this->poke,
				'url'        => $this->website_url
			);

			// Call the custom API.
			$response = wp_remote_post( $this->plugin_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			if ( is_wp_error( $response ) )
				return false;

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// Return boolean
			return ( $license_data->license == 'valid' );

		endif;
	}


	/**
	 * Debug function.
	 *
	 * @since 1.0.0
	 */
	protected function debug( $message ) {
		if ( $this->debug )
			echo '<script>console.log("Dashboard Customizer: ' . $message . '");</script>';
	}
}// End of class
