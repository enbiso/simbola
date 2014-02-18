<h2><?php echo $errstr; ?></h2>
<hr/>
<div class="greybox">
    Error on line <b><?php echo $errline; ?></b> in file <b><?php echo $errfile; ?></b>
</div>
<?php
if (file_exists($errfile)):
    $lines = file($errfile);
    foreach ($lines as $line_index => $line):
        ?>
        <?php $line_num = $line_index + 1; ?>
        <?php if ($line_num > ($errline - 5) && $line_num < ($errline + 5)): ?>
                <?php if ($line_num == $errline): ?>
                <div class='line err_line'>
                    <?php else: ?>
                    <div class='line'>
                        <?php endif; ?>
                    <span class='line_no'><?php echo $line_num; ?></span> <span='content'>
                <?php highlight_string("<?" . $line); ?></span>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <hr/>
<?php endif; ?>