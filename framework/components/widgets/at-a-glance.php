<?php

class DC_Widget_AtAGlance extends DashboardCustomizerEl {

 //   var $js_added = false;

	function name() {
		return __('At A Glance');
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
		return 'widgets';
	}

	function button_priority() {
		return 11;
	}

	function init() {

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



	function controls() {

	}


	function render($options, $defaults, $content) {
		?>
	   <div class="main">
	   	<ul>
	   	<?php
	   	// Posts and Pages.
	   	foreach ( array( 'post', 'page' ) as $post_type ) {
	   		$num_posts = wp_count_posts( $post_type );
	   		if ( $num_posts && $num_posts->publish ) {
	   			if ( 'post' === $post_type ) {
	   				/* translators: %s: Number of posts. */
	   				$text = _n( '%s Post', '%s Posts', $num_posts->publish );
	   			} else {
	   				/* translators: %s: Number of pages. */
	   				$text = _n( '%s Page', '%s Pages', $num_posts->publish );
	   			}
	   			$text             = sprintf( $text, number_format_i18n( $num_posts->publish ) );
	   			$post_type_object = get_post_type_object( $post_type );
	   			if ( $post_type_object && current_user_can( $post_type_object->cap->edit_posts ) ) {
	   				printf( '<li class="%1$s-count"><a href="%2$sedit.php?post_type=%1$s">%3$s</a></li>', $post_type, get_admin_url(), $text );
	   			} else {
	   				printf( '<li class="%1$s-count"><span>%2$s</span></li>', $post_type, $text );
	   			}
	   		}
	   	}
	   	// Comments.
	   	$num_comm = wp_count_comments();
	   	if ( $num_comm && ( $num_comm->approved || $num_comm->moderated ) ) {
	   		/* translators: %s: Number of comments. */
	   		$text = sprintf( _n( '%s Comment', '%s Comments', $num_comm->approved ), number_format_i18n( $num_comm->approved ) );
	   		?>
	   		<li class="comment-count"><a href="<?php echo get_admin_url(); ?>edit-comments.php"><?php echo $text; ?></a></li>
	   		<?php
	   		$moderated_comments_count_i18n = number_format_i18n( $num_comm->moderated );
	   		/* translators: %s: Number of comments. */
	   		$text = sprintf( _n( '%s Comment in moderation', '%s Comments in moderation', $num_comm->moderated ), $moderated_comments_count_i18n );
	   		?>
	   		<li class="comment-mod-count
	   		<?php
	   		if ( ! $num_comm->moderated ) {
	   			echo ' hidden';
	   		}
	   		?>
	   		"><a href="<?php echo get_admin_url(); ?>edit-comments.php?comment_status=moderated" class="comments-in-moderation-text"><?php echo $text; ?></a></li>
	   		<?php
	   	}

	   	/**
	   	 * Filters the array of extra elements to list in the 'At a Glance'
	   	 * dashboard widget.
	   	 *
	   	 * Prior to 3.8.0, the widget was named 'Right Now'. Each element
	   	 * is wrapped in list-item tags on output.
	   	 *
	   	 * @since 3.8.0
	   	 *
	   	 * @param string[] $items Array of extra 'At a Glance' widget items.
	   	 */
	   	$elements = apply_filters( 'dashboard_glance_items', array() );

	   	if ( $elements ) {
	   		echo '<li>' . implode( "</li>\n<li>", $elements ) . "</li>\n";
	   	}

	   	?>
	   	</ul>
	   	<?php
	   	//update_right_now_message();

	   	// Check if search engines are asked not to index this site.
	   	if ( ! is_network_admin() && ! is_user_admin() && current_user_can( 'manage_options' ) && '0' == get_option( 'blog_public' ) ) {

	   		/**
	   		 * Filters the link title attribute for the 'Search engines discouraged'
	   		 * message displayed in the 'At a Glance' dashboard widget.
	   		 *
	   		 * Prior to 3.8.0, the widget was named 'Right Now'.
	   		 *
	   		 * @since 3.0.0
	   		 * @since 4.5.0 The default for `$title` was updated to an empty string.
	   		 *
	   		 * @param string $title Default attribute text.
	   		 */
	   		$title = apply_filters( 'privacy_on_link_title', '' );

	   		/**
	   		 * Filters the link label for the 'Search engines discouraged' message
	   		 * displayed in the 'At a Glance' dashboard widget.
	   		 *
	   		 * Prior to 3.8.0, the widget was named 'Right Now'.
	   		 *
	   		 * @since 3.0.0
	   		 *
	   		 * @param string $content Default text.
	   		 */
	   		$content    = apply_filters( 'privacy_on_link_text', __( 'Search engines discouraged' ) );
	   		$title_attr = '' === $title ? '' : " title='$title'";

	   		echo "<p class='search-engines-info'><a href='<?php echo get_admin_url(); ?>options-reading.php'$title_attr>$content</a></p>";
	   	}
	   	?>
	   	</div>
	   	<?php
	   	/*
	   	 * activity_box_end has a core action, but only prints content when multisite.
	   	 * Using an output buffer is the only way to really check if anything's displayed here.
	   	 */
	   	ob_start();

	   	/**
	   	 * Fires at the end of the 'At a Glance' dashboard widget.
	   	 *
	   	 * Prior to 3.8.0, the widget was named 'Right Now'.
	   	 *
	   	 * @since 2.5.0
	   	 */
	   	do_action( 'rightnow_end' );

	   	/**
	   	 * Fires at the end of the 'At a Glance' dashboard widget.
	   	 *
	   	 * Prior to 3.8.0, the widget was named 'Right Now'.
	   	 *
	   	 * @since 2.0.0
	   	 */
	   	do_action( 'activity_box_end' );

	   	$actions = ob_get_clean();

	   	if ( ! empty( $actions ) ) :
	   		?>
	   	<div class="sub">
	   		<?php echo $actions; ?>
	   	</div>
	   		<?php
	   	endif;
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

new DC_Widget_AtAGlance();
