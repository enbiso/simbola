<div class="btn-group btn-group-sm">
    <?= shtml_action_link("Import", "system/rbam/import", array('class' => 'btn btn-default', 'target' => '_blank')) ?>
    <?= shtml_action_link("Export", "system/rbam/export", array('class' => 'btn btn-default')) ?>
</div>
<br/><br/>
<div class="panel panel-default">
    <div class="panel-heading">Advance export options</div>
    <form method="POST" action="<?= surl_geturl(array('system/rbam/export')) ?>">    
        <div class="panel-body">            
            <div class="col-md-4">
                <label class="checkbox"><input type="checkbox" name="type[access_object]" value="YES" checked/>Access Object</label>            
                <label class="checkbox"><input type="checkbox" name="type[access_role]" value="YES" checked/>Access Role</label>            
                <label class="checkbox"><input type="checkbox" name="type[enduser_role]" value="YES" checked/>End User Role</label>            
                <label class="checkbox"><input type="checkbox" name="type[object_relation]" value="YES" checked/>Object Relations</label>            
                <label class="checkbox"><input type="checkbox" name="type[system_user]" value="YES"/>System Users</label>            
                <label class="checkbox"><input type="checkbox" name="type[user_role]" value="YES"/>User Roles</label>            
            </div>
        </div>
        <div class="panel-footer">
            <input type="submit" class="btn btn-sm btn-default" value="Export Selected"/>        
        </div>
    </form>
</div>