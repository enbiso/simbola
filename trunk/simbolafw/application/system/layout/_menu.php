<?php
function menu_item($text, $url, $partial = 'action') {
    $page = new simbola\core\component\url\lib\Page();
    $page->loadFromArray($url);
    $page = simbola\Simbola::app()->router->initWithDefaults($page, false);
    $auth = simbola\Simbola::app()->auth;
    if(!$auth->checkPermissionByPage($page)){
        return;
    }
    $opts = array();
    if($partial == 'module'){
        if (simbola\Simbola::app()->router->page->module == $page->module){
            $opts['class'] = 'active';
        }
    }elseif($partial == 'lu'){
        if (simbola\Simbola::app()->router->page->module == $page->module &&
           \simbola\Simbola::app()->router->page->logicalUnit == $page->logicalUnit) {
            $opts['class'] = 'active';
        }
    }elseif($partial == 'action'){
        if (simbola\Simbola::app()->router->page->module == $page->module &&
           \simbola\Simbola::app()->router->page->logicalUnit == $page->logicalUnit &&
           \simbola\Simbola::app()->router->page->action == $page->action) {
            $opts['class'] = 'active';
        }
    }
    echo shtml_tag('li', $opts);
    echo shtml_action_link($text, $url);
    echo shtml_untag('li');
}
