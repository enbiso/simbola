<form method="POST" role="form">
    <div class="form-group">
        <label>Database name</label>
        <input type="text" value="<?php echo $data['install']['dbase']['dbname']; ?>" name="dbase[dbname]" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Create if not exist</label>
        <select name="dbase[dbcreate]" class="form-control">
            <option value="YES" <?php echo $data['install']['dbase']['dbcreate'] == "YES"?'selected="true"':''; ?>>Yes</option>
            <option value="NO" <?php echo $data['install']['dbase']['dbcreate'] == "NO"?'selected="true"':''; ?>>No</option>
        </select>
    </div>
    <div class="form-actions">
        <input type="submit" value="Change" class="btn btn-success"/>
    </div>
</form>