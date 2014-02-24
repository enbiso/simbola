<?php

namespace application\developer\library\module\generator;

/**
 * Description of Model
 *
 * @author Faraj
 */
class ModelGenerator extends CodeGenerator {

    public function __construct($module, $lu, $model, $purpose) {
        parent::__construct($module, $lu, $model, "", "", $purpose);
    }

    public function generate() {
        $content = $this->getTemplateContent('model.txt');
        $content = $this->initializeWithBasicInfo($content, true);
        //begin - enable validations, primary key, and relationships
        $tblMeta = $this->getTableMeta();
        $content = str_replace("#MODEL_PROPERTIES#", $this->getModelProperties($tblMeta), $content);
        $content = str_replace("#MODEL_PRIMARY_KEYS#", $this->getModelPrimaryKeys($tblMeta), $content);
        $content = str_replace("#MODEL_SETUP_HAS_MANY#", $this->getModelHasMany($tblMeta), $content);
        $content = str_replace("#MODEL_SETUP_BELONGS_TO#", $this->getModelBelongsTo($tblMeta), $content);
        $content = str_replace("#MODEL_SETUP_VALIDATION#", $this->getModelValidation($tblMeta), $content);
        //end - enable validations, primary key, and relationships
        $app = \simbola\Simbola::app();
        $mconf = $app->getModuleConfig($this->module);
        $modelPath = $mconf->getPath('model') . DIRECTORY_SEPARATOR . $this->lu;
        if (!is_dir($modelPath)) {
            mkdir($modelPath);
        }
        $modelPath .= DIRECTORY_SEPARATOR . ucfirst($this->model) . ".php";
        file_put_contents($modelPath, $content);
        //model term        
        $this->createTerms();
    }

    private function createTerms() {
        $app = \simbola\Simbola::app();
        $mconf = $app->getModuleConfig($this->module);
        $content = $this->getTemplateContent('term'
                . DIRECTORY_SEPARATOR . "en_US"
                . DIRECTORY_SEPARATOR . "model"
                . DIRECTORY_SEPARATOR . "model.txt");        
        $content = $this->initializeWithBasicInfo($content);
        
        $termEntries = array();
        $modelClassName = \simbola\core\application\AppModel::getClass($this->module, $this->lu, $this->model);
        foreach ($this->getColsArray() as $col) {
            $termName = \simbola\Simbola::app()->term->getModelTermName($modelClassName, $col);
            $termEntries[] = '$__term["' . $termName . '"] = "' . str_replace("_", " ", ucfirst($col)) . '";';
        }
        $content = str_replace("#TERM_ENTRIES#", implode("\n", $termEntries), $content);        

        //generate folders
        $termPath = $mconf->getPath('term')
                . DIRECTORY_SEPARATOR . 'en_US'
                . DIRECTORY_SEPARATOR . 'model';
        if (!is_dir($termPath)) {
            mkdir($termPath);
        }        
        $termPath = $termPath . DIRECTORY_SEPARATOR . $this->lu;
        if (!is_dir($termPath)) {
            mkdir($termPath);
        }
        //save file
        $dest = $termPath . DIRECTORY_SEPARATOR . ucfirst($this->model) . ".php";
        file_put_contents($dest, $content);        
    }

    private function getModelHasMany($tblMeta) {
        $relations = $tblMeta['relations']['has_many'];
        $hasMany = array();
        $template = 'self::hasMany(array("#NAME#", "class_name" => "application\#REF_MODULE_NAME#\model\#REF_LU_NAME#\#REF_ITEM_NAME#", "foreign_key" => "#REF_COL_NAME#", "primary_key" => "#COL_NAME#"))';
        foreach ($relations as $rel) {
            $content = $template;
            $content = str_replace("#NAME#", sstring_underscore_to_camelcase($rel['ref_column_name'])."s", $content);
            $content = str_replace("#REF_MODULE_NAME#", $rel['ref_table']['module'], $content);
            $content = str_replace("#REF_LU_NAME#", $rel['ref_table']['lu'], $content);
            $content = str_replace("#REF_ITEM_NAME#", $rel['ref_table']['name'], $content);
            $content = str_replace("#REF_COL_NAME#", $rel['ref_column_name'], $content);
            $content = str_replace("#COL_NAME#", $rel['column_name'], $content);
            $hasMany[] = $content;        
        }
        return implode("\n        ", $hasMany);
    }

    private function getModelBelongsTo($tblMeta) {
        $relations = $tblMeta['relations']['belongs_to'];
        $belongsTo = array();
        $template = 'self::belongsTo(array("#NAME#", "class_name" => "application\#REF_MODULE_NAME#\model\#REF_LU_NAME#\#REF_ITEM_NAME#", "foreign_key" => "COL_NAME#", "primary_key" => "#REF_COL_NAME#"))';
        foreach ($relations as $rel) {
            $content = $template;
            $content = str_replace("#NAME#", sstring_underscore_to_camelcase(str_replace("_id", "", $rel['column_name'])), $content);
            $content = str_replace("#REF_MODULE_NAME#", $rel['ref_table']['module'], $content);
            $content = str_replace("#REF_LU_NAME#", $rel['ref_table']['lu'], $content);
            $content = str_replace("#REF_ITEM_NAME#", $rel['ref_table']['name'], $content);
            $content = str_replace("#REF_COL_NAME#", $rel['ref_column_name'], $content);
            $content = str_replace("#COL_NAME#", $rel['column_name'], $content);
            $belongsTo[] = $content;        
        }
        return implode("\n        ", $belongsTo);
    }
    
    private function getModelPrimaryKeys($tblMeta) {
        $columns = $tblMeta['columns'];
        $keys = array();
        foreach ($columns as $column) {
            if($column['primary_key']){
                $keys[] = $column['name'];
            }
        }
        return implode("','", $keys);
    }

    private $modelPropTypes = array(
        'varchar' => 'String',
        'int' => 'Integer',
        'timestamp' => 'Long',
        'datetime' => 'DateTime',
        'tinyint' => 'Boolean',
        'text' => 'String',
    );
    
    private function getModelPropertyType($type) {        
        if(array_key_exists($type, $this->modelPropTypes)){
            return $this->modelPropTypes[$type];
        }else{
            return $type;
        }
    }
    
    private function getModelProperties($tblMeta) {
        $columns = $tblMeta['columns'];
        $props = array();
        foreach ($columns as $column) {
            $props[] = '@property '. $this->getModelPropertyType($column['type']) . " $" . $column['name'] . " " . ucfirst(sstring_underscore_to_space($column['name']));
        }
        return implode("\n * ", $props);
    }
    
    private function getModelValidation($tblMeta) {
        $columns = $tblMeta['columns'];
        $validations = array();        
        foreach ($columns as $column) {
            if($column['unique']){
                
            }
        }
        return implode('\n        ', $validations);
    }

}
