<select class="auth-select" id="role_accessobj_select">
    <?php foreach ($this->allRoles as $role): ?>
        <option value="<?php echo $role['item_name']; ?>"><?php echo $role['item_name']; ?></option>
    <?php endforeach; ?>
</select>
<div id="role_accessobj"></div>
<?php $this->pview('rbam/role_accessobj_script');?>