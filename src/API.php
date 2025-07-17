<?php

namespace Rumble\WPPD;

final class API {

	public static function send_registration_request( string $collector_url, string $environment ): array {
		$response = wp_remote_post(
			$collector_url . '/site/register',
			array(
				'method'  => 'POST',
				'headers' => array(
					'Content-Type' => 'application/json',
				),
				'body'    => json_encode(
					array(
						'name'        => get_bloginfo( 'name' ),
						'url'         => get_site_url(),
						'environment' => $environment,
					)
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return array(
				'message' => $response->get_error_message(),
				'data'    => null,
			);
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! isset( $body['message'] ) || ! isset( $body['data'] ) || ! isset( $body['data']['id'] ) || ! isset( $body['data']['token'] ) ) {
			return array(
				'message' => 'Invalid response from collector service',
				'data'    => null,
			);
		}

		return $body;
	}

	public static function send_update_request( string $collector_url, int $id, string $token ): bool {
		$response = wp_remote_post(
			$collector_url . '/site/' . $id . '/update',
			array(
				'method'  => 'PUT',
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $token,
				),
				'body'    => json_encode(
					array(
						'name'       => get_bloginfo( 'name' ),
						'url'        => get_site_url(),
						'phpVersion' => phpversion(),
						'wpVersion'  => get_bloginfo( 'version' ),
						'plugins'    => self::get_all_plugins(),
					)
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return false;
		}

		if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
			return false;
		}

		return true;
	}

	private static function get_all_plugins(): array {
		$allPlugins = array_merge( get_plugins(), get_mu_plugins() );

		return self::map_plugins( $allPlugins );
	}

	private static function map_plugins( array $plugins ): array {
		$mappedPlugins = array();

		foreach ( $plugins as $file => $plugin ) {
			$mappedPlugins[] = array(
				'file'    => $file,
				'name'    => ! empty( $plugin['Name'] ) ? $plugin['Name'] : null,
				'active'  => is_plugin_active( $file ),
				'version' => array(
					'installedVersion'   => ! empty( $plugin['Version'] ) ? $plugin['Version'] : null,
					'requiredPhpVersion' => ! empty( $plugin['RequiresPHP'] ) ? $plugin['RequiresPHP'] : null,
					'requiredWpVersion'  => ! empty( $plugin['RequiresWP'] ) ? $plugin['RequiresWP'] : null,
				),
			);
		}

		return $mappedPlugins;
	}
}
