<?php
$this->page_header = "Content";
$this->page_subheader = sterm_get('web.content.delete.title');;

$this->page_breadcrumb = array(
    'Web' => array('/web'),
    'Content' => array('/web/content'),        
    sterm_get('web.content.delete.title'));

$object = $this->object;
$this->page_menu = array(
    array(
        'title' => sterm_get('web.content.delete.menu.update'),
        'link' => array('/web/content/update',"id" => $object->id),
        'icon' => 'edit'
    ),
    array(
        'title' => sterm_get('web.content.delete.menu.view'),
        'link' => array('/web/content/view',"id" => $object->id),
        'icon' => 'file'
    ),
    array(
        'title' => sterm_get('web.content.delete.menu.list'),
        'link' => array('/web/content/list'),
        'icon' => 'list'
    ),
);
?>
<div class="panel panel-default">
    <div class="panel-heading"><?= sterm_get('web.content.delete.panel.heading') ?></div>
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
    <div class="panel-footer">
        <form method="POST"><?= shtmlform_input_hidden_for($this->object, 'id', 'keys') ?><input type="submit" class="btn-warning btn" value="<?= sterm_get('web.content.delete.panel.btn_ok')?>"/></form>
    </div>
</div>