<?php

/**
 * Create HTML form for the service
 * 
 * @param string $formId Form ID
 * @param array $service Service definitions associate array('module','lu','action')
 * @param string $redirect  Redirect page url
 * @param array $opts Options
 * @return string HTML Tag
 */
function shtmlform_start_service_proxy($formId, $service, $redirect, $opts = array()) {
    $page = new simbola\core\component\url\lib\Page();
    $page->type = simbola\core\component\url\lib\Page::TYPE_CONTROLLER;
    $page->module = 'system';
    $page->logicalUnit = 'serviceProxy';
    $page->action = 'call';
    $page->params = $service;
    $content = shtmlform_start($formId, $page, 'POST', $opts) . PHP_EOL;
    $content .= shtmlform_input('hidden', array(
                'name' => "redirect",
                'value' => $redirect)) . PHP_EOL;
    return $content;
}

/**
 * Create HTML form
 * 
 * @param string $formId Form ID
 * @param string $actionPage Action page
 * @param string $method POST or GET
 * @param array $opts Options
 * @return string HTML Tag
 */
function shtmlform_start($formId, $actionPage = null, $method = 'POST', $opts = array()) {
    $page = new simbola\core\component\url\lib\Page;
    if (is_array($actionPage)) {
        $page->loadFromArray($actionPage);
    } else if (is_string($actionPage)) {
        $page->loadFromUrl($actionPage);
    } else if ($actionPage instanceof simbola\core\component\url\lib\Page) {
        $page = $actionPage;
    } else {
        $page = null;
    }
    $form_opts = array('method' => $method, 'id' => $formId, 'role'=>'form');
    if($method == 'POST'){
        $form_opts['enctype'] = "multipart/form-data";
    }
    if (!is_null($page)) {
        $form_opts['action'] = $page->getUrl();            
    }
    $opts = array_merge($opts, $form_opts);
    return shtml_tag("form", $opts);
}

/**
 * End tag for the form
 * 
 * @return string HTML untag
 */
function shtmlform_end() {
    return shtml_untag('form');
}

/**
 * HTML input tag
 * 
 * @param string $type Input type
 * @param array $opts Options
 * @return string HTML tag
 */
function shtmlform_input($type, $opts = array()) {
    $opts = array_merge(array(
        'type' => $type), $opts);
    return shtml_taged("input", $opts);
}

/**
 * Create an input hidden field for the AppModel
 * 
 * @param \simbola\core\application\AppModel $object Model object
 * @param string $fieldName Field name
 * @param string $dataName Postback data name
 * @return string HTML Tag
 */
function shtmlform_input_hidden_for($object, $fieldName, $dataName = 'data'){
    $opts = array(
        'name' => "{$dataName}[{$fieldName}]",
        'value' => $object->$fieldName,        
    );
    return shtmlform_input('hidden', $opts);
}

/**
 * Create readonly field for the AppModel
 * 
 * @param \simbola\core\application\AppModel $object Model object
 * @param string $fieldName Field name 
 * @return string HTML Tag
 */
function shtmlform_readonly_text_for($object, $fieldName, $opts = array()) {
    $input_opts = array(        
        'value' => (is_null($object))?'':$object->$fieldName,
        'class' => 'form-control',
        'readonly' => 'true',
    );
    $opts = array_merge($input_opts, $opts);
    return shtmlform_input('text', $opts);
}

/**
 * Create an input text field for the AppModel
 * 
 * @param \simbola\core\application\AppModel $object Model object
 * @param string $fieldName Field name
 * @param string $dataName Postback data name
 * @return string HTML Tag
 */
function shtmlform_input_text_for($object, $fieldName, $dataName = 'data', $opts = array()) {
    return shtmlform_input_for('text', $object, $fieldName, $dataName, $opts);
}

/**
 * Create an input text field for the AppModel
 * 
 * @param \simbola\core\application\AppModel $object Model object
 * @param string $type text, password, datetime, datetime-local, date, month, time, week, number, email, url, search, tel, and colo
 * @param string $fieldName Field name
 * @param string $dataName Postback data name
 * @return string HTML Tag
 */
