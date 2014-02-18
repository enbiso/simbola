<div id="rename_modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="rename_modal_label"></h3>
            </div>
            <div class="modal-body">               
                <div class="input-group">
                    <span class="input-group-addon">Rename to</span>
                    <input class="form-control" id="rename_modal_value" type="text"/>           
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" id="rename_modal_rename">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    function rename_modal(title,value,post_func){    
        $('#rename_modal_label').text(title);        
        $('#rename_modal_value').val(value);        
        $('#rename_modal_rename').unbind('click');
        $('#rename_modal_rename').bind('click',post_func);
        $('#rename_modal').modal('show');
    }
    
    function rename_modal_close(){
        $('#rename_modal').modal('hide');
    }
</script>