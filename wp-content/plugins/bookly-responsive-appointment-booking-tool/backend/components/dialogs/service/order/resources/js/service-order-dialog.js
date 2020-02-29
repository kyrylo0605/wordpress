jQuery(function ($) {
    'use strict';

    $(document.body)
        .on('service.submitForm', {},
            // Bind submit handler for service saving.
            function (event, $panel, data) {
                let id = data.find(value => value.name === 'id').value,
                    title = data.find(value => value.name === 'title').value;

                BooklyServiceOrderDialogL10n.services
                    .find(service => service.id == id).title = title;
            })
        .on('service.deleted', {},
            function (event, services) {
                BooklyServiceOrderDialogL10n.services.forEach((service, index) => {
                    if (services.includes(String(service.id))) {
                        delete BooklyServiceOrderDialogL10n.services[index];
                    }
                });
                // Remove undefined values
                BooklyServiceOrderDialogL10n.services.filter(function (el) {
                    return el != undefined;
                })
            });

    var $dialog   = $('#bookly-service-order-modal'),
        $list     = $('#bookly-list', $dialog),
        $template = $('#bookly-service-template'),
        $table    = $('#services-list'),
        $save     = $('#bookly-save', $dialog)
    ;

    // Save categories
    $save.on('click', function (e) {
        e.preventDefault();
        var ladda      = Ladda.create(this),
            services = [];
        ladda.start();
        $list.find('li').each(function (position, category) {
            services.push($(category).find('[name="id"]').val());
        });
        $.post(ajaxurl, {
                action: 'bookly_update_service_positions',
                services,
                csrf_token: BooklyServiceOrderDialogL10n.csrfToken
            },
            function (response) {
                if (response.success) {
                    BooklyServiceOrderDialogL10n.services = response.data;
                    $dialog.modal('hide');
                }
                ladda.stop();
            });
    });

    $dialog.off().on('show.bs.modal', function () {
        $list.html('');
        BooklyServiceOrderDialogL10n.services.forEach(function (service) {
            $list.append(
                $template.clone().show().html()
                    .replace(/{{id}}/g, service.id)
                    .replace(/{{title}}/g, service.title)
            );
        });
    });

    $list.sortable({
        axis  : 'y',
        handle: '.bookly-js-draghandle',
    });
});