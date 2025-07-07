<?php

namespace Rumble\WPPD\Settings;

final class SettingsMenu {
    public const OPTION_PAGE = 'rumble-wppd-settings';
    public const OPTION_GROUP = 'rumble-wppd-settings-options';

    public static function add_menu_entry(): void {
        add_menu_page(
            'rumble',
            'WP Plugin Dashboard',
            'manage_options',
            self::OPTION_PAGE,
            self::render_menu_page(...)
        );
    }

    public static function render_menu_page(): void {
        ?>
        <div class="wrap">
            <h1>Rumble WPPD</h1>
    </div>
    <?php
    }
}