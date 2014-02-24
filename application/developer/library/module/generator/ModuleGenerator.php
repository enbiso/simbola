<?php
namespace application\developer\library\module\generator;
/**
 * Description of ModuleGenerator
 *
 * @author Faraj
 */
class ModuleGenerator extends CodeGenerator{
    
    public function __construct($module, $purpose) {
        parent::__construct($module, "", "", "", "", $purpose);
    }
    
    public function generate() {
        $app = \simbola\Simbola::app();
        $moduleName = $this->module;
        $modulePath = \simbola\core\application\AppModuleConfig::GetPathOfModule($moduleName);
        $configFile = $modulePath . DIRECTORY_SEPARATOR . 'Config.php';
        if (file_exists($modulePath)) {
            $this->_err("MODULE_EXIST");
        } else {
            $templatePath = $this->getTemplatePath('module.zip');
            $zip = new \ZipArchive;
            $zip->open($templatePath);
            $zip->extractTo($modulePath);
            $zip->close();
            $configContent = file_get_contents($configFile);
            $configContent = str_replace("#AUTHER#", \simbola\Simbola::app()->auth->getUsername(), $configContent);
            $configContent = str_replace("#TODAY_DATE#", date("dMY"), $configContent);
            $configContent = str_replace("#MODULE_NAME#", $this->module, $configContent);
            $configContent = str_replace("#MODULE_PURPOSE#", $this->purpose, $configContent);
            $configFile = $modulePath . DIRECTORY_SEPARATOR . "Config.php";
            file_put_contents($configFile, $configContent);
        }
    }
}
