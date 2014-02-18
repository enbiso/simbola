<form id="auth_register_form" class="sim-center">        
    <label for="username" class="sim-label sim-block"><b>Username</b><br/>
        <i>User Identity used to uniquely identify the user</i></label>
    <p><input type="text" name="username" id="username"/> </p>
    <label class="message sim-label sim-block"></label>
    <input type="button" class="reg_button" value="Register"/>
</form>


<script>
    $('#auth_register_form input.reg_button').button().bind('click', function(){
        url = "rbam/userRegister";         
        $('#auth_register_form label.message').removeClass("ui-state-error").removeClass("ui-state-highlight").html("");             
        var post_data = $('#auth_register_form').serialize();        
        $.post(url, post_data, function(data){
            if(data.type == 'success'){
                uiclass = 'ui-state-highlight';
                $('#auth_register_form').find("input[type=text], input[type=password]").val("");
            }else{
                uiclass = 'ui-state-error';
            }
            $('#auth_register_form label.message').html(data.text);
            $('#auth_register_form label.message').addClass(uiclass)
            $.pnotify(data);               
        },'json');          
        return false;
    });
</script>