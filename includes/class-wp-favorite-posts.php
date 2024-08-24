<?php

/**
 * Class WP_Favorite_Posts
 *
 * Main class for the WP Favorite Posts plugin. This class is responsible for initializing
 * the plugin, enqueuing scripts and styles, and registering AJAX actions for handling
 * favorite toggles and loading favorite posts.
 *
 * @package WP_Favorite_Posts
 * @since 1.0.0
 * @version 1.7
 * @author James Standbridge
 * @link https://github.com/JamesStandbridge
 */
class WP_Favorite_Posts
{
    /**
     * Initialize the plugin by setting up hooks and filters.
     *
     * This method is called during the plugin initialization process.
     * It registers actions to enqueue scripts and to handle AJAX requests
     * for both logged-in and non-logged-in users.
     *
     * @since 1.0.0
     */
    public static function init()
    {
        add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));

        add_action('wp_ajax_toggle_favorite', array('WP_Favorite_Posts_Ajax', 'toggle_favorite'));
        add_action('wp_ajax_nopriv_toggle_favorite', array('WP_Favorite_Posts_Ajax', 'toggle_favorite'));

        add_action('wp_ajax_load_favorite_posts', array('WP_Favorite_Posts_Ajax', 'load_favorite_posts'));
        add_action('wp_ajax_nopriv_load_favorite_posts', array('WP_Favorite_Posts_Ajax', 'load_favorite_posts'));
    }

    /**
     * Enqueue the scripts and styles needed for the plugin.
     *
     * This method enqueues the JavaScript and CSS files required by the plugin.
     * It also localizes script data, making it available to the JavaScript files.
     *
     * @since 1.0.0
     */
    public static function enqueue_scripts()
    {
        wp_enqueue_script('wp-favorite-posts', plugins_url('../assets/js/wp-favorite-posts.js', __FILE__), array('jquery'), '1.0', true);

        wp_localize_script('wp-favorite-posts', 'wpfp_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'add_text' => 'Ajouter aux favoris',
            'remove_text' => 'Retirer des favoris',
            'add_icon' => '',
            'remove_icon' => '',
        ));

        wp_enqueue_style('wp-favorite-posts', plugins_url('../assets/css/wp-favorite-posts.css', __FILE__));
    }
}

WP_Favorite_Posts::init();
