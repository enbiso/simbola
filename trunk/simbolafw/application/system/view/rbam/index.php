<?php $url = simbola\Simbola::app()->url; ?>
<div id="tabs-rbam-main">
    <ul>
        <li><a href="/system/rbam/tabUsers">Users</a></li>
        <li><a href="/system/rbam/tabRoles">Roles</a></li>
        <li><a href="/system/rbam/tabUserRole">User - Role</a></li>	
        <li><a href="/system/rbam/tabRoleRole">Role - Role</a></li>
        <li><a href="/system/rbam/tabRoleAccessObj">Role - Access Object</a></li>
        <li><a href="/system/rbam/tabManAccessObj">Manage Access Objects</a></li>
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