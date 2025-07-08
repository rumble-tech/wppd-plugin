<?php

namespace Rumble\WPPD;

use Rumble\WPPD\Settings\SettingsMenu;
use Rumble\WPPD\Settings\SettingsPage;

final class ActionHandlers {

	public static function register(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$collector_url         = esc_url_raw( $_POST['collector-url'] );
		$collector_environment = sanitize_text_field( $_POST['collector-environment'] );

		$response = API::send_registration_request( $collector_url, $collector_environment );

		if ( $response['message'] !== 'Site registered successfully' && $response['message'] !== 'Site updated successfully' ) {
			set_transient(
				SettingsPage::TRANSIENT_COLLECTOR_RESPONSE,
				array(
					'severity' => 'error',
					'message'  => $response['message'],
				),
				10
			);
			wp_redirect( admin_url( 'admin.php?page=' . SettingsMenu::PAGE_SLUG ) );
			exit;
		}

		update_option( SettingsPage::OPTION_COLLECTOR_URL, $collector_url );
		update_option( SettingsPage::OPTION_COLLECTOR_ENVIRONMENT, $collector_environment );
		update_option( SettingsPage::OPTION_COLLECTOR_ID, $response['data']['id'] );
		update_option( SettingsPage::OPTION_COLLECTOR_TOKEN, $response['data']['token'] );

		set_transient(
			SettingsPage::TRANSIENT_COLLECTOR_RESPONSE,
			array(
				'severity' => 'success',
				'message'  => $response['message'],
			),
			10
		);

		wp_redirect( admin_url( 'admin.php?page=' . SettingsMenu::PAGE_SLUG ) );
		exit;
	}

	public static function update(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$collector_url   = get_option( SettingsPage::OPTION_COLLECTOR_URL );
		$collector_id    = get_option( SettingsPage::OPTION_COLLECTOR_ID );
		$collector_token = get_option( SettingsPage::OPTION_COLLECTOR_TOKEN );

		$response = API::send_update_request( $collector_url, $collector_id, $collector_token );
		if ( ! $response ) {
			set_transient(
				SettingsPage::TRANSIENT_COLLECTOR_RESPONSE,
				array(
					'severity' => 'error',
					'message'  => 'Failed to update site information',
				),
				10
			);
			wp_redirect( admin_url( 'admin.php?page=' . SettingsMenu::PAGE_SLUG ) );
			exit;
		}

		set_transient(
			SettingsPage::TRANSIENT_COLLECTOR_RESPONSE,
			array(
				'severity' => 'success',
				'message'  => 'Site information updated successfully',
			),
			10
		);

		wp_redirect( admin_url( 'admin.php?page=' . SettingsMenu::PAGE_SLUG ) );
		exit;
	}

	public static function clear(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		delete_option( SettingsPage::OPTION_COLLECTOR_URL );
		delete_option( SettingsPage::OPTION_COLLECTOR_ENVIRONMENT );
		delete_option( SettingsPage::OPTION_COLLECTOR_ID );
		delete_option( SettingsPage::OPTION_COLLECTOR_TOKEN );

		set_transient(
			SettingsPage::TRANSIENT_COLLECTOR_RESPONSE,
			array(
				'severity' => 'success',
				'message'  => 'Collector service settings cleared successfully',
			),
			10
		);

		wp_redirect( admin_url( 'admin.php?page=' . SettingsMenu::PAGE_SLUG ) );
		exit;
	}
}
