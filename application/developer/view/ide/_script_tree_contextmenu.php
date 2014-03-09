<script>
//Context Menu--------------------------------------------------------------
    var command;
    function bindContextMenu(span) {
        $(span).contextMenu({menu: "webcode-context"}, function(action, el, pos) {
            var node = $.ui.dynatree.getNode(el);
            switch (action) {
                case "reload":                    
                    node.reloadChildren(function(node, isOk) {                        
                        node.expand();
                    });
                    break;
                case "cut":
                    command = 'move';
                    var selectedNodes = node.tree.getSelectedNodes();
                    $.map(selectedNodes, function(selectedNode) {
                        selectedNode.select(false);
                    });
                    node.select();
                    break;
                case "copy":
                    command = 'copy';
                    var selectedNodes = node.tree.getSelectedNodes();
                    $.map(selectedNodes, function(selectedNode) {
                        selectedNode.select(false);
                    });
                    node.select();
                    break;
                case "paste":
                    var selectedNodes = node.tree.getSelectedNodes();
                    if (command === 'copy' || command === 'move') {
                        $.map(selectedNodes, function(selectedNode) {
                            selectedNode.select(false);
                            moveFile(selectedNode, node, command);
                        });
                    }
                    command = null;
                    break;
                case "promote":
                    create_modal("Promote Files", "../prod", function() {
                        promoteFile(node, create_modal_data('name'));
                        create_modal_close();
                    }, 'promote');
                    break;
                case "reload_resource_cache":
                    loadResourceCache();
                    break;
                case "delete":
                    node.select(false);
                    confirm_modal("Deleteing file..", "Are you sure you want to delete the file, " + node.data.key, function() {
                        removeFile(node);
                        confirm_modal_close();
                    });
                    break;
                case "rename":
                    rename_modal("Rename file, " + node.data.key, node.data.title, function() {
                        renameFile(node, $('#rename_modal_value').val());
                        rename_modal_close();
                    });
                    break;
                case "new_file":
                    if (!node.data.isFolder) {
                        node = node.getParent();
                    }
                    create_modal("Create file in, " + node.data.key, "New File", function() {
                        createFile(node, create_modal_data('name'), 'file');
                        create_modal_close();
                    });
                    break;
                case "new_folder":
                    if (!node.data.isFolder) {
                        node = node.getParent();
                    }
                    create_modal("Create folder in, " + node.data.key, "New Folder", function() {
                        createFile(node, create_modal_data('name'), 'dir');
                        create_modal_close();
                    });
                    break;
                case "new_module":
                    var key = node.data.key;
                    if (key.match('^application$')) {
                        create_modal("Create new Module", "New Module", function() {
                            createModule(node, create_modal_data('name'),
                                    create_modal_data('purpose'));
                            create_modal_close();
                        }, 'module');
                    }
                    break;
                case "new_model":
                    var key = node.data.key;
                    if (key.match('application/[^/]*/model$')) {
                        create_modal("Create new Model", "New Model", function() {
                            createModel(node, node.parent.data.title,
                                    create_modal_data('lu'),
                                    create_modal_data('name'),
                                    create_modal_data('purpose'));
                            create_modal_close();
                        }, 'model');
                    }
                    break;
                case "new_service":
                    var key = node.data.key;
                    if (key.match('application/[^/]*/service$')) {
                        create_modal("Create new Service", "New Service", function() {
                            createService(node, node.parent.data.title,
                                    create_modal_data('lu'),
                                    create_modal_data('model'),
                                    create_modal_data('name'),
                                    create_modal_data('purpose'));
                            create_modal_close();
                        }, 'service');
                    }
                    break;
                case "new_controller":
                    var key = node.data.key;
                    if (key.match('application/[^/]*/controller$')) {
                        create_modal("Create new Controller", "New Controller", function() {
                            createController(node, node.parent.data.title,
                                    create_modal_data('lu'),
                                    create_modal_data('model'),
                                    create_modal_data('service'),
                                    create_modal_data('name'),
                                    create_modal_data('purpose'))
                            create_modal_close();
                        }, 'controller');
                    }
                    break;
                case "open_dbsetup":
                    var key = node.data.key;
                    //open DB SETUP...
                    if (key.match('application/[^/]*/database$')) {
                        arr = key.split("/");
                        openForm(simbola.url.action('/developer/ide/dbSetup',{module:arr[1]}));
                    }
                    break;
                case "new_logical_unit":
                    var key = node.data.key;
                    //DB LOGICAL UNIT
                    if (key.match('application/[^/]*/database$')) {
                        arr = key.split("/");
                        create_modal("Create new Logical Unit", "New Logical Unit", function() {
                            createLU(node, arr[1], create_modal_data('name'));
                            create_modal_close();
                        });
                    }
                    break;
                case "new_db_table":
                    var key = node.data.key;
                    //DB LOGICAL UNIT
                    if (key.match('application/[^/]*/database/[^/]*/table$')) {
                        arr = key.split("/");
                        create_modal("Create new Table", "New Table", function() {
                            createDbTable(node, arr[1], arr[3], create_modal_data('name'), create_modal_data('purpose'));
                            create_modal_close();
                        },'db-table');
                    }
                    break;
                case "all_logical_unit_execute":
                    var key = node.data.key;
                    //DB LOGICAL UNIT
                    arr = key.split("/");
                    if (key.match('application/[^/]*/database$')) {
                        executeAllLUs(node, arr[1]);
                    }
                    break;
                case "obj_execute":
                    var key = node.data.key;
                    //DB LOGICAL UNIT
                    arr = key.split("/");
                    if (key.match('application/[^/]*/database/[^/]*/[^/]*/[^/]*.php$')) {
                        executeObj(node, arr[1], arr[3], arr[4], arr[5].replace(".php", ""));
                    }
                    break;
                case "lu_execute":
                    var key = node.data.key;
                    //DB LOGICAL UNIT
                    arr = key.split("/");
                    if (key.match('application/[^/]*/database/[^/]*$')) {
                        executeLU(node, arr[1], arr[3]);
                    }
                    break;
            }
        }, function(el) {
            var node = $.ui.dynatree.getNode(el);
            var key = node.data.key;
            //LU
            if (key.match('application/[^/]*/database/[^/]*$')) {
                $('#webcode-context .lu_execute').show();
            } else {
                $('#webcode-context .lu_execute').hide();
            }
            //OBJ
            if (key.match('application/[^/]*/database/[^/]*/[^/]*/[^/]*.php$')) {
                $('#webcode-context .obj_execute').show();
            } else {
                $('#webcode-context .obj_execute').hide();
            }
            //DB MODEL
            if (key.match('application/[^/]*/database$')) {
                $('#webcode-context .open_dbsetup').show();
                $('#webcode-context .all_logical_unit_execute').show();
                $('#webcode-context .new_logical_unit').show();
                
            } else {
                $('#webcode-context .open_dbsetup').hide();
                $('#webcode-context .all_logical_unit_execute').hide();
                $('#webcode-context .new_logical_unit').hide();
            }
            //DB TABLE
            if (key.match('application/[^/]*/database/[^/]*/table$')) {
                $('#webcode-context .new_db_table').show();
            } else{
                $('#webcode-context .new_db_table').hide();   
            }
            //MODULE
            if (key.match('^application$')) {
                $('#webcode-context .new_module').show();
            } else {
                $('#webcode-context .new_module').hide();
            }
            //MODEL
            if (key.match('application/[^/]*/model$')) {
                $('#webcode-context .new_model').show();
            } else {
                $('#webcode-context .new_model').hide();
            }
            //SERVICE
            if (key.match('application/[^/]*/service')) {
                $('#webcode-context .new_service').show();
            } else {
                $('#webcode-context .new_service').hide();
            }
            //CONTROLLER
            if (key.match('application/[^/]*/controller')) {
                $('#webcode-context .new_controller').show();
            } else {
                $('#webcode-context .new_controller').hide();
            }
            //PROMOTE
            if (key.match('application*')) {
                $('#webcode-context .promote').show();
            } else {
                $('#webcode-context .promote').hide();
            }
            //RESOURCE CACHE
            if (key.match('resource*')) {
                $('#webcode-context .reload_resource_cache').show();
            } else {
                $('#webcode-context .reload_resource_cache').hide();
            }
        });
    }
</script>