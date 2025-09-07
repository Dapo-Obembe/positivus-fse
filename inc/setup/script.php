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
 * Adds the `type="module"` attribute to script tags handled by this theme.
 *
 * @param string $tag    The <script> tag for the enqueued script.
 * @param string $handle The script's handle.
 * @return string The modified script tag.
 */

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
			echo '<script type="module" src="http://localhost:5006/@vite/client"></script>' . "\n"; // phpcs:ignore
			echo '<script type="module" src="http://localhost:5006/assets/js/main.js"></script>' . "\n"; // phpcs:ignore
		}, 99
	);
}
add_action( 'init', 'enqueue_assets_front_n_back' );

/**
 * Enqueues Vite assets for production.
 *
 * @return void
 */
function psv_fse_enqueue_vite_assets() {
	// Production Mode: Load assets from the manifest file.
	$manifest_path = get_theme_file_path( 'dist/manifest.json' );

	if ( file_exists( $manifest_path ) ) {
		$manifest = json_decode( file_get_contents( $manifest_path ), true );

		if ( ! empty( $manifest['assets/js/main.js'] ) ) {
			$main_asset = $manifest['assets/js/main.js'];

			// Enqueue main JS file.
			wp_enqueue_script(
				'main-js',
				get_template_directory_uri() . '/dist/' . $main_asset['file'],
				array(),
				THEME_VERSION,
				true
			);

			// Enqueue associated CSS files.
			if ( ! empty( $main_asset['css'] ) ) {
				foreach ( $main_asset['css'] as $css_file ) {
					wp_enqueue_style(
						'main-css-' . basename( $css_file ),
						get_template_directory_uri() . '/dist/' . $css_file,
						array(),
						THEME_VERSION
					);
				}
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'psv_fse_enqueue_vite_assets' );
add_action( 'admin_enqueue_scripts', 'psv_fse_enqueue_vite_assets' );

/**
 * Enqueues Block Variation Script.
 *
 * @since 1.0
 *
 * @return void
 */
function positivus_fse_block_variation_asset_loader() {
	// Enqueue theme main script file.
	wp_enqueue_script( 'block-variation-script', get_stylesheet_directory_uri() . '/assets/js/block-variation.js', array( 'wp-blocks', 'wp-dom-ready', 'wp-element' ), THEME_VERSION, true );
}
add_action( 'enqueue_block_editor_assets', 'positivus_fse_block_variation_asset_loader' );

