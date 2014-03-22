<?php

/**
 * Create HTML Tag with an untag
 * 
 * @param string $tag Tag name
 * @param array $opts Tag options
 * @return string HTML tag with an untag
 */
function shtml_taged($tag, $opts = array()) {
    $options = "";
    foreach ($opts as $name => $value) {
        $options .= " {$name}='{$value}'";
    }
    return "<{$tag}{$options}/>";
}

/**
 * Create HTML Tag open
 * 
 * @param string $tag Tag name
 * @param array $opts Tag options
 * @return string HTML tag open
 */
function shtml_tag($tag, $opts = array()) {
    $options = "";
    foreach ($opts as $name => $value) {
        $options .= " {$name}='{$value}'";
    }
    return "<{$tag}{$options}>";
}

/**
 * Create an HTML close tag 
 * 
 * @param string $tag Tag name
 * @return string HTML tag close
 */
function shtml_untag($tag) {
    return "</{$tag}>";
}

/**
 * Create HTML CSS tag
 * 
 * @param string $module Module name
 * @param string $name Resource path/name
 * @return HTML tag for CSS
 */
function shtml_css($module, $name) {
    return \simbola\Simbola::app()->resource->getResourceTag(
                    simbola\core\component\resource\lib\ResItem::TYPE_CSS, $module, $name);
}

/**
 * Create HTML CSS tag and echo
 * 
 * @param string $module Module name
 * @param string $name Resource path/name 
 */
function shtml_ecss($module, $name) {
    echo shtml_css($module, $name);
}

/**
 * Create HTML JS tag
 * 
 * @param string $module Module name
 * @param string $name Resource path/name
 * @return HTML tag for JS
 */
function shtml_js($module, $name) {
    return \simbola\Simbola::app()->resource->getResourceTag(
                    simbola\core\component\resource\lib\ResItem::TYPE_JS, $module, $name);
}

/**
 * Returns the resource URL
 * 
 * @param string $module Module name
 * @param string $name Resource path/name
 * @return string Resourec URL
 */
function shtml_resurl($module, $name) {
    return \simbola\Simbola::app()->resource->getResourceTag(
                    simbola\core\component\resource\lib\ResItem::TYPE_MISC, $module, $name);
}

/**
 * Create HTML JS tag and echo
 * 
 * @param string $module Module name
 * @param string $name Resource path/name 
 */
function shtml_ejs($module, $name) {
    echo shtml_js($module, $name);
}

/**
 * Creates HTML anchor tag
 * 
 * @param string $value Link text
 * @param string $link URL of href
 * @param array $opts Options
 * @param string $icon Icon name
 * @param string $tooltip Tool tip
 * @return string HTML Tag for the link
 */
function shtml_link($value, $link = false, $opts = array(), $icon = null, $tooltip = null) {
    $value = shtml_translate($value);
    if($link){
        $opts['href'] = $link;
    }
    if(isset($tooltip)){
        $opts['data-toggle'] = "tooltip";
        $opts['data-placement'] = "top";
        $opts['title'] = $tooltip;
    }
    $code = shtml_tag('a', $opts);
    if (isset($icon)) {
        $code .= shtml_tag("span", array('class' => 'glyphicon glyphicon-' . $icon));
        $code .= shtml_untag("span");
        if ($value != "") {
            $code .= " ";
        }
    }
    $code.= $value;
    $code.= shtml_untag('a');
    return $code;
}

/**
 * Translate the text
 * 
 * @param string $value Term name starting from 'TERM:xxxxx'
 * @return string Translated text
 */
function shtml_translate($value) {
    if (sstring_starts_with($value, "TERM:")) {
        $value = sterm_get(str_replace("TERM:", "", $value));
    }
    return $value;
}

/**
 * Translate and echo the text
 * 
 * @param string $value Term name starting from 'TERM:xxxxx'
 */
function shtml_etranslate($value) {
    echo shtml_translate($value);
}

/**
 * 
 * @param string $value Display text
 * @param mixed $url Array/Page object representing the link
 * @param array $opts Options
 * @param string $icon Icon name
 * @param string $tooltip Tool tip
 * @return string HTML Tag for the link
 */
function shtml_action_link($value, $url, $opts = array(), $icon = null, $tooltip = null) {
    if ($url instanceof \simbola\core\component\url\lib\Page) {
        $page = $url;
    } else {
        if (!is_array($url)) {
            $url = array($url);
        }
        $page = new \simbola\core\component\url\lib\Page();
        $page->loadFromArray($url);
    }
    return shtml_Link($value, $page->getUrl(), $opts, $icon, $tooltip);
}

/**
 * 
 * @param array $buttons Array of HTML representing strings of buttons
 * 
 * @return string button group HTML
 */
function shtml_buttongroup($buttons){
    $content = shtml_tag('div',array('class'=>'btn-group'));
    foreach ($buttons as $button) {
        $content .= $button;
    }
    $content .= shtml_untag('div');
    return $content;
}

/**
 * Create the HTML tags for the bootstrap tab
 * 
 * @param string $id Tab ID
 * @param array $params Parameters
 */
