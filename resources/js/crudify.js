require('datatables.net-bs4');
require('datatables.net-responsive-bs4');

$(document).on('click', '[crudify-show-modal]', function () {
    $.get($(this).attr('crudify-show-modal'), function (response) {
        $(response).modal('show');
    });
});
$(document).on('shown.bs.modal', '[crudify-modal]', function () {
    $(this).find('script').each(function () {
        eval($(this).text());
    });
});
$(document).on('hidden.bs.modal', '[crudify-modal]', function () {
    $(this).remove();
});

$(document).on('submit', '[crudify-form]', function (event) {
    event.preventDefault();

    let form = $(this);
    let submit = $(this).find(':submit');

    if (form.attr('crudify-form') !== 'submitted') {
        form.attr('crudify-form', 'submitted');
        submit.attr('crudify-form-submit', submit.html());
        submit.css('width', submit.css('width'));
        submit.html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: new FormData(form[0]),
            contentType: false,
            processData: false,
            error: function (response) {
                if (response.hasOwnProperty('responseJSON')) {
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').html('').addClass('d-none').removeClass('d-block');

                    $.each(response.responseJSON.errors, function (key, value) {
                        $('[crudify-form-element="' + key + '"]').addClass('is-invalid');
                        $('[crudify-form-error="' + key + '"]').html(value[0]).addClass('d-block').removeClass('d-none');
                    });
                }
            },
            complete: function () {
                form.attr('crudify-form', '');
                submit.html(submit.attr('crudify-form-submit'));
                submit.removeAttr('crudify-form-submit');
            }
        });
    }
});

$(document).ajaxComplete(function (event, response) {
    if (response.hasOwnProperty('responseJSON')) {
        let json = response.responseJSON;

        if (json.hasOwnProperty('redirect')) $(location).attr('href', json.redirect);
        if (json.hasOwnProperty('reload_page')) location.reload();
        if (json.hasOwnProperty('reload_table')) $($.fn.dataTable.tables()).DataTable().ajax.reload(null, false);
        if (json.hasOwnProperty('show_modal')) $(json.show_modal).modal('show');
        if (json.hasOwnProperty('dismiss_modal')) $('[crudify-modal]').modal('toggle');
        if (json.hasOwnProperty('jquery')) $(json.jquery.selector)[json.jquery.method](json.jquery.content);
    }
});

$(document).on('click', '[crudify-confirm]', function () {
    return confirm($(this).attr('crudify-confirm'));
});
