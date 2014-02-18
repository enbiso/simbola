<?php
$this->includeFile('_logo');
$this->page_breadcrumb = array(
    'Web' => array('/web'),
    'Contact us');

$this->page_menu = array(
    array(
        'title' => sterm_get('web.contact.index.menu.list'),
        'link' => array('/web/contact/list'),
        'icon' => 'list'
    ),
);
?>
<div class="jumbotron">
    <h1>Contact <?= enbiso() ?></h1>           
    <p><?= \application\web\model\core\Content::getContent("WEB.CONTACT.INDEX.MESSAGE") ?></p>
    <p>
        <?php if ($this->isDataSet('error')): ?>
        <div class="alert alert-warning"><?= $this->error ?></div>    
        <?php endif; ?>
        <?php if ($this->isDataSet('success')): ?>
        <div class="alert alert-success"><?= sterm_get("web.contact.index.successMessage") ?></div>
        <?php endif; ?>
        <?php $this->pview('contact/_form'); ?>
    </p>
</div>