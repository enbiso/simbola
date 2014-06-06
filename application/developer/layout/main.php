<!DOCTYPE html>
<html lang="en" class="fuelux">
    <head>
        <title>Developer (<?php echo \simbola\Simbola::app()->getParam('APPNAME');?>)</title>
        <?= shtml_meta('utf-8', array(
            'description' => "Simbola Framework",
            'author' => 'Faraj Farook')) ?>        
        
        <?= shtml_resource_include(array(
            'jquery-pnotify', 'jquery', 'jquery-cookie', 'simbola', 'json', 'codemirror',
            'flexigrid', 'jquery-ui', 'jquery-pnotify', 'jquery-dynatree', 'jquery-contextmenu', 
            'rbam', 'less', 'simgrid', 'bootstrap', 'bootstrap-notify')) ?>     
        
        <style>
            body{
                padding-top: 80px;
            }
        </style>
        <?php simbola_js_init() ?>
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
    </body>
</html>
