<?php 
    $terms = array(
        'DBCONN' => 'MySQL Connection Check',
        'DBCONN_ERROR' => 'MySQL connection failed',
        'DBCONN_SUCCESS' => 'MySQL Server connection success',
        'DBASE' => 'Database Check',
        'DBASE_ERROR' => 'Database not exist',
        'DBASE_SUCCESS' => 'Database exist',
        'PHP' => 'PHP Runtime Check',
        'PHP_ERROR' => 'PHP Runtime version less than 5.4',
        'PHP_SUCCESS' => 'PHP runtime is check passed',
    );
?>
<div class="row">
    <?php foreach ($data['init'] as $key => $initValue):?>
    <div class="col-md-4">
        <div class="panel panel-<?php echo $initValue?'success':'danger' ?>">
        <div class="panel-heading"><?php echo $terms[$key]; ?></div>
        <div class="panel-body">
          <?php echo $terms[$key.($initValue?"_SUCCESS":"_ERROR")]; ?>
          <hr/>
          <?php include './views/init/_'.strtolower($key).".php"; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
</div>