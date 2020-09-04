<?php

defined( 'ABSPATH' ) || exit;


/**
 * Dashboard Customizer Admin.
 *
 * @since 1.0.0
 */
class EEDashboardCustomizer_Admin extends EditorEnhancer_Dashboard_Customizer_Config {

	/**
	 * Page hierarchy.
	 *
	 * @since 1.0.0
	 */
	private $_pageHierarchy = [];
	

	/**
	 * License page actions.
	 *
	 * @since 1.0.0
	 */
	public $license;


	/**
	 * Init.
	 *
	 * @since 1.0.0
	 */
	protected function init() {

		// The homepage slug, used for every page
		$this->homeSlug = $this->_doSlug( 'home' );

		// Main admin page and license page are always loaded in the admin
		add_action( 'admin_menu', [ $this, 'addBasicPages' ] );
		$this->_doLicenseActions();

		// Build the pages and settings
		$this->_addPagesConditionally();

		// Check license validity before running other methods
		if ( $this->isValid() ) :

			// Compile and register pages and settings
			add_action( 'admin_init', [ $this, 'registerSettings' ] );
			add_action( 'admin_menu', [ $this, 'buildPages' ] );

			// Post type
			add_action( 'admin_menu', [ $this, 'addDashboardMenuItems' ] );
			add_action( 'admin_init', [ $this, 'doCustomFields' ] );
			add_action( 'save_post', [ $this, 'saveCustomFields' ] );

			// Post type columns
			add_filter( 'manage_eedashboard_posts_columns', [ $this, 'set_custom_edit_eedashboard_columns' ] );
			add_action( 'manage_eedashboard_posts_custom_column' , [ $this, 'custom_eedashboard_column' ], 10, 2 );

			// Disable the admin bar if dashboard post type
			add_action( 'wp', [ $this, 'disable_wpadmin_bar'] );

			// Register the page
			add_action('admin_menu', [ $this, 'register_dashboard_page' ] );

			// For whatever reason, this has to be called just a little later......
			add_action( 'admin_head', [ $this, 'remove_dashboard_submenu' ] );
			
			// Set up redirect
			add_action( 'load-index.php', [ $this, 'redirect_dashboard' ] );

			//add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_special' ] );

		else :
			$this->debug( 'License not valid.' );
			
		endif;
	}


	/****************************************************************************
	 * Add pages.
	 */


	/**
	 * Add basic pages.
	 *
	 * @since 1.0.0
	 */
	public function addBasicPages() {
		// Top level page and Home submenu
		add_menu_page( 'Dashboard Customizer', 'Dashboard Customizer', 'manage_options', $this->homeSlug, [ $this, 'homePage' ] );
		add_submenu_page( $this->homeSlug, 'Home', 'Home', 'manage_options', $this->homeSlug, [ $this, 'homePage' ] );

		// License submenu item
		add_submenu_page( $this->homeSlug, 'License', 'License', 'manage_options', $this->_doSlug( 'license' ), [ $this, 'licensePage' ] );
	}


	/**
	 * Get pages conditionally.
	 *
	 * @since 1.0.0
	 */
	private function _addPagesConditionally() {
		$this->_addPage( 'Settings' );
	}


	/**
	 * Add dashboard pages
	 */
	public function addDashboardMenuItems() {
		// Dashboards
		add_submenu_page( $this->homeSlug, 'All Dashboards', 'All Dashboards', 'manage_options', 'edit.php?post_type=eedashboard' );
		add_submenu_page( $this->homeSlug, 'Add New Dashboards', 'Add New Dashboard', 'manage_options', 'post-new.php?post_type=eedashboard' );
	}


	/**
	 * Add custom fields
	 */
	public function doCustomFields() {
		add_meta_box( "user_types-meta", "User Roles", [ $this, "user_types" ], "eedashboard", "side", "low" );
		//add_meta_box( "widgets-meta", "Display Widgets", [ $this, "widgets" ], "eedashboard", "side", "low" );
	}

