<?php $this->page_header = array("User", "Register"); ?>
<div class="row">
    <?php if($this->isDataSet('errorMessage')): ?>
        <div class="alert alert-warning"><?= $this->errorMessage ?></div>
    <?php endif; ?>
    <form role="form" method="POST" class="col-md-4">
        <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" name="username" placeholder="Username"/>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="text" class="form-control" name="password" placeholder="Password"/>
        </div>
        <div class="form-group">
            <label>Password verify</label>
            <input type="text" class="form-control" name="password_verify" placeholder="Password verify"/>
        </div>
        <div class="form-actions">
            <input type="submit" value="Sign Up" class="btn btn-primary"/>
            <?php echo shtml_action_link("Already have a login", "web/user/login", array('class'=>'btn btn-default')); ?>
        </div>
    </form>
</div>    