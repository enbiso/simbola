(function($) {
    $.fn.simGrid = function(module, lu, name, columns, hiddenColumns, actions, conditions) {
        //init table obj
        var table = this;
        //init post data
        var data = {
            module: module,
            lu: lu,
            name: name,
            columns: columns,
            conditions: conditions
        };
        //init actions
        if (actions === undefined) {
            actions = {};
        }
        //post to get info
        $.post("/system/simGrid/data", data, function(data) {
            var content = "";
            $.each(data.rows, function(rowIndex, row) {
                content += '<tr>';
                $.each(columns, function(colIndex, column) {
                    if (hiddenColumns.indexOf(column)<0) {
                        content += '<td>';
                        if (row[column] === undefined) {
                            //actions
                            if (actions[column] === undefined) {
                                content += '';
                            } else {
                                var action = actions[column];
                                $.each(data.columns, function(actCIndex, actColumn) {
                                    var find = "%" + actColumn + "%";
                                    action = action.replace(new RegExp(find, 'g'), row[actColumn]);
                                });
                                content += action;
                            }
                        } else {
                            content += row[column];
                        }
                        content += '</td>';
                    }
                });
                content += '</tr>';
            });
            $(table).find('tbody').html(content);
        }, 'json');
    };
})(jQuery);