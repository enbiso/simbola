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
        $content = $this->initializeWithBasicInfo($content, true);

        $termEntries = array();
        $modelClassName = \simbola\core\application\AppModel::getClass($this->module, $this->lu, $this->model);
        foreach ($this->getColsArray() as $col) {
            $termName = \simbola\Simbola::app()->term->getModelTermName($modelClassName, $col);
            $termEntries[] = '$__term["' . $termName . '"] = "' . sstring_underscore_to_space($col, true) . '";';
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
        $template = "self::hasMany(array('#NAME#', 'class_name' => '\application\#MODULE_NAME#\model\#LU_NAME#\#ITEM_NAME#', 'foreign_key' => '#COL_NAME#', 'primary_key' => '#REF_COL_NAME#'));";
        foreach ($relations as $rel) {
            $content = $template;
            $content = str_replace("#NAME#", sstring_underscore_to_camelcase($rel['table']['name']) . "s", $content);
            $content = str_replace("#MODULE_NAME#", $rel['table']['module'], $content);
            $content = str_replace("#LU_NAME#", $rel['table']['lu'], $content);
            $content = str_replace("#ITEM_NAME#", sstring_underscore_to_camelcase($rel['table']['name'], true), $content);
            $content = str_replace("#REF_COL_NAME#", $rel['ref_column_name'], $content);
            $content = str_replace("#COL_NAME#", $rel['column_name'], $content);
            $hasMany[] = $content;
        }
        $fullContent = implode("\n        ", $hasMany);
        return empty($fullContent) ? "//None" : $fullContent;
    }

    private function getModelBelongsTo($tblMeta) {
        $relations = $tblMeta['relations']['belongs_to'];
        $belongsTo = array();
        $template = "self::belongsTo(array('#NAME#', 'class_name' => '\application\#REF_MODULE_NAME#\model\#REF_LU_NAME#\#REF_ITEM_NAME#', 'foreign_key' => '#COL_NAME#', 'primary_key' => '#REF_COL_NAME#'));";
        foreach ($relations as $rel) {
            $content = $template;
            $content = str_replace("#NAME#", sstring_underscore_to_camelcase(str_replace("_id", "", $rel['column_name'])), $content);
            $content = str_replace("#REF_MODULE_NAME#", $rel['ref_table']['module'], $content);
            $content = str_replace("#REF_LU_NAME#", $rel['ref_table']['lu'], $content);
            $content = str_replace("#REF_ITEM_NAME#", sstring_underscore_to_camelcase($rel['ref_table']['name'], true), $content);
            $content = str_replace("#REF_COL_NAME#", $rel['ref_column_name'], $content);
            $content = str_replace("#COL_NAME#", $rel['column_name'], $content);
            $belongsTo[] = $content;
        }
        $fullContent = implode("\n        ", $belongsTo);
        return empty($fullContent) ? "//None" : $fullContent;
    }

    private function getModelPrimaryKeys($tblMeta) {
        $columns = $tblMeta['columns'];
        $keys = array();
        foreach ($columns as $column) {
            if ($column['primary_key']) {
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
        if (array_key_exists($type, $this->modelPropTypes)) {
            return $this->modelPropTypes[$type];
        } else {
            return $type;
        }
    }

    private function getModelProperties($tblMeta) {
        $columns = $tblMeta['columns'];
        $props = array();
        foreach ($columns as $column) {
            $props[] = '@property ' . $this->getModelPropertyType($column['type']) . " $" . $column['name'] . " " . ucfirst(sstring_underscore_to_space($column['name']));
        }
        return implode("\n * ", $props);
    }

    private function getModelValidation($tblMeta) {
        $columns = $tblMeta['columns'];
        $templates = array(
            'nullable' => 'self::validatePresenceOf(array("#COL_NAME#"));',
            'unique' => 'self::validateUniquenessOf(array("#COL_NAME#"));',
            'size' => 'self::validateSizeOf(array("#COL_NAME#", "maximum" => #COL_SIZE#));',
            'integer' => 'self::validateNumericalityOf(array("#COL_NAME#", "only_integer" => true));',
            'number' => 'self::validateNumericalityOf(array("#COL_NAME#"));',
        );
        $validations = array();
        foreach ($columns as $column) {
            $validations[] = "// - {$column['name']}";
            if (!$column['nullable'] && !is_null($column['default'])) {
                $content = $templates['nullable'];
                $content = $this->getContentInitForField($content, $column);
                $validations[] = $content;
            }
            if ($column['unique']) {
                $content = $templates['unique'];
                $content = $this->getContentInitForField($content, $column);
                $validations[] = $content;
            }
            if (!is_null($column['length']) && in_array($column['type'], array('varchar', 'text'))) {
                $content = $templates['size'];
                $content = $this->getContentInitForField($content, $column);
                $validations[] = $content;
            }
            if (in_array($column['type'], array('integer', 'int', 'bigint'))) {
                $content = $templates['integer'];
                $content = $this->getContentInitForField($content, $column);
                $validations[] = $content;
            }
            if (in_array($column['type'], array('long', 'float', 'decimal', 'double', 'number'))) {
                $content = $templates['number'];
                $content = $this->getContentInitForField($content, $column);
                $validations[] = $content;
            }
        }
        return implode("\n        ", $validations);
    }

    private function getContentInitForField($content, $column) {
        $content = str_replace("#COL_NAME#", $column['name'], $content);
        $content = str_replace("#COL_SIZE#", $column['length'], $content);
        return $content;
    }

}
