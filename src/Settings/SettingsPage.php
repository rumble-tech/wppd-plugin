<?php

namespace Rumble\WPPD\Settings;

final class SettingsPage {

	public const OPTION_GROUP                 = 'rumble-wppd-options';
	public const OPTION_COLLECTOR_URL         = 'rumble-wppd-collector-url';
	public const OPTION_COLLECTOR_ENVIRONMENT = 'rumble-wppd-collector-environment';
	public const OPTION_COLLECTOR_ID          = 'rumble-wppd-collector-id';
	public const OPTION_COLLECTOR_TOKEN       = 'rumble-wppd-collector-token';

	public const ACTION_COLLECTOR_REGISTER = 'rumble-wppd-collector-register';
	public const ACTION_COLLECTOR_UPDATE   = 'rumble-wppd-collector-update';
	public const ACTION_COLLECTOR_CLEAR    = 'rumble-wppd-collector-clear';

	public const TRANSIENT_COLLECTOR_RESPONSE = 'rumble-wppd-collector-response';

	public static function register_settings(): void {
		register_setting(
			self::OPTION_GROUP,
			self::OPTION_COLLECTOR_URL,
			array(
				'type'              => 'string',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		register_setting(
			self::OPTION_GROUP,
			self::OPTION_COLLECTOR_ENVIRONMENT,
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => 'production',
			)
		);
		register_setting( self::OPTION_GROUP, self::OPTION_COLLECTOR_ID, array( 'type' => 'int' ) );
		register_setting( self::OPTION_GROUP, self::OPTION_COLLECTOR_TOKEN, array( 'type' => 'string' ) );
	}

	public static function render(): void {
		$collector_url         = get_option( self::OPTION_COLLECTOR_URL );
		$collector_environment = get_option( self::OPTION_COLLECTOR_ENVIRONMENT );
		$collector_id          = get_option( self::OPTION_COLLECTOR_ID );
		$collector_token       = get_option( self::OPTION_COLLECTOR_TOKEN );

		$is_registered = ! empty( $collector_url ) && ! empty( $collector_id ) && ! empty( $collector_token );

		$collector_response = get_transient( self::TRANSIENT_COLLECTOR_RESPONSE );

		?>
		<div class="wrap">
			<h1>Rumble WPPD Settings</h1>
			<hr>
			<?php
			if ( ! empty( $collector_response ) ) {
				echo '<div class="notice notice-' . esc_attr( $collector_response['severity'] ) . '"><p>' . esc_html( $collector_response['message'] ) . '</p></div>';
				delete_transient( self::TRANSIENT_COLLECTOR_RESPONSE );
			}
			?>

			<div class="notice notice-<?php echo ! $is_registered ? 'error' : 'success'; ?>">
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="<?php echo esc_attr( ! $is_registered ? self::ACTION_COLLECTOR_REGISTER : self::ACTION_COLLECTOR_CLEAR ); ?>">
					<table class="form-table" role="presentation">
						<tbody>
						<tr>
							<th scope="row">Collector Service URL</th>
							<td>
								<?php if ( $is_registered ) : ?>
									<input type="text" style="width: 15rem;" value="<?php echo esc_attr( $collector_url ); ?>" readonly>
								<?php else : ?>
									<input type="url" name="collector-url" style="width: 15rem;" required>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th scope="row">Site Environment</th>
							<td>
								<?php if ( $is_registered ) : ?>
									<input type="text" style="width: 15rem;" value="<?php echo esc_attr( $collector_environment ); ?>" readonly>
								<?php else : ?>
									<select name="collector-environment" style="width: 15rem;" required>
										<option value="production">Production</option>
										<option value="staging">Staging</option>
										<option value="development" >Development</option>
									</select>
								<?php endif; ?>
							</td>
						</tr>
						</tbody>
					</table>
					<?php submit_button( ! $is_registered ? 'Register' : 'Clear', 'primary' ); ?>
				</form>
			</div>
			<?php if ( $is_registered ) : ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="<?php echo esc_attr( self::ACTION_COLLECTOR_UPDATE ); ?>">
					<?php submit_button( 'Send update request now', 'secondary', '', '' ); ?>
				</form>
			<?php endif; ?>
		</div>
		<?php
	}
}