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

		$css = '';

		// List item padding top
		if ( isset( $options[$this->optionCSS( 'list_item_padding_top' )] ) ) :

			$li_pad_top_unit = isset( $options[$this->optionCSS( 'list_item_padding_top-unit' )] ) ? $options[$this->optionCSS( 'list_item_padding_top-unit' )] : 'px';

			$li_pad_top = $options[$this->optionCSS( 'list_item_padding_top' )].$li_pad_top_unit;

		endif;

		// List item padding bottom
		if ( isset( $options[$this->optionCSS( 'list_item_padding_bottom' )] ) ) :

			$li_pad_bottom_unit = isset( $options[$this->optionCSS( 'list_item_padding_bottom-unit' )] ) ? $options[$this->optionCSS( 'list_item_padding_bottom-unit' )] : 'px';

			$li_pad_bottom = $options[$this->optionCSS( 'list_item_padding_bottom' )].$li_pad_bottom_unit;

		endif;

		// List item padding left
		if ( isset( $options[$this->optionCSS( 'list_item_padding_left' )] ) ) :

			$li_pad_left_unit = isset( $options[$this->optionCSS( 'list_item_padding_left-unit' )] ) ? $options[$this->optionCSS( 'list_item_padding_left-unit' )] : 'px';

			$li_pad_left = $options[$this->optionCSS( 'list_item_padding_left' )].$li_pad_left_unit;

		endif;

		// List item padding right
		if ( isset( $options[$this->optionCSS( 'list_item_padding_right' )] ) ) :

			$li_pad_right_unit = isset( $options[$this->optionCSS( 'list_item_padding_right-unit' )] ) ? $options[$this->optionCSS( 'list_item_padding_right-unit' )] : 'px';

			$li_pad_right = $options[$this->optionCSS( 'list_item_padding_right' )].$li_pad_right_unit;

		endif;

		// List items
		$css .= $selector.' .eedc-rp-li {';
			$css .= isset( $li_pad_top ) ? 'padding-top:' . $li_pad_top . ';' : '';
			$css .= isset( $li_pad_bottom ) ? 'padding-bottom:' . $li_pad_bottom . ';' : '';
			$css .= isset( $li_pad_left ) ? 'padding-left:' . $li_pad_left . ';' : '';
			$css .= isset( $li_pad_right ) ? 'padding-right:' . $li_pad_right . ';' : '';
		$css .= '}';

		return $css;
	}

	function controls() {
/*
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
				'layers' => 'Layers'
			)
		)->rebuildElementOnChange();
*/

		/**
		 * Title controls
		 */
		$title = $this->addControlSection( 'title', 'TItle', '', $this);

		// Include title checkbox
		$include_title = $title->addOptionControl(
			array(
				'type'    => 'checkbox',
				'name'    => 'Include Title',
				'slug'    => 'include_title',
				'default' => 'true'
			)
		)->rebuildElementOnChange();

		// Title input box
		$title_input = $title->addOptionControl(
			array(
				'type'      => 'textfield',
				'name'      => 'Title',
				'slug'      => 'title',
				'condition' => 'include_title=true',
				'default'   => 'Recent Posts'
			)
		);

		// Title tag drop down
		$title_tag = $title->addOptionControl(
			 array(
				'type'      => 'dropdown',
				'name'      => 'Tag',
				'slug'      => 'title_tag',
				'condition' => 'include_title=true',
				'default'   => 'h3',
			 )
		)->setValue(
			array( 
				'h1' => 'h1',
				'h2' => 'h2',
				'h3' => 'h3',
				'h4' => 'h4',
				'h5' => 'h5',
				'h6' => 'h6'
			)
		)->rebuildElementOnChange();

		// Typography section
		$title_typography = $title->typographySection( 'Typography', '.eedc-rp-title', $this );


		/**
		 * List items
		 */
		$list = $this->addControlSection( 'list', 'List', '', $this);

		// Post count
		$post_count = $list->addOptionControl(
			array(
				'type'    => 'slider-measurebox',
				'name'    => 'Count',
				'slug'    => 'list_item_count',
				'default' => 5
			)
		)->setRange('1','20','1');
		
		$post_count->rebuildElementOnChange();

		// Display date/time
		$display_datetime = $list->addOptionControl(
			array(
				'type'    => 'checkbox',
				'name'    => 'Display date/time',
				'slug'    => 'display_date_time',
				'default' => 'true'
			)
		)->rebuildElementOnChange();

		// List items -> Layout
		$li_layout = $list->addControlSection( 'list_item_layout', 'List Item Layout', '', $this);

		$li_padding_top = $li_layout->addOptionControl(
			array(
				'type'    => 'slider-measurebox',
				'name'    => 'Padding Top',
				'slug'    => 'list_item_padding_top',
				'default' => '0'
			)
		)->rebuildElementOnChange();

		$li_padding_bottom = $li_layout->addOptionControl(
			array(
				'type'    => 'slider-measurebox',
				'name'    => 'Padding Bottom',
				'slug'    => 'list_item_padding_bottom',
				'default' => '0'
			)
		)->rebuildElementOnChange();

		$li_padding_left = $li_layout->addOptionControl(
			array(
				'type'    => 'slider-measurebox',
				'name'    => 'Padding Left',
				'slug'    => 'list_item_padding_left',
				'default' => '0'
			)
		)->rebuildElementOnChange();

		$li_padding_right = $li_layout->addOptionControl(
			array(
				'type'    => 'slider-measurebox',
				'name'    => 'Padding Right',
				'slug'    => 'list_item_padding_right',
				'default' => '0'
			)
		)->rebuildElementOnChange();

		$li_layout_flex = $li_layout->flex( '.eedc-rp-li', $this );

		// List items links
		$li_link = $list->typographySection( 'Link Typography', '.eedc-rp-link', $this );

		// List items date/time
		$li_datetime = $list->typographySection( 'DateTime Typography', '.eedc-rp-datetime', $this );

	}

	function render($options, $defaults, $content) {

		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'posts_per_page' => $options['list_item_count'],
			'no_found_rows'  => true,
			'cache_results'  => false
		);

		$posts = new WP_Query( $args );

		if ( $posts->have_posts() ) :

			// Do the heading conditionally
			if ( $options['include_title'] == 'true' && $options['title'] !== null )
				echo '<' . $options['title_tag'] . ' class="eedc-rp-title">' . $options['title'] . '</' . $options['title_tag'] . '>';

			// Start the list
			echo '<ul>';

			$today    = current_time( 'Y-m-d' );
			$tomorrow = current_datetime()->modify( '+1 day' )->format( 'Y-m-d' );
			$year     = current_time( 'Y' );

			while ( $posts->have_posts() ) :

				$posts->the_post();

				// Output the list item
				echo '<li class="eedc-rp-li">';

					$recent_post_link = current_user_can( 'edit_post', get_the_ID() ) ? get_edit_post_link() : get_permalink();

					echo '<a class="eedc-rp-link" href="' . $recent_post_link . '">' . get_the_title() . '</a>';

					// Only do the date stuff if the datetime is requested
					if ( $options['display_date_time'] == 'true' ) :

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

						echo '<span class="eedc-rp-datetime">' . sprintf( _x( '%1$s, %2$s', 'dashboard' ), $relative, get_the_time() ) . '</span>';

					endif;

				echo '</li>';


				// Output the list item
			//	printf(
			//		'<li><span>%1$s</span> <a href="%2$s" aria-label="%3$s">%4$s</a></li>',
			//		/* translators: 1: Relative date, 2: Time. */
			//		sprintf( _x( '%1$s, %2$s', 'dashboard' ), $relative, get_the_time() ),
			//		$recent_post_link,
			//		/* translators: %s: Post title. */
			//		esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), get_the_title() ) ),
			//		get_the_title()
			//	);

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
					margin:0;
				}

				.eedc-rp-li {
					display:flex;
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
