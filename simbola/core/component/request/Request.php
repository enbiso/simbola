<?php
namespace simbola\core\component\request;

class Request extends \simbola\core\component\system\lib\Component {

    function get($name) {
        if(isset($_GET[$name])){
            return $_GET[$name];
        }
        return null;
    }
    
    function post($name) {
        if(isset($_POST[$name])){
            return $_POST[$name];
        }
        return null;
    }

}