<form id="role_register_form" class="sim-center">        
    <label for="rolename" class="sim-label sim-block">Role Name</label>
    <p><input type="text" name="rolename" id="rolename"/></p>
    <label class="message sim-label sim-block"></label>
    <input type="button" class="reg_button" value="Create"/>
</form>
<script>    
    $('#role_register_form input.reg_button').button().bind('click', function(){        
        url = "rbam/roleRegister";         
        $('#role_register_form label.message').removeClass("ui-state-error").removeClass("ui-state-highlight").html("");             
        var post_data = $('#role_register_form').serialize();        
        $.post(url, post_data, function(data){
            if(data.type == 'success'){
                uiclass = 'ui-state-highlight';
                $('#role_register_form').find("input[type=text], input[type=password]").val("");
            }else{
                uiclass = 'ui-state-error';
            }
            $('#role_register_form label.message').html(data.text);
            $('#role_register_form label.message').addClass(uiclass)
            new PNotify(data);               
        },'json');          
        return false;
    });
</script>