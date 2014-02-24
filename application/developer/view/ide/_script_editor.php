<script>
    //Editor init---------------------------------------------------------------
    var currentFile;
    var currentFileData;
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
    
    //Auto complete-------------------------------------------------------------
    CodeMirror.commands.autocomplete = function(cm) {
        CodeMirror.simpleHint(cm, CodeMirror.javascriptHint);
    };


</script>