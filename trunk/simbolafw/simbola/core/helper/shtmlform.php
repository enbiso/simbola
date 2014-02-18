<?php

function shtmlform_start_service_proxy($formId, $service, $redirect, $method = 'POST', $opts = array()) {
    $page = new simbola\core\component\url\lib\Page();
    $page->type = simbola\core\component\url\lib\Page::$TYPE_CONTROLLER;
    $page->module = 'system';
    $page->logicalUnit = 'serviceProxy';
    $page->action = 'call';
    $page->params = $service;
    $content = shtmlform_start($formId, $page, $method, $opts) . PHP_EOL;
    $content .= shtmlform_input('hidden', array(
                'name' => "redirect",
                'value' => $redirect)) . PHP_EOL;
    return $content;
}

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

function shtmlform_end() {
    return shtml_untag('form');
}

function shtmlform_input($type, $opts = array()) {
    $opts = array_merge(array(
        'type' => $type), $opts);
    return shtml_taged("input", $opts);
}

function shtmlform_input_hidden_for($object, $fieldName, $dataName = 'data'){
    $opts = array(
        'name' => "{$dataName}[{$fieldName}]",
        'value' => $object->$fieldName,        
    );
    return shtmlform_input('hidden', $opts);
}

function shtmlform_readonly_text_for($object, $fieldName, $opts = array()) {
    $input_opts = array(        
        'value' => $object->$fieldName,
        'class' => 'form-control',
        'readonly' => 'true',
    );
    $opts = array_merge($input_opts, $opts);
    return shtmlform_input('text', $opts);
}

function shtmlform_input_text_for($object, $fieldName, $dataName = 'data', $opts = array()) {
    $input_opts = array(
        'name' => "{$dataName}[{$fieldName}]",
        'value' => $object->$fieldName,
        'class' => 'form-control',
        'placeholder' => $object->term($fieldName)
    );
    if(!$object->isEditable($fieldName)){
        $input_opts['readonly'] = 'true';
    }
    $opts = array_merge($input_opts, $opts);
    return shtmlform_input('text', $opts);
}

function shtmlform_file_for($object, $fieldName, $dataName = 'data', $opts = array()){
    $input_opts = array(
        'name' => "{$dataName}[{$fieldName}]",        
        'class' => 'form-control',        
    );
    $opts = array_merge($input_opts, $opts);
    return shtmlform_input('file', $opts);
}

function shtmlform_label_for($object, $fieldName, $opts = array()) {
    return shtmlform_label($object->term($fieldName), $opts);
}

function shtmlform_select_for($object, $fieldName, $data, $dataName = 'data', $opts = array()) {
    $select_opts = array(
        'name' => "{$dataName}[{$fieldName}]",        
        'class' => 'form-control',        
    );
    $opts = array_merge($select_opts, $opts);
    return shtml_select($data, $object->$fieldName, $opts);
}

function shtmlform_textarea_for($object, $fieldName, $dataName = 'data', $opts = array()){
    $textarea_opts = array(
        'name' => "{$dataName}[{$fieldName}]",
        'class' => 'form-control',
        'placeholder' => $object->term($fieldName)
    );
    $opts = array_merge($textarea_opts, $opts);
    return shtmlform_textarea($opts, $object->$fieldName);
}

function shtmlform_button($value, $opts = array()) {
    $content = shtml_tag('button', $opts);
    $content .= shtml_translate($value);
    $content .= shtml_untag('button');
    return $content;
}

function shtmlform_label($value, $opts = array()) {
    $content = shtml_tag('label', $opts);
    $content .= shtml_translate($value);
    $content .= shtml_untag('label');
    return $content;
}

function shtmlform_textarea($opts = array(), $value = '') {
    $content = shtml_tag('textarea', $opts);
    $content .= $value;
    $content .= shtml_untag('textarea');
    return $content;
}

function shtml_select($data, $selected, $opts = array()){
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