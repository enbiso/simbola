<?php

namespace application\developer\library\module\generator;

/**
 * Description of Model
 *
 * @author Faraj
 */
class DbTableGenerator extends CodeGenerator {

    public function __construct($module, $lu, $model, $purpose) {
        parent::__construct($module, $lu, $model, "", "", $purpose);
    }

    public function generate() {        
        $content = $this->getTemplateContent('db' . DIRECTORY_SEPARATOR . 'table.txt');        
        $content = $this->initializeWithBasicInfo($content, true);        
        $app = \simbola\Simbola::app();
        $mconf = $app->getModuleConfig($this->module);        
        $dbTablePath = $mconf->getPath('database') . DIRECTORY_SEPARATOR . $this->lu;
        $dbTablePath .= DIRECTORY_SEPARATOR . 'table' . DIRECTORY_SEPARATOR . ucfirst($this->model) . ".php";
        file_put_contents($dbTablePath, $content);
    }

}
