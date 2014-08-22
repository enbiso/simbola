<?php $this->pview('transaction/schedule/display/_common') ?>
<div class="row">
    <div class="col-md-2 text-muted"><?= $this->object->eTerm('content') ?></div>
    <div class="col-md-4"><code><?php echo nl2br(shtml_encode($this->object->content)); ?></code></div>        
</div>