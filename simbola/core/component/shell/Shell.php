<?php
namespace simbola\core\component\shell;

class Shell extends \simbola\core\component\system\lib\Component{
    private $prompt = "\nsimbola$ ";
    
    public function execute() {
        while (true) {
            echo $this->prompt;
            $command = $this->getCommand();            
            $this->performCommand($command);
        }
    }
    
    function getCommand() {        
        $comStr = fgets(STDIN);
        $commandArr = explode(" ", $comStr);
        return array(
            'command' => $commandArr[0],
            'params' => array_slice($commandArr,1)
        );
    }
    
    function performCommand($command) {     
        $class_name = $this->getClassFromCommand($command['command']);                        
        if(!class_exists($class_name)){
            $class_name = $this->getClassFromCommand('usage');                                    
        }
        $commandObj = new $class_name($command['params']);                            
        $commandObj->perform();
    }
    
    function getClassFromCommand($command_name) {
        return "simbola\\base\\shell\\command\\".ucfirst(trim($command_name));
    }
}
?>
