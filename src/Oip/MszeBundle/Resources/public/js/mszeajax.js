
$.oip = $.oip || {}
$.oip.ajax = $.oip.ajax || {}

$.oip.ajax.getCities = function(pattern, callback) {
    
    if (pattern == '')
    {
        container.html('');
    }
    else
    {
        $.oip.ajax.get(('show_cities'), { _format: 'json' }, 
            { s: escape(pattern) }, callback);
    }
}

$.oip.ajax.getJSON = function(route, route_params, data, callback) {
    $.get(
        Routing.generate(route, route_params), 
        data, 
        function(data) 
        {                
            callback(data); 
        }, 'json');
}

$.oip.ajax.get = function(route, route_params, data, callback) {
    $.get(
        Routing.generate(route, route_params), 
        data, 
        function(data) 
        {                
            callback(data); 
        });
}

$.oip.ajax.postJSON = function(route, route_params, data, callback) {
    $.post(
        Routing.generate(route, route_params), 
        data, 
        function(data) 
        {                
            callback(data); 
        },
        'json');
}

$.oip.ajax.post = function(route, route_params, data, callback) {
    $.post(
        Routing.generate(route, route_params), 
        data, 
        function(data) 
        {                
            callback(data); 
        });
}