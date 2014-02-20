<?php

namespace simbola\core\component\url;

/**
 * Description of Util
 *
 * @author Faraj
 */
class Url extends \simbola\core\component\system\lib\Component {

    public function setupDefault() {
        $this->setParamDefault("HIDE_INDEX", true);
        $this->setParamDefault("URL_BASE", false);
    }
    
    public function decode($url_string) {
        $page = new lib\Page();
        $page->loadFromUrl($url_string);
        return $page;
    }
    
    public function getAbsoluteUrl() {
        return $this->getBaseUrl() . $_SERVER['REQUEST_URI'];
    }

    public function getBaseUrl() {        
        $url = 'http' . ((!empty($_SERVER['HTTPS'])) ? "s" : "") . "://" . $_SERVER['SERVER_NAME'];
        $appBaseUrl = $this->getAppUrlBase();
        if($appBaseUrl != ""){
            $url  = $url . "/" . $this->getAppUrlBase();
        }
        return $url;
    }
    
    public function getAppUrlBase() {
        $postFix = "";
        if($this->getParam("URL_BASE")){
            $postFix = $this->getParam("URL_BASE");
        }
        return $postFix;
    }

    public function redirect($page) {        
        header("Location: {$page->getUrl()}");
    }

}

?>
