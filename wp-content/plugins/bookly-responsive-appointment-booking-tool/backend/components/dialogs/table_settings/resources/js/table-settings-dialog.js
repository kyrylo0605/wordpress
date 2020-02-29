jQuery(function ($) {
    'use strict';
    var $modal       = $('#bookly-table-settings-modal'),
        $save_button = $('.bookly-js-table-settings-save', $modal),
        $columns     = $modal.find('.bookly-js-table-columns'),
        $template    = $('#bookly-table-settings-template');

    // Save settings.
    $save_button.on('click', function (e) {
        e.preventDefault();
        let ladda   = Ladda.create(this),
            columns = {};
        ladda.start();
        $modal.find('input[type=checkbox]').each(function () {
            columns[this.name] = this.checked ? 1 : 0;
        });
        $.post(
            ajaxurl,
            {
                action    : 'bookly_update_table_settings',
                table     : $modal.find('[name="bookly-table-name"]').val(),
                columns   : columns,
                csrf_token: BooklyTableSettingsDialogL10n.csrfToken
            },
            function (response) {
                location.reload();
            });
    });

    $columns.sortable({
        axis  : 'y',
        handle: '.bookly-js-draghandle',
    });

    // Open table settings modal.
    $('.bookly-js-table-settings').off().on('click', function () {
        var table_settings = window[$(this).data('setting-name')].datatables[$(this).data('table-name')].settings,
            table_titles   = window[$(this).data('setting-name')].datatables[$(this).data('table-name')].titles;

        $modal.find('[name="bookly-table-name"]').val($(this).data('table-name'));

        // Generate columns.
        $columns.html('');
        $.each(table_settings.columns, function (name, show) {
            $columns.append(
                $template.clone().show().html()
                    .replace(/{{name}}/g, name)
                    .replace(/{{title}}/g, table_titles[name])
                    .replace(/{{checked}}/g, show ? 'checked' : '')
            );
        });

        $('#bookly-table-settings-modal').modal('show');
    });
});