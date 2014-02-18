<form method="POST" role="form">
    <div class="form-group">
        <label>Server</label>
        <input type="text" value="<?php echo $data['install']['dbconn']['server']; ?>" name="dbconn[server]" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Username</label>
        <input type="text" value="<?php echo $data['install']['dbconn']['username']; ?>" name="dbconn[username]" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="text" value="<?php echo $data['install']['dbconn']['password']; ?>" name="dbconn[password]" class="form-control"/>
    </div>
    <div class="form-actions">
        <input type="submit" value="Change" class="btn btn-success"/>
    </div>
</form>