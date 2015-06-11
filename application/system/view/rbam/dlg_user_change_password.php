<form id="auth_change_password_form">        
    <label for="username" class="sim-label sim-block">Username</label>
    <br/>
    <input type="text" name="username" id="username" class="sim-block" value="<?php echo $this->username; ?>" placeholder="Username"/>    
    <br/>
    <label for="password" class="sim-label sim-block">Password</label>
    <br/>
    <input type="password" name="password" id="password" class="sim-block" placeholder="Password"/>
    <br/>
    <label for="password_repeat" class="sim-label sim-block">Password Repeat</label>
    <br/>
    <input type="password" name="password_repeat" id="password_repeat" class="sim-block" placeholder="Password Repeat"/>
    <label class="message sim-label sim-block"></label>
    <hr/>
    <input type="button" class="reg_button" value="Change"/>
</form>


<script>
    $('#auth_change_password_form input.reg_button').button().bind('click', function(){
        url = "rbam/userChangePassword";         
        $('#auth_change_password_form label.message').removeClass("ui-state-error").removeClass("ui-state-highlight").html("");             
        var post_data = $('#auth_change_password_form').serialize();        
        $.post(url, post_data, function(data){
            if(data.type == 'success'){
                uiclass = 'ui-state-highlight';
                $('#auth_change_password_form').find("input[type=text], input[type=password]").val("");
            }else{
                uiclass = 'ui-state-error';
            }
            $('#auth_change_password_form label.message').html(data.text);
            $('#auth_change_password_form label.message').addClass(uiclass);
            new PNotify(data);     
        },'json');          
        return false;
    });
</script>