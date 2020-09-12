<div class="wrap">

	<?php require_once 'tabs.php'; ?>
	
	<h1><?php _e('Plugin License Options'); ?></h1>

	<form method="post" action="options.php">
		<?php

		settings_fields( $this->prefix . '_license' );

		?>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" valign="top">
						<label class="description" for="<?php echo $this->prefix; ?>_license_key"><?php _e('License Key'); ?></label>
					</th>
					<td>
						<input id="<?php echo $this->prefix; ?>_license_key"
							name="<?php echo $this->prefix; ?>_license_key"
							type="<?php echo $input_type; ?>"
							class="regular-text"
							value="<?php esc_attr_e( $license ); ?>"
							placeholder="<?php _e('Enter your license key'); ?>" />
					</td>
				</tr>
				<?php

				//if ( $license != false ) :

					?>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php //_e('Activate License'); ?>
						</th>

						<td>
							<?php
							wp_nonce_field( $this->prefix . '_nonce', $this->prefix . '_nonce' );

							if ( $this->prefix == 'eedc' ) :
								if ( $status !== false && $status == 'valid' ) :

									?>
									<span style="color:green;"><?php _e('active'); ?></span>

									<input type="submit"
										class="button-secondary"
										name="<?php echo $this->prefix; ?>_license_deactivate"
										value="<?php _e('Deactivate License'); ?>"/>
									<?php

								else :

									?>
									<input type="submit"
										class="button-primary"
										name="<?php echo $this->prefix; ?>_license_activate"
										value="<?php _e('Activate License'); ?>"/>
									<?php

								endif;
							endif;

							?>
						</td>
					</tr>
					<?php

				//endif;

				?>
			</tbody>
		</table>
		<?php

		//submit_button();

		?>
	</form>
</div>
