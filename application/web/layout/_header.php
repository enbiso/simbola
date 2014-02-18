<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>            
            <a href="/" class="navbar-brand" style="border-right: 1px solid #777; color: black"><?= enbiso(); ?></a>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="navbar-nav nav">
                <?php
                menu_item('Products', array('product'), 'module');
                menu_item('Support', array('support'), 'module');
                menu_item('Enterprise', array('enterprise'), 'module');
                menu_item('Project', array('project'), 'module');
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">                
                <?php menu_item('Contact', array('web/contact')); ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php
                        menu_item('Login', array('web/user/login'));
                        menu_item(ucfirst(simbola\Simbola::app()->auth->getUsername()), array('web/user/myProfile'));
                        ?>
                        <li class="divider"></li>                            
                            <?php
                            menu_item('Logout', array('web/user/logout'));
                            menu_item('Register', array('web/user/register'));
                            ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>