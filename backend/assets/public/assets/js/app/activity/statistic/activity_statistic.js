ActivityStatistic = function (config) {

    $.extend(this, config);

    this.targets = null;
}

extend(ActivityStatistic, BaseForm, {
    start: function () {
        return this.initEvents();
    },

    initEvents: function () {
        $(document).on('change', '.js-not-using-importer-config, .js-pre-check-statistic-config', $.proxy(this.onChangeActivityParams, this));
        $(document).on('change', '.js-activity-statistic-quarter', $.proxy(this.onChangeActivityStatisticQuarter, this));

        $(document).on('click', '.js-disable-activity-static-block', $.proxy(this.onDisableActivityStatisticBlock, this));
        $(document).on('click', '.js-activate-activity-static-block', $.proxy(this.onActivateActivityStatisticBlock, this));

        $(document).on('click', '.js-load-block-settings-and-fields', $.proxy(this.onLoadBlockData, this));

        $(document).on("input", ".field-item", $.proxy(this.onFieldDataChanged, this));
        $(document).on("change", ".field-item", $.proxy(this.onFieldDataChanged, this));

        $(document).on("click", ".js-btn-save-field", $.proxy(this.onSaveField, this));
        $(document).on("click", ".js-btn-delete-field", $.proxy(this.onDeleteField, this));

        $(document).on("click", "#js-config-statistic-params", $.proxy(this.onShowStatisticConfig, this));

        $(document).on("click", ".img-block-graph", $.proxy(this.onChangeBlockGraphType, this));

        $(document).on("input", "#js-search-by-dealer-number", $.proxy(this.onSearchDealerByNumber, this));

        new ActivityBlockForm({form: '#form-block-settings'}).start();
        new ActivityBlockForm({form: '#form-new-field-add'}).start();
        new ActivityBlockForm({form: '#form-activity-settings'}).start();

        new ActivityBlockForm({
            form: '#form-new-field-formula',
            custom_fn: $.proxy(this.formulaWorkflow, this)
        }).start();

    },

    onSearchDealerByNumber: function (event) {
        var element = $(event.currentTarget);

        $.post(element.data('url'), {
            dealer_number: element.val()
        }, $.proxy(this.onDealerSearchResult, this));
    },

    onDealerSearchResult: function (data) {
        $('#dealer-search-result').html(data.html);
    },

    onChangeBlockGraphType: function (event) {
        var element = $(event.currentTarget);

        $('.img-block-graph').removeClass('active');
        element.addClass('active');

        $('input[name*=graph_type]').val(element.data('type'));
    },

    onShowStatisticConfig: function (event) {
        var element = $(event.currentTarget);

        this.showHideConfigBtn(false);

        $.post(element.data('url'), {
            id: element.data('id')
        }, $.proxy(this.onShowConfigResult, this));
    },

    onShowConfigResult: function (result) {
        this.getContentContainer().html(result.html);
    },

    onChangeActivityStatisticQuarter: function (event) {
        var element = $(event.currentTarget), quarters = [];

        $('.js-activity-statistic-quarter').each(function (ind, el) {
            if ($(el).is(':checked')) {
                quarters.push($(el).data('quarter'));
            }
        });

        $.post(element.data('url'), {
            id: element.data('id'),
            quarters: quarters.join(':'),
            year: element.data('year')
        }, $.proxy(this.onBindActivityQuarterResult, this));
    },

    onBindActivityQuarterResult: function (result) {
        result.success ? Materialize.toast("Данные успешно сохранены.", 2500) : Materialize.toast("Ошибка сохранения данных.", 2500);
    },

    onChangeActivityParams: function (event) {
        var element = $(event.currentTarget), fields = [];

        $(".form-config-field-" + getElementData(element, "id")).each(function (ind, el) {
            if ($(el).hasClass("checkbox")) {
                fields.push({
                    field: $(el).data("field"),
                    val: $(el).is(":checked") ? 1 : 0
                });
            }
        });

        $.post(getElementData(element, "url"), {
            activity: getElementData(element, "id"),
            fields: fields
        }, function (result) {
            result.success ? Materialize.toast("Данные успешно сохранены.", 2500) : Materialize.toast("Ошибка сохранения данных.", 2500);
        });
    },

    onLoadBlockData: function (event) {
        var element = $(event.currentTarget);

        $('.section-template-block').removeClass('z-depth-2 animated pulse active-block-item');

        element.closest('.card').addClass('z-depth-2 animated pulse active-block-item');

        showLoader();
        $.post(element.data('url'), {
            section_id: element.data('section-id')
        }, $.proxy(this.onLoadBlockDataResult, this));
    },

    onLoadBlockDataResult: function (result) {
        hideLoader();
        if (result.success) {
            this.getContentContainer().html(result.html);

            if (result.html_fields != undefined) {
                $(result.html_fields.html_container).html(result.html_fields.html);
            }

            this.showHideConfigBtn(true);

            this.initElements(result);
            //table.addClass("has-sort");
        } else {
            Materialize.toast("Ошибка загрузки данных.", 2500)
        }
    },

    onDisableActivityStatisticBlock: function (event) {
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

    onDisableBlockResult: function (result) {
        hideLoader();
        if (result.success) {
            $('.section-template-block-' + result.section_template_id).removeClass('green').addClass('grey');

            $('.activate-block-' + result.section_template_id).show();
            $('.disable-block-' + result.section_template_id).hide();

            this.getContentContainer().html(result.html);
            $('.block-row-item-' + result.section_template_id).html(result.block_html);

            this.initElements(result);
            this.showHideConfigBtn(false);

            Materialize.toast("Блок успешно отключен.", 2500);

        } else {
            Materialize.toast("Ошибка сохранения данных.", 2500)
        }
    },

    onActivateActivityStatisticBlock: function (event) {
        var element = $(event.currentTarget);

        if (confirm('Подключить блок ?')) {
            showLoader();
            $.post(element.data('url'), {
                id: element.data('activity-id'),
                section_template_id: element.data('section-template-id')
            }, $.proxy(this.onActivateBlockResult, this));
        }
    },

    onActivateBlockResult: function (result) {
        hideLoader();
        if (result.success) {
            $('.section-template-block-' + result.section_template_id).removeClass('grey').addClass('green');

            $('.activate-block-' + result.section_template_id).hide();
            $('.disable-block-' + result.section_template_id).show();

            this.getContentContainer().html(result.html);
            $('.block-row-item-' + result.section_template_id).html(result.block_html);

            this.initElements(result);

            this.showHideConfigBtn(true);
            Materialize.toast("Блок успешно активирован.", 2500);
        } else {
            Materialize.toast("Ошибка сохранения данных.", 2500)
        }
    },

    getContentContainer: function () {
        $('#container-activity-statistic-fields-list').html('');

        return $('#container-activity-statistic-data');
    },

    onFieldDataChanged: function (element) {
        var $el = $(element.currentTarget);
        var reg = new RegExp($el.data('regexp'));

        if ($el.data('type') != 'date') {
            if (!reg.test($el.val()) && $el.data('type') == 'number') {
                $el.val($el.val().replace(/[^\d.]/, ''));
            }
        }

        $(".btn-save-field" + $(element.currentTarget).closest("tr").data("id")).fadeIn();
    },

    onSaveField: function (event) {
        var element = $(event.currentTarget).parent(), parent = element.closest("tr"), url = parent.data("url"),
            data = [], self = this;

        parent.find("input,select,textarea").each(function (index, item) {
            if ($(item).hasClass("checkbox")) {
                data.push({
                    field: $(item).data("field"),
                    value: $(item).is(":checked") ? 1 : 0
                });
            } else if ($(item).data("field") != undefined) {
                data.push({
                    field: $(item).data("field"),
                    value: $(item).val()
                });
            }
        });

        $.post(url, {
            data: data,
            field_id: parent.data('id'),
            section_id: parent.data('section-id')
        }, function (result) {
            if (result.html_fields != undefined) {
                $(result.html_fields.html_container).html(result.html_fields.html);

                self.initElements(result);
            }

            Materialize.toast(result.msg, 2500);
        });

        $(event.currentTarget).fadeOut();
    },

    onDeleteField: function (event) {
        var element = $(event.currentTarget);

        if (confirm('Удалить поле ?')) {
            $.post(element.data('url'), {
                id: element.data('id'),
                section_id: element.data('section-id')
            }, $.proxy(this.onDeleteResult, this));
        }
    },

    onDeleteResult: function (result) {
        if (result.success) {
            if (result.html != undefined) {
                this.getContentContainer().html(result.html);
            }

            if (result.html_fields != undefined) {
                $(result.html_fields.html_container).html(result.html_fields.html);

                this.reloadFieldsCount(result);
            }

            this.initElements(result);
        }

        Materialize.toast(result.message, 2500);
    },

    /**
     * Кастомный обработчик событий для блоков с вычисляемыми полями
     */
    formulaWorkflow: function () {
        $(document).on("change", ".ch-calc-field", $.proxy(this.onCheckUncheckFieldAndFormula, this));
        $(document).on("click", "#js-save-calc-value", $.proxy(this.onAddNewFormula, this));
        $(document).on("click", ".delete-formula-field", $.proxy(this.onDeleteFormulaField, this));
        $(document).on("change", "#field-calc-type", $.proxy(this.onChangeCalcType, this));
        $(document).on("click", ".js-btn-edit-field", $.proxy(this.onEditCalculatedField, this));
    },

    onEditCalculatedField: function (event) {
        var element = $(event.currentTarget);

        $.post(element.data('url'), {
            field_id: element.data('id'),
            section_id: element.data('section-id')
        }, $.proxy(this.onEditCalculatedFieldResult, this));
    },

    onEditCalculatedFieldResult: function (result) {
        if (result.success) {
            $('#container-calculated-field').html(result.html);

            this.initElements(result);

            offset = $('#calculated_field_ancor').offset().top - 10;
            $('#modal-dialog').animate({
                    scrollTop: offset + "px"
                },
                {duration: 500});
        }
    },

    onCheckUncheckFieldAndFormula: function (event) {
        var checked_calc_fields = this.getCalcCheckedFields(), element = $(event.currentTarget);

        if (element.is(":checked")) {
            $("#checked-calc-field").append("<li class='dd-item' data-id='" + element.data("id") + "'><div class='dd-handle'>" + "[" + element.data('section-name') + "] " + element.data("name") + "</div></li>");
        } else {
            $("li[data-id='" + element.data("id") + "']").remove();
        }

        this.getFieldCalcType().val() == 'percent' && checked_calc_fields.length >= 1
            ? $("#js-save-calc-value").fadeIn()
            : checked_calc_fields.length >= 2 ? $("#js-save-calc-value").fadeIn() : $("#js-save-calc-value").fadeOut();
    },

    onChangeCalcType: function (event) {
        var checked_calc_fields = this.getCalcCheckedFields(), element = $(event.currentTarget);

        element.val() == 'percent' && checked_calc_fields.length >= 1
            ? $("#js-save-calc-value").fadeIn()
            : checked_calc_fields.length >= 2 ? $("#js-save-calc-value").fadeIn() : $("#js-save-calc-value").fadeOut();
    },

    onAddNewFormula: function (event) {
        event.preventDefault();

        var element = $(event.currentTarget), self = this, field_header = $.trim($('#calculated_field_header').val());

        var items = [];
        $("#checked-calc-field .dd-item").each(function (ind, item) {
            items.push({
                id: $(item).data("id")
            });
        });

        if (field_header.length == 0) {
            Materialize.toast("Введите название вычисляемого поля.", 2500);
            return;
        }

        if (items.length < 2 && this.getFieldCalcType().val() != 'percent') {
            Materialize.toast("Для продолжения необходимо выбрать несколько полей.", 2500);
            return;
        }

        $.post(element.data("url"), {
            data: items,
            field_header: field_header,
            field_id: element.data('id') != undefined ? element.data('id') : '',
            section_id: element.data("section-id"),
            calc_type: $("#field-calc-type").val(),
            show_in_export: $('#show_in_export_calc').is(':checked') ? 1 : 0,
            show_in_statistic: $('#show_in_statistic_calc').is(':checked') ? 1 : 0,
        }, function (result) {
            if (result.html_fields != undefined) {
                $(result.html_fields.html_container).html(result.html_fields.html);
            }

            self.initElements(result);
            self.reloadFieldsCount(result);

            Materialize.toast(result.msg, 2500);
        });
    },

    onDeleteFormulaField: function (event) {
        var element = $(event.currentTarget);

        if (confirm('Удалить формулу ?')) {
            $.post(element.data('url'), {
                id: element.data('id'),
                section_id: element.data('section-id')
            }, $.proxy(this.onDeleteResult, this));
        }
    },

    getCalcCheckedFields: function () {
        var calc_checked_fields = [];

        $(".ch-calc-field").each(function (ind, item) {
            if ($(item).is(":checked")) {
                calc_checked_fields.push({
                    id: $(item).data("id")
                });
            }
        });

        return calc_checked_fields;
    },

    getFieldCalcType: function () {
        return $('#field-calc-type');
    },

    showHideConfigBtn: function (show) {
        show ? $('#js-config-statistic-params').fadeIn() : $('#js-config-statistic-params').fadeOut();
    }
});
