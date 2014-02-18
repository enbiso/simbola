<?php
$this->page_header = "Content";
$this->page_subheader = sterm_get('web.content.view.title');;

$this->page_breadcrumb = array(
    'Web' => array('/web'),
    'Content' => array('/web/content'),    
    sterm_get('web.content.view.title'));

$object = $this->object;
$this->page_menu = array(    
    array(
        'title' => sterm_get('web.content.view.menu.create'),
        'link' => array('/web/content/create'),
        'icon' => 'plus'
    ),   
    array(
        'title' => sterm_get('web.content.view.menu.update'),
        'link' => array('/web/content/update',"id" => $object->id),
        'icon' => 'edit'
    ),
    array(
        'title' => sterm_get('web.content.view.menu.delete'),
        'link' => array('/web/content/delete',"id" => $object->id),
        'icon' => 'remove'
    ),    
);
?>
<div class="panel panel-default">
    <div class="panel-heading"><?= sterm_get('web.content.view.panel.heading') ?></div>
    <div class="panel-body">
                <div class="row">
            <div class="col-md-2 text-muted"><?= $this->object->eTerm('id') ?></div>
            <div class="col-md-4"><?php echo $this->object->id; ?></div>        
        </div>        <hr/>
        <div class="row">
            <div class="col-md-2 text-muted"><?= $this->object->eTerm('description') ?></div>
            <div class="col-md-4"><?php echo $this->object->description; ?></div>        
        </div>        <hr/>
        <div class="row">
            <div class="col-md-2 text-muted"><?= $this->object->eTerm('content') ?></div>
            <div class="col-md-4"><?php echo $this->object->content; ?></div>        
        </div>        
    </div>
</div>