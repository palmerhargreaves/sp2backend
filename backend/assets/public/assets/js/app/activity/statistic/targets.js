/**
 * Created by kostet on 05.09.2018.
 */
ActivityBlockTargets = function(config) {
    $.extend(this, config);
}

ActivityBlockTargets.prototype = {
    start: function() {
        this.initEvents();

        return this;
    },

    initEvents: function() {
        $(document).on('beforeSubmit', '#form-new-field-add', $.proxy(this.onBeforeSubmit, this));
        $(document).on('submit', '#form-new-field-add', $.proxy(this.obSubmit, this));
    },

    obSubmit: function(e) {
        e.preventDefault();
    },

    onBeforeSubmit: function () {
        var form = $(this);
        var formData = form.serialize();

        console.log(formData);
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            success: function (data) {
                alert('Test');
            },
            error: function () {
                alert("Something went wrong");
            }
        });
    }
}
