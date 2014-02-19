<?php
function surl_geturl($array){
    $page = new \simbola\core\component\url\lib\Page;
    $page->loadFromArray($array);
    return $page->getUrl();
}