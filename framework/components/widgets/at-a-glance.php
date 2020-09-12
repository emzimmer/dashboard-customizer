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
		//$this->removeApplyParamsButton();
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
		$css .= $selector.' .eedc-aag-li {';
			$css .= isset( $li_pad_top ) ? 'padding-top:' . $li_pad_top . ';' : '';
			$css .= isset( $li_pad_bottom ) ? 'padding-bottom:' . $li_pad_bottom . ';' : '';
			$css .= isset( $li_pad_left ) ? 'padding-left:' . $li_pad_left . ';' : '';
			$css .= isset( $li_pad_right ) ? 'padding-right:' . $li_pad_right . ';' : '';
		$css .= '}';

		return $css;
	}



	function controls() {
		
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
				'default'   => 'At a Glance'
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
		$title_typography = $title->typographySection( 'Typography', '.eedc-aag-title', $this );


		/**
		 * List items
		 */
		$list = $this->addControlSection( 'list', 'List', '', $this);

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

		// List items links
		$li_link = $list->typographySection( 'Link Typography', '.eedc-aag-link', $this );

	}


	function render($options, $defaults, $content) {

		// Do the heading conditionally
		if ( $options['include_title'] == 'true' && $options['title'] !== null )
			echo '<' . $options['title_tag'] . ' class="eedc-aag-title">' . $options['title'] . '</' . $options['title_tag'] . '>';

		echo '<ul>';
		
		// Posts and pages
		foreach ( [ 'post', 'page' ] as $post_type ) :

			// How many posts of each?
			$num_posts = wp_count_posts( $post_type );

			// If not zero, and published count is not zero
			if ( $num_posts && $num_posts->publish ) :

				if ( $post_type === 'post' ) :
					$text = _n( '%s Post', '%s Posts', $num_posts->publish );

				else :
					$text = _n( '%s Page', '%s Pages', $num_posts->publish );

				endif;

				$text = sprintf( $text, number_format_i18n( $num_posts->publish ) );

				$post_type_object = get_post_type_object( $post_type );

				if ( $post_type_object && current_user_can( $post_type_object->cap->edit_posts ) ) :
					echo '<li class="eedc-aag-li"><a class="eedc-aag-link" href="' . get_admin_url() . 'edit.php?post_type=' . $post_type . '">' . $text . '</a></li>';

				else :
					echo '<li class="eedc-aag-li">' . $text . '</li>';

				endif;

			endif;

		endforeach;


		// Comments
		$num_comm = wp_count_comments();

		if ( $num_comm && ( $num_comm->approved || $num_comm->moderated ) ) :

			// Approved comments
			if ( $num_comm->approved ) :
				$text = sprintf( _n( '%s Comment', '%s Comments', $num_comm->approved ), number_format_i18n( $num_comm->approved ) );

				echo '<li class="eedc-aag-li"><a class="eedc-aag-link" href="' . get_admin_url() . 'edit-comments.php">' . $text . '</a></li>';
			endif;

			// Moderated comments
			//if ( $num_comm->moderated ) :

				$moderated_comments_count_i18n = number_format_i18n( $num_comm->moderated );

				$text = sprintf( _n( '%s Comment in moderation', '%s Comments in moderation', $num_comm->moderated ), $moderated_comments_count_i18n );

				echo '<li class="eedc-aag-li"><a class="eedc-aag-link" href="' . get_admin_url() . 'edit-comments.php?comment_status=moderated">' . $text . '</a></li>';
			//endif;

		endif;

		echo '</ul>';

	}

	function defaultCSS() {

		$css = '.oxy-dashboard-customizer-at-a-glance ul {
					list-style:none;
					padding:0;
					margin:0;
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

new DC_Widget_AtAGlance();
