<?php
function sbootstrap_buttongroup($buttons){
    $content = shtml_tag('div',array('class'=>'btn-group'));
    foreach ($buttons as $button) {
        $content .= $button;
    }
    $content .= shtml_untag('div');
    return $content;
}