<?php

/**
 * Class WP_Favorite_Posts_Ajax
 *
 * This class handles the AJAX actions for the WP Favorite Posts plugin.
 * It provides methods to toggle the favorite status of a post and to load
 * the user's favorite posts with pagination.
 *
 * @package WP_Favorite_Posts
 * @since 1.0.0
 * @version 1.6
 * @author James Standbridge
 * @link https://github.com/JamesStandbridge
 */
class WP_Favorite_Posts_Ajax
{

    /**
     * Toggle the favorite status of a post via AJAX.
     *
     * This method is called when a user clicks the "Favorite" button. It checks
     * if the post is already in the user's favorites and either adds it or removes it
     * from their favorites list. The updated list of favorites is returned as a JSON response.
     *
     * @since 1.0.0
     */
    public static function toggle_favorite()
    {
        if (!is_user_logged_in() || !isset($_POST['post_id'])) {
            wp_send_json_error('Invalid request');
        }

        $post_id = intval($_POST['post_id']);
        $user_id = get_current_user_id();

        $favorites = get_user_meta($user_id, '_wp_favorite_posts', true);

        if (empty($favorites)) {
            $favorites = array();
        }

        if (in_array($post_id, $favorites)) {
            $favorites = array_diff($favorites, array($post_id));
        } else {
            $favorites[] = $post_id;
        }

        update_user_meta($user_id, '_wp_favorite_posts', $favorites);

        wp_send_json_success($favorites);
    }

    /**
     * Load the user's favorite posts with pagination via AJAX.
     *
     * This method is used to load the user's favorite posts dynamically, allowing
     * for pagination without a full page reload. It generates the HTML content for
     * the favorite posts list and returns it as a JSON response.
     *
     * @since 1.0.0
     */
    public static function load_favorite_posts()
    {
        if (!is_user_logged_in() || !isset($_POST['paged']) || !isset($_POST['post_type']) || !isset($_POST['posts_per_page'])) {
            wp_send_json_error('Invalid request');
        }

        $user_id = get_current_user_id();
        $paged = intval($_POST['paged']);
        $post_type = sanitize_text_field($_POST['post_type']);
        $posts_per_page = intval($_POST['posts_per_page']);

        ob_start();
        WP_Favorite_Posts_Shortcodes::render_favorite_posts($user_id, $post_type, $posts_per_page, $paged, 'Next', 'Previous');
        $content = ob_get_clean();

        wp_send_json_success(['content' => $content]);
    }
}
