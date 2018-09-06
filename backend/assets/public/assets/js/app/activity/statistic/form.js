/**
 * Created by kostet on 05.09.2018.
 */
ActivityBlockForm = function(config) {
    this.form = '';

    $.extend(this, config);
}

ActivityBlockForm.prototype = {
    start: function() {
        this.initEvents();

        return this;
    },

    initEvents: function() {
        $(document).on('beforeSubmit', this.form, $.proxy(this.onBeforeSubmit, this));
    },

    onBeforeSubmit: function (event) {
        var form = $(event.currentTarget);

        if (form.find('.has-error').length) {
            return false;
        }

        // submit form
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: form.serialize(),
            success: function (response) {
                Materialize.toast(response.message, 2500)

                if (response.html_container != undefined) {
                    $(response.html_container).html(response.html);
                }
            },
            error: function(response) {
                Materialize.toast("Ошибка сохранения данных.", 2500)
            }
        });

        return false;
    }
}
