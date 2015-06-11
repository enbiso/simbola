<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Role Based Access Manager');

$url = simbola\Simbola::app()->url; ?>
<ul class="nav nav-tabs tabs-up tabs-rbam">
    <li role="presentation" class="active"><?= shtml_action_link("Users", array('/system/rbam/tabUsers')) ?></li>
    <li role="presentation"><?= shtml_action_link("Roles", array('/system/rbam/tabRoles')) ?></li>
    <li role="presentation"><?= shtml_action_link("User - Role", array('/system/rbam/tabUserRole')) ?></li>
    <li role="presentation"><?= shtml_action_link("Role - Role", array('/system/rbam/tabRoleRole')) ?></li>
    <li role="presentation"><?= shtml_action_link("Role - Access Object", array('/system/rbam/tabRoleAccessObj')) ?></li>
    <li role="presentation"><?= shtml_action_link("Manage Access Objects", array('/system/rbam/tabManAccessObj')) ?></li>
    <li role="presentation"><?= shtml_action_link("Import & Export", array('/system/rbam/tabImportExport')) ?></li>
</ul>
<br/>
<div class="page-display"></div>
<script>
    
    $('.tabs-rbam > li > a').click(function(e) {
        e.preventDefault();
        var loadurl = $(this).attr('href');
        $.get(loadurl, function(data) {
            $('.page-display').html(data);
        });
        $(this).parent().tab('show');
    });
    
    $.get($('.tabs-rbam > li.active > a').attr('href'), function(data) {
        $('.page-display').html(data);
    });
    
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