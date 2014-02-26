<?php

namespace simbola\core\component\session;

/**
 * Description of Session
 *
 * @author Faraj
 */
class Session extends \simbola\core\component\system\lib\Component {

    public function get($key, $unset = false) {
        if (isset($_SESSION[$key])) {
            $data = $_SESSION[$key];
            if ($unset) {
                $this->set($key, NULL);
            }
            return $data;
        } else {
            return null;
        }
    }

    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    public function getGlobal($key, $unset = false) {
        if (isset($GLOBALS[$key])) {
            $data = $GLOBALS[$key];
            if ($unset) {
                $this->setGlobal($key, NULL);
            }
            return $data;
        } else {
            return null;
        }
    }

    public function setGlobal($key, $value) {
        $GLOBALS[$key] = $value;
    }

}

?>
