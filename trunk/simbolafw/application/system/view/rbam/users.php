<?php
$grid = new application\system\library\flexigrid\WidgetFlexiGrid('user_list',true);
$grid->title = "User List";
$grid->usepager = true;
$grid->sortname = "user_id";
$grid->sortorder = "asc";

$grid->setDirect(true);
$grid->setDataSource('system', 'auth', 'tbl_user', 'user_id');
$grid->addColModel("User ID", "user_id", "50", true, 'left');
$grid->addColModel("Username", "user_name", "150", true, 'left');
$grid->addColModel("Status", "_state", "70", true, 'left');

$grid->addSearchItem("Username", 'username', true);

$grid->addButton('Register', 'auth_action');
$grid->addButton('Unregister', 'auth_action');
$grid->addButton('Change Password', 'auth_action');
$grid->addButton('Activate', 'auth_action');
$grid->addButton('Deactivate', 'auth_action');
echo $grid->getDisplayData();
?>
<script>
    function auth_action(name,grid){     
        var selectedItems = $('#user_list').flexReturnSelected();
        switch (name) {
            case 'Register':                    
                    openDialog('Register','rbam/dlgUserRegister',{
                        modal:true,
                        draggable:false,
                        close:function(){
                            $('#user_list').flexReload();
                        }
                    });
                break;
            case 'Change Password':                    
                    data = {username:''};
                    if(selectedItems.length > 0){
                        data.username = selectedItems[0].user;                        
                    }
                    openDialog('Change password','rbam/dlgUserChangePassword',{
                        modal:true,
                        draggable:false
                    },data);
                break;                
            case 'Unregister':                                             
                    $.each(selectedItems,function(index,user){
                        var url = "rbam/userUnregister";  
                        $.post(url,{username:user.user},function(data){
                            $.pnotify(data);  
                            $('#user_list').flexReload();
                        },'json');
                    });
                break;
            case 'Activate':                    
                    $.each(selectedItems,function(index,user){
                        var url = "rbam/userActivate";  
                        $.post(url,{username:user.user},function(data){
                            $.pnotify(data);  
                            $('#user_list').flexReload();
                        },'json');
                    });
                break;
            case 'Deactivate':                    
                    $.each(selectedItems,function(index,user){
                        var url = "rbam/userDeactivate";  
                        $.post(url,{username:user.user},function(data){                            
                            $.pnotify(data);                            
                            $('#user_list').flexReload();
                        },'json');
                    });
                break;
            default:
                break;
            }
        }
</script>
