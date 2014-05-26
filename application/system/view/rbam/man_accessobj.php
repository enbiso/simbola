<div class="row">
    <div class="form-group col-md-4 col-sm-6 col-xs-6">        
        <div class="input-group input-group-sm">
            <select id="select_modules" class="form-control">
                <?php foreach ($this->moduleNames as $moduleName):?>
                <option value="<?php echo $moduleName;?>"><?php echo $moduleName;?></option>
                <?php endforeach;?>
            </select>            
            <a href="#" onclick="{registerModule()}" class="btn btn-default input-group-addon">Register</a>
            <a href="#" onclick="{registerAllModules()}" class="btn btn-default input-group-addon">Register All</a>      
        </div>
    </div>
    <a href="#" onclick="{removeObjs()}" class="btn btn-sm btn-default col-md-1 disabled" id="btn_access_obj_list_remove">Remove</a>
</div>
<div id="access_obj_list"></div>
<?php $this->pview('rbam/man_accessobj_script');?>