function shtmlform_input_for($type, $object, $fieldName, $dataName = 'data', $opts = array()) {
    $input_opts = array(
        'name' => "{$dataName}[{$fieldName}]",
        'value' => (is_null($object))?'':$object->$fieldName,
        'class' => 'form-control field-'.$fieldName,
        'placeholder' => $object->term($fieldName)
    );
    if(!$object->isEditable($fieldName) && !$object->is_new_record()){
        $input_opts['readonly'] = 'true';
    }
    $opts = array_merge($opts, $input_opts);
    return shtmlform_input($type, $opts);
}

/**
 * Create an input file field for the AppModel
 * 
 * @param \simbola\core\application\AppModel $object Model object
 * @param string $fieldName Field name
 * @param string $dataName Postback data name
 * @return string HTML Tag
 */
function shtmlform_file_for($object, $fieldName, $dataName = 'data', $opts = array()){
    $input_opts = array(
        'name' => "{$dataName}[{$fieldName}]",        
        'class' => 'form-control',        
    );
    $opts = array_merge($input_opts, $opts);
    return shtmlform_input('file', $opts);
}

/**
 * Create an label for the AppModel
 * 
 * @param \simbola\core\application\AppModel $object Model object
 * @param string $fieldName Field name 
 * @return string HTML Tag
 */
function shtmlform_label_for($object, $fieldName, $opts = array()) {
    return shtmlform_label($object->term($fieldName), $opts);
}

/**
 * Create an input select for the AppModel
 * 
 * @param \simbola\core\application\AppModel $object Model object
 * @param string $fieldName Field name
 * @param string $dataName Postback data name
 * @param string $data Array of data to display. Key, Value pair
 * @return string HTML Tag
 */
function shtmlform_select_for($object, $fieldName, $data, $dataName = 'data', $opts = array()) {
    $select_opts = array(
        'name' => "{$dataName}[{$fieldName}]",        
        'class' => 'form-control field-'.$fieldName,        
    );
    if(!$object->isEditable($fieldName) && !$object->is_new_record()){
        $select_opts['readonly'] = 'true';
    }
    $opts = array_merge($select_opts, $opts);
    return shtmlform_select($data, $object->$fieldName, $opts);
}

/**
 * Create an textarea field for the AppModel
 * 
 * @param \simbola\core\application\AppModel $object Model object
 * @param string $fieldName Field name
 * @param string $dataName Postback data name
 * @return string HTML Tag
 */
function shtmlform_textarea_for($object, $fieldName, $dataName = 'data', $opts = array()){
    $textarea_opts = array(
        'name' => "{$dataName}[{$fieldName}]",
        'class' => 'form-control',
        'placeholder' => $object->term($fieldName)
    );
    if(!$object->isEditable($fieldName) && !$object->is_new_record()){
        $textarea_opts['readonly'] = 'true';
    }
    $opts = array_merge($textarea_opts, $opts);
    return shtmlform_textarea($opts, $object->$fieldName);
}

/**
 * HTML Button
 * 
 * @param string $value Display text
 * @param array $opts Options
 * @return string HTML tag
 */
function shtmlform_button($value, $opts = array()) {
    $content = shtml_tag('button', $opts);
    $content .= shtml_translate($value);
    $content .= shtml_untag('button');
    return $content;
}

/**
 * HTML Label
 * 
 * @param string $value Display text
 * @param array $opts Options
 * @return string HTML tag
 */
function shtmlform_label($value, $opts = array()) {
    $content = shtml_tag('label', $opts);
    $content .= shtml_translate($value);
    $content .= shtml_untag('label');
    return $content;
}

/**
 * HTML textarea
 * 
 * @param array $opts Options
 * @param string $value Content
 * @return string HTML tag
 */
function shtmlform_textarea($opts = array(), $value = '') {
    $content = shtml_tag('textarea', $opts);
    $content .= $value;
    $content .= shtml_untag('textarea');
    return $content;
}

/**
 * HTML Select
 * 
 * @param boolean $selected Selected value 
 * @param array $opts Options
 * @param array $data Display data options
 * @return string HTML tag
 */
