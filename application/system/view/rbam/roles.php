<?php
$grid = new application\system\library\flexigrid\WidgetFlexiGrid('role_list',true);
$grid->title = "Role List";
$grid->usepager = true;
$grid->sortname = "item_name";
$grid->sortorder = "asc";

$grid->setDirect(true);
$grid->setDataSource('system', 'auth', 'role', 'item_name');
$grid->addColModel("Item Name", "item_name", "130", true, 'left');
$grid->addColModel("Item Type", "item_type", "130", true, 'left');

$grid->addSearchItem("Item Name", 'item_name', true);

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
                    $.post(url,{rolename:item.item_name},function(data){
                        $.pnotify(data);  
                        $('#role_list').flexReload();
                    },'json');
                });
                break;                
            case 'Enduser':
                $.each(selectedItems,function(index,item){
                    var url = "rbam/roleSetEnduser";  
                    $.post(url,{rolename:item.item_name},function(data){
                        $.pnotify(data);  
                        $('#role_list').flexReload();
                    },'json');
                });
                break;     
            case 'Access':
                $.each(selectedItems,function(index,item){
                    var url = "rbam/roleSetAccess";  
                    $.post(url,{rolename:item.item_name},function(data){
                        $.pnotify(data);  
                        $('#role_list').flexReload();
                    },'json');
                });
                break;                
            default:
                break;
            }
        }
</script>