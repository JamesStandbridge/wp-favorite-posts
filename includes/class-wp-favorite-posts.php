<?php

class WP_Favorite_Posts
{
    public static function init()
    {
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));

        // AJAX actions for toggling favorites
        add_action('wp_ajax_toggle_favorite', array('WP_Favorite_Posts_Ajax', 'toggle_favorite'));
        add_action('wp_ajax_nopriv_toggle_favorite', array('WP_Favorite_Posts_Ajax', 'toggle_favorite'));

        // AJAX actions for loading favorite posts with pagination
        add_action('wp_ajax_load_favorite_posts', array('WP_Favorite_Posts_Ajax', 'load_favorite_posts'));
        add_action('wp_ajax_nopriv_load_favorite_posts', array('WP_Favorite_Posts_Ajax', 'load_favorite_posts'));
    }

    public static function enqueue_scripts()
    {
        // Enqueue the main JavaScript file
        wp_enqueue_script('wp-favorite-posts', plugins_url('../assets/js/wp-favorite-posts.js', __FILE__), array('jquery'), '1.0', true);

        // Localize the script with new data
        wp_localize_script('wp-favorite-posts', 'wpfp_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'add_text' => 'Ajouter aux favoris',
            'remove_text' => 'Retirer des favoris',
            'add_icon' => '',
            'remove_icon' => '',
        ));

        // Enqueue the main CSS file
        wp_enqueue_style('wp-favorite-posts', plugins_url('../assets/css/wp-favorite-posts.css', __FILE__));
    }
}

// Initialize the plugin
WP_Favorite_Posts::init();
