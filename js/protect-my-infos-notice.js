jQuery(document).ready(function ($) {

$('.yw-dismiss-notice').on('click', function (e) {
    e.preventDefault();

    $.ajax({
        url: ywProtectMyInfosNotice.ajax_url,
        method: 'POST',
        data: {
            action: 'yw_dismiss_notice',
            nonce: ywProtectMyInfosNotice.nonce,
        },
        success: function (response) {
            if (response.success) {
                $('.yw-protect-my-infos-notice').fadeOut();
            } else {
                alert('Failed to dismiss the notice. Please try again.');
            }
        },
    });
});

});
