<?php $this->includeFile('_menu'); ?>
<?php $this->includeFile('_logo'); ?>
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
        shtml_ecss('system', 'jquery-pnotify/jquery.pnotify.default.css');
        shtml_ecss('system', 'jquery-pnotify/jquery.pnotify.default.icons.css');
        shtml_ecss('system', 'flexigrid/flexigrid.css');
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
        shtml_ejs('system', 'simgrid/simgrid.js');

        shtml_ejs('system', 'bootstrap/js/bootstrap.min.js');
        shtml_ejs('system', 'bootstrap-notify/js/bootstrap-notify.js');

        shtml_ejs('system', 'less/less.min.js');
        shtml_ejs('system', 'json/json2.js');

        shtml_ejs('system', 'flexigrid/flexigrid.js');

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
        <?php simbola_js_init(); ?>
    </body>
</html>
