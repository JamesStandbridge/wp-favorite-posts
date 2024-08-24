<?php

class WP_Favorite_Posts_Shortcodes
{

    public static function init()
    {
        add_shortcode('favorite_posts', array(__CLASS__, 'favorite_posts_shortcode'));
        add_shortcode('favorite_button', array(__CLASS__, 'favorite_button_shortcode'));
    }

    public static function favorite_button_shortcode($atts)
    {
        if (! is_user_logged_in()) {
            return "";
        }

        $atts = shortcode_atts(array(
            'post_id' => get_the_ID(),
            'class' => 'favorite-button'
        ), $atts, 'favorite_button');

        $post_id = intval($atts['post_id']);
        $user_id = get_current_user_id();
        $favorites = get_user_meta($user_id, '_wp_favorite_posts', true);

        $button_text = in_array($post_id, (array) $favorites) ? 'Retirer des favoris' : 'Ajouter aux favoris';

        return '<button class="' . esc_attr($atts['class']) . '" data-post-id="' . esc_attr($post_id) . '">' . esc_html($button_text) . '</button>';
    }

    public static function favorite_posts_shortcode($atts)
    {
        $atts = shortcode_atts(array(
            'post_type' => 'post',
            'posts_per_page' => 10,
        ), $atts, 'favorite_posts');

        if (! is_user_logged_in()) {
            return '<p>You must be logged in to view your favorite posts.</p>';
        }

        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $user_id = get_current_user_id();
        $favorites = get_user_meta($user_id, '_wp_favorite_posts', true);

        if (empty($favorites)) {
            return '<p>You have no favorite posts.</p>';
        }

        $args = array(
            'post_type' => $atts['post_type'],
            'posts_per_page' => $atts['posts_per_page'],
            'post__in' => $favorites,
            'paged' => $paged,
        );

        $query = new WP_Query($args);

        ob_start();
        if ($query->have_posts()) {
            echo '<ul class="favorite-posts">';
            while ($query->have_posts()) : $query->the_post();
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            endwhile;
            echo '</ul>';

            $big = 999999999; // need an unlikely integer
            echo paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, $paged),
                'total' => $query->max_num_pages
            ));
        } else {
            echo '<p>No favorite posts found.</p>';
        }
        wp_reset_postdata();

        return ob_get_clean();
    }
}

WP_Favorite_Posts_Shortcodes::init();
