<?php

class WP_Favorite_Posts_Queries
{

    public static function get_user_favorites($user_id, $post_type = 'post', $number = 10, $paged = 1)
    {
        $favorites = get_user_meta($user_id, '_wp_favorite_posts', true);

        if (empty($favorites)) {
            return false;
        }

        $args = array(
            'post_type' => $post_type,
            'posts_per_page' => $number,
            'post__in' => $favorites,
            'paged' => $paged,
        );

        return new WP_Query($args);
    }
}
