<?php

function sterm_get($name, $data = array()) {
    return simbola\core\component\term\Term::Get($name, $data);
}

function sterm_show($name, $data = array()) {
    \simbola\core\component\term\Term::eGet($name, $data);
}

?>
