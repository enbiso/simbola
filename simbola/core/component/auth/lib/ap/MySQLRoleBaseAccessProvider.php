<?php

namespace simbola\core\component\auth\lib\ap;

/**
 * Description of MySQLRoleBaseAccessProvider
 *
 * @author Faraj
 */
class MySQLRoleBaseAccessProvider extends DBRoleBaseAccessProvider {

    /**
     * Create module
     * 
     * @return boolean
     */
    public function moduleCreate() {
        return true;
    }  
}

?>
