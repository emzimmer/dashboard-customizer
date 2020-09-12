<?php

defined( 'ABSPATH' ) || exit;


/**
 * Dashboard Customizer Admin.
 *
 * @since 1.0.0
 */
class EEDashboardCustomizer_Interface extends EditorEnhancer_Dashboard_Customizer_Config {

	/**
	 * Constructor.
	 */
	public function init() {

		// Don't do anything unless validity is confirmed
		if ( $this->isValid() ) :

			// Need to hook into a later hook so that is_singular can work
			require_once EEDC_COMPONENTS . 'dashboardEl.php';
			$this->load_files();
			add_action( 'wp', [ $this, 'do_addplus_sections' ] );

			//$this->do_addplus_sections();

		else :
			$this->debug( 'License not valid.' );

		endif;
	}


	/**
	 * The dashboard components need to load after a test for is_singular, so
	 * this hooks into 'wp' to load a bit later than everything else
	 */
	public function do_addplus_sections() {

		// Verify the post type
		if ( is_singular( 'eedashboard' ) ) :

			// Remove the WP Admin top bar
			add_filter('show_admin_bar', '__return_false');

			// Register +Add section
			add_action( 'oxygen_add_plus_sections', [ $this, 'register_add_plus_section' ] );

			// Register +Add subsections
			// oxygen_add_plus_{$id}_section_content
			add_action('oxygen_add_plus_dashboard_customizer_section_content', [ $this, 'register_add_plus_subsections' ]);

		endif;

	}


	public function load_files() {
		include_once EEDC_COMPONENTS . 'widgets/at-a-glance.php';
		include_once EEDC_COMPONENTS . 'widgets/recent-posts.php';
		include_once EEDC_COMPONENTS . 'widgets/future-posts.php';
		include_once EEDC_COMPONENTS . 'widgets/recent-comments.php';
		include_once EEDC_COMPONENTS . 'widgets/site-health-status.php';
		include_once EEDC_COMPONENTS . 'links/admin-link-wrapper.php';
		include_once EEDC_COMPONENTS . 'links/admin-text-link.php';
	}

	public function register_add_plus_section() {
		CT_Toolbar::oxygen_add_plus_accordion_section( 'dashboard_customizer', __('Dashboard Customizer') );
	}

	public function register_add_plus_subsections() {

		$dashboard_customizer_categories = [
			'Widgets',
			'Other'
		];

		foreach ( $dashboard_customizer_categories as $category ) :
			?>

			<h2><?php echo $category; ?></h2>

			<?php
			do_action( 'oxygen_add_plus_dashboard_customizer_' . strtolower( str_replace( ' ', '_', $category ) ) );

		endforeach;

	}

}// End of class
