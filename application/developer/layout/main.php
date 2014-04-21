<!DOCTYPE html>
<html lang="en" class="fuelux">
    <head>
        <title>Developer (<?php echo \simbola\Simbola::app()->getParam('APPNAME');?>)</title>
        <?= shtml_meta('utf-8', array(
            'description' => "Simbola Framework",
            'author' => 'Faraj Farook')) ?>        
        
        <?= shtml_resource_include(array(
            'jquery-pnotify', 'jquery', 'jquery-cookie', 'simbola', 'json', 'codemirror',
            'flexigrid', 'jquery-ui', 'jquery-dynatree', 'jquery-contextmenu', 
            'rbam', 'less', 'simgrid', 'bootstrap', 'bootstrap-notify')) ?>     
        
        <?= shtml_js('system', 'codemirror/lib/util/matchbrackets.js') ?>    
        <?= shtml_js('system', 'codemirror/lib/util/continuecomment.js') ?>    
        <?= shtml_js('system', 'codemirror/lib/util/simple-hint.js') ?>    
        <?= shtml_js('system', 'codemirror/lib/util/javascript-hint.js') ?>    
        <?= shtml_js('system', 'codemirror/mode/htmlmixed/htmlmixed.js') ?>            
        <?= shtml_js('system', 'codemirror/mode/xml/xml.js') ?>            
        <?= shtml_js('system', 'codemirror/mode/javascript/javascript.js') ?>            
        <?= shtml_js('system', 'codemirror/mode/css/css.js') ?>            
        <?= shtml_js('system', 'codemirror/mode/php/php.js') ?>            
        <?= shtml_js('system', 'codemirror/mode/clike/clike.js') ?>                           
        <?= shtml_js('system', 'codemirror/mode/css/css.js') ?>    
        <?= shtml_js('system', 'codemirror/mode/javascript/javascript.js') ?>           
        <?= shtml_js('system', 'codemirror/mode/plsql/plsql.js') ?>  
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
