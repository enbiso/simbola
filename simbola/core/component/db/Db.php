<?php
namespace simbola\core\component\db;
/**
 * Description of Database
 *
 * @author Faraj
 */
class Db extends \simbola\core\component\system\lib\Component {

    public static $NUMBER = 'num';
    public static $STRING = 'str';
    public static $DATE = 'dat';
    private $dbDriver;

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
            $this->dbDriver->setDebug($this->params['DEBUG']);
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

    public function getUsername() {
        return $this->params['USERNAME'];
    }

    public function getVendor() {
        return $this->params['VENDOR'];
    }

    public function __call($name, $arguments) {           
        if(method_exists($this->dbDriver, $name)){
            return call_user_func_array(array($this->dbDriver, $name), $arguments);        
        }else{
            throw new \Exception("Method not found {$name}");
        }
    }
}

?>
