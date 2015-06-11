<?php
$grid = new application\system\library\flexigrid\WidgetFlexiGrid('role_list',true);
$grid->title = "Role List";
$grid->usepager = true;
$grid->sortname = "role";
$grid->sortorder = "asc";

$grid->setDirect(true);
$grid->setDataSource('system', 'auth', 'role', 'role');
$grid->addColModel("Role", "role", "130", true, 'left');
$grid->addColModel("Type", "type", "130", true, 'left');

$grid->addSearchItem("Role", 'role', true);

$grid->addButton('Register', 'role_action');
$grid->addButton('Unregister', 'role_action');
$grid->addButton('Enduser', 'role_action');
$grid->addButton('Access', 'role_action');

echo $grid->getDisplayData();
?>
<script>
    function role_action(name,grid){     
        var selectedItems = $('#role_list').flexReturnSelected();
        switch (name) {
            case 'Register':
                openDialog('Register','rbam/dlgRoleRegister',{
                    modal:true,
                    draggable:false,
                    close:function(){
                        $('#role_list').flexReload();
                    }
                });
                break;
            case 'Unregister':
                $.each(selectedItems,function(index,item){
                    var url = "rbam/roleUnregister";  
                    $.post(url,{rolename:item.role},function(data){
                        new PNotify(data);  
                        $('#role_list').flexReload();
                    },'json');
                });
                break;                
            case 'Enduser':
                $.each(selectedItems,function(index,item){
                    var url = "rbam/roleSetEnduser";  
                    $.post(url,{rolename:item.role},function(data){
                        new PNotify(data);  
                        $('#role_list').flexReload();
                    },'json');
                });
                break;     
            case 'Access':
                $.each(selectedItems,function(index,item){
                    var url = "rbam/roleSetAccess";  
                    $.post(url,{rolename:item.role},function(data){
                        new PNotify(data);  
                        $('#role_list').flexReload();
                    },'json');
                });
                break;                
            default:
                break;
            }
        }
</script>