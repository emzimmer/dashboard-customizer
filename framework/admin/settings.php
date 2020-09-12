
<link rel="stylesheet" href="<?php echo EEDC_ADMIN_URI . 'styles.css'; ?>">
<script src="https://use.fontawesome.com/c59de2e120.js"></script>
<?php

// Did the user submit the settings?
if ( isset( $_GET['settings-updated'] ) ) {
	// Show saved message
	add_settings_error( $this->_doSlug( 'settings' ) . '_messages', $this->_doSlug( 'settings' ) . '_message', __( 'Settings Saved', $this->_doSlug( 'settings' ) ), 'updated' );
}

// Show error/update messages
settings_errors( $this->_doSlug( 'settings' ) . '_messages' );
?>
<div class="wrap">
	<?php require_once 'tabs.php'; ?>
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form action="options.php" method="post">
		<?php
		// Output security fields for the registered setting
		settings_fields( $this->_doSlug( 'settings' ) );
		?>
		<div class="settings-section">
			<div class="settings-row settings-row-head">
				<h3>General</h3>
			</div>
			<?php
			$this->_addSetting( 'Use white background for dashboards. (Default is to use WP background color).', 'use_white_background', $options );
			//$this->_addSetting( 'Remove dashboard footer. (Thank you message and WP version).', 'remove_dashboard_footer', $options );
			?>
		</div>

		<div id="buttons-row">
			<?php submit_button( $button_text ); ?>
		</div>
	</form>
</div>
