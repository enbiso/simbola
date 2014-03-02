<?php

namespace simbola\core\component\session;

/**
 * Session component definitions
 *
 * @author Faraj Farook
 */
class Session extends \simbola\core\component\system\lib\Component {

    /**
     * Cache session name
     */
    const CACHE = 'simbola_cache';
    
    /**
     * Initialization of session component
     */
    public function init() {
        parent::init();
        $this->cacheInit();
    }

    /**
     * Returns the session value according to the key name provided
     * 
     * @param string $key Session key name
     * @param boolean $unset TRUE - unset the session when return the value, FALSE (default)
     * @return mixed
     */
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

    /**
     * Store a value for the given session name
     * 
     * @param string $key Session key name
     * @param mixed $value Session value
     */
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Initialize session cache
     */
    private function cacheInit() {
        if (is_null($this->get(self::CACHE))) {
            $this->set(self::CACHE, array());
        }
    }

    /**
     * Set session cache
     * 
     * @param string $name Session cache name
     * @param mixed $value
     */
    public function cacheSet($name, $value) {
        $cache = $this->get(self::CACHE);
        $cache[$name] = $value;
        $this->set(self::CACHE, $cache);
    }

    /**
     * Check if the session cache is set for the given name
     * 
     * @param string $name Session cache name
     * @return boolean
     */
    public function cacheIsset($name) {
        $cache = $this->get(self::CACHE);
        return array_key_exists($name, $cache) && isset($cache[$name]);
    }

    /**
     * Returns the session cache data
     * 
     * @param string $name Session cache name
     * @return mixed
     */
    public function cacheGet($name = null) {
        $cache = $this->get(self::CACHE);
        return is_null($name) ? $cache : $cache[$name];
    }
    
    /**
     * Reset the session cache object
     * 
     * @param string $name Session cache name
     */
    public function cacheReset($name) {
        $cache = $this->get(self::CACHE);
        unset($cache[$name]);
        $this->set(self::CACHE, $cache);
    }
}

?>
