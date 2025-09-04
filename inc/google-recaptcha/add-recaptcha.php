<?php
/**
 * Add the appropriate reCAPTCHA widget or token to the comment form.
 */
if(!defined('ABSPATH')) exit;
/**
 * Adds recaptcha checkbox in the post comment form.
 */
function add_recaptcha_to_comment_form_block() {
    // Get the version selected.
    $options = get_option('recaptcha_settings');
    $selected_version = $options['recaptcha_version'] ?? 'v2'; // Default to v2 if not set

    $recaptcha_v2_site_key = get_recaptcha_v2_site_key();
    $recaptcha_v3_site_key = get_recaptcha_v3_site_key();

    // Recaptcha v2 is selected.
    if ($selected_version === 'v2') {
        // Check if the v2 site key exists
        if (!empty($recaptcha_v2_site_key)) {
            echo '<div class="g-recaptcha" data-sitekey="' . esc_attr($recaptcha_v2_site_key) . '"></div>';
        } else {
            echo '<p class="recaptcha-error has-small-font-size">reCAPTCHA keys not configured. Comment won\'t be submitted. Sorry!</p>';
        }
    // if recaptcha v3 is selected.
    } elseif( 'v3' === $selected_version ) {
        // Check if the v3 site key exists.
        if (!empty($recaptcha_v3_site_key)) {
            // Output the reCAPTCHA v3 script and a hidden input to hold the token.
            echo '<script src="https://www.google.com/recaptcha/api.js?render=' . esc_attr($recaptcha_v3_site_key) . '"></script>';
            echo '<script>
                grecaptcha.ready(function() {
                    grecaptcha.execute("' . esc_attr($recaptcha_v3_site_key) . '", { action: "comment_form" }).then(function(token) {
                        document.getElementById("g-recaptcha-token").value = token;
                    });
                });
            </script>';
            echo '<input type="hidden" id="g-recaptcha-token" name="g-recaptcha-token" value="">';
        } else {
            echo '<p class="recaptcha-error has-small-font-size">reCAPTCHA keys is not configured. Comment won\'t be submitted. Sorry!</p>';
        }
    }

}
add_action('comment_form_after_fields', 'add_recaptcha_to_comment_form_block');

