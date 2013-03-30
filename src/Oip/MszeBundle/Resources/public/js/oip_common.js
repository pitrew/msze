$.oip = $.oip || {}

$.oip.escape = function(text) {
    return $('<div/>').text(text).html();
}