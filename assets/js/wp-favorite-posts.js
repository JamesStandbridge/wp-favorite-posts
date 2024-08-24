jQuery(document).ready(function($) {
    $('.favorite-button').on('click', function(e) {
        e.preventDefault();

        var post_id = $(this).data('post-id');

        $.ajax({
            type: 'POST',
            url: wpfp_ajax.ajax_url,
            data: {
                action: 'toggle_favorite',
                post_id: post_id
            },
            success: function(response) {
                if (response.success) {
                    alert('Favorites updated');
                    // Optionally update the button text or state here
                } else {
                    alert('Error updating favorites');
                }
            }
        });
    });
});
