<?php
namespace simbola\core\component\db;

include_once 'activerecord/ActiveRecord.php';
/**
 * Description of Database
 *
 * @author Faraj 
 */
class Db extends \simbola\core\component\system\lib\Component {

    /**
     * Database driver
     * @var driver\AbstractDbDriver
     */
    private $dbDriver;

    /**
     * Setup the component
     */
    public function setup() {                
        switch ($this->params['VENDOR']) {
            case 'MYSQL':
                $this->dbDriver = new driver\MySQLDriver();                
                break;
            case 'PGSQL':
                $this->dbDriver = new driver\PgSQLDriver();                
                break;
        }
        if (isset($this->dbDriver)) {
            $this->dbDriver->setServer($this->params['SERVER']);
            $this->dbDriver->setUsername($this->params['USERNAME']);
            $this->dbDriver->setPassword($this->params['PASSWORD']);            
            $this->dbDriver->setDbName($this->params['DBNAME']);
            $this->dbDriver->setPageLength($this->params['PAGE_LENGTH']);            
            $this->dbDriver->connect();
        }

        \simbola\Simbola::app()->import('core/component/db/activerecord/ActiveRecord');
        //setup active record
        \ActiveRecord\Config::initialize(function($cfg) {
                    $app = \simbola\Simbola::app();
                    $dbvendor = strtolower($app->db->getParam('VENDOR'));
                    $cfg->set_connections(array(
                        'simbola_connection' => "{$dbvendor}://{$app->db->getParam('USERNAME')}:{$app->db->getParam('PASSWORD')}@{$app->db->getParam('SERVER')}/{$app->db->getParam('DBNAME')}"));
                    $cfg->set_default_connection('simbola_connection');
                });
    }

    /**
     * Get database username
     * 
     * @return string
     */
    public function getUsername() {
        return $this->params['USERNAME'];
    }

    /**
     * Get the database ventor
     * 
     * @return string MYSQL/PGSQL
     */
    public function getVendor() {
        return $this->params['VENDOR'];
    }
    
    /**
     * Gets the database driver
     * 
     * @return driver\AbstractDbDriver
     */
    public function getDriver() {
        return $this->dbDriver;
    }
}

?>
