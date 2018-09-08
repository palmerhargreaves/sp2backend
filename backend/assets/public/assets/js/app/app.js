/**
 * Created by kostet on 05.07.2017.
 */
$(function() {
    /*if ($(".ckeditor").length) {
        CKEDITOR.replace(".ckeditor");
    }*/

});

function extend(subc, superc, overrides) {
    var F = function() {};
    F.prototype=superc.prototype;
    subc.prototype=new F();
    subc.prototype.constructor=subc;
    subc.superclass=superc.prototype;
    if (superc.prototype.constructor == Object.prototype.constructor) {
        superc.prototype.constructor=superc;
    }

    if (overrides) {
        for (var i in overrides) {
            subc.prototype[i]=overrides[i];
        }
    }
}
