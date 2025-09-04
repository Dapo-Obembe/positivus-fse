<?php
/**
 * Hook into the admin menu to add a custom settings page.
 */
if(!defined('ABSPATH')) exit;
add_action('admin_menu', function () {
    add_menu_page(
        'Recaptcha Settings',
        'Recaptcha Settings',
        'manage_options',
        'recaptcha-settings',
        'render_recaptcha_settings_page',
        'dashicons-admin-generic',
        110
    );
});

/**
 * Render the settings page.
 */
function render_recaptcha_settings_page() {
    ?>
    <div class="wrap">
        <h1>Recaptcha Settings</h1>
        <p>Configure reCaptcha to prevent spam comments</p>
        <hr />
        <form method="post" action="options.php">
            <?php
            // Display settings sections and fields.
            settings_fields('recaptcha_settings_group');
            do_settings_sections('recaptcha-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Hook into admin_init to register settings and fields.
 */
add_action('admin_init', function () {
    // Register settings group
    register_setting('recaptcha_settings_group', 'recaptcha_settings', 'sanitize_recaptcha_settings');

    // Add a settings section for reCAPTCHA
    add_settings_section(
        'recaptcha_settings_section',
        'reCAPTCHA Settings',
        'recaptcha_settings_section_callback',
        'recaptcha-settings'
    );

    // Add a setting to choose between reCAPTCHA v2 and v3
    add_settings_field(
        'recaptcha_version',
        'Select reCAPTCHA Version',
        'render_recaptcha_version_field',
        'recaptcha-settings',
        'recaptcha_settings_section'
    );

    // Add fields for reCAPTCHA v2 keys
    add_settings_field(
        'recaptcha_v2_site_key',
        'reCAPTCHA v2 Site Key',
        'render_text_field',
        'recaptcha-settings',
        'recaptcha_settings_section',
        [
            'label_for' => 'recaptcha_v2_site_key',
            'field_key' => 'recaptcha_v2_site_key',
            'description' => 'Enter your site key for reCAPTCHA v2.',
            'class' => 'recaptcha-v2-fields'
        ]
    );

    add_settings_field(
        'recaptcha_v2_secret_key',
        'reCAPTCHA v2 Secret Key',
        'render_text_field',
        'recaptcha-settings',
        'recaptcha_settings_section',
        [
            'label_for' => 'recaptcha_v2_secret_key',
            'field_key' => 'recaptcha_v2_secret_key',
            'description' => 'Enter your secret key for reCAPTCHA v2.',
            'class' => 'recaptcha-v2-fields'
        ]
    );

    // Add fields for reCAPTCHA v3 keys
    add_settings_field(
        'recaptcha_v3_site_key',
        'reCAPTCHA v3 Site Key',
        'render_text_field',
        'recaptcha-settings',
        'recaptcha_settings_section',
        [
            'label_for' => 'recaptcha_v3_site_key',
            'field_key' => 'recaptcha_v3_site_key',
            'description' => 'Enter your site key for reCAPTCHA v3.',
            'class' => 'recaptcha-v3-fields'
        ]
    );

    add_settings_field(
        'recaptcha_v3_secret_key',
        'reCAPTCHA v3 Secret Key',
        'render_text_field',
        'recaptcha-settings',
        'recaptcha_settings_section',
        [
            'label_for' => 'recaptcha_v3_secret_key',
            'field_key' => 'recaptcha_v3_secret_key',
            'description' => 'Enter your secret key for reCAPTCHA v3.',
            'class' => 'recaptcha-v3-fields'
        ]
    );
});

/**
 * Render the reCAPTCHA version selection field.
 */
function render_recaptcha_version_field() {
    $options = get_option('recaptcha_settings');
    $selected_version = $options['recaptcha_version'] ?? 'v2';
    ?>
    <select id="recaptcha_version" name="recaptcha_settings[recaptcha_version]">
        <option value="v2" <?php selected($selected_version, 'v2'); ?>>reCAPTCHA v2</option>
        <option value="v3" <?php selected($selected_version, 'v3'); ?>>reCAPTCHA v3</option>
    </select>
    <p class="description">Select which version of reCAPTCHA you want to use on your site.</p>
    <?php
}

/**
 * Section callback for reCAPTCHA settings.
 */
function recaptcha_settings_section_callback() {
    echo '<p>Provide the reCAPTCHA keys to enable spam protection on your site. Select v2 or V3 as you deem fit.</p>';
}

/**
 * Render a text field.
 */
function render_text_field($args) {
    $options = get_option('recaptcha_settings');
    $field_value = $options[$args['field_key']] ?? '';
    ?>
    <input type="text" id="<?php echo esc_attr($args['label_for']); ?>" 
           name="recaptcha_settings[<?php echo esc_attr($args['field_key']); ?>]" 
           value="<?php echo esc_attr($field_value); ?>" 
           class="regular-text <?php echo esc_attr($args['class']); ?>">
    <p class="description"><?php echo esc_html($args['description']); ?></p>
    <?php
}

/**
 * Sanitize the settings input.
 */
function sanitize_recaptcha_settings($input) {
    return [
        'recaptcha_version' => sanitize_text_field($input['recaptcha_version'] ?? 'v2'),
        'recaptcha_v2_site_key' => sanitize_text_field($input['recaptcha_v2_site_key'] ?? ''),
        'recaptcha_v2_secret_key' => sanitize_text_field($input['recaptcha_v2_secret_key'] ?? ''),
        'recaptcha_v3_site_key' => sanitize_text_field($input['recaptcha_v3_site_key'] ?? ''),
        'recaptcha_v3_secret_key' => sanitize_text_field($input['recaptcha_v3_secret_key'] ?? ''),
    ];
}

/**
 * Add admin notices for settings save success.
 */
add_action('admin_notices', function () {
    if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
        echo '<div class="notice notice-success is-dismissible">
            <p>' . __('Settings saved successfully!', 'refined-health-hub') . '</p>
        </div>';
    }
});
