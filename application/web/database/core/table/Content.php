<?php
namespace application\web\database\core\table;
/**
 * Description of Message
 *
 * @author FARFLK
 */
class Content extends \application\system\library\dbsetup\Table {
    
    public function init() {
        $this->setModule("web");
        $this->setLu("core");
        $this->setName("content");
    }

    public function setup() {
        //r0
        $this->addTable();
        //r1
        $this->addColumns(array(
            'id VARCHAR(100) PRIMARY KEY',
            'description TEXT',
            'content TEXT',
        ));
    }
}
