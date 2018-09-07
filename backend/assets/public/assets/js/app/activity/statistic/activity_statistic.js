ActivityStatistic = function(config) {

    $.extend(this, config);

    this.targets = null;
}

ActivityStatistic.prototype = {
    start: function() {
        return this.initEvents();
    },

    initEvents: function () {
        $(document).on('click', '.js-disable-activity-static-block', $.proxy(this.onDisableActivityStatisticBlock, this));
        $(document).on('click', '.js-activate-activity-static-block', $.proxy(this.onActivateActivityStatisticBlock, this));

        $(document).on('click', '.js-load-block-settings-and-fields', $.proxy(this.onLoadBlockData, this));

        $(document).on("input", ".field-item", $.proxy(this.onFieldDataChanged, this));

        $(document).on("click", ".js-btn-save-field", $.proxy(this.onSaveField, this));
        $(document).on("click", ".js-btn-delete-field", $.proxy(this.onDeleteField, this));

        new ActivityBlockForm({ form: '#form-block-settings' }).start();
        new ActivityBlockForm({ form: '#form-new-field-add' }).start();
    },

    initElements: function() {
        $('select').material_select();
        $('.tooltipped').tooltip({
            delay: 50
        });
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

            if (result.html_fields != undefined) {
                $(result.html_fields.html_container).html(result.html_fields.html);
            }

            var dragger = tableDragger(document.querySelector(".sortable-list-" + result.section_id), {
                mode: "row",
            });

            dragger.on("drop", function(from, to, el) {
                var table = $(el), fields = [];

                table.find("tbody > tr").each(function(ind, item) {
                    fields.push($(item).data("id"));
                });

                $.post(table.data("url"), {
                    section: table.data("section-id"),
                    fields: fields
                }, function(result) {
                    Materialize.toast("Сортировка выполнена успешно.", 2500);
                });
            });

            this.initElements();
            //table.addClass("has-sort");
        } else {
            Materialize.toast("Ошибка загрузки данных.", 2500)
        }
    },

    onDisableActivityStatisticBlock: function(event) {
        var element = $(event.currentTarget);

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

            this.initElements();

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

            this.initElements();

            Materialize.toast("Блок успешно активирован.", 2500);
        } else {
            Materialize.toast("Ошибка сохранения данных.", 2500)
        }
    },

    getContentContainer: function () {
        $('#container-activity-statistic-fields-list').html('');

        return $('#container-activity-statistic-data');
    },

    onFieldDataChanged: function(element) {
        var $el = $(element.currentTarget);
        var reg = new RegExp($el.data('regexp'));

        if ($el.data('type') != 'date') {
            if (!reg.test($el.val()) && $el.data('type') == 'number') {
                $el.val($el.val().replace(/[^\d.]/, ''));
            }
        }

        $(".btn-save-field" + $(element.currentTarget).closest("tr").data("id")).fadeIn();
    },

    onSaveField: function(event) {
        var element = $(event.currentTarget).parent(), parent = element.closest("tr"), url = parent.data("url"), data = [];

        parent.find("input,select").each(function(index, item) {
            if ($(item).hasClass("checkbox")) {
                data.push({
                    field : $(item).data("field"),
                    value : $(item).is(":checked") ? 1 : 0
                });
            } else if ($(item).data("field") != undefined) {
                data.push({
                    field : $(item).data("field"),
                    value : $(item).val()
                });
            }
        });

        $.post(url, {
            data: data,
            field_id: parent.data("id")
        }, function(result) {
            Materialize.toast(result.msg, 2500);
        });

        $(event.currentTarget).fadeOut();
    },

    onDeleteField: function(event) {
        var element = $(event.currentTarget);

        if (confirm('Удалить поле ?')) {
            $.post(element.data('url'), {
                id: element.data('id'),
                section_id: element.data('section-id')
            }, $.proxy(this.onDeleteResult, this));
        }
    },

    onDeleteResult: function(result) {
        if (result.success) {
            this.getContentContainer().html(result.html);

            this.initElements();
            if (result.html_fields != undefined) {
                $(result.html_fields.html_container).html(result.html_fields.html);
            }


        }

        Materialize.toast(result.message, 2500);
    },
}
