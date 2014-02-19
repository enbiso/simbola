<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>            
            <a href="<?= surl_geturl(array('/')) ?>" class="navbar-brand"><?= logo(); ?></a>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="navbar-nav nav">
                <?php
                menu_item('RBAM', array('system/rbam'));
                menu_item('Developer IDE', array('developer/ide'));
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">                
                <?php menu_item('About', array('system/about')); ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php
                        menu_item('Login', array('system/auth/login'));
                        menu_item(ucfirst(simbola\Simbola::app()->auth->getUsername()), array('system'));
                        menu_item('Logout', array('system/auth/logout'));
                        ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>