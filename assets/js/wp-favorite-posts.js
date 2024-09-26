/**
 * WP Favorite Posts JavaScript
 *
 * This script handles the AJAX functionality for the WP Favorite Posts plugin, including
 * pagination of the favorite posts list and toggling the favorite status of individual posts.
 *
 * @package WP_Favorite_Posts
 * @since 1.0.0
 * @version 1.8
 * @author James Standbridge
 * @link https://github.com/JamesStandbridge
 */
jQuery(document).ready(function($) {


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

    $('.remove-favorite-button').on('click', function(e) {
        e.preventDefault();

        var $button = $(this);
        var post_id = $button.data('post-id');
        var $listItem = $button.closest('li.favorite-recette-item');
        var $container = $('#favorite-posts-container');
        var paged = $container.data('paged') || 1;
        var post_type = $container.data('post-type');
        var posts_per_page = $container.data('posts-per-page');
        var container_tag = $container.data('container-tag');
        var container_class = $container.data('container-class');
        var item_class = $container.data('item-class');

        $button.prop('disabled', true);
        $button.append('<span class="spinner-border" role="status" aria-hidden="true"></span>');

        $.ajax({
            type: 'POST',
            url: wpfp_ajax.ajax_url,
            data: {
                action: 'remove_favorite',
                post_id: post_id
            },
            success: function(response) {
                if (response.success) {
                    $(this).remove();

                    // Après la suppression, vérifiez s'il y a encore des items dans la liste
                    if ($container.find('li.favorite-recette-item').length === 0 && paged > 1) {
                        // Si la page est vide et qu'il y a des pages précédentes, on charge la page précédente
                        loadPage(paged - 1, post_type, posts_per_page, container_tag, container_class, item_class, function(content) {
                            $container.html(content);
                            $container.data('paged', paged - 1); // Met à jour la page courante
                        });
                    } else {
                        // Sinon, on recharge simplement la page actuelle
                        loadPage(paged, post_type, posts_per_page, container_tag, container_class, item_class, function(content) {
                            $container.html(content);
                        });
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
