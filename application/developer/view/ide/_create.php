<div id="create_modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="create_modal_label"></h3>
            </div>
            <div class="modal-body">                
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
                    <input class="form-control" id="create_modal_name_value" type="text"/>           
                </div>                
                <div class="input-group modal_grp" id="create_modal_grp_lu">                                                                            
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th-list"></span></span>
                    <input class="form-control" id="create_modal_lu_value" type="text" placeholder="Logical Unit of Model"/>           
                </div>                
                <div class="input-group modal_grp" id="create_modal_grp_model">                                                        
                    <span class="input-group-addon"><span class="glyphicon glyphicon-list-alt"></span></span>
                    <input class="form-control" id="create_modal_model_value" type="text" placeholder="Model Name"/>           
                </div>                
                <div class="input-group modal_grp" id="create_modal_grp_service">                                                        
                    <span class="input-group-addon"><span class="glyphicon glyphicon-tasks"></span></span>
                    <input class="form-control" id="create_modal_service_value" type="text" placeholder="Service Name"/>           
                </div>                
                <div class="input-group modal_grp" id="create_modal_grp_purpose">                                    
                    <span class="input-group-addon"><span class="glyphicon glyphicon-file"></span></span>
                    <input class="form-control" id="create_modal_purpose_value" type="text" placeholder="Purpose of the object"/>           
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" id="create_modal_create">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    var modal_type_gbl;
    function create_modal(title, value, post_func, modal_type) {
        modal_type_gbl = modal_type;
        $('#create_modal_label').text(title);
        $('#create_modal_name_value').val(value);
        $('#create_modal .modal_grp').hide();
        switch(modal_type){
            case 'controller':                                
                $('#create_modal_grp_service').show();
            case 'service':                
                $('#create_modal_grp_model').show();                                
            case 'model':                                       
                $('#create_modal_grp_lu').show();
            case 'module':
            case 'db_table':
                $('#create_modal_grp_purpose').show();
                break;
        }
        $('#create_modal_create').unbind('click');
        $('#create_modal_create').bind('click', post_func);
        $('#create_modal').modal('show');
    }
    
    function create_modal_data(key){        
        return $('#create_modal_' + key + "_value").val();
    }

    function create_modal_close() {
        $('#create_modal .modal_grp input').val("");
        $('#create_modal').modal('hide');
    }
    
    $(function(){
       $('#create_modal_name_value').keyup(function(){
           var value = $('#create_modal_name_value').val();
           var lu = $('#create_modal_lu_value').val();
           var modelVal = getModelValue(value, lu);
           $('#create_modal_model_value').val(modelVal); 
           $('#create_modal_service_value').val(value);
           var purpose = value.replace(/([A-Z])/g, ' $1').replace(/^./, function(str){ return str.toUpperCase(); }).trim() + ' ' 
                   + modal_type_gbl.charAt(0).toUpperCase() + modal_type_gbl.slice(1);
           $('#create_modal_purpose_value').val(purpose);
       }); 
       $('#create_modal_lu_value').keyup(function(){
           var lu = $('#create_modal_lu_value').val();
           var value = $('#create_modal_name_value').val();
           var modelVal = getModelValue(value, lu);
           $('#create_modal_model_value').val(modelVal); 
       });
    });
    
    function getModelValue(name, lu){
        if(name.lastIndexOf(lu)===0){ 
            name = name.replace(lu ,"");
            name = name.charAt(0).toLowerCase() + name.slice(1);
        }
        return name;
    }
</script>