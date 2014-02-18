<?php
echo "<h3>", $exception->getMessage(), "</h3>";

foreach ($exception->getTrace() as $ex_entry):
    $errline = $ex_entry['line'];
    $errfile = $ex_entry['file'];
    $ex_entry['args'] = (count($ex_entry['args']) == 0) ? NULL : $ex_entry['args'];
    ?>  
    <hr/>
    <div class="greybox">
        <?php echo "$errfile({$errline}):"?>
    </div>
    <pre><?php echo "{$ex_entry['function']}(" . var_export($ex_entry['args'], true) . ")";?></pre>
    <?php
    if (file_exists($errfile)):
        $lines = file($errfile);
        foreach ($lines as $line_index => $line):
            ?>
            <?php $line_num = $line_index + 1; ?>
            <?php if ($line_num > ($errline - 3) && $line_num < ($errline + 3)): ?>
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
        <?php endif; ?>

<?php endforeach; ?>