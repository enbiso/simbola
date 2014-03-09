<?php
namespace simbola\core\application\dbobj;
/**
 * Description of Function
 *
 * @author Faraj
 */
abstract class AppDbProcedure extends AbstractDbObject{
    
    public function __construct($db) {
        parent::__construct($db);
        $this->type = 'procedure';
    }
    
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
