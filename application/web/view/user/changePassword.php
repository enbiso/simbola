<?php
$this->page_breadcrumb = array(
    'My Profile' => array('web/user/myProfile'),
    'Change Password'
);
$this->page_header = $this->username."'s ";
$this->page_subheader = "change password";
?>
<?php if($this->isDataSet('error')):?>
<div class="alert alert-error">
    <?php echo $this->error;?>
</div>
<?php endif; ?>
<?php if($this->isDataSet('success')):?>
<div class="alert alert-success">
    <?php echo $this->success;?>
</div>
<?php endif; ?>
<div class="row">
    <form role="form" method="POST" class="col-md-4">
        <div class="form-group">
            <label>Current Password</label>
            <input type="text" required name="password" placeholder="Current Password" class="form-control"/>
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="text" required name="new_password" placeholder="New Password" class="form-control"/>
        </div>
        <div class="form-group">
            <label>Password Repeat</label>
            <input type="text" required name="password_repeat" placeholder="Password Repeat" class="form-control"/>
        </div>
        <div class="form-actions">
            <input type="submit" value="Change Password" class="btn btn-default"/>
        </div>
    </form>
</div>