<?php

class DC_Widget_Site_Health_Status extends DashboardCustomizerEl {

 //   var $js_added = false;

	function name() {
		return __('Site Health Status');
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
		$get_issues = get_transient( 'health-check-site-status-result' );

		$issue_counts = array();

		if ( false !== $get_issues ) {
			$issue_counts = json_decode( $get_issues, true );
		}

		if ( ! is_array( $issue_counts ) || ! $issue_counts ) {
			$issue_counts = array(
				'good'        => 0,
				'recommended' => 0,
				'critical'    => 0,
			);
		}

		$issues_total = $issue_counts['recommended'] + $issue_counts['critical'];
		?>
		<div class="health-check-title-section site-health-progress-wrapper loading hide-if-no-js">
			<div class="site-health-progress">
				<svg role="img" aria-hidden="true" focusable="false" width="100%" height="100%" viewBox="0 0 200 200" version="1.1" xmlns="http://www.w3.org/2000/svg">
					<circle r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
					<circle id="bar" r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
				</svg>
			</div>
			<div class="site-health-progress-label">
				<?php if ( false === $get_issues ) : ?>
					<?php _e( 'No information yet&hellip;' ); ?>
				<?php else : ?>
					<?php _e( 'Results are still loading&hellip;' ); ?>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( false === $get_issues ) : ?>
			<p>
				<?php
				printf(
					/* translators: %s: URL to Site Health screen. */
					__( 'Site health checks will automatically run periodically to gather information about your site. You can also <a href="%s">visit the Site Health screen</a> to gather information about your site now.' ),
					esc_url( admin_url( 'site-health.php' ) )
				);
				?>
			</p>
		<?php else : ?>
			<p>
				<?php if ( $issue_counts['critical'] > 0 ) : ?>
					<?php _e( 'Your site has critical issues that should be addressed as soon as possible to improve its performance and security.' ); ?>
				<?php elseif ( $issues_total <= 0 ) : ?>
					<?php _e( 'Great job! Your site currently passes all site health checks.' ); ?>
				<?php else : ?>
					<?php _e( 'Your site&#8217;s health is looking good, but there are still some things you can do to improve its performance and security.' ); ?>
				<?php endif; ?>
			</p>
		<?php endif; ?>

		<?php if ( $issues_total > 0 && false !== $get_issues ) : ?>
			<p>
				<?php
				printf(
					/* translators: 1: Number of issues. 2: URL to Site Health screen. */
					_n(
						'Take a look at the <strong>%1$d item</strong> on the <a href="%2$s">Site Health screen</a>.',
						'Take a look at the <strong>%1$d items</strong> on the <a href="%2$s">Site Health screen</a>.',
						$issues_total
					),
					$issues_total,
					esc_url( admin_url( 'site-health.php' ) )
				);
				?>
			</p>
		<?php endif; ?>

		<?php
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

new DC_Widget_Site_Health_Status();
