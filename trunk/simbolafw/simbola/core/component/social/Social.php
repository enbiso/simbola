<?php

namespace simbola\core\component\social;

include_once 'Hybrid/Auth.php';
include_once 'Hybrid/Endpoint.php';

/**
 * Social Component Definitions
 *
 * @author Faraj Farook
 */
class Social extends \simbola\core\component\system\lib\Component {

    /**
     * Hybrid auth object
     * @var \Hybrid_Auth 
     */
    protected $hybrid;
    
    /**
     * Setup default component values
     */
    public function setupDefault() {
        $this->setParamDefault('DEBUG', array(
            'ENABLE' => false,            
            'FILE' => "./".\simbola\Simbola::app()->getParam('BASE')."/social.logs"
        ));
        $this->setParamDefault("PROVIDERS", array());
    }

    /**
     * Intilialize the component
     */
    public function init() {
        parent::init();
        if(!isset($this->params['BASE_URL'])){            
            $socialPage = new \simbola\core\component\url\lib\Page();
            $socialPage->type = \simbola\core\component\url\lib\Page::TYPE_CONTROLLER;
            $socialPage->logical_unit = "system";
            $socialPage->action = "social";            
            $this->params['BASE_URL'] = $socialPage->getAbsoluteUrl();
        }
        
        if($this->params['DEBUG']['ENABLE'] && !file_exists($this->params['DEBUG']['FILE'])){
            touch($this->params['DEBUG']['FILE']);
        }
        
        $config = array(
            "base_url" => $this->params['BASE_URL'],            
            "debug_mode" => $this->params['DEBUG']['ENABLE'],
            "debug_file" => $this->params['DEBUG']['FILE'],
            "providers" => $this->params['PROVIDERS']
        );        
        $this->hybrid = new \Hybrid_Auth($config);
    }

    /**
     * Returns the Hybrid social object accoridng to the given provider name     
     * 
     * @param string $provider Hybrid Social provider name
     * @return type
     */
    public function get($provider) {
        return $this->hybrid->authenticate($provider);  
    }
}