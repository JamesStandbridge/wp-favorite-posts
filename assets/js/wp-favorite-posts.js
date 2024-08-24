jQuery(document).ready(function($) {
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
