<?php

class WP_Favorite_Posts
{

    public static function init()
    {
        add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
        add_action('wp_ajax_toggle_favorite', array('WP_Favorite_Posts_Ajax', 'toggle_favorite'));
        add_action('wp_ajax_nopriv_toggle_favorite', array('WP_Favorite_Posts_Ajax', 'toggle_favorite'));
    }

    public static function enqueue_scripts()
    {
        wp_enqueue_script('wp-favorite-posts', plugins_url('../assets/js/wp-favorite-posts.js', __FILE__), array('jquery'), '1.0', true);
        wp_localize_script('wp-favorite-posts', 'wpfp_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
        wp_enqueue_style('wp-favorite-posts', plugins_url('../assets/css/wp-favorite-posts.css', __FILE__));
    }
}
