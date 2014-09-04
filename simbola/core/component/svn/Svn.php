<?php
namespace simbola\core\component\svn;

include_once 'phpsvnclient/phpsvnclient.php';
/**
 * SVN Component Definitions
 *
 * @author Faraj Farook
 */
class Svn extends \simbola\core\component\system\lib\Component{
    
    /**
     * Client Object
     * @var \phpsvnclient Client Object
     */
    private $client;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->client = new \phpsvnclient();
    }
    
    /**
     * Get php SVN client object
     * @return \phpsvnclient
     */
    public function client() {
        return $this->client;
    }
}

?>
