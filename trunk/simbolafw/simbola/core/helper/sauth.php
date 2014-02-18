<?php

function sauth_check($url) {
    if (is_string($url)) {
        $url = array($url);
    }
    $page = new simbola\core\component\url\lib\Page();
    $page->loadFromArray($url);
    $page = simbola\Simbola::app()->router->initWithDefaults($page);
    $auth = simbola\Simbola::app()->auth;
    return $auth->checkPermissionByPage($page);
}

function sauth_filter_menu_array($values) {
    $valuesOut = array();
    foreach ($values as $key => $value) {
        $link = is_numeric($key) ? $value['link'] : $value;
        if (sauth_check($link)) {
            $valuesOut[$key] = $value;
        }
    }
    return $valuesOut;
}
