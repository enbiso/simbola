<?php
namespace simbola\core\application\dbobj;
/**
 * Description of Function
 *
 * @author Faraj
 */
abstract class AppDbProcedure extends AbstractDbObject{
    
    /**
     * Constructor
     * @param \simbola\core\component\db\driver\AbstractDbDriver $db DB Driver
     */
    public function __construct($db) {
        parent::__construct($db);
        $this->type = 'procedure';
    }
    
    /**
     * Drop create the procedure
     * @param type $content SQL content
     */
    function dropCreate($content) {
        $funcName = $this->dbDriver->getProcedureName($this->module, $this->lu, $this->name);
        $this->setContent("DROP FUNCTION IF EXIST {$funcName}");
        $this->execute();
        $this->setContent("DROP PROCEDURE IF EXIST {$funcName}");
        $this->execute();
        $this->setContent($content);
        $this->execute();
    }
}
?>
