<?php 
$this->page_breadcrumb = array(
    'My Profile');

$this->page_menu = array(    
    array(
        'title' => 'Edit',
        'link' => array('/web/user/editProfile'),
        'icon' => 'edit'
    ),
);
?>
<div class="panel panel-default">
  <div class="panel-heading">Personal information</div>
  <div class="panel-body">
      <div class="row">
          <div class="col-md-2 text-muted">Name</div>
          <div class="col-md-6"><?php echo $this->object->name; ?></div>
      </div>
      <hr/>
      <div class="row">
          <div class="col-md-2 text-muted">Email</div>
          <div class="col-md-6"><?php echo $this->object->email; ?></div>
      </div>
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">User information</div>
  <div class="panel-body">
      <div class="row">
          <div class="col-md-2 text-muted">Username</div>
          <div class="col-md-6"><?php echo $this->object->user->username; ?></div>
      </div>
      <hr/>
      <div class="row">
          <div class="col-md-2 text-muted">Password</div>          
          <div class="col-md-6"><?php echo shtml_action_link("change", array("web/user/changePassword")); ?></div>
      </div>
  </div>
</div>
