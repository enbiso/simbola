<!DOCTYPE html>
<html lang="en" class="fuelux">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>RBAM (<?php echo \simbola\Simbola::app()->getParam('APPNAME');?>)</title>
        
        <!-- Le styles -->        
        <?php 
        shtml_ecss('system', 'jquery-ui/smoothness/jquery.ui.css');
        shtml_ecss('system', 'jquery-dynatree/skin-vista/ui.dynatree.css');
        shtml_ecss('system', 'jquery-contextmenu/jquery.contextMenu.css');
        shtml_ecss('system', 'rbam/main.css');
        shtml_ecss('system', 'flexigrid/flexigrid.css');
        shtml_ecss('system', 'jquery-pnotify/jquery.pnotify.default.css');
        shtml_ecss('system', 'jquery-pnotify/jquery.pnotify.default.icons.css');
        ?>
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->        
        <?php
        shtml_ejs('system', 'jquery/jquery.min.js');            
        shtml_ejs('system', 'jquery/jquery.migrate.js');    
        shtml_ejs('system', 'jquery-cookie/jquery.cookie.js');         
        shtml_ejs('system', 'jquery-pnotify/jquery.pnotify.min.js');    
        shtml_ejs('system', 'flexigrid/flexigrid.js');    
        
        shtml_ejs('system', 'jquery-ui/jquery.ui.js');    
        shtml_ejs('system', 'jquery-dynatree/jquery.dynatree.js');    
        shtml_ejs('system', 'jquery-cookie/jquery.cookie.js');   
        shtml_ejs('system', 'jquery-contextmenu/jquery.contextMenu.js');   
        shtml_ejs('system', 'rbam/main.js');
        ?>
    </head>
    <body>
        <div class="container">
            <?php echo $content; ?>   
        </div> 
    </body>
</html>
