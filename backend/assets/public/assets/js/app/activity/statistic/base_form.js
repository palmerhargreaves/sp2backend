/**
 * Created by kostet on 08.09.2018.
 */

BaseForm = function(config) {
    $.extend(this, config);
}

BaseForm.prototype = {
    initElements: function(result) {
        $('select').material_select();
        /*$('.tooltipped').tooltip({
            delay: 50
        });*/

        if (result != undefined) {
            if (result.section_id != undefined) {
                var dragger = tableDragger(document.querySelector(".sortable-list-" + result.section_id), {
                    mode: "row",
                });

                dragger.on("drop", function (from, to, el) {
                    var table = $(el), fields = [];

                    table.find("tbody > tr").each(function (ind, item) {
                        fields.push($(item).data("id"));
                    });

                    $.post(table.data("url"), {
                        section: table.data("section-id"),
                        fields: fields
                    }, function (result) {
                        Materialize.toast("Сортировка выполнена успешно.", 2500);
                    });
                });

                $("#checked-calc-field").nestable({
                    group: 1
                });
            }
        }
    },

}
