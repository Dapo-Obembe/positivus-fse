<?php
/**
 * Theme scripts and styles declarations.
 *
 * @package AlphaWebConsult
 *
 * @author Dapo Obembe <https://www.dapoobembe.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Theme assets version.
define( 'THEME_VERSION', wp_get_theme()->get( 'Version' ) );

/**
 * This function enqueues the vite assets in the front and backend.
 *
 * @package AlphaWebConsult
 */
function enqueue_assets_front_n_back() {
	if ( wp_get_environment_type() !== 'local' ) {
		return;
	}

	// Echo the Vite client and entry script for both frontend and block editor.
	add_action(
		'wp_print_footer_scripts',
		function () {
			echo '<script type="module" src="http://localhost:3000/@vite/client"></script>' . "\n"; // phpcs:ignore
			echo '<script type="module" src="http://localhost:3000/assets/js/main.js"></script>' . "\n"; // phpcs:ignore
		},
		1
	);

	add_action(
		'admin_print_footer_scripts',
		function () {
        echo '<script type="module" src="http://localhost:3000/@vite/client"></script>' . "\n"; // phpcs:ignore
        echo '<script type="module" src="http://localhost:3000/assets/js/main.js"></script>' . "\n"; // phpcs:ignore
		},
		1
	);
}
add_action( 'init', 'enqueue_assets_front_n_back' );


if ( ! function_exists( 'positivus_asset_loader' ) ) :
	/**
	 * Enqueues style.css on the front.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	function positivus_asset_loader() {
		$vite_dev_server = 'http://localhost:3000';

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$response = wp_remote_get( $vite_dev_server . '/@vite/client', array( 'timeout' => 1 ) );
		}

		if ( wp_get_environment_type() !== 'local' ) {
			// Production Mode.
			$manifest_path = get_theme_file_path( 'dist/manifest.json' );
			if ( file_exists( $manifest_path ) ) {
				$manifest = json_decode( wp_remote_get( $manifest_path ), true );

				$main = $manifest['assets/js/main.js'];

				// Enqueue main CSS.
				if ( isset( $main['css'] ) ) {
					foreach ( $main['css'] as $css_file ) {
						wp_enqueue_style( 'main-css', get_template_directory_uri() . '/dist/' . $css_file, array(), THEME_VERSION );
					}
				}

				// Enqueue main JS.
				wp_enqueue_script(
					'main-js',
					get_template_directory_uri() . '/dist/' . $main['file'],
					array(),
					THEME_VERSION,
					true
				);
			}
		}

		// Enqueue font.
		wp_enqueue_style( 'space-grotesk-font', get_template_directory_uri() . '/assets/fonts/space-grotesk/stylesheet.css', array(), THEME_VERSION );
	}
	add_action( 'wp_enqueue_scripts', 'positivus_asset_loader' );


endif;
add_action( 'wp_enqueue_scripts', 'positivus_asset_loader' );
