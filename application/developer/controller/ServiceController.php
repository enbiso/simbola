<?php
namespace application\developer\controller;

/**
 * Description of Service
 *
 * @author Faraj
 */
class ServiceController extends \simbola\core\application\AppController {
    
    function actionIndex() {
        $data = array();
        foreach (\simbola\Simbola::app()->getModuleNames() as $moduleName) {
            $module = array();
            $mConf = \simbola\Simbola::app()->getModuleConfig($moduleName);
            $serviceNs = $mConf->getNamespace("service");
            $servicePath = $mConf->getPath("service");
            $serviceWildcard = $servicePath . DIRECTORY_SEPARATOR . "*.php";
            foreach (glob($serviceWildcard) as $serviceFile) {
                $service = array();
                $serviceName = lcfirst(str_replace("Service", "", basename($serviceFile, ".php")));
                $serviceClass = $serviceNs . "\\" . basename($serviceFile, ".php");                
                foreach (get_class_vars($serviceClass) as $propName => $value) {      
                    if(sstring_starts_with($propName, "schema_")){
                        $actionName = str_replace("schema_", "", $propName);                    
                        $service[$actionName] = $value;
                    }
                }
                $module[$serviceName] = $service;
            } 
            $data[$moduleName] = $module;
        }
        $this->setViewData('modules', $data);
        $this->view('service/index');
    }
    
    function actionTester() {
        if($this->issetGet('service')){
            $serArr = explode(".", $this->get('service'));
            $mConf = \simbola\Simbola::app()->getModuleConfig($serArr[0]);
            $serviceNs = $mConf->getNamespace("service");
            $serviceClass = $serviceNs."\\".ucfirst($serArr[1])."Service";
            $schemaProp = "schema_{$serArr[2]}";
            $serObj = new $serviceClass;
            $schema = $serObj->$schemaProp;
            $this->setViewData("module", $serArr[0]);
            $this->setViewData("service", $serArr[1]);
            $this->setViewData("action", $serArr[2]);
            $this->setViewData("schema", $schema);
            if($this->issetGet('partial')) {
                $this->pview("service/tester");
            } else {
                $this->view("service/tester");
            }
        }else{
            if($this->issetGet('partial')){
                $this->pview("service/tester_default");
            }  else {
                $this->view("service/tester_default");
            }
        }
    }
}
