<?php

namespace Rumble\WPPD\Settings;

use Rumble\WPPD\ActionHandlers;

final class SettingsMenu {
	public const PAGE_SLUG = 'rumble-wppd-settings';

	public static function init() {
		add_action( 'admin_menu', self::add_menu_entry( ... ) );
		add_action( 'admin_init', SettingsPage::register_settings( ... ) );
		add_action( 'admin_post_' . SettingsPage::ACTION_COLLECTOR_REGISTER, ActionHandlers::register( ... ) );
		add_action( 'admin_post_' . SettingsPage::ACTION_COLLECTOR_UPDATE, ActionHandlers::update( ... ) );
		add_action( 'admin_post_' . SettingsPage::ACTION_COLLECTOR_CLEAR, ActionHandlers::clear( ... ) );
	}

	private static function add_menu_entry(): void {
		add_menu_page(
			'Rumble WPPD',
			'Rumble WPPD',
			'manage_options',
			self::PAGE_SLUG,
			SettingsPage::render( ... )
		);
	}
}
