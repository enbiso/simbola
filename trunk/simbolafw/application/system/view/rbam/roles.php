<?php
$grid = new application\system\library\simgrid\WidgetSimGrid("auth_roles");
$grid->setTitle("Authentication Roles");
$grid->setTableCss('table-condensed table-hover');
$grid->setDataSource("system", "auth", "item");
$grid->setCondition(array('item_type IN(?)', array(AUTH_ITEM_TYPE_ACCESS_ROLE, AUTH_ITEM_TYPE_ENDUSER_ROLE)));
$grid->setColumns(array(
    	"item_name" => "Role",
        "item_type" => "Type",
    "Actions",
));
$grid->setActions(array(
    shtml_action_link("open", "#", array('class' => 'btn btn-default btn-xs')),
));

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
                        $.pnotify(data);  
                        $('#role_list').flexReload();
                    },'json');
                });
                break;                
            case 'Enduser':
                $.each(selectedItems,function(index,item){
                    var url = "rbam/roleSetEnduser";  
                    $.post(url,{rolename:item.role},function(data){
                        $.pnotify(data);  
                        $('#role_list').flexReload();
                    },'json');
                });
                break;     
            case 'Access':
                $.each(selectedItems,function(index,item){
                    var url = "rbam/roleSetAccess";  
                    $.post(url,{rolename:item.role},function(data){
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
