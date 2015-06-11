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
            var post_data = {
                path: currentFile,
                data: btoa(editor.getValue())
            };
            set_cursor_busy();
            simbola.call.service('developer', 'ide', 'setFileContent', post_data, function(response) {
                if (response.status) {
                    $('._editor > .CodeMirror').animate({"border-color": "green", "background-color" : '#f0fff0'}, 'slow');                    
                    $('._editor > .CodeMirror').animate({"border-color": "#eee", "background-color" : 'white'}, 'slow');
                } else {
                    new PNotify({'text' : 'Error saving :(', type: 'error'});
                }
                set_cursor_auto();
            });            
        }
    }
</script>