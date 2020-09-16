<?php

class DC_Widget_Recent_Comments extends DashboardCustomizerEl {

 //   var $js_added = false;

	function name() {
		return __('Recent Comments');
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

		$total_items = 5;


		// Select all comment types and filter out spam later for better query performance.
		$comments = array();

		$comments_query = array(
			'number' => $total_items * 5,
			'offset' => 0,
		);
		if ( ! current_user_can( 'edit_posts' ) ) {
			$comments_query['status'] = 'approve';
		}

		while ( count( $comments ) < $total_items && $possible = get_comments( $comments_query ) ) {
			if ( ! is_array( $possible ) ) {
				break;
			}
			foreach ( $possible as $comment ) {
				if ( ! current_user_can( 'read_post', $comment->comment_post_ID ) ) {
					continue;
				}
				$comments[] = $comment;
				if ( count( $comments ) == $total_items ) {
					break 2;
				}
			}
			$comments_query['offset'] += $comments_query['number'];
			$comments_query['number']  = $total_items * 10;
		}

		if ( $comments ) {
			echo '<div id="latest-comments" class="activity-block">';
			echo '<h3>' . __( 'Recent Comments' ) . '</h3>';

			echo '<ul id="the-comment-list" data-wp-lists="list:comment">';
			foreach ( $comments as $comment ) {
				_wp_dashboard_recent_comments_row( $comment );
			}
			echo '</ul>';

			if ( current_user_can( 'edit_posts' ) ) {
				echo '<h3 class="screen-reader-text">' . __( 'View more comments' ) . '</h3>';
				_get_list_table( 'WP_Comments_List_Table' )->views();
			}

			wp_comment_reply( -1, false, 'dashboard', false );
			wp_comment_trashnotice();

			echo '</div>';
		} else {
			return false;
		}
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

new DC_Widget_Recent_Comments();
