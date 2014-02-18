<select class="auth-select" id="user_role_select">
    <?php foreach ($this->endusers as $role): ?>
        <option value="<?php echo $role['user_name']; ?>"><?php echo $role['user_name']; ?></option>
    <?php endforeach; ?>
</select>
<div id="user_role"></div>
<?php $this->pview('rbam/user_role_script');?>