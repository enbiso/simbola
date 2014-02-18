<?php
namespace application\system\library\dbsetup;
/**
 * Description of View
 *
 * @author Faraj
 */
abstract class View extends DbObject{
    public function __construct($db) {
        parent::__construct($db);
        $this->type = "view";
    }
    
    public function getViewName() {
        return $this->db->getViewName($this->module, $this->lu, $this->name);
    }
    
    public function createOrReplace($stmt) {        
        $this->setContent("CREATE OR REPLACE VIEW {$this->getViewName()} AS {$stmt}");
        $this->execute();        
    }
}

?>
