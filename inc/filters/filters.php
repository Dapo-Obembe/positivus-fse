<?php
/**
 * Filters
 *
 * @package AlphaWebConsult
 * 
 * @author Dapo Obembe <https://https://dapoobembe.com>
 * @since 1.0.0
 */
if(!defined('ABSPATH')) exit;

/**
 * Show '(No title)' if a post has no title.
 *
 * @since 1.0.0
 */
add_filter(
	'the_title',
	function( $title ) {
		if ( ! is_admin() && empty( $title ) ) {
			$title = _x( '(No title)', 'Used if posts or pages has no title', 'positivus' );
		}

		return $title;
	}
);

/**
 * Replace the default [...] excerpt more with an elipsis.
 *
 * @since 1.0.0
*/
add_filter(
	'excerpt_more',
	function( $more ) {
		return '&hellip;';
	}
);


/**
 * Modify the comment form cookies message.
 * Works for the Block Editor.
 * 
 * @return $block_content Return the modified cookies message.
 */
function modify_comment_form_block($block_content, $block) {
    // Check if this is the Post Comments Form block
    if ($block['blockName'] === 'core/post-comments-form') {
        // Modify the cookies message directly in the rendered block content
        $block_content = str_replace(
            '<p class="comment-form-cookies-consent">',
            '<p class="comment-form-cookies-consent has-small-font-size" style="display:flex;align-items:center;">',
            $block_content
        );
        $block_content = str_replace(
            'Save my name, email, and website in this browser for the next time I comment.',
            'Save my name and email for future comments on this site.',
            $block_content
        );
    }

    return $block_content;
}
add_filter('render_block', 'modify_comment_form_block', 10, 2);

/**
 * Remove the comment form website URL field.
 */
function remove_comment_form_website_field($fields) {
    if (isset($fields['url'])) {
        unset($fields['url']);
    }
    return $fields;
}
add_filter('comment_form_default_fields', 'remove_comment_form_website_field');

/**
 * Dynamically inject the current page title (slugified) as a class into the <body> tag only for pages that are not:
 * home, front_page, single, archive (custom post type archive).
 * 
 * PURSPOSE: Ability to do e.g body.page-login{} and the styles applied only when the body has that class
 */
function awc_add_prefixed_page_slug_body_class($classes) {
    // Skip home, front page, single posts, and archives
    if (is_front_page() || is_home() || is_post_type_archive()) {
        return $classes;
    }

    if (is_page()) {
        global $post;

        if ($post) {
            $slug = sanitize_title($post->post_name);
            if (!empty($slug)) {
                $classes[] = 'page-' . $slug;
            }
        }
    }

    return $classes;
}
add_filter('body_class', 'awc_add_prefixed_page_slug_body_class');
