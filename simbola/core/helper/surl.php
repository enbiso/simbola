<?php
/**
 * Get URL string from the array
 * 
 * @param array $array URL as array
 * @return string URL
 */
function surl_geturl($array){
    $page = new \simbola\core\component\url\lib\Page;
    $page->loadFromArray($array);
    return $page->getUrl();
}