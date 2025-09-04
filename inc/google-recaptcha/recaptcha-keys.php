<?php
/**
 * This function gets the Google reCaptcha
 * from the backend.
*/
if(!defined('ABSPATH')) exit;
/**
 * Get the reCAPTCHA v2 Site Key from theme settings.
 *
 * @return string|null The site key if set, or null if not configured.
 */
function get_recaptcha_v2_site_key() {
    $options = get_option('recaptcha_settings');

    return $options['recaptcha_v2_site_key'] ?? null;
}

/**
 * Get the reCAPTCHA v2 Secret Key from theme settings.
 *
 * @return string|null The secret key if set, or null if not configured.
 */
function get_recaptcha_v2_secret_key() {
    $options = get_option('recaptcha_settings');
    
    return $options['recaptcha_v2_secret_key'] ?? null;
}

/**
 * Get the reCAPTCHA v3 Site Key from theme settings.
 *
 * @return string|null The secret key if set, or null if not configured.
 */
function get_recaptcha_v3_site_key() {
    $options = get_option('recaptcha_settings');
    return $options['recaptcha_v3_site_key'] ?? null;
}
/**
 * Get the reCAPTCHA v3 Secret Key from theme settings.
 *
 * @return string|null The secret key if set, or null if not configured.
 */
function get_recaptcha_v3_secret_key() {
    $options = get_option('recaptcha_settings');
    return $options['recaptcha_v3_secret_key'] ?? null;
}

