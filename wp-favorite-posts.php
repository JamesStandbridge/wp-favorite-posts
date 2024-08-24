<?php
/*
Plugin Name: WP Favorite Posts
Plugin URI: https://github.com/jamesstandbridge/wp-favorite-posts
Description: WP Favorite Posts is a WordPress plugin that allows users to mark custom post types (CPT) as favorites and view a paginated list of their favorites.
Version: 1.3
Author: James Standbridge
Author URI: https://github.com/jamesstandbridge
Text Domain: wp-favorite-posts
Domain Path: /languages
Network: true
Requires at least: 4.0
Requires PHP: 7.4
License: MIT
License URI: https://opensource.org/licenses/MIT
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