	public function user_types(){
		global $post;
		$custom = get_post_custom( $post->ID );
		$user_types = unserialize( $custom['eedc_usertypes'][0] );
		$roles = wp_roles();

		foreach ( $roles->roles as $role => $data ) :
			?>
			<input id="eedc-<?php echo $role; ?>" name="eedc-<?php echo $role; ?>" type="checkbox" value="1" <?php echo isset( $user_types[$role] ) ? ( checked( $user_types[$role], 1, true ) ) : ''; ?>>
			<label for="eedc-<?php echo $role; ?>"><?php echo $data['name']; ?></label><br>
			<?php
		endforeach;
	}


/*
	public function widgets(){
		global $post;
		$custom = get_post_custom( $post->ID );
		$widgets = $custom['eedc_widgets'][0];
		?>
		<select name="eedc_widgets" id="eedc_widgets">
			<option value="never" <?php isset( $widgets ) ? ( selected( $widgets, 'never', true ) ) : ''; ?>>Never</option>
			<option value="above" <?php isset( $widgets ) ? ( selected( $widgets, 'above', true ) ) : ''; ?>>Above</option>
			<option value="below" <?php isset( $widgets ) ? ( selected( $widgets, 'below', true ) ) : ''; ?>>Below</option>
		</select>
		<label for="eedc-<?php echo $role; ?>"><?php echo $data['name']; ?></label><br>
		<?php
	}
*/

	public function saveCustomFields() {
		if ( is_admin() ) :
			global $post;
			$roles = wp_roles();

			$roles_for_db = [];

			foreach ( $roles->roles as $role => $data ) :

				if ( isset( $_POST['eedc-' . $role] ) )
					$roles_for_db[$role] = $_POST['eedc-' . $role];

			endforeach;
			
			update_post_meta( $post->ID, 'eedc_usertypes', $roles_for_db );

			//update_post_meta( $post->ID, 'eedc_widgets', $_POST['eedc_widgets'] );
		endif;
	}


	/**
	 * Disable the WP Admin bar
	 */
	public function disable_wpadmin_bar() {
		// Disable the Admin Bar on the front end if viewing this post type
		if ( is_singular( 'eedashboard' ) )
			add_filter( 'show_admin_bar', '__return_false' );

	}


	/**
	 * Register dashboard page
	 */
	public function register_dashboard_page() {
		// Add the dashboard page
		add_dashboard_page( 'Dashboard', 'Dashboard', 'read', 'eedash', [ $this, 'create_dashboard' ] );
	}

	public function remove_dashboard_submenu() {
		remove_submenu_page( 'index.php', 'eedash' );
	}

	public function create_dashboard() {

		// Is a dashboard available for the current user?
		$dash = $this->_get_user_dashboard();

		// Exit without the iframe if no user dashboard set
		if ( ! $dash ) return;

		// Get the dashboard slug
		$dashboard = get_posts( [
			'post_type' => 'eedashboard',
			'post__in' => [ $dash ]
		]);

		// The height is 100vh - 97px because the margin-bottom of wpbody-content is 65
		// and the height of the topbar is 32. 32 + 65 = 97
		?>
		<style type="text/css"> 
			#iframe-container {
				margin:0px;
				margin-left: -20px;
				padding:0px;
				overflow:hidden;
				height: calc(100vh - 35px);
			}

			#wpbody-content {
			    padding-bottom: 0;
			}

			iframe { 
	            margin: 0px; 
	            padding: 0px; 
	            height: 100%; 
	            border: none; 
	            display: block; 
	            width: 100%; 
	            border: none; 
	            overflow-y: auto; 
	            overflow-x: hidden; 
	        }

	        <?php

	        if ( $this->preferenceExists( 'use_white_background' ) ) :
	        	?>

	        	#iframe-container {
	        		background-color:white;
		        }

	        	<?php
	        endif;

	        ?>
		</style>

			<?php //print_r( parse_shortcodes( get_post_meta( $dash, 'ct_builder_shortcodes' )[0] ) ); ?>

			<?php

			//global $media_queries_list;
			//var_dump( $media_queries_list );

			//var_dump(get_post_meta($dash, 'ct_builder_shortcodes'));

