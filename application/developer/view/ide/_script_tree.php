<script>

    //Init Tree-----------------------------------------------------------------    
    $(document).ready(function() {
        $('#file_browser').dynatree({
            initAjax: {
                url: simbola.url.action("developer/ide/getFileList")
            },
            onLazyRead: function(node) {
                node.appendAjax({
                    url: simbola.url.action("developer/ide/getFileList"),
                    data: {path: node.data.key},
                    type: 'POST'
                });
            },
            onActivate: function(node) {
                if(node.data.isFolder){
                    return;
                }
                currentFile = node.data.key;
                simbola.call.service('developer', 'ide', 'getFileContent', {path: node.data.key}, function(data) {
                    currentFileData = data;
                    if (data.status) {
                        closeForm();
                        //hide all                        
                        $('#display ._image').hide();
                        $('#display ._video').hide();
                        $(editor.getWrapperElement()).hide();
                        //display
                        simbola.log("info", "MIME Detected: " + data.mime);
                        if (data.mime.indexOf("image") >= 0) {
                            $('#display ._image').show();
                            $('#display ._image').attr('src', 'data:image/png;base64,' + data.data);
                        } else {
                            $(editor.getWrapperElement()).show();
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
                        }
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

</script>