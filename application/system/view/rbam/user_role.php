<div class="row">
    <div class="col-md-4">
        <select class="auth-select form-control" id="user_role_select" size="10">
        <?php foreach ($this->endusers as $role): ?>
            <option value="<?php echo $role['user_name']; ?>">
            <?php echo $role['user_name']; ?>
            </option>
        <?php endforeach; ?>
    </select>
    </div>
    <div class="col-md-8">
       <div id="user_role"></div>
    </div>
 </div>
<?php $this->pview('rbam/user_role_script');?>