<div id="confirm_modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="confirm_modal_label"></h3>
            </div>
            <div class="modal-body">
                <p id="confirm_modal_body"></p>
            </div>
            <div class="modal-footer">
                <span class="alert" id="confirm_modal_alert"></span>
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-primary" id="confirm_modal_confirm">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    function confirm_modal(label,body,post_func){
        $('#confirm_modal_label').text(label);
        $('#confirm_modal_body').text(body);
        $('#confirm_modal_alert').hide();
        $('#confirm_modal_confirm').unbind('click');
        $('#confirm_modal_confirm').bind('click',post_func);
        $('#confirm_modal').modal('show');
    }
    
    function confirm_model_alert(message){
        $('#confirm_modal_alert').text(message);
        $('#confirm_modal_alert').show();
    }
    
    function confirm_modal_close(){
        $('#confirm_modal').modal('hide');
    }
</script>