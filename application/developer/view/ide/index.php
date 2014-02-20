<div class="row">
    <div class="col-lg-3" id="file_browser" style="border-right: 1px solid silver">
    </div>
    <div class="col-lg-8" id="display">        
        <div class="_form"></div>
        <div class="_editor"></div>
    </div>
</div>

<?php $this->controller->pview('ide/_confirm'); ?>
<?php $this->controller->pview('ide/_rename'); ?>
<?php $this->controller->pview('ide/_create'); ?>

<ul id="webcode-context" class="contextMenu">    
    <li class="new_module"><a href="#new_module">New Module</a></li>

    <li class="new_model"><a href="#new_model">New Model</a></li>
    <li class="new_service"><a href="#new_service">New Service</a></li>
    <li class="new_controller"><a href="#new_controller">New Controller</a></li>

    <li class="open_dbsetup"><a href="#open_dbsetup">DB Setup...</a></li>
    <li class="new_logical_unit"><a href="#new_logical_unit">New LU</a></li>
    <li class="all_logical_unit_execute"><a href="#all_logical_unit_execute">Execute All LUs</a></li>
    <li class="obj_execute"><a href="#obj_execute">Execute Obj</a></li>
    <li class="lu_execute"><a href="#lu_execute">Execute LU</a></li>

    <li class="separator"></li>
    <li class="reload"><a href="#reload">Reload</a></li>
    <li class="separator"></li>
    <li class="new_file"><a href="#new_file">New File</a></li>
    <li class="new_folder"><a href="#new_folder">New Folder</a></li>
    <li class="rename separator"><a href="#rename">Rename</a></li>
    <li class="upload separator"><a href="#upload">Upload</a></li>  
    <li class="download"><a href="#download">Download</a></li>  
    <li class="promote separator"><a href="#promote">Promote</a></li>  
    <li class="cut separator"><a href="#cut">Cut</a></li>
    <li class="copy"><a href="#copy">Copy</a></li>
    <li class="paste"><a href="#paste">Paste</a></li>
    <li class="delete separator"><a href="#delete">Delete</a></li>  
</ul>
<style>
    .CodeMirror { border: 1px solid #eee; height: auto;}
    .CodeMirror-scroll { overflow-y: hidden; overflow-x: auto;  }
    .busy {  cursor: wait !important;   }  
    .contextMenu{ font: 12px "Trebuchet MS", sans-serif; margin: 0px;}    
    #editNode{
        font-size: 10px "Trebuchet MS", sans-serif;
        border: 1px solid #eee;        
    }
