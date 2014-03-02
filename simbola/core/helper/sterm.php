<?php
/**
 * Translate the term
 * 
 * @param string $term Term name 
 * @return string Translations
 */
function sterm_get($term) {
    return \simbola\core\component\term\Term::Get($term);
}

/**
 * Translate the term and echo
 * 
 * @param string $term Term name  
 */
function sterm_show($term) {
    echo sterm_get($term);
}

?>