function shtml_tab($id, $params) {
    $opts['class'] = isset($params['class']) ? $params['class'] . " " : "";
    $opts['class'] .= "tabbable";
    $data = '';
    $data .= shtml_tag('div', $opts);
    $data .= shtml_tag('ul', array('class' => 'nav nav-tabs'));
    foreach ($params['tabs'] as $tab_id => $tab) {
        if (\simbola\Simbola::app()->auth->checkPermissionByUrl($tab['permission'])) {
            $data .= shtml_tag('li');
            $linkopts = array(
                'id' => "nav_{$id}_{$tab_id}",
                'href' => "#tab_{$id}_{$tab_id}",
                'data-toggle' => 'tab'
            );
            $data .= shtml_tag('a', $linkopts);
            $data .= $tab['title'];
            $data .= shtml_untag('a');
            $data .= shtml_untag('li');
        }
    }
    $data .= shtml_untag('ul');
    $data .= shtml_tag('div', array('class' => 'tab-content'));
    foreach ($params['tabs'] as $tab_id => $tab) {
        if (\simbola\Simbola::app()->auth->checkPermissionByUrl($tab['permission'])) {
            $data .= shtml_tag('div', array('class' => 'tab-pane', 'id' => "tab_{$id}_{$tab_id}"));
            $data .= $tab['content'];
            $data .= shtml_untag('div');
        }
    }
    $data .= shtml_untag('div');
    $data .= shtml_untag('div');
    $data .= shtml_tag('script');
    $tab_ids = array_keys($params['tabs']);
    $params['default'] = isset($params['default']) ? $params['default'] : $tab_ids[0];
    $openId = "#nav_{$id}_{$params['default']}";
    $data .= '$(function () { $("' . $openId . '").tab("show");})';
    $data .= shtml_untag('script');
    return $data;
}

/**
 * Encode to html string
 * 
 * @param string $value String value to encode for html
 * @return string encoded string
 */
function shtml_encode($value) {
    if (!isset($value)) {
        $value = "--";
    }
    return htmlspecialchars($value);
}

/**
 * Encode to html string and echo
 * 
 * @param string $value String value to encode for html 
 */
function shtml_eencode($value) {
    echo shtml_encode($value);
}

/**
 * Create bootstrap breadcrumb
 * 
 * @param array $values Breadcrumb data
 * @return string HTML tag
 */
function shtml_breadcrumb($values) {    
    $val = "";
    $val .= shtml_tag("ol", array('class' => 'breadcrumb'));
    foreach ($values as $key => $value) {
        if (is_array($value)) {
            $val .= shtml_tag("li");
            $val .= shtml_action_link($key, $value);
        } else {
            $val .= shtml_tag("li", array('class' => 'active'));
            $val .= $value;
        }
        $val .= shtml_untag("li");
    }
    $val .= shtml_untag("ol");
    return $val;
}

/**
 * Create bootstrap dropdown menu
 * 
 * @param array $values Dropmenu data
 * @return string HTML tag
 */
function shtml_dropmenu($values) {
    $val = shtml_tag('div', array('class' => 'btn-group'));
    $val .= shtml_tag('button', array('type' => 'button', 'class' => 'btn btn-default dropdown-toggle', 'data-toggle' => 'dropdown'));
    $val .= shtml_taged('span', array('class' => 'glyphicon glyphicon-th-large'));
    $val .= shtml_untag('button');
    $val .= shtml_tag("ul", array('class' => 'dropdown-menu', 'role' => 'menu'));
    foreach ($values as $key => $value) {
        if (!is_numeric($key)) {
            $value = array('key' => $key, 'link' => $value);
        }
        if (is_array($value['link'])) {
            $val .= shtml_tag('li');
            $val .= shtml_action_link($value['key'], $value['link']);
            $val .= shtml_untag('li');
        } else if ($value['link'] == "-") {
            $val .= shtml_taged('li', array('class' => 'divider'));
        }
    }
    $val .= shtml_untag('ul');
    $val .= shtml_untag('div');
    return $val;
}

/**
 * Create bootstrap button group menu
 * 
 * @param type $values Button group data
 * @return string HTML tag
 */
function shtml_btngroupmenu($values) {
    $val = shtml_tag('div', array('class' => 'btn-group'));
    foreach ($values as $key => $value) {
        if (!is_numeric($key)) {
            $value = array('title' => $key, 'link' => $value);
        }
        if(!array_key_exists('class', $value)){
            $value['class'] = '';
        }
        if(!array_key_exists('icon', $value)){
            $value['icon'] = 'th';
        }
        $value['class'] = 'btn btn-default ' . $value['class'];
        if (is_array($value['link'])) {
            $val .= shtml_action_link($value['title'], $value['link'], array('class' => $value['class']), $value['icon']);
        } else if ($value['link'] == "-") {
            $val .= shtml_untag('div');
            $val .= shtml_tag('div', array('class' => 'btn-group btn-group-sm'));
        }
    }
    $val .= shtml_untag('div');
    return $val;
}

function shtml_ul($data, $opts = array()){
    if(is_string($data)){
        $data = array($data);
    }
    $output = shtml_tag("ul", $opts);
    foreach ($data as $value) {
        $output .= shtml_tag("li");
        $output .= shtml_encode($value);
        $output .= shtml_untag("li");
    }
    $output .= shtml_untag("ul");
    return $output;
}
?>