</style>
<script>
    //Cursor--------------------------------------------------------------------
    function set_cursor_auto() {
        if ($('body').hasClass('busy')) {
            $('body').removeClass('busy');
        }
    }

    function set_cursor_busy() {
        set_cursor_auto();
        $('body').addClass('busy');
    }
    //open/close form
    function openForm(url) {
        $('#display ._editor').hide();
        $('#display ._form').load(url);
    }
    function closeForm() {
        $('#display ._form').html("");
        $('#display ._editor').show();
    }
    //execute All LUs
    function executeAllLUs(node, moduleName) {
        simbola.call.service('system', 'dbsetup', 'setupModule', {module: moduleName}, function(data) {
            $.pnotify({
                title: 'Executed',
                text: 'Executed with success'
            });
        });
    }
    function executeLU(node, moduleName, luName) {
        simbola.call.service('system', 'dbsetup', 'setupLu', {module: moduleName, lu: luName}, function(data) {
            $.pnotify({
                title: 'Executed',
                text: 'Executed with success'
            });
        });
    }
    function executeObj(node, moduleName, luName, objType, objName) {
        simbola.call.service('system', 'dbsetup', 'setupObj', {module: moduleName, lu: luName, type: objType, name: objName}, function(data) {
            $.pnotify({
                title: 'Executed',
                text: 'Executed with success'
            });
        });
    }
    //create LU
    function createLU(node, module, lu) {
        set_cursor_busy();
        simbola.call.service('system', 'dbsetup', 'createLu', {module: module, name: lu}, function(data) {
            node.reloadChildren(function(node, isOk) {
                node.expand();
                set_cursor_auto();
            });
        });
    }
    //CReate Module
    function createModule(node, module, purpose) {
        set_cursor_busy();
        if (purpose === undefined) {
            purpose = module;
        }
        simbola.call.service('developer', 'module', 'create', {module: module, purpose: purpose}, function(data) {
            node.reloadChildren(function(node, isOk) {
                node.expand();
                set_cursor_auto();
            });
        });
    }
    //Create Model
    function createModel(node, module, lu, model, purpose) {
        set_cursor_busy();
        if (purpose === undefined) {
            purpose = module;
        }
        var params = {
            module: module,
            purpose: purpose,
            lu: lu,
            model: model
        };
        simbola.call.service('developer', 'module', 'createModel', params, function(data) {
            node.reloadChildren(function(node, isOk) {
                node.expand();
                set_cursor_auto();
            });
        });
    }
    //Create Service
    function createService(node, module, lu, model, service, purpose) {
        set_cursor_busy();
        if (purpose === undefined) {
            purpose = service;
        }
        var params = {
            module: module,
            purpose: purpose,
            lu: lu,
            model: model,
            service: service
        };

        simbola.call.service('developer', 'module', 'createService', params, function(data) {
            node.reloadChildren(function(node, isOk) {
                node.expand();
                set_cursor_auto();
            });
        });
    }
    //Create Controller
    function createController(node, module, lu, model, service, controller, purpose) {
        set_cursor_busy();
        if (purpose === undefined) {
            purpose = service;
        }
        var params = {
            module: module,
            purpose: purpose,
            lu: lu,
            model: model,
            service: service,
            controller: controller
        };

        simbola.call.service('developer', 'module', 'createController', params, function(data) {
            node.reloadChildren(function(node, isOk) {
                node.expand();
                set_cursor_auto();
            });
        });
    }
    //Create--------------------------------------------------------------------
    function createFile(node, filename, filetype) {
        set_cursor_busy();
        simbola.call.service('developer', 'ide', 'createFile', {path: node.data.key, name: filename, type: filetype}, function(data) {
            node.reloadChildren(function(node, isOk) {
                node.expand();
                set_cursor_auto();
            });
        });
    }

    //Rename--------------------------------------------------------------------
    function renameFile(node, newName) {
        set_cursor_busy();
        simbola.call.service('developer', 'ide', 'renameFile', {old_path: node.data.key, new_name: newName}, function(data) {
            node.getParent().reloadChildren(function(node, isOk) {
                node.getParent().expand();
                set_cursor_auto();
            });
        });
    }
    
    //Promote-------------------------------------------------------------------
    function promoteFile(node, promotePath){
        set_cursor_busy();
        simbola.call.service('developer', 'ide', 'promoteFile', {path: node.data.key, promotePath: promotePath}, function(data) {
            set_cursor_auto();
            $.pnotify({title:'Promote files', text:'Files promoted to the location provided.'});            
        });
    }

    //Context Menu--------------------------------------------------------------
    var command;
    function bindContextMenu(span) {
        $(span).contextMenu({menu: "webcode-context"}, function(action, el, pos) {
            var node = $.ui.dynatree.getNode(el);
            switch (action) {
                case "reload":
                    set_cursor_busy();
                    node.reloadChildren(function(node, isOk) {
                        node.expand();
                        set_cursor_auto();
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
                    },'promote');
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
                        },'module');
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
                        openForm('/system/dbsetup/install[module:' + arr[1] + ']');
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
        });
    }
    ;

    //Auto complete-------------------------------------------------------------
    CodeMirror.commands.autocomplete = function(cm) {
        CodeMirror.simpleHint(cm, CodeMirror.javascriptHint);
    };

    //Editor init---------------------------------------------------------------
    var currentFile;
    var editor = CodeMirror($('#display ._editor').get(0), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        extraKeys: {"Ctrl-Space": "autocomplete"}
    });

    //Init Tree-----------------------------------------------------------------
    $(document).ready(function() {
        $('#file_browser').dynatree({
            initAjax: {
                url: "/developer/ide/getFileList"
            },
            onLazyRead: function(node) {
                node.appendAjax({
                    url: "/developer/ide/getFileList",
                    data: {path: node.data.key},
                    type: 'POST'
                });
            },
            onActivate: function(node) {
                currentFile = node.data.key;
                simbola.call.service('developer', 'ide', 'getFileContent', {path: node.data.key}, function(data) {
                    if (data.status) {
                        closeForm();
                        switch (currentFile.slice(-3).toLowerCase()) {
                            case "php":
                                editor.setOption("mode", "application/x-httpd-php");
                                break;
                            case "sql":
                                editor.setOption("mode", "text/x-plsql"/*"text/x-sql"*/);
                                break;
                            case ".js":
                                editor.setOption("mode", "text/javascript");
                                break;
                            case "css":
                                editor.setOption("mode", "text/css");
                                break;
                            default:
                                editor.setOption("mode", "text/plain");
                                break;
                        }
                        editor.setValue(atob(data.data));
                    } else {
                        alert('File failed to read.');
                    }
                    set_cursor_auto();
                });
            },
            onClick: function(node, event) {
                // Close menu on click
                if ($(".contextMenu:visible").length > 0) {
                    $(".contextMenu").hide();
                    //          return false;
                }
            },
            onKeydown: function(node, event) {
                // Eat keyboard events, when a menu is open
                if ($(".contextMenu:visible").length > 0)
                    return false;

                switch (event.which) {

                    // Open context menu on [Space] key (simulate right click)
                    case 32: // [Space]
                        $(node.span).trigger("mousedown", {
                            preventDefault: true,
                            button: 2
                        })
                                .trigger("mouseup", {
                                    preventDefault: true,
                                    pageX: node.span.offsetLeft,
                                    pageY: node.span.offsetTop,
                                    button: 2
                                });
                        return false;

                        // Handle Ctrl-C, -X and -V
                    case 67:
                        if (event.ctrlKey) { // Ctrl-C
                            copyPaste("copy", node);
                            return false;
                        }
                        break;
                    case 86:
                        if (event.ctrlKey) { // Ctrl-V
                            copyPaste("paste", node);
                            return false;
                        }
                        break;
                    case 88:
                        if (event.ctrlKey) { // Ctrl-X
                            copyPaste("cut", node);
                            return false;
                        }
                        break;
                }
            },
            /*Bind context menu for every node when it's DOM element is created.
             We do it here, so we can also bind to lazy nodes, which do not
             exist at load-time. (abeautifulsite.net menu control does not
             support event delegation)*/
            onCreate: function(node, span) {
                bindContextMenu(span);
            },
            dnd: {
                preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
                onDragStart: function(node) {
                    return true;
                },
                onDragEnter: function(node, sourceNode) {
                    if (node.data.isFolder) {
                        return ["over"];
                    } else {
                        return ["before", "after"];
                    }
                },
                onDrop: function(node, sourceNode, hitMode, ui, draggable) {
                    moveFile(sourceNode, node, 'move');
                }
            }
        });
    });

    function removeFile(node) {
        set_cursor_busy();
        simbola.call.service('developer', 'ide', 'removeFile', {path: node.data.key}, function(data) {
            node.getParent().reloadChildren(function(node, isOk) {
                node.getParent().expand();
                set_cursor_auto();
            });
        });
    }

    function moveFile(sourceNode, node, command) {
        set_cursor_busy();
        var post_data = {
            source_path: sourceNode.data.key,
            dest_path: (node.data.isFolder) ? node.data.key : node.getParent().data.key,
            method: command
        };
        simbola.call.service('developer', 'ide', 'moveFile', post_data, function(response) {
            if (response.status) {
                if (node.data.isLazy) {
                    sourceNode.getParent().reloadChildren(function() {
                        node.reloadChildren(function(node, isOk) {
                            node.expand();
                            set_cursor_auto();
                        });
                    });
                } else {
                    set_cursor_auto();
                }
            } else {
                set_cursor_auto();
                alert(response.message);
            }
        });
    }

    function call_action(action) {
        switch (action) {
            case 'save':
                _action_save();
                break;
        }
    }

    function _action_save() {
        if (currentFile === undefined) {
            return;
        }
        confirm_modal("Saving file..", "Are you sure you want to save the file, " + currentFile, function() {
            var post_data = {
                path: currentFile,
                data: editor.getValue()
            };
            set_cursor_busy();
            simbola.call.service('developer', 'ide', 'setFileContent', post_data, function(response) {
                if (response.status) {
                    confirm_model_alert("File Saved");
                    confirm_modal_close();
                } else {
                    confirm_model_alert("Error in saving the file");
                }
                set_cursor_auto();
            });
        });
    }
</script>