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
        simbola.call.service('developer', 'dbsetup', 'setupModule', {module: moduleName}, function(data) {
            $.pnotify({
                title: 'Executed',
                text: 'Executed with success'
            });
        });
    }
    function executeLU(node, moduleName, luName) {
        simbola.call.service('developer', 'dbsetup', 'setupLu', {module: moduleName, lu: luName}, function(data) {
            $.pnotify({
                title: 'Executed',
                text: 'Executed with success'
            });
        });
    }
    function executeObj(node, moduleName, luName, objType, objName) {
        simbola.call.service('developer', 'dbsetup', 'setupObj', {module: moduleName, lu: luName, type: objType, name: objName}, function(data) {
            $.pnotify({
                title: 'Executed',
                text: 'Executed with success'
            });
        });
    }
    //create LU
    function createLU(node, module, lu) {
        set_cursor_busy();
        simbola.call.service('developer', 'dbsetup', 'createLu', {module: module, name: lu}, function(data) {
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
    //Create DbTable
    function createDbTable(node, module, lu, model, purpose) {
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
        simbola.call.service('developer', 'module', 'createDbTable', params, function(data) {
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
        simbola.call.service('developer', 'ide', 'renameFile', {key: node.data.key, new_name: newName}, function(data) {
            node.getParent().reloadChildren(function(node, isOk) {
                node.getParent().expand();
                set_cursor_auto();
            });
        });
    }

    //Promote-------------------------------------------------------------------
    function promoteFile(node, promotePath) {
        set_cursor_busy();
        simbola.call.service('developer', 'ide', 'promoteFile', {path: node.data.key, promotePath: promotePath}, function(data) {
            set_cursor_auto();
            $.pnotify({title: 'Promote files', text: 'Files promoted to the location provided.'});
        });
    }
    //Upgrade-------------------------------------------------------------------
    function upgradeModule(module, svn_url, svn_username, svn_password){
        set_cursor_busy();
        simbola.call.service('developer', 'module', 'upgradeFromSvn', {
            module: module,
            svn_username: svn_username,
            svn_password: svn_password,
            svn_url: svn_url
        }, function(data) {
            set_cursor_auto();
            $.pnotify({title: 'Upgrade module', text: 'Module upgraded from SVN provided.'});
        });
    }
    //Reload resource cache-----------------------------------------------------
    function loadResourceCache(node) {
        set_cursor_busy();
        simbola.call.service('developer', 'ide', 'loadResourceCache', {}, function(data) {
            set_cursor_auto();
            $.pnotify({title: 'Resource cache', text: 'Resource case reloaded successfully.'});
        });
    }
    //Remove File---------------------------------------------------------------
    function removeFile(node) {
        set_cursor_busy();
        simbola.call.service('developer', 'ide', 'removeFile', {path: node.data.key}, function(data) {
            node.getParent().reloadChildren(function(node, isOk) {
                node.getParent().expand();
                set_cursor_auto();
            });
        });
    }

    //Move File-----------------------------------------------------------------
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


</script>