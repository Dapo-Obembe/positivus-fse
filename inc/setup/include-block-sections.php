<?php
/**
 * Include block sections.
 * 
 * Useful for blocks that are used as a full page.
 * 
 * @package AlphaWebConsult
 */

 if( !defined( 'ABSPATH' ) ) {
    exit;
 }

/**
 * ACF BLOCKS global helper function
 */
function awc_include_block_section($block_name, $section_slug) {
    $path = get_theme_file_path("acf-blocks/{$block_name}/sections/{$section_slug}.php");
    if (file_exists($path)) {
        include $path;
    }
}