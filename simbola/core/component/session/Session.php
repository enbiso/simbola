<?php

namespace simbola\core\component\session;

/**
 * Description of Session
 *
 * @author Faraj
 */
class Session extends \simbola\core\component\system\lib\Component {

    public function init() {
        parent::init();
        $this->cacheInit();
    }

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
    
    //Session Cache - Start

    const CACHE = 'simbola_cache';

    private function cacheInit() {
        if (is_null($this->get(self::CACHE))) {
            $this->set(self::CACHE, array());
        }
    }

    public function cacheSet($name, $value) {
        $cache = $this->get(self::CACHE);
        $cache[$name] = $value;
        $this->set(self::CACHE, $cache);
    }

    public function cacheIsset($name) {
        $cache = $this->get(self::CACHE);
        return array_key_exists($name, $cache) && isset($cache[$name]);
    }

    public function cacheGet($name = null) {
        $cache = $this->get(self::CACHE);
        return is_null($name) ? $cache : $cache[$name];
    }
    
    public function cacheReset($name) {
        $cache = $this->get(self::CACHE);
        unset($cache[$name]);
        $this->set(self::CACHE, $cache);
    }
    //Session Cache - End
}

?>
