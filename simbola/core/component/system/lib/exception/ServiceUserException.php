<?php
namespace simbola\core\component\system\lib\exception;

/**
 * ServiceUserException Thrown when the service resturns with a user exception
 *
 * @author Faraj
 */
class ServiceUserException extends \Exception{
    private $output;
    public function __construct($output) {
        parent::__construct($output['body']['message']);
        $this->output = $output;
    }
    
    public function getResponse($name = null) {
        if(isset($name)){
            return $this->output['body']['response'][$name];
        }else{
            return $this->output['body']['response'];
        }
    }
}
