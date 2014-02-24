<?php
namespace application\developer\library\module\generator;
/**
 * Description of ServiceGenerator
 *
 * @author Faraj
 */
class ServiceGenerator extends CodeGenerator{
    public function __construct($module, $lu, $model, $service, $purpose) {
        parent::__construct($module, $lu, $model, $service, "", $purpose);
    }
    
    public function generate() {                
        $content = $this->getTemplateContent("service.txt");
        $content = $this->initializeWithBasicInfo($content);
        $app = \simbola\Simbola::app();
        $mconf = $app->getModuleConfig($this->module);
        $servicePath = $mconf->getPath('service')
                . DIRECTORY_SEPARATOR . ucfirst($this->service) . "Service.php";
        file_put_contents($servicePath, $content);
    }
}
