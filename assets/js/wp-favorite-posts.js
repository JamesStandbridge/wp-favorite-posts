/**
 * WP Favorite Posts JavaScript
 *
 * This script handles the AJAX functionality for the WP Favorite Posts plugin, including
 * pagination of the favorite posts list and toggling the favorite status of individual posts.
 *
 * @package WP_Favorite_Posts
 * @since 1.0.0
 * @version 1.7
 * @author James Standbridge
 * @link https://github.com/JamesStandbridge
 */
jQuery(document).ready(function($) {

    // Initialize the cache for favorite posts pages
    var favoritePostsCache = {}; // Declare the cache variable

    $(document).on('click', '.favorite-pagination-btn', function(e) {
        e.preventDefault();

        var $button = $(this);
        var paged = $button.data('paged');
        var $container = $('#favorite-posts-container');
        var post_type = $container.data('post-type');
        var posts_per_page = $container.data('posts-per-page');
        var container_tag = $container.data('container-tag');
        var container_class = $container.data('container-class');
        var item_class = $container.data('item-class');

        // Disable the button and show the spinner
        $button.prop('disabled', true);
        $button.append('<span class="spinner-border pagination-spinner" role="status" aria-hidden="true"></span>');

        // Load the requested page
        loadPage(paged, post_type, posts_per_page, container_tag, container_class, item_class, function(content) {
            $container.html(content);

            // Preload next and previous pages if they exist
            if (paged > 1) {
                loadPage(paged - 1, post_type, posts_per_page, container_tag, container_class, item_class, function() {});
            }
            loadPage(paged + 1, post_type, posts_per_page, container_tag, container_class, item_class, function() {});

            // Re-enable the button and remove the spinner
            $button.prop('disabled', false);
            $button.find('.pagination-spinner').remove();
        });
    });

    function loadPage(paged, post_type, posts_per_page, container_tag, container_class, item_class, callback) {
        if (favoritePostsCache[paged]) {
            callback(favoritePostsCache[paged]);
        } else {
            $.ajax({
                type: 'POST',
                url: wpfp_ajax.ajax_url,
                data: {
                    action: 'load_favorite_posts',
                    paged: paged,
                    post_type: post_type,
                    posts_per_page: posts_per_page,
                    container_tag: container_tag,
                    container_class: container_class,
                    item_class: item_class,
                },
                success: function(response) {
                    if (response.success) {
                        favoritePostsCache[paged] = response.data.content;
                        callback(response.data.content);
                    } else {
                        console.log('Error loading favorite posts:', response.data);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', error);
                }
            });
        }
    }

    $('.favorite-button').on('click', function(e) {
        e.preventDefault();

        var $button = $(this);
        var post_id = $button.data('post-id');

        $button.prop('disabled', true);
        $button.append('<span class="spinner-border" role="status" aria-hidden="true"></span>');

        $.ajax({
            type: 'POST',
            url: wpfp_ajax.ajax_url,
            data: {
                action: 'toggle_favorite',
                post_id: post_id
            },
            success: function(response) {
                if (response.success) {
                    if ($button.hasClass('add-favorite')) {
                        $button.removeClass('add-favorite').addClass('remove-favorite');
                        $button.find('.favorite-text').text($button.data('remove-text'));
                        $button.find('.favorite-icon').attr('src', $button.data('remove-icon'));
                    } else {
                        $button.removeClass('remove-favorite').addClass('add-favorite');
                        $button.find('.favorite-text').text($button.data('add-text'));
                        $button.find('.favorite-icon').attr('src', $button.data('add-icon'));
                    }
                }
            },
            complete: function() {
                $button.prop('disabled', false);
                $button.find('.spinner-border').remove();
            }
        });
    });
});
