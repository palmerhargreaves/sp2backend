ActivityStatistic = function(config) {

    $.extend(this, config);
}

ActivityStatistic.prototype = {
    start: function() {
        return this.initEvents();
    },

    initEvents: function () {
        $(document).on('click', '.js-disable-activity-static-block', $.proxy(this.onDisableActivityStatisticBlock, this));
        $(document).on('click', '.js-activate-activity-static-block', $.proxy(this.onActivateActivityStatisticBlock, this));

        $(document).on('click', '.js-load-block-settings-and-fields', $.proxy(this.onLoadBlockData, this));
    },

    onLoadBlockData: function(event) {
        var element = $(event.currentTarget);

        $('.section-template-block').removeClass('z-depth-2 animated pulse active-block-item');

        element.closest('.card').addClass('z-depth-2 animated pulse active-block-item');

        showLoader();
        $.post(element.data('url'), {
            section_id: element.data('section-id')
        }, $.proxy(this.onLoadBlockDataResult, this));
    },

    onLoadBlockDataResult: function(result) {
        hideLoader();
        if (result.success) {
            this.getContentContainer().html(result.html);

            $("body, html").animate({
                    scrollTop: "10px"
                },
                {duration: 500});

        } else {
            Materialize.toast("Ошибка загрузки данных.", 2500)
        }
    },

    onDisableActivityStatisticBlock: function(event) {
        var element = $(event.currentTarget);

        console.log(element);
        if (confirm('Отключить блок ?')) {
            showLoader();
            $.post(element.data('url'), {
                section_id: element.data('section-id'),
                id: element.data('activity-id'),
                section_template_id: element.data('section-template-id')
            }, $.proxy(this.onDisableBlockResult, this));
        }
    },

    onDisableBlockResult: function(result) {
        hideLoader();
        if (result.success) {
            $('.section-template-block-' + result.section_template_id).removeClass('green').addClass('grey');

            $('.activate-block-' + result.section_template_id).show();
            $('.disable-block-' + result.section_template_id).hide();

            this.getContentContainer().html(result.html);
            $('.block-row-item-' + result.section_template_id).html(result.block_html);

            Materialize.toast("Блок успешно отключен.", 2500);

        } else {
            Materialize.toast("Ошибка сохранения данных.", 2500)
        }
    },

    onActivateActivityStatisticBlock: function(event) {
        var element = $(event.currentTarget);

        if (confirm('Подключить блок ?')) {
            showLoader();
            $.post(element.data('url'), {
                id: element.data('activity-id'),
                section_template_id: element.data('section-template-id')
            }, $.proxy(this.onActivateBlockResult, this));
        }
    },

    onActivateBlockResult: function(result) {
        hideLoader();
        if (result.success) {
            $('.section-template-block-' + result.section_template_id).removeClass('grey').addClass('green');

            $('.activate-block-' + result.section_template_id).hide();
            $('.disable-block-' + result.section_template_id).show();

            this.getContentContainer().html(result.html);
            $('.block-row-item-' + result.section_template_id).html(result.block_html);

            Materialize.toast("Блок успешно активирован.", 2500);
        } else {
            Materialize.toast("Ошибка сохранения данных.", 2500)
        }
    },

    getContentContainer: function () {
        return $('#container-activity-statistic-data');
    }

}
