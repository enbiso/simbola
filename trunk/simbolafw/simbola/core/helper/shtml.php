<?php

function shtml_taged($tag, $opts = array()) {
    $options = "";
    foreach ($opts as $name => $value) {
        $options .= " {$name}='{$value}'";
    }
    return "<{$tag}{$options}/>";
}

function shtml_tag($tag, $opts = array()) {
    $options = "";
    foreach ($opts as $name => $value) {
        $options .= " {$name}='{$value}'";
    }
    return "<{$tag}{$options}>";
}

function shtml_untag($tag) {
    return "</{$tag}>";
}

function shtml_css($module, $name) {
    return \simbola\Simbola::app()->resource->getResourceTag(
                    simbola\core\component\resource\lib\ResItem::$TYPE_CSS, $module, $name);
}

function shtml_ecss($module, $name) {
    echo shtml_css($module, $name);
}

function shtml_js($module, $name) {
    return \simbola\Simbola::app()->resource->getResourceTag(
                    simbola\core\component\resource\lib\ResItem::$TYPE_JS, $module, $name);
}

function shtml_resurl($module, $name) {
    return \simbola\Simbola::app()->resource->getResourceTag(
                    simbola\core\component\resource\lib\ResItem::$TYPE_MISC, $module, $name);
}

function shtml_ejs($module, $name) {
    echo shtml_js($module, $name);
}

function shtml_link($value, $link, $opts = array(), $icon = null, $tooltip = null) {
    $value = shtml_translate($value);
    $opts['href'] = $link;
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

function shtml_translate($value) {
    if (sstring_starts_with($value, "TERM:")) {
        $value = shtml_term(str_replace("TERM:", "", $value));
    }
    return $value;
}

function shtml_etranslate($value) {
    echo shtml_translate($value);
}

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

function shtml_tab($id, $params) {
    $opts['class'] = isset($params['class']) ? $params['class'] . " " : "";
    $opts['class'] .= "tabbable";
    echo shtml_tag('div', $opts);
    echo shtml_tag('ul', array('class' => 'nav nav-tabs'));
    foreach ($params['tabs'] as $tab_id => $tab) {
        if (\simbola\Simbola::app()->auth->checkPermissionByUrl($tab['permission'])) {
            echo shtml_tag('li');
            $linkopts = array(
                'id' => "nav_{$id}_{$tab_id}",
                'href' => "#tab_{$id}_{$tab_id}",
                'data-toggle' => 'tab'
            );
            echo shtml_tag('a', $linkopts);
            echo $tab['title'];
            echo shtml_untag('a');
            echo shtml_untag('li');
        }
    }
    echo shtml_untag('ul');
    echo shtml_tag('div', array('class' => 'tab-content'));
    foreach ($params['tabs'] as $tab_id => $tab) {
        if (\simbola\Simbola::app()->auth->checkPermissionByUrl($tab['permission'])) {
            echo shtml_tag('div', array('class' => 'tab-pane', 'id' => "tab_{$id}_{$tab_id}"));
            echo $tab['content'];
            echo shtml_untag('div');
        }
    }
    echo shtml_untag('div');
    echo shtml_untag('div');
    echo shtml_tag('script');
    $tab_ids = array_keys($params['tabs']);
    $params['default'] = isset($params['default']) ? $params['default'] : $tab_ids[0];
    $openId = "#nav_{$id}_{$params['default']}";
    echo '$(function () { $("' . $openId . '").tab("show");})';
    echo shtml_untag('script');
}

function shtml_term($term) {
    return \simbola\core\component\term\Term::Get($term);
}

function shtml_eterm($term) {
    echo shtml_term($term);
}

function shtml_encode($value) {
    if (!isset($value)) {
        $value = "--";
    }
    return htmlspecialchars($value);
}

function shtml_eencode($value) {
    echo shtml_encode($value);
}

function shtml_breadcrumb($values) {
    $val = "";
    $val .= shtml_tag("ol", array('class' => 'breadcrumb'));
    foreach ($values as $key => $value) {
        if (is_string($key)) {
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

?>
