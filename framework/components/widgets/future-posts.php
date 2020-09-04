<?php

class DC_Widget_Future_Posts extends DashboardCustomizerEl {

 //   var $js_added = false;

	function name() {
		return __('Future Posts');
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
		$query_args = array(
			'post_type'      => 'post',
			'post_status'    => 'future',
			'orderby'        => 'date',
			'order'          => 'ASC',
			'posts_per_page' => 5,
			'no_found_rows'  => true,
			'cache_results'  => false
		);

		/**
		 * Filters the query arguments used for the Recent Posts widget.
		 *
		 * @since 4.2.0
		 *
		 * @param array $query_args The arguments passed to WP_Query to produce the list of posts.
		 */
		//$query_args = apply_filters( 'dashboard_recent_posts_query_args', $query_args );
		$posts      = new WP_Query( $query_args );

		if ( $posts->have_posts() ) {

			echo '<div id="future-posts" class="activity-block">';

			echo '<h3>Publishing Soon</h3>';

			echo '<ul>';

			$today    = current_time( 'Y-m-d' );
			$tomorrow = current_datetime()->modify( '+1 day' )->format( 'Y-m-d' );
			$year     = current_time( 'Y' );

			while ( $posts->have_posts() ) {
				$posts->the_post();

				$time = get_the_time( 'U' );
				if ( gmdate( 'Y-m-d', $time ) == $today ) {
					$relative = __( 'Today' );
				} elseif ( gmdate( 'Y-m-d', $time ) == $tomorrow ) {
					$relative = __( 'Tomorrow' );
				} elseif ( gmdate( 'Y', $time ) !== $year ) {
					/* translators: Date and time format for recent posts on the dashboard, from a different calendar year, see https://www.php.net/date */
					$relative = date_i18n( __( 'M jS Y' ), $time );
				} else {
					/* translators: Date and time format for recent posts on the dashboard, see https://www.php.net/date */
					$relative = date_i18n( __( 'M jS' ), $time );
				}

				// Use the post edit link for those who can edit, the permalink otherwise.
				//$recent_post_link = current_user_can( 'edit_post', get_the_ID() ) ? get_edit_post_link() : get_permalink();

				$draft_or_post_title = get_the_title();
				printf(
					'<li><span>%1$s</span> <a href="%2$s" aria-label="%3$s">%4$s</a></li>',
					/* translators: 1: Relative date, 2: Time. */
					sprintf( _x( '%1$s, %2$s', 'dashboard' ), $relative, get_the_time() ),
					$recent_post_link,
					/* translators: %s: Post title. */
					esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $draft_or_post_title ) ),
					$draft_or_post_title
				);
			}

			echo '</ul>';
			echo '</div>';

		} else {
			return false;
		}

		wp_reset_postdata();

		return true;
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

new DC_Widget_Future_Posts();
