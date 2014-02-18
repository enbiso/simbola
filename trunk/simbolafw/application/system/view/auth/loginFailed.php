<h2>Default Simbola: Login failed</h2>
<h2><small><?=$this->errorMessage;?></small></h2>
<hr/>
<?php echo shtml_action_link("try again...", "system/auth/login", array('class'=> 'btn btn-default'));?>