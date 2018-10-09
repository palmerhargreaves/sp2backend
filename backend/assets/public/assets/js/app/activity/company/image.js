/**
 * Created by kostet on 09.10.2018.
 */
var ActivityCompanyTypeImage = function(config) {

    $.extend(this, config);

    this.form = '#form-activity-company-image';
}

ActivityCompanyTypeImage.prototype = {
    start: function() {
        this.initEvents();

        return this;
    },

    initEvents: function() {
        $(document).on('click', '.js-show-activity-company-type-upload-image', $.proxy(this.onShowActivityCompanyTypeImage, this));
    },

    onShowActivityCompanyTypeImage: function(e) {
        var element = $(e.currentTarget),
            activity_id = $('[name*="activity_id"]', this.getForm()),
            company_id = $('[name*="company_type_id"]', this.getForm()),
            id = $('[name*="id"]:last', this.getForm());

        activity_id.val(element.data('activity-id'));
        company_id.val(element.data('company-id'));
        id.val(element.data('company-type-image-id'));
    },

    getForm: function() {
        return $(this.form);
    }
}
