<?php
$this->page_header = "Contact";
$this->page_subheader = sterm_get('web.contact.delete.title');;

$this->page_breadcrumb = array(
    'Web' => array('/web'),
    'Contact' => array('/web/contact'),        
    sterm_get('web.contact.delete.title'));

$object = $this->object;
$this->page_menu = array(
    array(
        'title' => sterm_get('web.contact.delete.menu.update'),
        'link' => array('/web/contact/update',"id" => $object->id),
        'icon' => 'edit'
    ),
    array(
        'title' => sterm_get('web.contact.delete.menu.view'),
        'link' => array('/web/contact/view',"id" => $object->id),
        'icon' => 'file'
    ),
    array(
        'title' => sterm_get('web.contact.delete.menu.list'),
        'link' => array('/web/contact/list'),
        'icon' => 'list'
    ),
);
?>
<div class="panel panel-default">
    <div class="panel-heading"><?= sterm_get('web.contact.delete.panel.heading') ?></div>
    <div class="panel-body">
                <div class="row">
            <div class="col-md-2 text-muted"><?= $this->object->eTerm('id') ?></div>
            <div class="col-md-4"><?php echo $this->object->id; ?></div>        
        </div>        <hr/>
        <div class="row">
            <div class="col-md-2 text-muted"><?= $this->object->eTerm('title') ?></div>
            <div class="col-md-4"><?php echo $this->object->title; ?></div>        
        </div>        <hr/>
        <div class="row">
            <div class="col-md-2 text-muted"><?= $this->object->eTerm('message') ?></div>
            <div class="col-md-4"><?php echo $this->object->message; ?></div>        
        </div>        <hr/>
        <div class="row">
            <div class="col-md-2 text-muted"><?= $this->object->eTerm('email') ?></div>
            <div class="col-md-4"><?php echo $this->object->email; ?></div>        
        </div>        <hr/>
        <div class="row">
            <div class="col-md-2 text-muted"><?= $this->object->eTerm('is_read') ?></div>
            <div class="col-md-4"><?php echo $this->object->is_read; ?></div>        
        </div>        
    </div>
    <div class="panel-footer">
        <form method="POST"><?= shtmlform_input_hidden_for($this->object, 'id', 'keys') ?><input type="submit" class="btn-warning btn" value="<?= sterm_get('web.contact.delete.panel.btn_ok')?>"/></form>
    </div>
</div>