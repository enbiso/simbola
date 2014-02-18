<select class="auth-select" id="role_role_select">
    <?php foreach ($this->allRoles as $role): ?>
        <option value="<?php echo $role['item_name']; ?>"><?php echo $role['item_name']; ?></option>
    <?php endforeach; ?>
</select>
<div id="role_role"></div>
<?php $this->pview('rbam/role_role_script');?>