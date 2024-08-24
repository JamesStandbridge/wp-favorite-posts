<?php

class WP_Favorite_Posts_Ajax
{

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
