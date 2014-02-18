<?php

namespace simbola\core\application;

/**
 * Description of AppService
 *
 * @author farflk
 */
class AppService extends AppController {

    const STATUS_OK = '200';
    const STATUS_USER_ERROR = '201';
    const STATUS_INVALID_SERVICE = '404';
    const STATUS_BAD_REQUEST = '400';
    const STATUS_FORBIDDEN = '403';

    private static $STATUS_TEXT = array(
        self::STATUS_OK => 'OK',
        self::STATUS_USER_ERROR => 'USER_ERROR',
        self::STATUS_INVALID_SERVICE => 'INVALID_SERVICE',
        self::STATUS_BAD_REQUEST => 'BAD_REQUEST',
        self::STATUS_FORBIDDEN => 'FORBIDDEN',
    );
    
    protected $output;

    public function _res($name, $value) {
        $this->output['body']['response'][$name] = $value;
    }

    public function _req() {
        return $_POST;
    }

    public function _req_auth() {
        return $this->post('auth');
    }

    public function _req_auth_isset() {
        if ($this->issetPost('auth')) {
            $auth = $this->_req_auth();
            return isset($auth['username']) && isset($auth['skey']);
        } else {
            return false;
        }
    }

    public function _req_service() {
        return $this->post('service');
    }

    public function _req_service_isset() {
        return $this->issetPost('service');
    }

    public function _req_action() {
        return $this->post('action');
    }

    public function _req_action_isset() {
        return $this->issetPost('action');
    }
        
    public function _req_params($name = null) {        
        $params = $this->currentPage->params;
        if (isset($name)) {
            $params = isset($params[$name]) ? $params[$name] : null;
        }
        return $params;
    }
    
    public function _err($data) {
        $this->output['body']['message'] = $data;
        $this->_status(self::STATUS_USER_ERROR);
    }

    public function _status($code) {
        $this->output['header']['status'] = $code;
        $this->output['header']['status_text'] = self::$STATUS_TEXT[$code];
    }

    public function preAction($page) {
        parent::preAction($page);
        if (!$this->_req_auth_isset()) {
            $_POST['auth'] = array('username' => 'guest', 'skey' => NULL);
        }
        $this->output['header'] = array(
            'version' => 'Simbola 1.4',
            'timestamp' => time(),
            'status' => self::STATUS_OK,
            'status_text' => self::$STATUS_TEXT[self::STATUS_OK],
            'service' => $this->_req_service() . "." . $this->_req_action());
    }

    private $return = false;

    public function setReturn($value) {
        $this->return = $value;
    }

    public function run($page) {        
        $this->currentPage = $page;
        $funcName = 'action' . ucfirst($page->action);
        $this->preAction($page);
        if (!method_exists($this, $funcName)) {            
            $this->_status(self::STATUS_INVALID_SERVICE);
        } else if ($this->checkSecurityBypass($page->action) || $this->checkSecurity($page)) {
            //check parameters exists
            $schemaProp = "schema_{$page->action}";
            $schema = $this->$schemaProp;
            $paramsFound = TRUE;
            foreach ($schema['req']['params'] as $paramName) {                
                $paramsFound = $paramsFound && $page->issetParam($paramName);
            }
            if (!$paramsFound) {
                $this->_status(self::STATUS_BAD_REQUEST);
            } else {
                //Initialize the response values in body
                $this->output['body'] = array('response' => array(), 'message' => null);
                foreach ($schema['res'] as $resKey) {
                    $this->output['body']['response'][$resKey] = null;
                }
                $this->$funcName();
            }
        } else {
            $this->_status(self::STATUS_FORBIDDEN);
        }
        return $this->postAction($page);
    }
    
    public function postAction($page) {        
        if (!$this->return) {
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/json');
            $this->json($this->output);
        } else {
            return $this->output;
        }
    }

    public function checkSecurity($page) {
        $auth = $this->_req_auth();
        $permObj = new \simbola\core\component\auth\lib\PermObject($page);
        return \simbola\Simbola::app()->auth->checkPermission($permObj, $auth['username'], $auth['skey']);
    }

}

?>
