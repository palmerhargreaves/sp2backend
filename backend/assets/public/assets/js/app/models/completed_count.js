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
        $(document).on('change', '.quarters', $.proxy(this.onSelectQuarter, this));
    },

    onSelectQuarter: function(event) {
        //Если есть выбранные кварталы, делаем доступные месяцы только выбранных кварталов
        if ($('.quarters').is(':checked')) {
            console.log('test');
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
    }
}
