<?php $this->page_header = "Login failed :("; ?>
<div><?php echo $this->errorMessage;?> <?php echo shtml_action_link("try again", "web/user/login", array('class'=> 'btn btn-default'));?>
</div>