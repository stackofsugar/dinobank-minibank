/// <reference path="F:/jquery-3.6.0.min.js" />

function mousedownPasswordPeek(that) {
    let input_elem = $(that).siblings(".form-control");
    input_elem.attr("type", "text");
    $(that).find("i").removeClass("bi-eye-fill");
    $(that).find("i").addClass("bi-eye-slash-fill");
}
function mouseupPasswordPeek(that) {
    let input_elem = $(that).siblings(".form-control");
    input_elem.attr("type", "password");
    $(that).find("i").removeClass("bi-eye-slash-fill");
    $(that).find("i").addClass("bi-eye-fill");
}
