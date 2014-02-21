<!DOCTYPE html>
<html lang="en" class="fuelux">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Developer (<?php echo \simbola\Simbola::app()->getParam('APPNAME');?>)</title>
        
        <style>
/*            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
            .sidebar-nav {
                padding: 9px 0;
            }*/
        </style>
        <!-- Le styles -->        
        <?php         
        shtml_ecss('system', 'jquery-ui/smoothness/jquery.ui.css');
        shtml_ecss('system', 'bootstrap/css/bootstrap.min.css');
        shtml_ecss('system', 'bootstrap/css/bootstrap-theme.min.css');
        shtml_ecss('system', 'bootstrap-notify/css/bootstrap-notify.css');
        shtml_ecss('system', 'jquery-dynatree/skin-vista/ui.dynatree.css');
        shtml_ecss('system', 'jquery-contextmenu/jquery.contextMenu.css');
        shtml_ecss('system', 'codemirror/lib/codemirror.css'); 
        shtml_ecss('system', 'codemirror/lib/util/simple-hint.css');
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
        
        shtml_ejs('system', 'simbola/simbola.js');     
        shtml_ejs('system', 'simbola/simbola.jquery.js');     
        shtml_ejs('system', 'simbola/simbola.bootstrap.js');     
        
        shtml_ejs('system', 'bootstrap/js/bootstrap.min.js');       
        shtml_ejs('system', 'bootstrap-notify/js/bootstrap-notify.js');       
        
        shtml_ejs('system', 'jquery-ui/jquery.ui.js');    
        shtml_ejs('system', 'jquery-dynatree/jquery.dynatree.min.js');    
        shtml_ejs('system', 'jquery-cookie/jquery.cookie.js');   
        shtml_ejs('system', 'jquery-contextmenu/jquery.contextMenu.js');   
        shtml_ejs('system', 'jquery-pnotify/jquery.pnotify.min.js');   
        
        shtml_ejs('system', 'codemirror/lib/codemirror.js');    
        shtml_ejs('system', 'codemirror/lib/util/matchbrackets.js');    
        shtml_ejs('system', 'codemirror/lib/util/continuecomment.js');    
        shtml_ejs('system', 'codemirror/lib/util/simple-hint.js');    
        shtml_ejs('system', 'codemirror/lib/util/javascript-hint.js');    
        shtml_ejs('system', 'codemirror/mode/htmlmixed/htmlmixed.js');            
        shtml_ejs('system', 'codemirror/mode/xml/xml.js');            
        shtml_ejs('system', 'codemirror/mode/javascript/javascript.js');            
        shtml_ejs('system', 'codemirror/mode/css/css.js');            
        shtml_ejs('system', 'codemirror/mode/php/php.js');            
        shtml_ejs('system', 'codemirror/mode/clike/clike.js');                           
        shtml_ejs('system', 'codemirror/mode/css/css.js');    
        shtml_ejs('system', 'codemirror/mode/javascript/javascript.js');           
        shtml_ejs('system', 'codemirror/mode/plsql/plsql.js');   
        ?>
        <style>
            body{
                padding-top: 80px;
            }
        </style>
    </head>
    <body>
        <?php $this->includeFile('_header'); ?>
        <div class="container">
            <?php echo $content; ?>   
            <hr>
            <footer>
                <?php $this->includeFile('_footer'); ?>
            </footer>
        </div> 
        <?php simbola_js_init() ?>
    </body>
</html>
