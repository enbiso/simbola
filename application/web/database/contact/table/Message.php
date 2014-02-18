<?php
namespace application\web\database\contact\table;
/**
 * Description of Message
 *
 * @author FARFLK
 */
class Message extends \application\system\library\dbsetup\Table {
    
    public function init() {
        $this->setModule("web");
        $this->setLu("contact");
        $this->setName("message");
    }

    public function setup() {
        //r0
        $this->addTable();
        //r1
        $this->addColumns(array(
            'id BIGINT PRIMARY KEY AUTO_INCREMENT',
            'title VARCHAR(200) NOT NULL',
            'message TEXT NOT NULL',
            'email VARCHAR(200) NOT NULL',
            'is_read BOOLEAN DEFAULT FALSE'
        ));
    }

}
