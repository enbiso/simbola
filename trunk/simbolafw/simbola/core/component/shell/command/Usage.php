<?php
namespace simbola\core\component\shell\command;
/**
 * Description of Test
 *
 * @author Faraj
 */
class Usage extends Command{
    public function perform() {
        echo "Usage: <command_name> [<parameters>]";
    }
}
?>
