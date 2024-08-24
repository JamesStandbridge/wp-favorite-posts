
/**
 * WP Favorite Posts JavaScript
 *
 * This script handles the AJAX functionality for the WP Favorite Posts plugin, including
 * pagination of the favorite posts list and toggling the favorite status of individual posts.
 *
 * @package WP_Favorite_Posts
 * @since 1.0.0
 * @version 1.6
 * @author James Standbridge
 * @link https://github.com/JamesStandbridge
 */
jQuery(document).ready(function($) {

    /**
     * Handle pagination for the favorite posts list via AJAX.
     *
     * This event listener is attached to the pagination buttons within the favorite posts list.
     * When a button is clicked, it triggers an AJAX request to load the next or previous set of
     * favorite posts without reloading the page.
     *
     * @event click .favorite-pagination-btn
     * @param {Object} e - The event object.
     */
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
                    $container.html(response.data.content);
                } else {
                    console.log('Error loading favorite posts:', response.data);
                }
            },
            complete: function() {
                // Re-enable the button and remove the spinner
                $button.prop('disabled', false);
                $button.find('.pagination-spinner').remove();
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', error);
            }
        });
    });

    /**
     * Handle the toggling of a post's favorite status via AJAX.
     *
     * This event listener is attached to the favorite button. When the button is clicked, it sends
     * an AJAX request to either add or remove the post from the user's favorites. The button's text
     * and icon are updated based on the new favorite status, and a loading spinner is displayed while
     * the request is being processed.
     *
     * @event click .favorite-button
     * @param {Object} e - The event object.
     */
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
