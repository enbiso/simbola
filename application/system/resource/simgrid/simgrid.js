(function($) {
    $.fn.simGrid = function(opts) {
        //init table obj
        var grid = this;
        var table = this.find('table.simGrid-Table');
        //pagination
        if (opts.pageLength === undefined) {
            opts.pageLength = 20;
        }
        if (opts.page === undefined) {
            opts.page = 1;
        }
        //edit
        if (opts.editableColumns === undefined) {
            opts.editableColumns = {};
        }
        //init actions
        if (opts.actions === undefined) {
            opts.actions = [];
        }        
        //save data
        $(grid).data('opts', opts);
        //post to get info
        $(grid).simGrid_Reload();

        //event bindings
        this.find('.simGrid-Reload').bind('click', function(e) {
            $(grid).simGrid_Reload();
        });
        this.find('.simGrid-Pager-Prev').bind('click', function(e) {
            var page = parseInt(grid.find('.simGrid-Pager-Counter').val());
            if (page > 1) {
                var opts = grid.data('opts');
                opts.page = page - 1;
                grid.data('opts', opts);
                $(grid).simGrid_Reload();
            }
        });
        this.find('.simGrid-Pager-Next').bind('click', function(e) {
            var page = parseInt(grid.find('.simGrid-Pager-Counter').val());
            if (page < grid.data('pageCount')) {
                var opts = grid.data('opts');
                opts.page = page + 1;
                grid.data('opts', opts);
                $(grid).simGrid_Reload();
            }
        });
        this.find('.simGrid-Pager-Counter').bind('change', function(e) {
            var opts = grid.data('opts');
            opts.page = grid.find('.simGrid-Pager-Counter').val();
            grid.data('opts', opts);
            $(grid).simGrid_Reload();
        });        
        this.find('.simGrid-Search-Search').bind('click', function(e){
            var opts = grid.data('opts');
            opts.page = 1;
            var searchObj = $(grid).find('#' + opts.id + "_search_modal_form").serializeObject();
            opts.searchConditions = searchObj.data;
            grid.data('opts', opts);
            $(grid).simGrid_Reload();
        });
        return grid;
    };
    $.fn.simGrid_Reload = function() {
        var grid = this;
        var table = $(grid).find('table.simGrid-Table')[0];
        opts = $(grid).data('opts');
        //init post data
        var data = {
            source: opts.source,
            columns: opts.columns,
            conditions: opts.conditions,
            order: opts.order,
            searchConditions: opts.searchConditions,
            limit: opts.pageLength,
            offset: (opts.page - 1) * opts.pageLength
        };
        $(table).find('tbody').html("loading...");
        $.post(simbola.url.action("system/simGrid/data"), data, function(data) {
            var content = "";
            $.each(data.rows, function(rowIndex, row) {
                content += '<tr class=simGrid-Row" data-row="' + (rowIndex + 1) + '">';
                $.each(opts.columns, function(colIndex, column) {
                    if (opts.hiddenColumns.indexOf(column) < 0) {
                        content += '<td class="simGrid-Column" data-col="' + column + '">';
                        if (row[column] === undefined) {
                            //actions
                            if (opts.actions[column] === undefined) {
                                content += '';
                            } else {
                                var action = opts.actions[column];
                                $.each(data.columns, function(actCIndex, actColumn) {
                                    var find = "%" + actColumn + "%";
                                    action = action.replace(new RegExp(find, 'g'), row[actColumn]);
                                    var find = "{{" + actColumn + "}}";
                                    action = action.replace(new RegExp(find, 'g'), row[actColumn]);
                                });
                                action = action.replace(new RegExp("%__row__%", 'g'), (rowIndex + 1));
                                action = action.replace(new RegExp("{{__row__}}", 'g'), (rowIndex + 1));
                                action = action.replace(new RegExp("%__col__%", 'g'), column);
                                action = action.replace(new RegExp("{{__col__}}", 'g'), column);
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
            //pages
            var pageCount = Math.ceil(data.count / data.query.limit);
            grid.data('pageCount', pageCount);
            var selectedPage = (data.query.offset / data.query.limit) + 1;
            grid.find('.simGrid-Pager-Counter').html("");
            for (var page = 1; page <= pageCount; page++) {
                var optElem = jQuery('<option>', {
                    value: page
                }).html(page);
                if (page === selectedPage) {
                    optElem.prop('selected', true);
                }
                optElem.appendTo(grid.find('.simGrid-Pager-Counter'));
            }
        }, 'json');
    }
})(jQuery);