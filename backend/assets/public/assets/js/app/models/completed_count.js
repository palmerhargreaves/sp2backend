/**
 * Created by kostet on 08.01.2019.
 */

ModelsCompletedCount = function(config) {

    $.extend(this, config);
}

ModelsCompletedCount.prototype = {
    start: function() {
        this.initEvents();

        return this;
    },

    initEvents: function() {
        //Выбор кварталов
        $(document).on('change', '.quarters', $.proxy(this.onSelectQuarter, this));

        //Фильтр заявок по выбранным данным
        $(document).on('click', '#js-make-filter', $.proxy(this.onFilterData, this));
    },

    onFilterData: function(event) {
        var bt = $(event.currentTarget),
            quarters = this.getCheckedItems('.quarters', 'quarter'),
            months = this.getCheckedItems('.months', 'month'),
            year = $('#filter_year').val(),
            selected_data = $('#filter_date').parent().find("input[type=hidden]").val();

        $.post(bt.data('url'), {
            year: year,
            quarters: quarters,
            months: months,
            selected_data: selected_data
        }, $.proxy(this.filterResult, this));
    },

    filterResult: function(result) {
        this.getContainerResult().html(result);
    },

    onSelectQuarter: function() {
        //Если есть выбранные кварталы, делаем доступные месяцы только выбранных кварталов
        if ($('.quarters').is(':checked')) {
            $('.quarters').each(function(i, item) {
                //Проверяем на выбранный квартал
                if ($(item).is(':checked')) {
                    $('[data-month-quarter="'+ $(item).data('quarter') + '"]').prop('disabled', false);
                } else {
                    $('[data-month-quarter="'+ $(item).data('quarter') + '"]').prop('disabled', true);
                    $('[data-month-quarter="'+ $(item).data('quarter') + '"]').prop('checked', false);
                }
            });
        } else {
            //Все месяцы доступны
            $('.months').prop('checked', false).prop('disabled', false);
        }
    },

    //Получить поличество отмеченных элементов
    getCheckedItems: function(item, data) {
        var items = [];

        $(item).filter(function(i, item) {
            return $(item).is(':checked');
        }).each(function(i, item) {
            items.push($(item).data(data));
        });

        return items;
    },

    getContainerResult: function() {
        return $('#container-data');
    }
}
