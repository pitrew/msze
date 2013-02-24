(function ($) {
    var methods = {
        show: function (hideIndicator) {
            return this.each(function () {
                var $this = $(this),
                data = $this.data('oipLoading');
                if (data == undefined)
                {
                    data = {};
                }
                if (data.isLoading == undefined || data.isLoading === false)
                {
                    data.isLoading = true;
                    data.position = $this.css('position');
                    $this.data('oipLoading', data);
                    $this.css('position', 'relative');

                    var my_zindex = $this.css('z-index');
                    if (my_zindex == 'auto')
                    {
                        my_zindex = 5;
                    }
                    else
                    {
                        my_zindex = my_zindex + 5;
                    }
                                
                    $this.append("<div class='oipLoadingOverlay' style='z-index:" + my_zindex +"'></div>");
                    if (hideIndicator !== true) {
                        $this.append("<div class='oipLoadingIndicator' style='z-index:" + (my_zindex + 1) +"'></div>");
                    }

                    $this.find('.oipLoadingOverlay').fadeIn('fast');
                    if (hideIndicator !== true) {
                        $this.find('.oipLoadingIndicator').fadeIn('fast');
                    }
                }
            })
        },

        hide: function () {
            return this.each(function () {
                var $this = $(this),
                data = $this.data('oipLoading');

                if (data != undefined)
                {
                    data.isLoading = false;
                    $this.data('oipLoading', data);
                }

                $this.find('.oipLoadingIndicator').remove();
                $this.find('.oipLoadingOverlay').fadeOut('fast', function() {
                    $(this).remove();
                    if (data != undefined)
                    {
                        $this.css('position', data.position);
                    }
                });
            })
        },
     
        isLoading: function() {
            return this.each(function () {
                var $this = $(this),
                data = $this.data('oipLoading');
                
                if (data != undefined) {
                    return data.isLoading;
                }
                return false;
            });
        }
     };
     
    jQuery.fn.oipLoadingActive = function() {
        var $this = $(this),
        data = $this.data('oipLoading');

        if (data != undefined) {
            return data.isLoading;
        }
        return false;
    };
    
    jQuery.fn.oipLoading = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method "' + method + '" does not exist on jQuery.oipLoading');
        }
        return undefined;
    };
})(jQuery);

