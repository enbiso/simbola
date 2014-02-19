<?php $url = simbola\Simbola::app()->url; ?>
<div id="tabs-rbam-main">
    <ul>
        <li><?= shtml_action_link("Users", array('/system/rbam/tabUsers')) ?></li>
        <li><?= shtml_action_link("Roles", array('/system/rbam/tabRoles')) ?></li>
        <li><?= shtml_action_link("User - Role", array('/system/rbam/tabUserRole')) ?></li>
        <li><?= shtml_action_link("Role - Role", array('/system/rbam/tabRoleRole')) ?></li>
        <li><?= shtml_action_link("Role - Access Object", array('/system/rbam/tabRoleAccessObj')) ?></li>
        <li><?= shtml_action_link("Manage Access Objects", array('/system/rbam/tabManAccessObj')) ?></li>
    </ul>
</div>
<script>
    $('#tabs-rbam-main').tabs({cache: true});
    
    function openDialog(label, url, opts, post_data){         
        opts.show = 'clip';
        opts.hide = 'clip';
        opts.resize = "auto";
        id = url.replace("/","_").replace(":", "_");
        if($('#'+id).length > 0){
            $('#'+id).html("");
            $('#'+id).attr('title',label).load(url,post_data).dialog(opts);;
        }else{
            $('<div/>').attr('id', id).attr('title',label).load(url,post_data).dialog(opts);;
        }             
    }
</script>