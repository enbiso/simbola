<?php

namespace application\developer\service;

/**
 * Description of ModuleService
 *
 * @author Faraj
 */
class ModuleService extends \simbola\core\application\AppService {

    public $schema_create = array(
        'req' => array('params' => array('module', 'purpose')),
        'res' => array(),
        'err' => array('MODULE_EXIST')
    );

    function actionCreate() {        
        $app = \simbola\Simbola::app();
        $moduleName = $this->_req_params("module");        
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
            $configContent = str_replace("#MODULE_NAME#", $this->_req_params('module'), $configContent);
            $configContent = str_replace("#MODULE_PURPOSE#", $this->_req_params('purpose'), $configContent);
            $configFile = $modulePath . DIRECTORY_SEPARATOR . "Config.php";
            file_put_contents($configFile, $configContent);
        }
    }

    public $schema_createModel = array(
        'req' => array('params' => array('module', 'lu', 'model', 'purpose')),
        'res' => array(),
        'err' => array('MODEL_EXIST')
    );

    function actionCreateModel() {
        //model
        $templatePath = $this->getTemplatePath('model.txt');
        $content = file_get_contents($templatePath);
        $content = str_replace("#AUTHER#", \simbola\Simbola::app()->auth->getUsername(), $content);
        $content = str_replace("#TODAY_DATE#", date("dMY"), $content);
        $content = str_replace("#MODULE_NAME#", $this->_req_params('module'), $content);
        $content = str_replace("#MODEL_NAME#", $this->_req_params('model'), $content);
        $content = str_replace("#TABLE_NAME#", sstring_camelcase_to_underscore($this->_req_params('model')), $content);
        $content = str_replace("#MODEL_PURPOSE#", $this->_req_params('purpose'), $content);
        $content = str_replace("#LU_NAME#", $this->_req_params('lu'), $content);
        $content = str_replace("#MODEL_CLASS_NAME#", ucfirst($this->_req_params('model')), $content);
        $app = \simbola\Simbola::app();
        $mconf = $app->getModuleConfig($this->_req_params('module'));
        $modelPath = $mconf->getPath('model') . DIRECTORY_SEPARATOR . $this->_req_params('lu');
        if (!is_dir($modelPath)) {
            mkdir($modelPath);
        }
        $modelPath .= DIRECTORY_SEPARATOR . ucfirst($this->_req_params('model')) . ".php";
        file_put_contents($modelPath, $content);
        //model term        
        $this->createTerms('model', 'model');
    }

    public $schema_createService = array(
        'req' => array('params' => array('module', 'lu', 'model', 'service', 'purpose')),
        'res' => array(),
        'err' => array('SERVICE_EXIST')
    );

    function actionCreateService() {
        $templatePath = $this->getTemplatePath('service.txt');
        $content = file_get_contents($templatePath);
        $content = str_replace("#AUTHER#", \simbola\Simbola::app()->auth->getUsername(), $content);
        $content = str_replace("#TODAY_DATE#", date("dMY"), $content);
        $content = str_replace("#MODULE_NAME#", $this->_req_params('module'), $content);
        $content = str_replace("#MODEL_NAME#", $this->_req_params('model'), $content);
        $content = str_replace("#SERVICE_NAME#", $this->_req_params('service'), $content);
        $content = str_replace("#SERVICE_PURPOSE#", $this->_req_params('purpose'), $content);
        $content = str_replace("#LU_NAME#", $this->_req_params('lu'), $content);
        $content = str_replace("#SERVICE_CLASS_NAME#", ucfirst($this->_req_params('service')), $content);
        $modelClass = \simbola\core\application\AppModel::getClass($this->_req_params('module'), $this->_req_params('lu'), $this->_req_params('model'));
        $content = str_replace("#MODEL_CLASS#", $modelClass, $content);
        $app = \simbola\Simbola::app();
        $mconf = $app->getModuleConfig($this->_req_params('module'));
        $servicePath = $mconf->getPath('service')
                . DIRECTORY_SEPARATOR . ucfirst($this->_req_params('service')) . "Service.php";
        file_put_contents($servicePath, $content);
    }

    public $schema_createController = array(
        'req' => array('params' => array('module', 'lu', 'model', 'controller', 'service', 'purpose')),
        'res' => array(),
        'err' => array('CONTROLLER_EXIST')
    );
    private $modelClass;

    function actionCreateController() {
        //controller
        $this->loadKeysAndColsArrays();
        $this->loadFieldElements();
        $this->modelClass = \simbola\core\application\AppModel::getClass($this->_req_params('module'), $this->_req_params('lu'), $this->_req_params('model'));

        $templatePath = $this->getTemplatePath('controller.txt');
        $content = file_get_contents($templatePath);
        $content = str_replace("#AUTHER#", \simbola\Simbola::app()->auth->getUsername(), $content);
        $content = str_replace("#TODAY_DATE#", date("dMY"), $content);
        $content = str_replace("#MODULE_NAME#", $this->_req_params('module'), $content);
        $content = str_replace("#CONTROLLER_NAME#", $this->_req_params('controller'), $content);
        $content = str_replace("#CONTROLLER_PURPOSE#", $this->_req_params('purpose'), $content);
        $content = str_replace("#SERVICE_NAME#", $this->_req_params('service'), $content);
        $content = str_replace("#CONTROLLER_CLASS_NAME#", ucfirst($this->_req_params('controller')), $content);
        $content = str_replace("#KEYS_ARRAY_FROM_OBJ#", implode("," . PHP_EOL, $this->keysArrayFromObj), $content);
        $content = str_replace("#KEYS_ARRAY_FROM_GET#", implode("," . PHP_EOL, $this->keysArrayFromGet), $content);
        $keysArray = $this->getKeysArray($this->_req_params('module'), $this->_req_params('lu'), $this->_req_params('model'));
        $content = str_replace("#KEYS_COMMA_STR#", implode("','", $keysArray), $content);
        $content = str_replace("#MODEL_CLASS#", $this->modelClass, $content);
        $app = \simbola\Simbola::app();
        $mconf = $app->getModuleConfig($this->_req_params('module'));
        $servicePath = $mconf->getPath('controller')
                . DIRECTORY_SEPARATOR . ucfirst($this->_req_params('controller')) . "Controller.php";
        file_put_contents($servicePath, $content);

        //views & terms
        $this->createView('create');
        $this->createTerms('create');
        $this->createView('index');
        $this->createTerms('index');
        $this->createView('list');
        $this->createTerms('list');
        $this->createView('update');
        $this->createTerms('update');
        $this->createView('delete');
        $this->createTerms('delete');
        $this->createView('view');
        $this->createTerms('view');
        $this->createView('_form');
        $this->createTerms('_form');
    }

    private $keysArrayFromObj = array();
    private $keysArrayFromGet = array();
    private $keysArrayForLink = array();
    private $colsArrayForTable = array();

    private function loadKeysAndColsArrays() {
        $keysArray = $this->getKeysArray($this->_req_params('module'), $this->_req_params('lu'), $this->_req_params('model'));
        foreach ($keysArray as $key) {
            $this->keysArrayFromObj[] = '"' . $key . '" => $object->' . $key;
            $this->keysArrayFromGet[] = '"' . $key . '" => $this->get("' . $key . '")';
            $this->keysArrayForLink[] = '[' . $key . ':%' . $key . '%]';
        }
        $colsArray = $this->getColsArray($this->_req_params('module'), $this->_req_params('lu'), $this->_req_params('model'));
        foreach ($colsArray as $col) {
            $this->colsArrayForTable[] = "\t" . '"' . $col . '" => "' . $col . '"';
        }
    }

    private $formElements = array();
    private $formHiddenElements = array();
    private $viewElements = array();

    private function loadFieldElements() {
        $colsArray = $this->getColsArray($this->_req_params('module'), $this->_req_params('lu'), $this->_req_params('model'));
        $elem['textbox'] = file_get_contents($this->getTemplatePath('view' . DIRECTORY_SEPARATOR . "element" . DIRECTORY_SEPARATOR . "textbox.txt"));
        $elem['fieldbox'] = file_get_contents($this->getTemplatePath('view' . DIRECTORY_SEPARATOR . "element" . DIRECTORY_SEPARATOR . "fieldbox.txt"));
        $elem['hiddenbox'] = file_get_contents($this->getTemplatePath('view' . DIRECTORY_SEPARATOR . "element" . DIRECTORY_SEPARATOR . "hiddenbox.txt"));
        foreach ($colsArray as $col) {
            $content = $elem['textbox'];
            $content = str_replace("#CONTROLLER_NAME#", $this->_req_params('controller'), $content);
            $content = str_replace("#MODULE_NAME#", $this->_req_params('module'), $content);
            $content = str_replace("#FIELD_NAME#", $col, $content);
            $content = str_replace("#PLACEHOLDER#", ucfirst($col), $content);
            $this->formElements[] = $content;
            
            $content = $elem['fieldbox'];
            $content = str_replace("#FIELD_NAME#", $col, $content);            
            $this->viewElements[] = $content;
        }
        $keyArray = $this->getKeysArray($this->_req_params('module'), $this->_req_params('lu'), $this->_req_params('model'));
        foreach ($keyArray as $key) {
            $content = $elem['hiddenbox'];
            $content = str_replace("#FIELD_NAME#", $key, $content);            
            $this->formHiddenElements[] = $content;
        }
    }

    private function createTerms($template, $termType = 'controller') {
        $app = \simbola\Simbola::app();
        $mconf = $app->getModuleConfig($this->_req_params('module'));
        $templatePath = $this->getTemplatePath('term'
                . DIRECTORY_SEPARATOR . "en_US"
                . DIRECTORY_SEPARATOR . $termType
                . DIRECTORY_SEPARATOR . $template . ".txt");
        $content = file_get_contents($templatePath);
        $content = str_replace("#MODULE_NAME#", $this->_req_params('module'), $content);
        $content = str_replace("#CONTROLLER_NAME#", $this->_req_params('controller'), $content);
        $content = str_replace("#UCF_CONTROLLER_NAME#", ucfirst($this->_req_params('controller')), $content);
        $content = str_replace("#CTS_CONTROLLER_NAME#", sstring_camelcase_to_space($this->_req_params('controller')), $content);
        $content = str_replace("#AUTHER#", \simbola\Simbola::app()->auth->getUsername(), $content);
        $content = str_replace("#TODAY_DATE#", date("dMY"), $content);
        $content = str_replace("#CONTROLLER_PURPOSE#", $this->_req_params('purpose'), $content);

        if ($termType == 'model') {
            $termEntries = array();
            $modelClassName = \simbola\core\application\AppModel::getClass($this->_req_params('module'), $this->_req_params('lu'), $this->_req_params('model'));
            foreach ($this->getColsArray($this->_req_params('module'), $this->_req_params('lu'), $this->_req_params('model')) as $col) {
                $termName = \simbola\Simbola::app()->term->getModelTermName($modelClassName, $col);
                $termEntries[] = '$__term["' . $termName . '"] = "' . str_replace("_", " ", ucfirst($col)) . '";';
            }
            $content = str_replace("#TERM_ENTRIES#", implode("\n", $termEntries), $content);
            $content = str_replace("#AUTHER#", \simbola\Simbola::app()->auth->getUsername(), $content);
            $content = str_replace("#TODAY_DATE#", date("dMY"), $content);
            $content = str_replace("#MODEL_NAME#", $this->_req_params('model'), $content);
            $content = str_replace("#MODEL_PURPOSE#", $this->_req_params('purpose'), $content);
        }

        $termPath = $mconf->getPath('term')
                . DIRECTORY_SEPARATOR . 'en_US'
                . DIRECTORY_SEPARATOR . $termType;
        if (!is_dir($termPath)) {
            mkdir($termPath);
        }
        if ($termType == 'controller') {
            $termPath = $termPath . DIRECTORY_SEPARATOR . $this->_req_params('controller');
            if (!is_dir($termPath)) {
                mkdir($termPath);
            }
            $dest = $termPath . DIRECTORY_SEPARATOR . $template . ".php";
            file_put_contents($dest, $content);
        } else {
            $termPath = $termPath . DIRECTORY_SEPARATOR . $this->_req_params('lu');
            if (!is_dir($termPath)) {
                mkdir($termPath);
            }
            $dest = $termPath . DIRECTORY_SEPARATOR . ucfirst($this->_req_params('model')) . ".php";
            file_put_contents($dest, $content);
        }
    }

    private function createView($template) {
        $templatePath = $this->getTemplatePath('view'
                . DIRECTORY_SEPARATOR . $template . ".txt");
        $content = file_get_contents($templatePath);
        $content = str_replace("#AUTHER#", \simbola\Simbola::app()->auth->getUsername(), $content);
        $content = str_replace("#TODAY_DATE#", date("dMY"), $content);
        $content = str_replace("#MODULE_NAME#", $this->_req_params('module'), $content);
        $content = str_replace("#LU_NAME#", $this->_req_params('lu'), $content);
        $content = str_replace("#MODEL_NAME#", $this->_req_params('model'), $content);
        $content = str_replace("#MODEL_CLASS#", $this->modelClass, $content);
        $content = str_replace("#UCF_MODULE_NAME#", ucfirst($this->_req_params('module')), $content);
        $content = str_replace("#CONTROLLER_NAME#", $this->_req_params('controller'), $content);
        $content = str_replace("#UCF_CONTROLLER_NAME#", ucfirst($this->_req_params('controller')), $content);
        $content = str_replace("#CTS_CONTROLLER_NAME#", sstring_camelcase_to_space($this->_req_params('controller')), $content);
        $content = str_replace("#SERVICE_NAME#", $this->_req_params('service'), $content);
        $content = str_replace("#CONTROLLER_CLASS_NAME#", ucfirst($this->_req_params('controller')), $content);
        $content = str_replace("#KEYS_ARRAY_FROM_OBJ#", implode("," . PHP_EOL, $this->keysArrayFromObj), $content);
        $content = str_replace("#COLS_ARRAY_FOR_TABLE#", implode("," . PHP_EOL, $this->colsArrayForTable), $content);
        $content = str_replace("#KEYS_ARRAY_FOR_LINK#", implode("", $this->keysArrayForLink), $content);
        $content = str_replace("#FORM_ELEMENTS#", implode("\n", $this->formElements), $content);
        $content = str_replace("#FORM_HIDDEN_ELEMENTS#", implode("\n", $this->formHiddenElements), $content);
        $content = str_replace("#VIEW_ELEMENTS#", implode("<hr/>\n", $this->viewElements), $content);
        $app = \simbola\Simbola::app();
        $mconf = $app->getModuleConfig($this->_req_params('module'));
        $viewPath = $mconf->getPath('view')
                . DIRECTORY_SEPARATOR . $this->_req_params('controller');
        if (!is_dir($viewPath)) {
            mkdir($viewPath);
        }
        $servicePath = $viewPath . DIRECTORY_SEPARATOR . $template . ".php";
        file_put_contents($servicePath, $content);
    }

    private function getKeysArray($module, $lu, $model) {
        $class = \simbola\core\application\AppModel::getClass($module, $lu, $model);
        $keysArr = array();
        foreach ($class::Keys() as $key) {
            $keysArr[] = $key->name;
        }
        return $keysArr;
    }

    private function getColsArray($module, $lu, $model) {
        $class = \simbola\core\application\AppModel::getClass($module, $lu, $model);
        $colsArr = array();
        foreach ($class::Columns() as $col) {
            if (!sstring_starts_with($col->name, "_")) {
                $colsArr[] = $col->name;
            }
        }
        return $colsArr;
    }

    private function getTemplatePath($templateName) {
        $app = \simbola\Simbola::app();
        $mconf = $app->getModuleConfig('developer');
        return $mconf->getPath('library')
                . DIRECTORY_SEPARATOR . "module"
                . DIRECTORY_SEPARATOR . "templates"
                . DIRECTORY_SEPARATOR . $templateName;
    }

}

?>
