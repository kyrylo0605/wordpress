/**
 * Main scripts file for the welcome notice
 */

/* global tiAboutNotice */

(function ($) {
    $(document).ready(function () {
        $(document).on('click', '.notice.ti-about-notice .notice-dismiss', function () {
            $(this).parent().hide();
            jQuery.ajax({
                async: true,
                type: 'POST',
                data: {
                    action: 'dismiss_welcome_notice',
                    nonce: tiAboutNotice.dismissNonce
                },
                url: tiAboutNotice.ajaxurl
            });
        });
    });
})(jQuery);