?>
		<div id="iframe-container">

			<iframe
			src="<?php echo get_home_url() . '/eedashboard/' . $dashboard[0]->post_name; ?>?custom_dashboard_active=true"
			marginheight="0" 
			marginwidth="0" 
			width="100%" 
			height="100%" 
			scrolling="auto"></iframe>
		</div>
		<?php
		
	}


	/**
	 * Redirect dashboard
	 */
	public function redirect_dashboard() {
		//if( is_admin() ) {

			if ( $this->_get_user_dashboard() ) :
				$screen = get_current_screen();
				
				if( $screen->base == 'dashboard' ) :
					wp_redirect( admin_url( 'index.php?page=eedash' ) );
				endif;
			endif;
		//}
	}


	/**
	 * Add column for user roles
	 */

	// Add the custom columns to the eedashboard post type:
	public function set_custom_edit_eedashboard_columns( $columns ) {
	    $columns['user_roles'] = __( 'User Roles', 'editor_enhancerdc' );
	    return $columns;
	}

	// Add the data to the custom columns for the eedashboard post type:
	public function custom_eedashboard_column( $column, $post_id ) {
		if ( $column == 'user_roles' ) :
			$usertypes = get_post_meta( $post_id , 'eedc_usertypes' , true );

			foreach ( $usertypes as $usertype => $val ) :
				echo ucfirst($usertype) . '<br>';
			endforeach;
		endif;
	}


	/**
	 * Get user dashboard
	 * 
	 * If a dashboard has been set for the current user's role type, then it will
	 * return the ID of that post. Otherwise, it will return false.
	 */
	private function _get_user_dashboard() {

		// Assume no dashboard is available, so we'll use the default
		$dashboard_ID = false;

		// Current user's highest role
		$current_user_role = wp_get_current_user()->roles[0];

		// Get the dashboards
		$args = array(
		  'numberposts' => -1,
		  'post_type'   => 'eedashboard'
		);
		 
		$dashboards = get_posts( $args );

		// Run through the dashboards and check them against the current user
		foreach ( $dashboards as $dashboard ) :

			$usertypes = get_post_meta( $dashboard->ID, 'eedc_usertypes' )[0];

			if ( ! empty( $usertypes ) ) :
				foreach ( $usertypes as $usertype => $val ) :

					if ( $usertype === $current_user_role ) :
						$dashboard_ID = $dashboard->ID;
						
					endif;

				endforeach;
			endif;
		endforeach;

		return $dashboard_ID ? $dashboard_ID : false;
	}


	/****************************************************************************
	 * Page contents.
	 */


	/**
	 * Home page content.
	 *
	 * @since 1.0.0
	 */
	public function homePage() {
		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) )
			return;

		require_once EEDC_ADMIN . 'home.php';
	}


	/**
	 * License page content.
	 *
	 * @since 1.0.0
	 */
	public function licensePage() {
		$license = $this->getOption( 'license_key' );
		$status  = $this->getOption( 'license_status' );
		$input_type = $license ? 'password' : 'text';

		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) )
			return;

		require_once EEDC_ADMIN . 'license.php';
	}


	/**
	 * Settings page.
	 *
	 * @since 1.0.0
	 */
	public function settingsPage() {
		$extra_description = null;
		$button_text = 'Save Settings';
		$options = $this->getOption( 'settings' );

		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) )
			return;

		require_once EEDC_ADMIN . 'settings.php';
	}

	private function _addSetting( $name, $dbName, $options, $selectOptions = array() ) {
		?>
		<div class="settings-row<?php echo ! empty( $selectOptions ) ? ' is-select-box' : ''; ?>">
			<div class="settings-col">
				<?php
				if ( ! empty( $selectOptions ) ) :

					?>
					<select name="eedc_settings[<?php echo $dbName; ?>]" id="<?php echo $dbName; ?>">
						<?php

						foreach ( $selectOptions as $option => $value ) :

							?>
							<option value="<?php echo $value; ?>" <?php echo isset( $options[$dbName] ) ? ( selected( $options[$dbName], $value, false ) ) : ''; ?>><?php echo $option; ?></option>
							<?php

						endforeach;

						?>
					</select>
					<?php

				else :

					?>
					<input id="<?php echo $dbName; ?>" class="cmn-toggle cmn-toggle-round" type="checkbox" value="1" name="eedc_settings[<?php echo $dbName; ?>]" <?php echo isset( $options[$dbName] ) ? ( checked( $options[$dbName], 1, true ) ) : ''; ?>>
					<label for="<?php echo $dbName; ?>"></label>
					<?php

				endif;
				
				?>
			</div>
			<div class="settings-col">
				<?php echo $name; ?>
			</div>
		</div>
		<?php
	}


	/****************************************************************************
	 * Page builder functions.
	 */


	/**
	 * Register settings.
	 *
	 * @since 1.0.0
	 */
	public function registerSettings() {

		// Register settings for fields
		foreach ( $this->_pageHierarchy as $page => $pageInfo ) :

			// Register the page setting
			register_setting( $this->_doSlug( $page ), $this->_doSlug( $page ) );

			if ( isset( $pageInfo['sections'] ) ) :
				foreach ( $pageInfo['sections'] as $section => $sectionInfo ) :

					// Register sections
					add_settings_section( 
						$this->_doSlug( $section ),
						$sectionInfo['title'],
						[ $this, 'gratuitousSectionCallback' ],
						$this->_doSlug( $page )
					);

					if ( isset( $sectionInfo['fields'] ) ) :
						foreach ( $sectionInfo['fields'] as $field => $fieldInfo ) :

							$fieldCallback = $fieldInfo['type'] . 'Callback';

							// Add the field
							add_settings_field(
								$field,
								$fieldInfo['label'],
								[ $this, $fieldCallback ],
								$this->_doSlug( $page ),
								$this->_doSlug( $section ),
								array(
									// Setting is registered by the page
									'setting'     => $this->_doSlug( $page ),

									// Label used for identifier column
									'label_for'   => $field,

									// Description is optional
									'description' => isset( $fieldInfo['description'] ) ? $fieldInfo['description'] : '',

									// Options are only used for select fields
									'options'     => isset( $fieldInfo['args']['options'] ) ? $fieldInfo['args']['options'] : array(),

									// Custom values are added since 1.0.0 for Editor Optimizer
									'value' => isset( $fieldInfo['args']['value'] ) ? $fieldInfo['args']['value'] : false
								)
							);

						endforeach;
					endif;
				endforeach;
			endif;
		endforeach;
	}


	/**
	 * Build pages.
	 *
	 * @since 1.0.0
	 */
	public function buildPages() {
		foreach ( $this->_pageHierarchy as $page => $pageInfo ) :

			add_submenu_page(
				$this->homeSlug,
				$pageInfo['title'],
				$pageInfo['title'],
				'manage_options',
				$this->_doSlug( $page ),
				[ $this, $page . 'Page' ]
			);

		endforeach;
	}


	/**
	 * Add a page.
	 *
	 * @since 1.0.0
	 */
	private function _addPage( $title, $capability = 'manage_options' ) {
		$slug = strtolower( str_replace( [ ' / ', ' ' ], '_', $title ) );

		$this->_pageHierarchy[$slug] = [
			'title'      => $title,
			'capability' => $capability
		];
	}


	/**
	 * Add a section.
	 *
	 * @since 1.0.0
	 */
	private function _addSection( $parent, $title ) {
		$parentSlug = strtolower( str_replace( [ ' / ', ' ' ], '_', $parent ) );
		$slug = strtolower( str_replace( ' ', '_', $title ) );

		if ( ! isset( $this->_pageHierarchy[$parentSlug]['sections'] ) )
			$this->_pageHierarchy[$parentSlug]['sections'] = [];

		$this->_pageHierarchy[$parentSlug]['sections'][$slug] = [
			'title' => $title
		];
	}


	/**
	 * Add a field.
	 *
	 * @since 1.0.0
	 */
	private function _addField( $parent, $section, $label, $description, $type, $args = [] ) {

		$parentSlug = strtolower( str_replace( ' ', '_', $parent ) );
		$sectionSlug = strtolower( str_replace( ' ', '_', $section ) );
		$slug = strtolower( str_replace( ' ', '_', $label ) );

		if ( ! isset( $this->_pageHierarchy[$parentSlug]['sections'][$sectionSlug]['fields'] ) )
			$this->_pageHierarchy[$parentSlug]['sections'][$sectionSlug]['fields'] = [];

		$this->_pageHierarchy[$parentSlug]['sections'][$sectionSlug]['fields'][$slug] = [
			'label'       => $label,
			'description' => $description,
			'type'        => $type,
			'args'        => $args
		];

		// Add to defaults, if default is set
		if ( isset( $args['default'] ) ) :
			if ( ! isset( $this->defaults[$this->_doSlug( $parentSlug )] ) )
				$this->defaults[$this->_doSlug( $parentSlug )] = [];

			$this->defaults[$this->_doSlug( $parentSlug )][$slug] = strtolower( str_replace( ' ', '_', $args['default'] ) );
		endif;
	}


	/**
	 * Return a page slug.
	 *
	 * @since 1.0.0
	 */
	private function _doSlug( $slug ) {
		return $this->prefix . '_' . $slug;
	}


	/**
	 * Generic admin options page.
	 *
	 * @since 1.0.0
	 */
	private function _genericAdminPage( $slug, $button_text = 'Save Settings', $extra_description = null ) {
		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) )
			return;

		?>
		<link rel="stylesheet" href="<?php echo EEDC_ADMIN_URI . 'styles.css'; ?>">
		<?php

		// Did the user submit the settings?
		if ( isset( $_GET['settings-updated'] ) ) {
			// Show saved message
			add_settings_error( $this->_doSlug( $slug ) . '_messages', $this->_doSlug( $slug ) . '_message', __( 'Settings Saved', $this->_doSlug( $slug ) ), 'updated' );
		}

		// Show error/update messages
		settings_errors( $this->_doSlug( $slug ) . '_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<?php
			if ( $extra_description )
				echo '<div>' . $extra_description . '</div>';
			?>
			<form action="options.php" method="post">
				<?php
				// Output security fields for the registered setting
				settings_fields( $this->_doSlug( $slug ) );

				// Output sections and fields
				do_settings_sections( $this->_doSlug( $slug ) );

				// Output save settings button
				submit_button( $button_text );
				?>
			</form>
		</div>
		<?php
	}


	/**
	 * Checkbox field callback.
	 *
	 * @since 1.0.0
	 */
	public function checkboxCallback( $args ) {
		$label   = esc_attr( $args['label_for'] );
		$setting = esc_attr( $args['setting'] );
		$options = get_option( $setting );
		$checked = isset( $options[ $label ] ) ? ( checked( $options[ $label ], 1, false ) ) : '';
		$value = $args['value'] ? $args['value'] : $label;
		?>
		<input id="<?php echo $label; ?>" type="checkbox" value="1" name="<?php echo $setting . '[' . $value . ']'; ?>" <?php echo isset( $options[ $value ] ) ? ( checked( $options[ $value ], 1, false ) ) : ''; ?>>
		<?php
		$this->_fieldDescription( $args );
	}


	/**
	 * Select field callback.
	 *
	 * @since 1.0.0
	 */
	public function selectCallback( $args ) {
		$label   = esc_attr( $args['label_for'] );
		$setting = esc_attr( $args['setting'] );
		$options = get_option( $setting );
		$given_value = $args['value'] ? $args['value'] : $label;
		?>
		<select id="<?php echo $label; ?>" name="<?php echo $setting . '[' . $given_value . ']'; ?>">
			<?php
			foreach ( $args['options'] as $option ) :
				$value = strtolower( str_replace( ' ', '_', $option ) );
				$selected = isset( $options[ $given_value ] ) ? ( selected( $options[ $given_value ], $value, false ) ) : '';
				?>
				<option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
				<?php
			endforeach;
			?>
		</select>
		<?php
		$this->_fieldDescription( $args );
	}


	/**
	 * Text field callback.
	 *
	 * @since 1.0.0
	 */
	public function textCallback( $args ) {
		$label   = esc_attr( $args['label_for'] );
		$setting = esc_attr( $args['setting'] );
		$max_length = esc_attr( $args['max_length'] );
		$options = get_option( $setting );
		$value = isset( $options[ $label ] ) ? $options[ $label ] : '';
		?>
		<input id="<?php echo $label; ?>" type="text" name="<?php echo $setting . '[' . $label . ']'; ?>" value="<?php echo $value; ?>" maxlength="<?php echo $max_length; ?>">
		<?php
		$this->_fieldDescription( $args );
	}


	/**
	 * Description output for callbacks.
	 *
	 * @since 1.0.0
	 */
	private function _fieldDescription( $args ) {
		if ( isset( $args['description'] ) && $args['description'] !== '' ) :
			?>
			<p class="description"><?php echo $args['description']; ?></p>
			<?php
		endif;
	}


	/****************************************************************************
	 * Inclusions.
	 */


	/**
	 * Set license actions.
	 *
	 * @since 1.0.0
	 */
	private function _doLicenseActions() {
		require_once 'license.php';
		$this->license = new EditorEnhancer_DC_Admin_License(
			$this->prefix,
			$this->name,
			$this->plugin_url,
			$this->website_url
		);
	}


	/****************************************************************************
	 * Additional, miscellaneous functions.
	 */


	/**
	 * Update option data.
	 *
	 * @since 1.0.0
	 */
	protected function updateOption( $option, $data ) {
		update_option( $this->prefix . '_' . $option, $data );
	}


	/**
	 * Required function for sections, though no need to have any actual content or output.
	 *
	 * @since 1.0.0
	 */
	public function gratuitousSectionCallback() {}


	/**
	 * Get page settings.
	 *
	 * @since 1.0.0
	 */
	private function _getPageSettings( $pageSlug ) {
		$pageCallback = '_' . $pageSlug . 'PageSettings';
		$this->$pageCallback();
	}


	/**
	 * External link shortcut.
	 *
	 * @since 1.0.0
	 */
	private function _externalLink( $title, $url ) {
		?>
		<a class="eedc-external-link" href="<?php echo $url; ?>" target="_blank">
			<span><?php echo $title; ?></span>
			<div>
				<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="external-link-alt" class="svg-inline--fa fa-external-link-alt fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M432,320H400a16,16,0,0,0-16,16V448H64V128H208a16,16,0,0,0,16-16V80a16,16,0,0,0-16-16H48A48,48,0,0,0,0,112V464a48,48,0,0,0,48,48H400a48,48,0,0,0,48-48V336A16,16,0,0,0,432,320ZM488,0h-128c-21.37,0-32.05,25.91-17,41l35.73,35.73L135,320.37a24,24,0,0,0,0,34L157.67,377a24,24,0,0,0,34,0L435.28,133.32,471,169c15,15,41,4.5,41-17V24A24,24,0,0,0,488,0Z"></path></svg>
				<span><?php echo $url; ?></span>
			</div>
		</a>
		<?php
	}
}// End of class
