<?php $this->includeFile('_menu'); ?>  
<nav class="navbar navbar-default  navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Developer (<?php echo simbola\Simbola::app()->getParam('APPNAME'); ?>)</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
                <?php menu_item("IDE", array("developer/ide/index"), "lu") ?>
                <?php menu_item("Service Manager", array("developer/service/index"), "lu") ?>
            </ul>
        </div>
    </div>
</nav>