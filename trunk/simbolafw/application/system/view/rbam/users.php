<?php
$grid = new application\system\library\flexigrid\WidgetFlexiGrid('user_list',true);
$grid->title = "User List";
$grid->usepager = true;
$grid->sortname = "user_name";
$grid->sortorder = "asc";

$grid->setDirect(true);
$grid->setDataSource('system', 'auth', 'system_user', 'user_id');
$grid->addColModel("ID", "user_id", "10", true, 'left');
$grid->addColModel("User Name", "user_name", "130", true, 'left');
$grid->addColModel("Active", "user_active", "70", true, 'left');

$grid->addSearchItem("User Name", 'user_name', true);

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
                        data.username = selectedItems[0].user_name;                        
                    }
                    openDialog('Change password','rbam/dlgUserChangePassword',{
                        modal:true,
                        draggable:false
                    },data);
                break;                
            case 'Unregister':                                             
                    $.each(selectedItems,function(index,user){
                        var url = "rbam/userUnregister";  
                        $.post(url,{username:user.user_name},function(data){
                            $.pnotify(data);  
                            $('#user_list').flexReload();
                        },'json');
                    });
                break;
            case 'Activate':                    
                    $.each(selectedItems,function(index,user){
                        var url = "rbam/userActivate";  
                        $.post(url,{username:user.user_name},function(data){
                            $.pnotify(data);  
                            $('#user_list').flexReload();
                        },'json');
                    });
                break;
            case 'Deactivate':                    
                    $.each(selectedItems,function(index,user){
                        var url = "rbam/userDeactivate";  
                        $.post(url,{username:user.user_name},function(data){                            
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
