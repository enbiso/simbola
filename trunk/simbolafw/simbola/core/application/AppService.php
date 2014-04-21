<?php

namespace simbola\core\application;

/**
 * The abstract base class that should be used to define the Application Service
 *
 * @author Faraj Farook
 */
class AppService extends AppController {

    const STATUS_OK = '200';
    const STATUS_ERROR = '500';
    const STATUS_USER_ERROR = '201';
    const STATUS_INVALID_SERVICE = '404';
    const STATUS_BAD_REQUEST = '400';
    const STATUS_FORBIDDEN = '403';

    /**
     * Status text repesentations
     * 
     * @var array 
     */
    private static $STATUS_TEXT = array(
        self::STATUS_OK => 'OK',
        self::STATUS_ERROR => 'ERROR',
        self::STATUS_USER_ERROR => 'USER_ERROR',
        self::STATUS_INVALID_SERVICE => 'INVALID_SERVICE',
        self::STATUS_BAD_REQUEST => 'BAD_REQUEST',
        self::STATUS_FORBIDDEN => 'FORBIDDEN',
    );

    /**
     * Used to store the service output data
     * 
     * @var array
     */
    protected $output;

    /**
     * Set to output mode or not
     *      
     * @var boolean
     */
    private $return = false;

    /**
     * Method used to set the response data in the service output
     * 
     * @param string $name Name of the data
     * @param mixed $value Value of the data
     */
    public function _res($name, $value) {
        $this->output['body']['response'][$name] = $value;
    }

    /**
     * Used to get the whole service request as an array
     * 
     * @return array The request data from $_POST
     */
    public function _req() {
        return $_POST;
    }

    /**
     * Returns the authentication information from the service request
     * 
     * @return array (username => [USERNAME], skey => [SESSION_KEY])
     */
    public function _req_auth() {
        return $this->post('auth');
    }

    /**
     * Check if the authentication information is set of not
     * 
     * @return boolean
     */
    public function _req_auth_isset() {
        if ($this->issetPost('auth')) {
            $auth = $this->_req_auth();
            return isset($auth['username']) && isset($auth['skey']);
        } else {
            return false;
        }
    }

    /**
     * Returns the module name from the service request
     * 
     * @return string 
     */
    public function _req_module() {
        return $this->post('module');
    }

    /**
     * Returns if the module name from the service request isset
     * 
     * @return boolean
     */
    public function _req_module_isset() {
        return $this->issetPost('module');
    }

    /**
     * Returns the service name from the service request
     * 
     * @return string 
     */
    public function _req_service() {
        return $this->post('service');
    }

    /**
     * Returns if the module name from the service request isset
     * 
     * @return boolean
     */
    public function _req_service_isset() {
        return $this->issetPost('service');
    }

    /**
     * Returns the action name from the service request
     * 
     * @return string 
     */
    public function _req_action() {
        return $this->post('action');
    }

    /**
     * Returns if the module name from the service request isset
     * 
     * @return boolean
     */
    public function _req_action_isset() {
        return $this->issetPost('action');
    }

    /**
     * Used to fetch the service request Parameter by given name or if not
     * provided then as a whole
     * 
     * @param string $name Name of the serice request parameter
     * @return mixed 
     */
    public function _req_params($name = null) {
        $params = $this->currentPage->params;
        if (isset($name)) {
            $params = isset($params[$name]) ? $params[$name] : null;
        }
        return $params;
    }

    /**
     * Used to set the response user specific error message
     * 
     * @param string $message The error message
     */
    public function _err($message) {
        $this->output['body']['message'] = $message;
        $this->_status(self::STATUS_USER_ERROR);
    }

    /**
     * Implementation method to set the response status code
     * 
     * @param int $code The error codes
     */
    private function _status($code) {
        $this->output['header']['status'] = $code;
        $this->output['header']['status_text'] = self::$STATUS_TEXT[$code];
    }

    /**
     * The pre service initialization method. Used by the framework before the 
     * service execution. When overriding, make sure to call the parent function.
     * DO NOT CALL THIS FUNCTION EXPLICITLY
     * 
     * @param \simbola\core\component\url\lib\Page $page Execution service page
     */
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
            'service' => $this->_req_module() . "."
            . $this->_req_service() . "."
            . $this->_req_action());
    }

    /**
     * Used to set the output is set as return
     * 
     * @param boolean $value
     */
    public function setReturn($value) {
        $this->return = $value;
    }

    /**
     * Used by the framework to run the service. 
     * DO NOT CALL THIS FUNCTION EXPLICITLY
     * 
     * @param \simbola\core\component\url\lib\Page $page
     * @return mixed
     */
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
                try {
                    $this->$funcName();
                } catch (\Exception $ex) {
                    slog_syserror(__METHOD__, $ex->getTraceAsString());
                    $this->_err($ex->getMessage());
                    $this->_status(self::STATUS_ERROR);
                }
            }
        } else {
            $this->_status(self::STATUS_FORBIDDEN);
        }
        return $this->postAction($page);
    }

    /**
     * The post function in the service call. This fucntion defines the methods of
     * return. Echo or Return as array using the setReturn method
     * DO NOT CALL THIS FUNCTION EXPLICITLY
     * 
     * @param \simbola\core\component\url\lib\Page $page
     * @return mixed
     */
    public function postAction($page) {
        if (!$this->return) {
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/json');
            $output = $this->parseOutput($this->output);
            $this->json($output);
        } else {
            return $this->output;
        }
    }

    /**
     * Parse output for json
     * 
     * @param \simbola\core\application\AppModel $obj
     * @return array
     */
    private function parseOutput($obj) {
        if ($obj instanceof AppModel) {
            return json_decode($obj->to_json());
        }
        $arrObj = is_object($obj) ? get_object_vars($obj) : $obj;
        $arr = array();
        foreach ($arrObj as $key => $val) {
            $val = (is_array($val) || is_object($val)) ? $this->parseOutput($val) : $val;
            $arr[$key] = $val;
        }
        return $arr;
    }

    /**
     * Check the security by passing the page object.
     * 
     * @param \simbola\core\component\url\lib\Page $page
     * @return boolean
     */
    public function checkSecurity($page) {
        $auth = $this->_req_auth();
        $permObj = new \simbola\core\component\auth\lib\PermObject($page);
        return \simbola\Simbola::app()->auth->checkPermission($permObj, $auth['username'], $auth['skey']);
    }

}

?>
