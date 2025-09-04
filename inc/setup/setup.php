<?php
/**
 * Theme setup files.
 *
 * @package AlphaWebConsult
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Adds theme support for post formats.
if ( ! function_exists( 'awc_tw_plate_setup' ) ) :

	/**
	* Add theme support for title tag
	*/
	add_theme_support( 'title-tag' );
	/**
	 * Adds theme support for post formats.
	 *
	 * @since AWC TW Plate 1.0
	 *
	 * @return void
	 */
	function awc_tw_plate_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}

	/**
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// Register navigation menus.
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'positivus' ),
			'footer'  => esc_html__( 'Footer Menu', 'positivus' ),
		)
	);

	/**
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);

	// Custom logo support.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 500,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array( 'site-title', 'site-description' ),
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Gutenberg responsive embed support.
	add_theme_support( 'responsive-embeds' );

	// Remove core block patterns.
	remove_theme_support( 'core-block-patterns' );


endif;
add_action( 'after_setup_theme', 'awc_tw_plate_setup' );

add_filter( 'should_load_remote_block_patterns', '__return_false' );

// Register pattern categories.
if( ! function_exists( 'psv_pattern_categories' ) ) {
	function psv_pattern_categories(){
			register_block_pattern_category( 
				'hero', 
				array( 
					'label' => __( 'Hero', 'positivus-fse' ),
					'description' => __( 'Hero sections with text and image', 'positivus-fse' )
				) 
			);
	}
	add_action( 'after_setup_theme', 'psv_pattern_categories' );
}

// Registering Block Styles.
if( ! function_exists( 'psv_register_block_styles' ) ) {
	function psv_register_block_styles() {
		// 1. Register the stlysheet for block styles.
		wp_register_style('psv-button-styles', get_template_directory_uri() . '/assets/css/button-block-styles.css', array(), filemtime( get_template_directory() . '/assets/css/button-block-styles.css' ) );

		// 2. Register a new block style for the core/button block.
		register_block_style(
			'core/button',
			array(
				'name'         => 'psv-primary',
				'label'        => __( 'Primary', 'positivus-fse' ),
				'is_default'   => false,
				'style_handle' => 'psv-button-styles',
			)
		);
	}
	add_action( 'init', 'psv_register_block_styles' );
}
