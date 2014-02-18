<?php
$this->page_header = "Contact";
$this->page_subheader = sterm_get('web.contact.view.title');;

$this->page_breadcrumb = array(
    'Web' => array('/web'),
    'Contact' => array('/web/contact'),    
    sterm_get('web.contact.view.title'));

$object = $this->object;
$this->page_menu = array(    
    array(
        'title' => sterm_get('web.contact.view.menu.update'),
        'link' => array('/web/contact/update',"id" => $object->id),
        'icon' => 'edit'
    ),
    array(
        'title' => sterm_get('web.contact.view.menu.delete'),
        'link' => array('/web/contact/delete',"id" => $object->id),
        'icon' => 'remove'
    ),    
);
?>
<div class="panel panel-default">
    <div class="panel-heading"><?= sterm_get('web.contact.view.panel.heading') ?></div>
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
            <div class="col-md-4"><?php echo $this->object->is_read?'YES':'NO'; ?></div>        
        </div>        
    </div>
</div>