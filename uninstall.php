<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

function wp_favorite_posts_cleanup()
{
    $users = get_users(array('fields' => 'ID'));

    foreach ($users as $user_id) {
        delete_user_meta($user_id, '_wp_favorite_posts');
    }
}

wp_favorite_posts_cleanup();
