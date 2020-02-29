jQuery(function($) {
    $('[for-modal]').on('click', function () {
        $('#' + $(this).attr('for-modal')).modal('show');
    });
});