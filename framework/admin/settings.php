
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
	<?php
	/*
	<div id="eedc-header">
		<a class="header-button" href="">Home</a>
		<a class="header-button" href="">Preferences</a>
		<a class="header-button" href="">Back to WP Links</a>
		<a class="header-button" href="">Keyboard Shortcuts</a>
		<a class="header-button" href="">Custom Block Categories</a>
		<a class="header-button" href="">Editor Optimizer</a>
		<a class="header-button" href="">License</a>
	</div>
	*/
	?>
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<p>Manage your settings. Items marked with a star (<i class="fa fa-star"></i>) are extensions that add extra functionalities to the builder.</p>
	<?php
	if ( $extra_description )
		echo '<div>' . $extra_description . '</div>';
	?>
	<form action="options.php" method="post">
		<?php
		// Output security fields for the registered setting
		settings_fields( $this->_doSlug( 'settings' ) );

		// Output sections and fields
		//do_settings_sections( $this->_doSlug( 'keyboard_shortcuts' ) );
		/*
		?>
		<div class="settings-section">
			<div class="settings-row settings-row-head">
				<h3>Editor Color Scheme</h3>
			</div>
			<?php
			$this->_addSetting( 'Select an editor color scheme', 'editor_colors', $options, [
				'Oxygen Classic' => 'oxygen_classic',
				'EE Dark' => 'ee_dark',
				'EE Light' => 'ee_light',
			] );
			?>
		</div>
		*/
		?>
		<div class="settings-section">
			<div class="settings-row settings-row-head">
				<h3>Styles Panel</h3>
			</div>
			<?php
			$this->_addSetting( '<i class="fa fa-star"></i> <strong>Class Companion</strong>: Enabling adds buttons to copy/move ID styles to new classes and reset ID styles. <a href="https://editorenhancer.com/documentation/class-companion" target="_blank">Documentation</a>', 'enable_class_companion', $options );
			$this->_addSetting( '<i class="fa fa-star"></i> <strong>Style Flow</strong>: Enabling adds a button next to "Primary" and "Advanced" to open an aggregate styles manager. <a href="https://editorenhancer.com/documentation/style-flow" target="_blank">Documentation</a>', 'enable_style_flow', $options );
			$this->_addSetting( '<i class="fa fa-star"></i> <strong>Advanced Shortcuts</strong>: Enabling adds a row of buttons below the "Primary" and "Advanced" tabs to access advanced sub-panels quickly. <a href="https://editorenhancer.com/documentation/advanced-shortcuts" target="_blank">Documentation</a>', 'enable_advanced_shortcuts', $options );
			$this->_addSetting( '<i class="fa fa-star"></i> <strong>Class Picker</strong>: Enabling adds a button next to selectors. When clicked, reveal a dropdown of all of your website\'s classes, sorted alphabetically, to click and insert. <a href="https://editorenhancer.com/documentation/class-picker" target="_blank">Documentation</a>', 'enable_class_picker', $options );
			$this->_addSetting( '<i class="fa fa-star"></i> <strong>Handy Text</strong>: Enabling adds a textarea input for heading and text components for quick editing and restoring components with no content. <a href="https://editorenhancer.com/documentation/handy-text" target="_blank">Documentation</a>', 'enable_handy_text', $options );
			//$this->_addSetting( 'Use compact, easy-access unit selectors', 'enable_compact_unit_selectors', $options );
			$this->_addSetting( 'Use compact, easy-access selectors for font weight', 'enable_compact_font_weight', $options );
			$this->_addSetting( 'Use compact, easy-access selectors for heading tags', 'enable_compact_heading_tag', $options );
			$this->_addSetting( 'Show styles panel on clicking viewport component', 'show_styles_panel_on_viewport_click', $options );
			$this->_addSetting( 'Hide the styles panel when editor loads', 'hide_styles_panel', $options );
			?>
		</div>

		<div class="settings-section">
			<div class="settings-row settings-row-head">
				<h3>Add+ Panel</h3>
			</div>
			<?php
			$this->_addSetting( 'Hover over buttons to switch Add+ Panel frames', 'addplus_button_hover', $options );
			$this->_addSetting( 'Show Add+ components as list', 'addplus_use_list', $options );
			$this->_addSetting( 'Include WordPress subframes in main Add+ Panel button list', 'addplus_include_wp_subs', $options );
			$this->_addSetting( 'Keep Add+ Panel open while adding multiple elements (close by moving mouse outside of panel).', 'addplus_keep_open_on_hover', $options );
			?>
		</div>

		<div class="settings-section">
			<div class="settings-row settings-row-head">
				<h3>Other Panels</h3>
			</div>
			<?php
			$this->_addSetting( 'Show the structure panel when editor loads', 'show_structure_panel', $options );
			$this->_addSetting( 'Double the width of the library flyout panel', 'double_library_flyout', $options );
			?>
		</div>

		<div class="settings-section">
			<div class="settings-row settings-row-head">
				<h3>Top Bar</h3>
			</div>
			<?php
			$this->_addSetting( '<i class="fa fa-star"></i> <strong>Session Snapshots</strong>: Enabling adds a button to the topbar to open a new panel where you can save snapshots of current builds, which you can restore later during A/B testing. <a href="https://editorenhancer.com/documentation/session-snapshots" target="_blank">Documentation</a>', 'enable_session_snapshots', $options );
			$this->_addSetting( 'Use compact, easy-access topbar buttons', 'enable_compact_topbar_buttons', $options );
			$this->_addSetting( 'Convert the settings button to a dropdown for easy access to settings panels, and include helpful functions. Note, this setting should be paired with the compact topbar buttons.', 'enable_settings_dropdown', $options );
			$this->_addSetting( 'Include reference and link to currently editing', 'include_currently_editing', $options );
			$this->_addSetting( 'Open WordPress links dropdown on hover', 'open_wp_links_on_hover', $options );
			$this->_addSetting( 'Move media query selectors to the top bar', 'move_media_query_selectors_to_topbar', $options );
			//$this->_addSetting( 'Move text editor buttons (e.g. bold, italic, span, etc) from topbar to the top of the viewport', 'move_text_editor_buttons_to_viewport', $options );
			?>
		</div>

		<div class="settings-section">
			<div class="settings-row settings-row-head">
				<h3>Viewport/IFrame</h3>
			</div>
			<?php
			$this->_addSetting( '<i class="fa fa-star"></i> <strong>Viewport Buttons</strong>: Enabling adds a floating container of quick-access buttons for advanced styles tabs above/below component titlebar. <a href="https://editorenhancer.com/documentation/viewport-buttons" target="_blank">Documentation</a>', 'enable_viewport_buttons', $options );
			$this->_addSetting( '<i class="fa fa-star"></i> <strong>Right-Click Menu</strong>: Enabling includes a right-click context menu with common functions. <a href="https://editorenhancer.com/documentation/viewport-context-menu" target="_blank">Documentation</a>', 'enable_viewport_context_menu', $options );
			?>
		</div>

		<div class="settings-section">
			<div class="settings-row settings-row-head">
				<h3>Utilities</h3>
			</div>
			<?php
			$this->_addSetting( '<i class="fa fa-star"></i> <strong>Keyboard Shortcuts</strong>: Enabling adds an admin page for you to customize keyboard shortcuts for 50+ editor actions. <a href="https://editorenhancer.com/documentation/keyboard-shortcuts" target="_blank">Documentation</a>', 'enable_keyboard_shortcuts', $options );
			/*?>
			<div class="settings-row" id="enable-custom-undo-redo-ks">
				<div class="settings-col">
					
				</div>
				<div class="settings-col" style="display:flex;align-content: center;">



					<input id="enable_custom_undo_redo_ks" class="cmn-toggle cmn-toggle-round" type="checkbox" value="1" name="eedc_settings[enable_custom_undo_redo_ks]" <?php echo isset( $options['enable_custom_undo_redo_ks'] ) ? ( checked( $options['enable_custom_undo_redo_ks'], 1, true ) ) : ''; ?>>
					<label for="enable_custom_undo_redo_ks"></label>
					<small style="margin-left:10px;">Enable custom keyboard shortcuts for Oxygen Undo/Redo</small>



					<!--<input id="enable_custom_undo_redo_ks" type="checkbox" value="<?php echo isset( $options['enable_custom_undo_redo_ks'] ) ? $options['enable_custom_undo_redo_ks'] : 0; ?>" name="eedc_settings[enable_custom_undo_redo_ks]">-->
				</div>
			</div>
			<?php*/
			$this->_addSetting( '<i class="fa fa-star"></i> <strong>Copy/Paste</strong>: Enabling adds copy and paste functionality for components and styles, which can be pasted in editors on other sites. <a href="https://editorenhancer.com/documentation/copy-paste" target="_blank">Documentation</a>', 'enable_copy_paste', $options );
			$this->_addSetting( '<i class="fa fa-star"></i> <strong>Tabulator</strong>: Enabling adds functionality to tab between templates from within the editor. <a href="https://editorenhancer.com/documentation/tabulator" target="_blank">Documentation</a>', 'enable_tabulator', $options );
			$this->_addSetting( '<i class="fa fa-star"></i> <strong>Editor Optimizer</strong>: Enabling adds an admin page for you to manage which plugins load (or don\'t load) while editing with Oxygen. <a href="https://editorenhancer.com/documentation/editor-optimizer" target="_blank">Documentation</a>', 'enable_editor_optimizer', $options );
			$this->_addSetting( '<i class="fa fa-star"></i> <strong>Custom Block Categories</strong>: Enabling adds an admin page for you to manage, add, edit, or remove block categories for design sets. <a href="https://editorenhancer.com/documentation/custom-block-categories" target="_blank">Documentation</a>', 'enable_custom_block_categories', $options );
			//$this->_addSetting( 'Use right-aligned panel positioning', 'use_right_positioning', $options );
			$this->_addSetting( 'Enable automatic saving (note: autosave will not be performed if you are editing text or heading components)', 'enable_autosave', $options );
			?>
			<div class="settings-row" id="auto-save-timing">
				<div class="settings-col">
					
				</div>
				<div class="settings-col">
					<small>Automatic saving frequency (in minutes):</small> <input id="autosave_timing" type="number" value="<?php echo isset( $options['autosave_timing'] ) ? $options['autosave_timing'] : 0; ?>" name="eedc_settings[autosave_timing]"> <small>Default: 10</small>
				</div>
			</div>
			<?php
			$this->_addSetting( 'Load Oxygen assets locally', 'enable_local_loading', $options );
			?>
		</div>

		<div class="settings-section">
			<div class="settings-row settings-row-head">
				<h3>Admin Navigation & Organization</h3>
			</div>
			<?php
			$this->_addSetting( '<i class="fa fa-star"></i> <strong>Template Categories</strong>: Enabling adds Template Categories taxonomy and filter for organizing your templates. <a href="https://editorenhancer.com/documentation/template-categories" target="_blank">Documentation</a>', 'admin_enable_template_categories', $options );
			$this->_addSetting( 'Enable dropdown for Oxygen Templates in WP Admin topbar', 'enable_admin_topbar_templates', $options );
			$this->_addSetting( 'Enable dropdown for Oxygen Reusable Parts in WP Admin topbar', 'enable_admin_topbar_reusables', $options );
			$this->_addSetting( 'Enable dropdown for Oxygen Blocks in WP Admin topbar', 'enable_admin_topbar_blocks', $options );
			?>
		</div>

		<div class="settings-section">
			<div class="settings-row settings-row-head">
				<h3>Gutenberg</h3>
			</div>
			<?php
			$this->_addSetting( 'Replace Gutenberg color palette with Oxygen global colors', 'replace_gutenberg_colors', $options );
			$this->_addSetting( 'Remove option to add custom colors in Gutenberg', 'remove_gutenberg_custom_color', $options );
			?>
		</div>

		<div id="buttons-row">
			<?php submit_button( $button_text ); ?>
			<a href="?page=eedc_settings&restore_defaults=true">Restore Defaults</a>
			<a href="?page=eedc_settings&clear_all=true">Clear All</a>
		</div>
	</form>
</div>

<script
  src="https://code.jquery.com/jquery-1.12.4.js"
  integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU="
  crossorigin="anonymous"></script>
<script>

	// Autosave subs
	if ( $('#enable_autosave').attr('checked') == 'checked' ) {
		$('#auto-save-timing').show();
	}
	else {
		$('#auto-save-timing').hide();
	}

	$('label[for="enable_autosave"]').click(function() {
		$('#auto-save-timing').toggle();
	});

	// Keyboard shortcuts subs
	/*if ( $('#enable_keyboard_shortcuts').attr('checked') == 'checked' ) {
		$('#enable-custom-undo-redo-ks').show();
	}
	else {
		$('#enable-custom-undo-redo-ks').hide();
	}

	$('label[for="enable_keyboard_shortcuts"]').click(function() {
		$('#enable-custom-undo-redo-ks').toggle();
	});*/

	

</script>
