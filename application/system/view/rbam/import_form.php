<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Simbola Framework</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Simbola Framwork">
        <meta name="author" content="Faraj">        
        <!-- Le styles -->
        <?php
        shtml_ecss('system', 'bootstrap/css/bootstrap.min.css');
        shtml_ecss('system', 'bootstrap/css/bootstrap-theme.min.css');
        shtml_ecss('system', 'bootstrap-notify/css/bootstrap-notify.css');
        ?>        
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <?php
        shtml_ejs('system', 'jquery/jquery.min.js');
        shtml_ejs('system', 'jquery/jquery.migrate.js');
        shtml_ejs('system', 'jquery-cookie/jquery.cookie.js');

        shtml_ejs('system', 'simbola/simbola.js');
        shtml_ejs('system', 'simbola/simbola.jquery.js');
        shtml_ejs('system', 'simbola/simbola.bootstrap.js');
        
        shtml_ejs('system', 'bootstrap/js/bootstrap.min.js');
        shtml_ejs('system', 'bootstrap-notify/js/bootstrap-notify.js');
        ?>
    </head>
    <body>
        <div id="wrap">
            <div class="container">                                
                <br/>
                <?php if($this->isDataSet('message')): ?>
                <div class="alert alert-success"><?= $this->message ?></div>
                <?php endif; ?>
                <?php if($this->isDataSet('error')): ?>
                <div class="alert alert-warning"><?= $this->error ?></div>
                <?php endif; ?>                
                <div class="panel panel-default">
                    <div class="panel-heading">Simbola Security Import</div>                    
                    <div class="row panel-body">
                        <?= shtmlform_start("rbam_import", null, 'POST', array('class'=>'col-md-12')) ?>
                        <div class="form-group">                            
                            <input type="file" name="secFile" class="form-control" placeholder="Backup JSON file"/>
                        </div>
                        <hr/>
                        <div class="form-actions">
                            <input type="submit" value="Import" class="btn btn-primary"/>
                        </div>
                        <?= shtmlform_end() ?>
                    </div>
                </div>
            </div>                    
        </div>
        <?php simbola_js_init(); ?>
    </body>
</html>
