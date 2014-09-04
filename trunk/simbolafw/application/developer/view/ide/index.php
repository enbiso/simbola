<?php 
$this->page_breadcrumb = array(
    'Developer' => array('/developer'),    
    'Integrated Development Environment');
?>

<div class="row">
    <div class="col-lg-3" id="file_browser" style="border-right: 1px solid silver">
    </div>
    <div class="col-lg-8" id="display">        
        <div class="btn-group btn-group-sm">
            <a href="#" onclick="{call_action('save')}" class="btn btn-success btn-sm">Save</a>        
            <button class="btn btn-default disabled">Reload</button>
        </div>
        <hr/>
        <div class="_form"></div>
        <div class="_editor"></div>
        <img class="_image"/>
        <div class="_video"></div>
    </div>
</div>

<ul id="webcode-context" class="contextMenu">    
    <li class="new_module"><a href="#new_module">New Module</a></li>

    <li class="new_model"><a href="#new_model">New Model</a></li>
    <li class="new_service"><a href="#new_service">New Service</a></li>
    <li class="new_controller"><a href="#new_controller">New Controller</a></li>

    <li class="open_dbsetup"><a href="#open_dbsetup">DB Setup...</a></li>
    <li class="new_logical_unit"><a href="#new_logical_unit">New LU</a></li>
    <li class="new_db_table"><a href="#new_db_table">New Table</a></li>
    <li class="all_logical_unit_execute"><a href="#all_logical_unit_execute">Execute All LUs</a></li>
    <li class="obj_execute"><a href="#obj_execute">Execute Obj</a></li>
    <li class="lu_execute"><a href="#lu_execute">Execute LU</a></li>

    <li class="separator"></li>
    <li class="reload"><a href="#reload">Reload</a></li>    
    <li class="reload_resource_cache separator"><a href="#reload_resource_cache">Load Cache</a></li>
    <li class="new_file separator"><a href="#new_file">New File</a></li>
    <li class="new_folder"><a href="#new_folder">New Folder</a></li>
    <li class="rename separator"><a href="#rename">Rename</a></li>
    <li class="upload separator"><a href="#upload">Upload</a></li>  
    <li class="download"><a href="#download">Download</a></li>  
    <li class="promote separator"><a href="#promote">Promote</a></li>  
    <li class="upgrade separator"><a href="#upgrade">Upgrade</a></li>  
    <li class="cut separator"><a href="#cut">Cut</a></li>
    <li class="copy"><a href="#copy">Copy</a></li>
    <li class="paste"><a href="#paste">Paste</a></li>
    <li class="delete separator"><a href="#delete">Delete</a></li>  
</ul>
<!-- MODAL -->
<?php $this->controller->pview('ide/_confirm'); ?>
<?php $this->controller->pview('ide/_rename'); ?>
<?php $this->controller->pview('ide/_create'); ?>
<!-- STYLE -->
<?php $this->controller->pview('ide/_style'); ?>
<!-- SCRIPT -->
<?php $this->controller->pview('ide/_script_functions'); ?>
<?php $this->controller->pview('ide/_script_actions'); ?>
<?php $this->controller->pview('ide/_script_editor'); ?>
<?php $this->controller->pview('ide/_script_tree'); ?>
<?php $this->controller->pview('ide/_script_tree_contextmenu'); ?>