<?php
$this->page_breadcrumb = array(
    'System' => array('/system'),
    'Role Based Access Manager');

$url = simbola\Simbola::app()->url; ?>
<ul class="list-group col-md-4">
    <li class="list-group-item"><?= shtml_action_link("User Managment", array('/system/rbam/user')) ?></li>
    <li class="list-group-item"><?= shtml_action_link("Role Management", array('/system/rbam/role')) ?></li>
    <li class="list-group-item"><?= shtml_action_link("User - Role", array('/system/rbam/userRole')) ?></li>
    <li class="list-group-item"><?= shtml_action_link("Role - Role", array('/system/rbam/roleRole')) ?></li>
    <li class="list-group-item"><?= shtml_action_link("Role - Access Object", array('/system/rbam/roleAccessObj')) ?></li>
    <li class="list-group-item"><?= shtml_action_link("Manage Access Objects", array('/system/rbam/manageAccessObj')) ?></li>
    <li class="list-group-item"><?= shtml_action_link("Import & Export", array('/system/rbam/importExport')) ?></li>
</ul>    