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
            'container_tag' => 'ul',  // The tag for the container (ul, div, section, etc.)
            'container_class' => 'favorite-posts',  // CSS class for the container
            'item_class' => '',  // CSS class for each item
            'next_text' => 'Next',
            'prev_text' => 'Previous',
        ), $atts, 'favorite_posts');

        if (!is_user_logged_in()) {
            return '<p>You must be logged in to view your favorite posts.</p>';
        }

        $user_id = get_current_user_id();
        $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

        ob_start();
        echo '<div id="favorite-posts-container" data-post-type="' . esc_attr($atts['post_type']) . '" data-posts-per-page="' . esc_attr($atts['posts_per_page']) . '" data-container-tag="' . esc_attr($atts['container_tag']) . '" data-container-class="' . esc_attr($atts['container_class']) . '" data-item-class="' . esc_attr($atts['item_class']) . '">';
        self::render_favorite_posts($user_id, $atts['post_type'], $atts['posts_per_page'], $paged, $atts['container_tag'], $atts['container_class'], $atts['item_class'], $atts['next_text'], $atts['prev_text']);
        echo '</div>';
        return ob_get_clean();
    }

    public static function render_favorite_posts($user_id, $post_type, $posts_per_page, $paged, $container_tag, $container_class, $item_class, $next_text, $prev_text)
    {
        $favorites = get_user_meta($user_id, '_wp_favorite_posts', true);

        if (empty($favorites)) {
            echo '<p>You have no favorite posts.</p>';
            return;
        }

        $args = array(
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'post__in' => $favorites,
            'paged' => $paged,
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            echo '<' . esc_html($container_tag) . ' class="' . esc_attr($container_class) . '">';
            while ($query->have_posts()) : $query->the_post();

                // Path to the custom template in the active theme
                $template_name = 'favorite-' . $post_type . '-item.php';
                $template_path = locate_template($template_name);

                echo '<div class="' . esc_attr($item_class) . '">'; // Start item container

                if ($template_path) {
                    // Use the custom template if it exists
                    include($template_path);
                } else {
                    // Default output if no custom template exists
                    echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
                }

                echo '</div>'; // End item container

            endwhile;
            echo '</' . esc_html($container_tag) . '>';

            $total_pages = $query->max_num_pages;

            if ($total_pages > 1) {
                echo '<div class="pagination-buttons">';
                if ($paged > 1) {
                    echo '<button class="favorite-pagination-btn" data-paged="' . ($paged - 1) . '">' . esc_html($prev_text) . '</button>';
                }
                if ($paged < $total_pages) {
                    echo '<button class="favorite-pagination-btn" data-paged="' . ($paged + 1) . '">' . esc_html($next_text) . '</button>';
                }
                echo '</div>';
            }
        } else {
            echo '<p>No favorite posts found.</p>';
        }

        wp_reset_postdata();
    }
}

WP_Favorite_Posts_Shortcodes::init();
