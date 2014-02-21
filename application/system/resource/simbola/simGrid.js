(function($){
    $.fn.simGrid = function(module, lu, name, columns){    
        var self = this;
        var data = {
            "module" : module,
            "lu" : lu,
            "name" : name,
            "columns" : columns
        };
        $.post("system/simGrid/data", data, function (response){
            $(self).find("tdata").html(response);
        });
    };    
})(jQuery);