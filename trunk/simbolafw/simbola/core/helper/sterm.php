<?php
/**
 * Translate the term
 * 
 * @param string $term Term name 
 * @param array $params Parameters
 * @return string Translations
 */
function sterm_get($term, $params = array()) {
    return \simbola\core\component\term\Term::Get($term, $params);
}

/**
 * Translate the term and echo
 * 
 * @param string $term Term name 
 * @param array $params Parameters 
 */
function sterm_show($term, $params = array()) {
    echo sterm_get($term, $params);
}

?>
