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

if (file_exists(__DIR__ . '/vendor/autoload.php') ) {
    include __DIR__ . '/vendor/autoload.php';
}

add_action( 'admin_menu', SettingsMenu::add_menu_entry(...) );