<div class="row">
    <div class="col-md-4">
        <select class="auth-select form-control" id="role_role_select" size="10">
        <?php foreach ($this->allRoles as $role): ?>
            <option value="<?php echo $role['item_name']; ?>"><?php echo $role['item_name']; ?></option>
        <?php endforeach; ?>
    </select>
    </div>
    <div class="col-md-8">
       <div id="role_role"></div>
    </div>
 </div>
<?php $this->pview('rbam/role_role_script');?>