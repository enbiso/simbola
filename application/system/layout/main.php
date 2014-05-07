<?php $this->includeFile('_menu'); ?>
<?php $this->includeFile('_logo'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Simbola Framework</title>
        <?= shtml_meta('utf-8', array(
            'description' => "Simbola Framework",
            'author' => 'Faraj Farook')) ?>        
        
        <?= shtml_resource_include(array(
            'jquery-pnotify', 'jquery', 'jquery-cookie', 'simbola', 'json',
            'flexigrid', 'jquery-ui', 'jquery-dynatree', 'jquery-contextmenu', 
            'rbam', 'less', 'simgrid', 'bootstrap', 'bootstrap-notify')) ?>
        
        <?php
        //setup pagemenu
        if ($this->isDataSet('page_menu')) {
            $this->page_menu = sauth_filter_menu_array($this->page_menu);
            $pageMenuWidth = ceil(count($this->page_menu) * 1) + 1;
        } else {
            $pageMenuWidth = 0;
        }
        //page header        
        if ($this->isDataSet('page_header') && is_array($this->page_header)) {
            $this->page_subheader = $this->page_header[1];
            $this->page_header = $this->page_header[0];
        }
        ?>
        <style>
            html,
            body {
                height: 100%;
            }
            
            #wrap {
                min-height: 100%;
                height: auto !important;
                height: 100%;
                margin: 0 auto -60px;                
                padding: 0 0 60px;
            }
            
            #footer {
                height: 60px;
                background-color: #f5f5f5;
            }

            @media (max-width: 767px) {
                #footer {
                    margin-left: -20px;
                    margin-right: -20px;
                    padding-left: 20px;
                    padding-right: 20px;
                }
            }
            
            #wrap > .container {
                padding: 60px 15px 0;
            }
            
            .container .credit {
                margin: 20px 0;
            }
            
            .center{
                text-align: center;
            }
        </style>
        <?php simbola_js_init(); ?>
    </head>
    <body>
        <div id="wrap">
            <?php $this->includeFile('_header'); ?>
            <div class="container">
                <div class="row">                    
                    <div class="col-md-<?= 12 - $pageMenuWidth ?>">
                        <?php
                        if ($this->isDataSet('page_breadcrumb')) {
                            echo shtml_breadcrumb($this->page_breadcrumb);
                        }
                        ?>
                    </div>
                        <?php if ($pageMenuWidth > 0): ?>
                        <div class="col-md-<?= $pageMenuWidth ?>">
                        <?= shtml_btngroupmenu($this->page_menu); ?>
                        </div>
                <?php endif ?>
                </div>
                <?php if ($this->isDataSet('page_header')): ?>
                    <h2><?= $this->page_header ?> <small><?= $this->isDataSet('page_subheader') ? $this->page_subheader : '' ?></small></h2><hr/>
            <?php endif; ?>
            <?php echo $content; ?>               
            </div>        
            <?php $this->includeFile('_footer'); ?>        
        </div>
    </body>
</html>
