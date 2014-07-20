<?php
/**
 * Simbola javascript initialization. Have to place it in the end of the layout
 */
function simbola_js_init(){
    $params = json_encode(array(
        'sys' => simbola\Simbola::app()->getParams(),
        'url' => simbola\Simbola::app()->url->getParams(),
    ));
    $auth = json_encode(array(
        'username' => \simbola\Simbola::app()->auth->getUsername(),
        'skey' => \simbola\Simbola::app()->auth->getSessionKey()
    ));
    echo shtml_tag("script");
    echo "simbola.init({$params},{$auth})";
    echo shtml_untag("script");
}