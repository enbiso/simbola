<?php
namespace simbola\core\application\dbobj;
/**
 * Description of View
 *
 * @author Faraj
 */
abstract class AppDbView extends AbstractDbObject{
    public function __construct($db) {
        parent::__construct($db);
        $this->type = "view";
    }
    
    public function getViewName() {
        return $this->dbDriver->getViewName($this->module, $this->lu, $this->name);
    }
    
    public function createOrReplace($stmt) {        
        $this->setContent("CREATE OR REPLACE VIEW {$this->getViewName()} AS {$stmt}");
        $this->execute();        
    }
}

?>
