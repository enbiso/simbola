<?php

namespace simbola\core\component\social;

include_once 'Hybrid/Auth.php';
include_once 'Hybrid/Endpoint.php';

class Social extends \simbola\core\component\system\lib\Component {

    protected $hybrid;
    
    public function setupDefault() {
        $this->setParamDefault('DEBUG', array(
            'ENABLE' => false,            
            'FILE' => "./".\simbola\Simbola::app()->getParam('BASE')."/social.logs"
        ));
        $this->setParamDefault("PROVIDERS", array());
    }

    public function init() {
        parent::init();
        if(!isset($this->params['BASE_URL'])){            
            $socialPage = new \simbola\core\component\url\lib\Page();
            $socialPage->type = \simbola\core\component\url\lib\Page::$TYPE_CONTROLLER;
            $socialPage->logical_unit = "system";
            $socialPage->action = "social";            
            $this->params['BASE_URL'] = $socialPage->getUrlWithBaseUrl();
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

    public function get($provider) {
        return $this->hybrid->authenticate($provider);  
    }
}