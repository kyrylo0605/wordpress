jQuery(function ($) {
    let $servicesList   = $('#services-list'),
        $checkAllButton = $('#bookly-check-all'),
        filters = {
            category: $('#bookly-filter-category'),
            search: $('#bookly-filter-search')
        },
        $deleteButton   = $('#bookly-delete'),
        $deleteModal    = $('.bookly-js-delete-cascade-confirm'),
        urlParts        = document.URL.split('#'),
        columns         = [],
        order           = []
    ;

    $('.bookly-js-select').val(null);

    // Apply filter from anchor
    if (urlParts.length > 1) {
        urlParts[1].split('&').forEach(function (part) {
            var params = part.split('=');
            $('#bookly-filter-' + params[0]).val(params[1]);
        });
    } else {
        $.each(BooklyL10n.datatables.services.settings.filter, function (field, value) {
            if (value != '') {
                $('#bookly-filter-' + field).val(value);
            }
            // check if select has correct values
            if ($('#bookly-filter-' + field).prop('type') == 'select-one') {
                if ($('#bookly-filter-' + field + ' option[value="' + value + '"]').length == 0) {
                    $('#bookly-filter-' + field).val(null);
                }
            }
        });
    }

    /**
     * Init Columns.
     */
    if (BooklyL10n.show_type) {
        columns.push({
            responsivePriority: 1,
            orderable: false,
            render: function (data, type, row, meta) {
                return '<i class="fa fa-fw ' + row.type_icon + '" title="' + row.type + '"></i>';
            },
        });
    }
    columns.push({
        responsivePriority: 1,
        orderable: false,
        render: function (data, type, row, meta) {
            return '<i class="fa fa-fw fa-circle" style="color:' + row.colors[0] + ';">';
        }
    });

    $.each(BooklyL10n.datatables.services.settings.columns, function (column, show) {
        if (show) {
            switch (column) {
                case 'category_id':
                    columns.push({
                        data: column,
                        render: function (data, type, row, meta) {
                            if (row.category != null) {
                                return BooklyL10n.categories.find(function (category) {
                                    return category.id === row.category;
                                }).name;
                            } else {
                                return BooklyL10n.uncategorized;
                            }
                        }
                    });
                    break;
                default:
                    columns.push({data: column});
                    break;
            }
        }
    });
    columns.push({
        responsivePriority: 1,
        orderable: false,
        searchable: false,
        width: 90,
        render: function (data, type, row, meta) {
            return '<button type="button" class="btn btn-default" data-action="edit"><i class="fa fa-fw fa-edit"></i> ' + BooklyL10n.edit + '</a>';
        }
    });
    columns.push({
        responsivePriority: 1,
        orderable: false,
        searchable: false,
        render: function (data, type, row, meta) {
            return '<input type="checkbox" value="' + row.id + '">';
        }
    });
    $.each(BooklyL10n.datatables.services.settings.order, function (_, value) {
        const index = columns.findIndex(c => c.data === value.column);
        if (index !== -1) {
            order.push([index, value.order]);
        }
    });

    /**
     * Init DataTables.
     */
    var dt = $servicesList.DataTable({
        order       : order,
        info        : false,
        searching   : false,
        lengthChange: false,
        processing  : true,
        responsive  : true,
        pageLength  : 25,
        pagingType  : 'numbers',
        serverSide  : true,
        ajax        : {
            url : ajaxurl,
            type: 'POST',
            data: function (d) {
                let data = $.extend({action: 'bookly_get_services', csrf_token: BooklyL10n.csrfToken, filter: {}}, d);

                Object.keys(filters).map(filter => data.filter[filter] = filters[filter].val());

                return data;
            }
        },
        columns   : columns,
        dom       : "<'row'<'col-sm-6'<'pull-left'>><'col-sm-6'>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row pull-left'<'col-sm-12 bookly-margin-top-lg'p>>",
        language  : {
            zeroRecords: BooklyL10n.zeroRecords,
            processing : BooklyL10n.processing
        }
    });

    /**
     * On filter search change.
     */
    filters.search
        .on('keyup', function () {
            dt.ajax.reload();
        })
        .on('keydown', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        })
    ;
    filters.category
        .on('change', function () {
            dt.ajax.reload();
        });

    /**
     * Select all appointments.
     */
    $checkAllButton.on('change', function () {
        $servicesList.find('tbody input:checkbox').prop('checked', this.checked);
    });

    /**
     * On appointment select.
     */
    $servicesList.on('change', 'tbody input:checkbox', function () {
        $checkAllButton.prop('checked', $servicesList.find('tbody input:not(:checked)').length == 0);
    });

    $deleteButton.on('click', function (e) {
        e.preventDefault();
        var data     = {
                action    : 'bookly_remove_services',
                csrf_token: BooklyL10n.csrfToken,
            },
            services = [],
            button   = this;

        var delete_services = function (ajaxurl, data) {
            var ladda       = rangeTools.ladda(button),
                service_ids = [],
                $checkboxes = $servicesList.find('tbody input:checked');

            $checkboxes.each(function () {
                service_ids.push(dt.row($(this).closest('td')).data().id);
            });
            data['service_ids[]'] = service_ids;

            $.post(ajaxurl, data, function (response) {
                if (!response.success) {
                    switch (response.data.action) {
                        case 'show_modal':
                            $deleteModal
                                .modal('show');
                            $('.bookly-js-delete', $deleteModal).off().on('click', function () {
                                delete_services(ajaxurl, $.extend(data, {force_delete: true}));
                                $deleteModal.modal('hide');
                            });
                            $('.bookly-js-edit', $deleteModal).off().on('click', function () {
                                rangeTools.ladda(this);
                                window.location.href = response.data.filter_url;
                            });
                            break;
                        case 'confirm':
                            if (confirm(BooklyL10n.are_you_sure)) {
                                delete_services(ajaxurl, $.extend(data, {force_delete: true}));
                            }
                            break;
                    }
                } else {
                    dt.rows($checkboxes.closest('td')).remove().draw();
                    $(document.body).trigger('service.deleted', [service_ids]);
                }
                ladda.stop();
            });
        };

        delete_services(ajaxurl, data);
    });

    $('.bookly-js-select')
        .select2({
            width: '100%',
            theme: 'bootstrap',
            allowClear: true,
            placeholder: '',
            language  : {
                noResults: function() { return BooklyL10n.noResultFound; }
            }
        });
});