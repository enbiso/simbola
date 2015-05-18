<?php

namespace simbola\core\component\url;

/** 
 * Url component definitions
 *
 * @author Faraj Farook
 */
class Url extends \simbola\core\component\system\lib\Component {

    /**
     * Setup the defaults
     */
    public function setupDefault() {
        $this->setParamDefault("HIDE_INDEX", false);
        $this->setParamDefault("URL_BASE", false);        
        //Alias - Start
        $this->setParamDefault("ALIAS", array());        
        //Alias - End
    }
    
    /**
     * Decodes the url string and returns the Page object
     * 
     * @param type $urlString
     * @return \simbola\core\component\url\lib\Page
     */
    public function decode($urlString) {
        $page = new lib\Page();    
        $page->loadFromUrl($urlString);
        return $page;
    }
     
    /**
     * Fetch the absolute URL of the request 
     *  For example 
     *   http://www.example.com/app/index.php/www/site/index
     *   http://www.example.com/app/www/site/index
     *   http://www.example.com/www/site/index
     * 
     * @return string
     */
    public function getAbsoluteUrl() {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Fetch and returns the base URL of the application
     *  http://www.example.com/[app_base] - returns if set withAppBaseUrl to TRUE
     *  http://www.example.com
     * @param bool $withAppBaseUrl withAppBaseUrl default TRUE
     * @return string
     */
    public function getBaseUrl($withAppBaseUrl = true) {        
        $url = 'http' . ((!empty($_SERVER['HTTPS'])) ? "s" : "") . "://" . $_SERVER['SERVER_NAME'];
        $appBaseUrl = $this->getAppUrlBase();
        if($withAppBaseUrl && $appBaseUrl != ""){
            $url  = $url . "/" . $this->getAppUrlBase();
        }
        return $url;
    }
    
    /**
     * Fetch and returns Application URL_BASE parameter 
     * 
     * @return string
     */
    public function getAppUrlBase() {        
        $urlBase = $this->getParam("URL_BASE");
        if(!$urlBase) {
            return "";
        }else if(!sstring_ends_with($urlBase, "/")){
            return "{$urlBase}/";
        } else {    
            return $urlBase;    
        }
    }

    /**
     * Redirect to the page specified
     * 
     * @param lib\Page $page
     */
    public function redirect($page) {        
        header("Location: {$page->getUrl()}");
    }

}

?>
