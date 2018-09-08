/**
 * Created by kostet on 05.09.2018.
 */
ActivityBlockForm = function(config) {
    this.form = '';
    this.custom_fn = null;

    $.extend(this, config);
}

extend(ActivityBlockForm, BaseForm, {
    start: function() {
        this.initEvents();

        return this;
    },

    initEvents: function() {
        $(document).on('beforeSubmit', this.form, $.proxy(this.onBeforeSubmit, this));

        if (this.custom_fn != null) {
            this.custom_fn();
        }
    },

    onBeforeSubmit: function (event) {
        var form = $(event.currentTarget), self = this;

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

                    self.initElements(response);
                }
            },
            error: function(response) {
                Materialize.toast("Ошибка сохранения данных.", 2500)
            }
        });

        return false;
    }
});

