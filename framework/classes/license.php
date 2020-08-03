<?php

defined( 'ABSPATH' ) || exit;


/**
 * Dashboard Customizer License.
 *
 * @since 1.0.0
 */
class EditorEnhancer_DC_Admin_License {

	public function __construct($prefix, $name, $plugin_url, $website_url) {
		$this->prefix      = $prefix;
		$this->name        = $name;
		$this->plugin_url  = $plugin_url;
		$this->website_url = $website_url;

		add_action( 'admin_init', [ $this, 'registerLicenseOption' ] );
		add_action( 'admin_init', [ $this, 'activateLicense' ] );
		add_action( 'admin_init', [ $this, 'deactivateLicense' ] );
		add_action( 'admin_notices', [ $this, 'licenseNotices' ] );
	}


	/**
	 * Register license option.
	 *
	 * @since 1.0.0
	 */
	public function registerLicenseOption() {
		register_setting(
			$this->prefix . '_license',
			$this->prefix . '_license_key',
			[ $this, 'sanitizeLicense' ]
		);
	}


	/**
	 * Activate license key.
	 *
	 * @since 1.0.0
	 */
	public function activateLicense() {

		// listen for our activate button to be clicked
		if( isset( $_POST[$this->prefix . '_license_activate'] ) ) {

			// run a quick security check
		 	if( ! check_admin_referer( $this->prefix . '_nonce', $this->prefix . '_nonce' ) )
				return; // get out if we didn't click the Activate button

			if ( isset( $_POST[$this->prefix . '_license_key'] ) && $_POST[$this->prefix . '_license_key'] !== '' ) :
				update_option( $this->prefix . '_license_key', $_POST[$this->prefix . '_license_key'] );
			else :
				return;
			endif;


			// retrieve the license from the database
			$license = trim( get_option( $this->prefix . '_license_key' ) );


			// data to send in our API request
			$api_params = array(
				'edd_action' => 'activate_license',
				'license'    => $license,
				'item_name'  => urlencode( $this->name ), // the name of our product in EDD
				'url'        => $this->website_url
			);

			// Call the custom API.
			$response = wp_remote_post( $this->plugin_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}

			} else {

				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				if ( false === $license_data->success ) {

					switch( $license_data->error ) {

						case 'expired' :

							$message = sprintf(
								__( 'Your license key expired on %s.' ),
								date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
							);
							break;

						case 'disabled' :
						case 'revoked' :

							$message = __( 'Your license key has been disabled.' );
							break;

						case 'missing' :

							$message = __( 'Invalid license.' );
							break;

						case 'invalid' :
						case 'site_inactive' :

							$message = __( 'Your license is not active for this URL.' );
							break;

						case 'item_name_mismatch' :

							$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), $this->name );
							break;

						case 'no_activations_left':

							$message = __( 'Your license key has reached its activation limit.' );
							break;

						default :

							$message = __( 'An error occurred, please try again.' );
							break;
					}
				}
			}

			// Check if anything passed on a message constituting a failure
			if ( ! empty( $message ) ) {
				$base_url = admin_url( 'admin.php?page=' . $this->prefix . '_license' );
				$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

				wp_redirect( $redirect );
				exit();
			}

			// $license_data->license will be either "valid" or "invalid"

			update_option( $this->prefix . '_license_status', $license_data->license );
			// Done during defaults setting: update_option( $this->prefix . '_version', EDITOR_ENHANCER_VERSION );
			wp_redirect( admin_url( 'admin.php?page=' . $this->prefix . '_license' ) );
			exit();
		}
	}


	/**
	 * Deactivate license key.
	 *
	 * @since 1.0.0
	 */
	public function deactivateLicense() {

		// listen for our activate button to be clicked
		if( isset( $_POST[$this->prefix . '_license_deactivate'] ) ) {

			// run a quick security check
		 	if( ! check_admin_referer( $this->prefix . '_nonce', $this->prefix . '_nonce' ) )
				return; // get out if we didn't click the Activate button

			// retrieve the license from the database
			$license = trim( get_option( $this->prefix . '_license_key' ) );


			// data to send in our API request
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => $license,
				'item_name'  => urlencode( $this->name ), // the name of our product in EDD
				'url'        => $this->website_url
			);

			// Call the custom API.
			$response = wp_remote_post( $this->plugin_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}

				$base_url = admin_url( 'admin.php?page=' . $this->prefix . '_license' );
				$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

				wp_redirect( $redirect );
				exit();
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if( $license_data->license == 'deactivated' ) {
				delete_option( $this->prefix . '_license_status' );
				delete_option( $this->prefix . '_license_key' );
				delete_option( $this->prefix . '_version' );
			}

			wp_redirect( admin_url( 'admin.php?page=' . $this->prefix . '_license' ) );
			exit();

		}
	}


	/**
	 * Admin notices for license.
	 *
	 * @since 1.0.0
	 */
	public function licenseNotices() {
		if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {

			switch( $_GET['sl_activation'] ) {

				case 'false':
					$message = urldecode( $_GET['message'] );
					?>
					<div class="error">
						<p><?php echo $message; ?></p>
					</div>
					<?php
					break;

				case 'true':
				default:
					// Developers can put a custom success message here for when activation is successful if they way.
					break;
			}
		}
	}


	/**
	 * Sanitize license.
	 *
	 * @since 1.0.0
	 */
	public function sanitizeLicense( $new ) {
		$old = get_option( $this->prefix . '_license_key' );
		if( $old && $old != $new ) {
			delete_option( $this->prefix . '_license_status' ); // new license has been entered, so must reactivate
		}
		return $new;
	}
}// End of class
