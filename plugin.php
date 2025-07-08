<?php
/**
 * Plugin Name: Rumble WPPD Plugin
 * Version: 1.0.0
 * Author: rumble GmbH & Co KG
 * Author URI: https://rumble.de
 */

namespace Rumble\WPPD;

use Rumble\WPPD\Settings\SettingsMenu;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	include __DIR__ . '/vendor/autoload.php';
}

if ( ! defined( 'DISABLE_WP_CRON' ) ) {
	define( 'DISABLE_WP_CRON', true );
}

add_action( 'plugins_loaded', SettingsMenu::init( ... ) );
add_action( 'init', Scheduler::init( ... ) );
register_deactivation_hook( __FILE__, array( Scheduler::class, 'deactivate' ) );
