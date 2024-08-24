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
            'class' => 'favorite-button',
            'add_text' => 'Ajouter aux favoris',
            'remove_text' => 'Retirer des favoris',
            'add_icon' => '',  // URL to the add icon image or SVG
            'remove_icon' => '',  // URL to the remove icon image or SVG
            'display' => 'both', // options: 'both', 'text', 'icon'
        ), $atts, 'favorite_button');

        $post_id = intval($atts['post_id']);
        $user_id = get_current_user_id();
        $favorites = get_user_meta($user_id, '_wp_favorite_posts', true);

        $is_favorite = in_array($post_id, (array) $favorites);

        $button_class = $is_favorite ? 'remove-favorite' : 'add-favorite';
        $button_text = $is_favorite ? $atts['remove_text'] : $atts['add_text'];
        $button_icon = $is_favorite ? $atts['remove_icon'] : $atts['add_icon'];

        // Construct the button content
        $button_content = '';
        if ($atts['display'] == 'both' || $atts['display'] == 'icon') {
            if (!empty($button_icon)) {
                $button_content .= '<img src="' . esc_url($button_icon) . '" alt="" class="favorite-icon" /> ';
            }
        }
        if ($atts['display'] == 'both' || $atts['display'] == 'text') {
            $button_content .= '<span class="favorite-text">' . esc_html($button_text) . '</span>';
        }

        return '<button class="' . esc_attr($atts['class']) . ' ' . esc_attr($button_class) . '" data-post-id="' . esc_attr($post_id) . '" data-add-text="' . esc_attr($atts['add_text']) . '" data-remove-text="' . esc_attr($atts['remove_text']) . '" data-add-icon="' . esc_url($atts['add_icon']) . '" data-remove-icon="' . esc_url($atts['remove_icon']) . '">' . $button_content . '</button>';
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
