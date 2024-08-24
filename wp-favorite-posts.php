<?php
/*
Plugin Name: WP Favorite Posts
Description: A plugin to add favorite functionality for custom post types.
Version: 1.2
Author: James Standbridge (https://github.com/jamesstandbridge)
Text Domain: wp-favorite-posts
*/

// Prevent direct access
if (! defined('ABSPATH')) {
    exit;
}

// Define plugin path
define('WPFP_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Include required files
require_once WPFP_PLUGIN_PATH . 'includes/class-wp-favorite-posts.php';
require_once WPFP_PLUGIN_PATH . 'includes/class-wp-favorite-posts-shortcodes.php';
require_once WPFP_PLUGIN_PATH . 'includes/class-wp-favorite-posts-ajax.php';
require_once WPFP_PLUGIN_PATH . 'includes/class-wp-favorite-posts-queries.php';

// Initialize the main plugin class
WP_Favorite_Posts::init();
