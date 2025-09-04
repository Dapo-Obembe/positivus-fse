<?php
/**
 * Validate Google reCAPTCHA on the comment form submission.
 * 
 * @package alpha-web-boilerplate
 * @return $commentdata Loads the comment form data.
 * 
 * @since 1.0.0
 */
if(!defined('ABSPATH')) exit;
function validate_recaptcha_on_comment_submission($commentdata) {
    
    // Skip validation for admins
    if (is_admin() || current_user_can('manage_options')) {
        return $commentdata;
    }

    $options = get_option('recaptcha_settings');
    $selected_version = $options['recaptcha_version'] ?? 'v2'; // Default to v2 if not set

    // Get the referring post URL from the HTTP referer
    $referrer_url = isset($_SERVER['HTTP_REFERER']) ? esc_url($_SERVER['HTTP_REFERER']) : home_url();

    // Validate based on the selected version
    if ($selected_version === 'v2') {
        // reCAPTCHA v2 Validation
        $recaptcha_v2_secret_key = get_recaptcha_v2_secret_key(); // Retrieve the reCaptcha secret key.
        $recaptcha_response = isset($_POST['g-recaptcha-response']) ? sanitize_text_field($_POST['g-recaptcha-response']) : '';

        $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret' => $recaptcha_v2_secret_key,
                'response' => $recaptcha_response,
                'remoteip' => $_SERVER['REMOTE_ADDR'],
            ],
        ]);

        $response_body = wp_remote_retrieve_body($response);
        $result = json_decode($response_body, true);

       if (empty($result['success'])) {
            // Get the referring post URL from the HTTP referer
            $referrer_url = isset($_SERVER['HTTP_REFERER']) ? esc_url($_SERVER['HTTP_REFERER']) : home_url();

            // Construct the error message
            $error_message = sprintf(
                __(
                    'Error: Please complete the reCAPTCHA to submit the comment. <a href="%s">Go back to the post</a>.'
                ),
                $referrer_url
            );

            // Display the error with wp_die()
            wp_die($error_message, 'reCAPTCHA Error', ['back_link' => false]);
        }

    } elseif ($selected_version === 'v3') {
        // reCAPTCHA v3 Validation
        $recaptcha_v3_secret_key = get_recaptcha_v3_secret_key();
        $token = $_POST['g-recaptcha-token'] ?? '';
        $remote_ip = $_SERVER['REMOTE_ADDR'];

        if (!empty($recaptcha_v3_secret_key) && !empty($token)) {
            $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
                'body' => [
                    'secret' => $recaptcha_v3_secret_key,
                    'response' => $token,
                    'remoteip' => $remote_ip
                ]
            ]);

            if (is_wp_error($response)) {
                // Construct the error message
                $error_message = sprintf(
                    __(
                        'Error: reCAPTCHA verification failed. <a href="%s">Try Again</a>.'
                    ),
                    $referrer_url
                );
                // Display the error with wp_die()
                wp_die($error_message, 'reCAPTCHA Error', ['back_link' => false]);
            }

            $response_body = json_decode(wp_remote_retrieve_body($response), true);

            if (empty($response_body['success']) || $response_body['score'] < 0.5) {

                // Construct the error message
                $error_message = sprintf(
                    __(
                        'Error: Your comment seems to be spam. <a href="%s">Try Again</a>.'
                    ),
                    $referrer_url
                );
                // Display the error with wp_die()
                wp_die($error_message, 'reCAPTCHA Error', ['back_link' => false]);
            }
        } else {
            // Construct the error message
            $error_message = sprintf(
                __(
                    'Error: reCAPTCHA is not configured properly. Your comment won\'t be submitted at this time. <a href="%s">Close this page</a>.'
                ),
                $referrer_url
            );
            // Display the error with wp_die()
            wp_die($error_message, 'reCAPTCHA Error', ['back_link' => false]);
        }
    }

    return $commentdata;
}
add_filter('preprocess_comment', 'validate_recaptcha_on_comment_submission');