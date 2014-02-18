<?php
namespace simbola\core\component\shell\command;
/**
 * Description of Command
 *
 * @author Faraj
 */
abstract class Command {
    protected $params;    
    public function __construct($params = array()) {
        $this->params = $params;
    }
    
    abstract function perform();
}

?>
