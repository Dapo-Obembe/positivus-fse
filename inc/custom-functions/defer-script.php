<?php
/**
 * Defer jQuery/Scripts
 */
if(!defined('ABSPATH')) exit;

function defer_jquery_loading() {
    if (!is_admin()) {
        wp_scripts()->add_data('jquery', 'group', 1);
        wp_scripts()->add_data('jquery-core', 'group', 1);
        wp_scripts()->add_data('jquery-migrate', 'group', 1);
    }
}
add_action('wp_enqueue_scripts', 'defer_jquery_loading', 999);