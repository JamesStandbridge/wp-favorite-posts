jQuery(document).ready(function($) {
    // Handle pagination buttons
    $(document).on('click', '.favorite-pagination-btn', function(e) {
        e.preventDefault();

        var $button = $(this);
        var paged = $button.data('paged');
        var $container = $('#favorite-posts-container');
        var post_type = $container.data('post-type');
        var posts_per_page = $container.data('posts-per-page');

        $.ajax({
            type: 'POST',
            url: wpfp_ajax.ajax_url,
            data: {
                action: 'load_favorite_posts',
                paged: paged,
                post_type: post_type,
                posts_per_page: posts_per_page,
            },
            success: function(response) {
                if (response.success) {
                    $container.html(response.data.content);
                }
            }
        });
    });

    // Existing code for favorite button functionality
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
