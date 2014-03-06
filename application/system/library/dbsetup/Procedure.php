<?php
namespace application\system\library\dbsetup;
/**
 * Description of Function
 *
 * @author Faraj
 */
abstract class Procedure extends DbObject{
    
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
