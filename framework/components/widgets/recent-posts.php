<?php

class DC_Widget_Recent_Posts extends DashboardCustomizerEl {

 //   var $js_added = false;

	function name() {
		return __('Recent Posts');
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
		$css = $selector.' li {
					
				}

		';

		return $css;
	}

	function controls() {

		$this->addOptionControl(
			array(
				'type'    => 'checkbox',
				'name'    => 'Include Title',
				'slug'    => 'include_title',
				'default' => 'true'
			)
		)->rebuildElementOnChange();

		$this->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => 'Title',
				'slug'      => 'title',
				'condition' => 'include_title=true',
				'default'   => 'Recent Posts'
			)
		)->rebuildElementOnChange();

		$this->addOptionControl(
			array(
				"type"    => "dropdown",
				"name"    => "Layout",
				"slug"    => "layout",
				"default" => 'rows'
			)
		)->setValue(
			array( 
				"rows"   => "Rows",
				'table' => 'Table'
			)
		)->rebuildElementOnChange();
	}

	function render($options, $defaults, $content) {

		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'posts_per_page' => 5,
			'no_found_rows'  => true,
			'cache_results'  => false
		);

		$posts = new WP_Query( $args );

		if ( $posts->have_posts() ) :

			// Do the heading conditionally
			if ( $options['include_title'] && $options['title'] !== null )
				echo '<h3>' . $options['title'] . '</h3>';

			// Start the list
			echo '<ul>';

			$today    = current_time( 'Y-m-d' );
			$tomorrow = current_datetime()->modify( '+1 day' )->format( 'Y-m-d' );
			$year     = current_time( 'Y' );

			while ( $posts->have_posts() ) :

				$posts->the_post();

				$time = get_the_time( 'U' );

				if ( gmdate( 'Y-m-d', $time ) == $today ) :
					$relative = __( 'Today' );

				elseif ( gmdate( 'Y-m-d', $time ) == $tomorrow ) :
					$relative = __( 'Tomorrow' );

				elseif ( gmdate( 'Y', $time ) !== $year ) :
					/* translators: Date and time format for recent posts on the dashboard, from a different calendar year, see https://www.php.net/date */
					$relative = date_i18n( __( 'M jS Y' ), $time );

				else :
					/* translators: Date and time format for recent posts on the dashboard, see https://www.php.net/date */
					$relative = date_i18n( __( 'M jS' ), $time );

				endif;

				// Output the list item
				printf(
					'<li><span>%1$s</span> <a href="%2$s" aria-label="%3$s">%4$s</a></li>',
					/* translators: 1: Relative date, 2: Time. */
					sprintf( _x( '%1$s, %2$s', 'dashboard' ), $relative, get_the_time() ),
					$recent_post_link,
					/* translators: %s: Post title. */
					esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), get_the_title() ) ),
					get_the_title()
				);

			endwhile;

			// End the list
			echo '</ul>';

		else :
			return false;

		endif;

		wp_reset_postdata();

		return true;
	}

	function defaultCSS() {
		$css = '.oxy-dashboard-customizer-recent-posts ul {
					list-style:none;
					padding:0;
				}

			';

		return $css;
	}

	/**
	 * Output JS for toggle menu in responsive mode
	 *
	 * @since 2.0
	 * @author Ilya K.
	 */
	
	function output_js() {}


}

new DC_Widget_Recent_Posts();
