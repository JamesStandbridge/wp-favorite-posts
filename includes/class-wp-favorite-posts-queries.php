<?php


/**
 * Class WP_Favorite_Posts_Queries
 *
 * This class provides query methods for retrieving a user's favorite posts.
 * It is used to query posts that a user has marked as favorites, allowing
 * them to be displayed in various parts of the site.
 *
 * @package WP_Favorite_Posts
 * @since 1.0.0
 * @version 1.6
 * @author James Standbridge
 * @link https://github.com/JamesStandbridge
 */
class WP_Favorite_Posts_Queries
{

    /**
     * Retrieve a list of a user's favorite posts.
     *
     * This method queries the posts that a specific user has marked as favorites,
     * based on the user ID. It supports pagination and allows querying of specific
     * custom post types.
     *
     * @param int $user_id The ID of the user whose favorites are being queried.
     * @param string $post_type The type of posts to query (default is 'post').
     * @param int $number The number of posts to retrieve per page (default is 10).
     * @param int $paged The current page of results for pagination (default is 1).
     * @return WP_Query|false The WP_Query object containing the user's favorite posts, or false if no favorites are found.
     * @since 1.0.0
     */
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
