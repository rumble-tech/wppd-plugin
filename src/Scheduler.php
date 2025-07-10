<?php

namespace Rumble\WPPD;

use Rumble\WPPD\Settings\SettingsPage;

final class Scheduler {

	private const CRON_HOOK_NAME = 'rumble-wppd-scheduler';

	public static function init(): void {
		add_filter( 'cron_schedules', self::add_custom_cron_schedule( ... ) );

		if ( ! wp_next_scheduled( self::CRON_HOOK_NAME ) ) {
			wp_schedule_event( time(), 'every_three_hours', self::CRON_HOOK_NAME );
		}

		add_action( self::CRON_HOOK_NAME, self::execute_job( ... ) );
	}

	public static function execute_job(): void {
		$collector_url   = get_option( SettingsPage::OPTION_COLLECTOR_URL );
		$collector_id    = get_option( SettingsPage::OPTION_COLLECTOR_ID );
		$collector_token = get_option( SettingsPage::OPTION_COLLECTOR_TOKEN );

		$response = API::send_update_request( $collector_url, $collector_id, $collector_token );

		if ( $response === false ) {
			delete_option( SettingsPage::OPTION_COLLECTOR_URL );
			delete_option( SettingsPage::OPTION_COLLECTOR_ENVIRONMENT );
			delete_option( SettingsPage::OPTION_COLLECTOR_ID );
			delete_option( SettingsPage::OPTION_COLLECTOR_TOKEN );
		}
	}

	public static function deactivate(): void {
		$timestamp = wp_next_scheduled( self::CRON_HOOK_NAME );

		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, self::CRON_HOOK_NAME );
		}
	}


	private static function add_custom_cron_schedule( array $schedules ): array {
		if ( ! isset( $schedules['every_three_hours'] ) ) {
			$schedules['every_three_hours'] = array(
				'interval' => 60 * 60 * 3,
				'display'  => __( 'Every three hours' ),
			);
		}

		return $schedules;
	}
}