function shtmlform_select($data, $selected, $opts = array()){
    $content = shtml_tag('select', $opts);
    foreach ($data as $value => $label) {
        $opt_opts = array('value' => $value);
        if($value == $selected){
            $opt_opts['selected'] = 'true';
        }
        $content .= shtml_tag("option", $opt_opts);
        $content .= $label;
        $content .= shtml_untag('option');
    }
    $content .= shtml_untag('select');
    return $content;
}

/**
 * Generate for group input
 * 
 * @param string $type text, password, datetime, datetime-local, date, month, time, week, number, email, url, search, tel, and colo
 * @param simbola\core\application\AppModel $object Model Object
 * @param string $fieldName Field name
 * @param string $dataName Data name, default 'data'
 * @param array $opts Options
 * @return string HTML Tag
 */
function shtmlform_group_input_for($type, $object, $fieldName, $dataName = 'data', $opts = array()){
    $formGroupOpts = array('class'=>'form-group');    
    $hasError = isset($object->errors) && !is_null($object->errors->on($fieldName));
    if($hasError){ 
        $formGroupOpts['class'] .= ' has-error has-feedback';        
    }
    $output = shtml_tag("div", $formGroupOpts);    
    $output .= shtmlform_label_for($object, $fieldName);
    $output .= shtmlform_input_for($type, $object, $fieldName, $dataName, $opts);
    if($hasError){ 
        $output .= shtml_tagged('span', array('class'=> "glyphicon glyphicon-warning-sign form-control-feedback"));
        $output .= shtml_tag("span", array('class'=>'help-text'));
        $output .= $object->errors->on($fieldName);
        $output .= shtml_untag("span");
    }
    $output .= shtml_untag("div");    
    return $output;
}

/**
 * Generate for group select
 * 
 * @param simbola\core\application\AppModel $object Model Object
 * @param string $fieldName Field name
 * @param array $data Select data
 * @param string $dataName Data name, default 'data'
 * @param array $opts Options
 * @return string HTML Tag
 */
function shtmlform_group_select_for($object, $fieldName, $data, $dataName = 'data', $opts = array()){
    $formGroupOpts = array('class'=>'form-group');    
    $hasError = isset($object->errors) && !is_null($object->errors->on($fieldName));
    if($hasError){ 
        $formGroupOpts['class'] .= ' has-error has-feedback';        
    }
    $output = shtml_tag("div", $formGroupOpts);    
    $output .= shtmlform_label_for($object, $fieldName);
    $output .= shtmlform_select_for($object, $fieldName, $data, $dataName, $opts);
    if($hasError){ 
        $output .= shtml_tagged('span', array('class'=> "glyphicon glyphicon-warning-sign form-control-feedback"));
        $output .= shtml_tag("span", array('class'=>'help-text'));
        $output .= $object->errors->on($fieldName);
        $output .= shtml_untag("span");
    }
    $output .= shtml_untag("div");    
    return $output;
}

/**
 * Generate for group textarea
 * 
 * @param simbola\core\application\AppModel $object Model Object
 * @param string $fieldName Field name 
 * @param string $dataName Data name, default 'data'
 * @param array $opts Options
 * @return string HTML Tag
 */
function shtmlform_group_textarea_for($object, $fieldName, $dataName = 'data', $opts = array()){
    $formGroupOpts = array('class'=>'form-group');    
    $hasError = isset($object->errors) && !is_null($object->errors->on($fieldName));
    if($hasError){ 
        $formGroupOpts['class'] .= ' has-error has-feedback';        
    }
    $output = shtml_tag("div", $formGroupOpts);    
    $output .= shtmlform_label_for($object, $fieldName);
    $output .= shtmlform_textarea_for($object, $fieldName, $dataName, $opts);
    if($hasError){ 
        $output .= shtml_tagged('span', array('class'=> "glyphicon glyphicon-warning-sign form-control-feedback"));
        $output .= shtml_tag("span", array('class'=>'help-text'));
        $output .= $object->errors->on($fieldName);
        $output .= shtml_untag("span");
    }
    $output .= shtml_untag("div");    
    return $output;
}