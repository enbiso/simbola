<script> 
    function call_action(action) {
        switch (action) {
            case 'save':
                _action_save();
                break;
        }
    }

    function _action_save() {
        if (currentFile !== undefined && (currentFileData.status === true) && (currentFileData.mime.indexOf('image') < 0)) {
            confirm_modal("Saving file..", "Are you sure you want to save the file, " + currentFile, function() {
                var post_data = {
                    path: currentFile,
                    data: btoa(editor.getValue())
                };
                set_cursor_busy();
                simbola.call.service('developer', 'ide', 'setFileContent', post_data, function(response) {
                    if (response.status) {
                        $.pnotify({'title' : 'Saved Successfully', 'text' : currentFile + ' saved!'});
                        confirm_modal_close();
                    } else {
                        confirm_model_alert("Error in saving the file");
                    }
                    set_cursor_auto();
                });
            });
        }
    }
</script>