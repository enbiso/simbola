<div class="row container">
    <form method="POST" role="form">
        <div class="panel panel-primary">
            <div class="panel-heading">Setup</div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Application Name</label>
                    <input type="text" value="<?php echo $data['install']['application']['name']; ?>" name="application[name]" class="form-control"/>
                </div>
                <div class="form-group">
                    <label>URL Base</label>
                    <input type="text" value="<?php echo $data['install']['application']['urlbase']; ?>" name="application[urlbase]" class="form-control" placeholder="URL Base"/>                    
                </div>
                <input type="hidden" name="application[execute]" value="YES"/>
            </div>
            <div class="panel-footer">
                <input type="submit" value="Install" class="btn btn-primary"/>
            </div>
        </div>
    </form>
</div>
<hr/>