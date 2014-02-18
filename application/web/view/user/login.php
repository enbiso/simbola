<?php $this->page_header = "User"; $this->page_subheader = "Login"; ?>
<div class="row">
    <form method="POST" role="form" class="col-md-4">
        <div class="form-group">
            <label>Username</label>
            <input class="form-control" type="text" name="username" placeholder="username"/>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input class="form-control" type="password" name="password" placeholder="password"/>
        </div>
        <div class="form-actions">
            <input type="submit" value="Login" class="btn btn-primary"/>
            <?php echo shtml_action_link("I don't have a login", "web/user/register", array('class'=>'btn btn-default')); ?>
        </div>
    </form>
</div>