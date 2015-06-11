<!DOCTYPE html>
<html lang="en" class="fuelux">
    <head>
        <title>Developer (<?php echo \simbola\Simbola::app()->getParam('APPNAME');?>)</title>
        <?= shtml_meta('utf-8', array(
            'description' => "Simbola Framework",
            'author' => 'Faraj Farook')) ?>        
        
        <?= shtml_resource_include(array(
            'jquery', 'jquery-pnotify', 'jquery-cookie', 'simbola', 'json', 'codemirror',
            'flexigrid', 'jqueryui', 'jquery-pnotify', 'jquery-dynatree', 'jquery-contextmenu', 
            'rbam', 'less', 'simgrid', 'bootstrap', 'bootstrap-notify', 'mousetrap')) ?>     
        
        <style>
            body{
                padding-top: 60px;
            }
        </style>
        <?php simbola_js_init() ?>
    </head>
    <body>
        <?php $this->includeFile('_header'); ?>
        <div class="container">
            <?php
            if ($this->isDataSet('page_breadcrumb')) {
                echo shtml_breadcrumb($this->page_breadcrumb);
            }
            ?>
            <?php echo $content; ?>   
            <hr>
            <footer>
                <?php $this->includeFile('_footer'); ?>
            </footer>
        </div> 
    </body>
</html>
