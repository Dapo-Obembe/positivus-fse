<?php
/**
 * Register work CPT.
 * 
 * @package AlphaWebConsult
 * @since 1.0.0
 */
if(!defined('ABSPATH')) exit;

/**
 * This function registers the Custom Work Post type.
 */
function works_post_types() {
	register_post_type(
		'work',
		array(
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'public'       => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-businessman',
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => 'work', 'with_front' => false ),
			'has_category' => true,
			'taxonomies'   => array( 'post_tag' ),
			'labels'       => array(
				'name'          => 'Works',
				'add_new_item'  => 'Add New Work',
				'edit_item'     => 'Edit Work',
				'all_items'     => 'All Works',
				'singular_name' => 'Work',
			),

		)
	);
}
add_action( 'init', 'works_post_types' );