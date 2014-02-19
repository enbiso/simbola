<h1>Simbola Login.</h1>
To customize, configure params of 'Auth' component, <strong>Default login ' admin / admin '</strong>
<hr/>
<div class="row">
    <?php if($this->isDataSet('errorMessage')): ?>
    <div class="alert alert-warning"><?= $this->errorMessage ?></div>
    <?php endif;?>
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
            <input type="submit" value="login" class="btn btn-primary"/>
        </div>
    </form>
</div>