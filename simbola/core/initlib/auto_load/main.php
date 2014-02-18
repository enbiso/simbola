<?php
function __autoload($class_name) {    
    $file_name = str_replace("\\", DIRECTORY_SEPARATOR, $class_name). ".php";      
    $file_name = DIRECTORY_SEPARATOR.$file_name;    
    $fw_pos = strpos($file_name, "simbola".DIRECTORY_SEPARATOR);
    if($fw_pos){
        $file_path = simbola\Simbola::app()->basepath('fw') . substr($file_name, $fw_pos + 7);//8 = strlen("simbola")
    }else{
        $file_path = simbola\Simbola::app()->basepath('app') . $file_name;
    }
    if (file_exists($file_path)) {
        include_once $file_path;
        if(method_exists($class_name, "__static")){            
            $class_name::__static();
        }   
    } else{
        throw new \Exception("Cannot load {$class_name} from {$file_path}");
    }    
}
