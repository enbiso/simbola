<?php
namespace simbola\core\application\dbobj;
/**
 * Description of View
 *
 * @author Faraj
 */
abstract class AppDbView extends AbstractDbObject{
    
    /**
     * Constructor
     * @param \simbola\core\component\db\driver\AbstractDbDriver $db  DB Driver
     */
    public function __construct($db) {
        parent::__construct($db);
        $this->type = "view";
    }
    
    /**
     * Get View name 
     * @return string
     */
    public function getViewName() {
        return $this->dbDriver->getViewName($this->module, $this->lu, $this->name);
    }
    
    /**
     * Create or replace the given statement
     * @param string $stmt SQL statement
     */
    public function createOrReplace($stmt) {        
        $this->setContent("CREATE OR REPLACE VIEW {$this->getViewName()} AS {$stmt}");
        $this->execute();        
    }
}

?>
