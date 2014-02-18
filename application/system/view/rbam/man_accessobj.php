<label for="txt_register_module">Module Name</label><br/>
<select id="select_modules">
    <?php foreach ($this->moduleNames as $moduleName):?>
    <option value="<?php echo $moduleName;?>"><?php echo $moduleName;?></option>
    <?php endforeach;?>
</select>
<a href="#" onclick="{registerModule()}">Register</a>
<a href="#" onclick="{registerAllModules()}">Register All</a>
<hr/>
<div id="access_obj_list"></div>
<a href="#" onclick="{removeObjs()}">Remove Selected Objects</a>
<?php $this->pview('rbam/man_accessobj_script');?>