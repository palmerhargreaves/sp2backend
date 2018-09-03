/*================================================================================
	Item Name: Materialize - Material Design Admin Template
	Version: 3.1
	Author: GeeksLabs
	Author URL: http://www.themeforest.net/user/geekslabs
================================================================================

NOTE:
------
*/

function showLoader() {
    $("body").removeClass("loaded");

    $(".modal-window").hide();
}

function hideLoader() {
    $("body").addClass("loaded");

    $(".modal-window").show();
}

function showProgress(el) {
    el != undefined ? $(el).show() : $('.loading-progress').show();
}

function hideProgress(el) {
    el != undefined ? $(el).hide() : $('.loading-progress').hide();
}

function makeTableDraggable(selector, parent_attr) {
    var dragger = tableDragger(document.querySelector(selector), {
        mode: "row",
    });

    dragger.on("drop", function(from, to, el) {
        var table = $(el), items = [];

        table.find("tbody > tr").each(function(ind, item) {
            items.push($(item).data("id"));
        });

        $.post(table.data("url"), {
            parent_id: table.data(parent_attr),
            items: items
        }, function(result) {
            Materialize.toast("Сортировка выполнена успешно.", 2500);
        });
    });
}

function initLeanModal(cls) {
    $(cls).leanModal({
        dismissible: true, // Modal can be dismissed by clicking outside of the modal
        opacity: .5, // Opacity of modal background
        in_duration: 300, // Transition in duration
        out_duration: 200, // Transition out duration
        ready: function () {
            //alert('Ready');
        }, // Callback for Modal open
        complete: function () {
            //alert('Closed');
        } // Callback for Modal close
    });
}

function getElement(event) {
    var element
}

function getElementData(element, attr, return_element) {
    if (element.data(attr) != undefined) {
        return return_element ? element : element.data(attr);
    }

    return return_element ? element : element.parent().data(attr);
}

$(function() {
    // Pikadate datepicker
    $('.datepicker_new').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15, // Creates a dropdown of 15 years to control year
        closeOnSelect: false,
        closeOnClear: true
    });

    /* specturm colorpicker */
    if($(".colorpicker").length > 0){
        $(".colorpicker").spectrum({showAlpha: true, showPalette: true, showSelectionPalette: true, palette: [], localStorageKey: "spectrum.homepage", showInput: true,preferredFormat: "hex", maxSelectionSize: 20});
    }
});
