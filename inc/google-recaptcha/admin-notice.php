<?php
/**
 * Display admin notices if reCAPTCHA keys are missing based on the selected version.
 */
if(!defined('ABSPATH')) exit;

add_action('admin_notices', function () {
    $options = get_option('recaptcha_settings');
    $selected_version   = $options['recaptcha_version'] ?? 'v2'; // Default to v2 if not set

    $missing_keys       = [];
    $keys_to_check      = [];

    // Define required keys based on the selected version
    if ($selected_version === 'v2') {
        $keys_to_check = [
            'v2 Site Key' => get_recaptcha_v2_site_key(),
            'v2 Secret Key' => get_recaptcha_v2_secret_key(),
        ];
    } elseif ($selected_version === 'v3') {
        $keys_to_check = [
            'v3 Site Key' => get_recaptcha_v3_site_key(),
            'v3 Secret Key' => get_recaptcha_v3_secret_key(),
        ];
    }

    // Check for missing keys
    foreach ($keys_to_check as $key_name => $key_value) {
        if (empty($key_value)) {
            $missing_keys[] = $key_name;
        }
    }

    // Display an error notice if any keys are missing
    if (!empty($missing_keys)) {
        $missing_keys_list = implode(', ', $missing_keys);
        echo '<div class="notice notice-error is-dismissible">
            <p>' . sprintf(
                __('Warning: The following reCAPTCHA keys are missing for %s: %s. Please configure them in Theme Settings.', 'refined-health-hub'),
                esc_html(strtoupper($selected_version)),
                esc_html($missing_keys_list)
            ) . '</p>
        </div>';
    }
});