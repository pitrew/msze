$.oip = $.oip || {}

$.oip.escape = function(text) {
    return $('<div/>').text(text).html();
}

String.prototype.contains = function(it) { return this.indexOf(it) != -1; };