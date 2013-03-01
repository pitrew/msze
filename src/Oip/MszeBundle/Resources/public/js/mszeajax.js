
$.oip = $.oip || {}
$.oip.ajax = $.oip.ajax || {}

$.oip.ajax.getCities = function(pattern, container) {
    
    if (pattern == '')
    {
        container.html('');
    }
    else
    {
        $.get(
            Routing.generate('show_cities', { _format: 'json' }), 
            { s: escape(pattern) }, 
            function(data) {
                var found = '<ul class="sim_list_city">';
                for(var x = 0; x < data.length && x < 10; x++)
                {
                    found += '<li class="sim_c">' + data[x].name + ' - ' + data[x].district + '</li>';
                }
                if (data.length > 10)
                {
                    found += '<li>Oraz ' + (data.length - 10) + ' innych</li>';
                }

                if (data.length == 0)
                {
                    found += '<li>Nie ma jeszcze takiego miasta.</li>';
                }
                found += '</ul>';
                container.html(found);
          });
    }
}