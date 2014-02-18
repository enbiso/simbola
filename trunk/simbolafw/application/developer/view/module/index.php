<h3><?php shtml_eterm('developer.module.index.modules') ?></h3>
<hr/>
<pre><?php var_dump($this->sOutput); ?></pre>
<ol>
<?php foreach ($this->modules as $module):?>
    <li><?php echo $module;?></li>
<?php endforeach;?>
    <li><?php echo shtml_action_link("TERM:developer.module.index.createNew", "developer/module/create");?></li>
</ol>