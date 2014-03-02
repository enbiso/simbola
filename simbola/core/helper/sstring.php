<?php

/**
 * String starts with
 * 
 * @param string $haystack String to check
 * @param string $needle String needle to check
 * @return boolean
 */
function sstring_starts_with($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

/**
 * String ends with
 * 
 * @param string $haystack String to check
 * @param string $needle String needle to check
 * @return boolean
 */
function sstring_ends_with($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

/**
 * String contains
 * 
 * @param string $haystack String to check
 * @param string $needle String needle to check
 * @return boolean
 */
function sstring_contains($haystack, $needle) {
    return (strpos($haystack, $needle) !== false);
}

/**
 * String camel case to underscore
 * 
 * @param string $string String input
 * @return string String output
 */
function sstring_camelcase_to_underscore($string) {
    $string = preg_replace('/(?<=\\w)(?=[A-Z])/', "_$1", $string);
    return strtolower($string);
}

/**
 * String underscore to spaces
 * 
 * @param string $string String input
 * @param boolean $firstCharCaps Capitalize the first letter
 * @return string String output
 */
function sstring_underscore_to_space($string, $firstCharCaps = false) {
    $string = str_replace("_", " ", $string);
    return ($firstCharCaps) ? ucwords($string) : $string;
}

/**
 * String underscore to camel case
 * 
 * @param string $string String input
 * @param boolean $firstCharCaps Capitalize the first letter
 * @return string String output
 */
function sstring_underscore_to_camelcase($string, $firstCharCaps = false) {
    if ($firstCharCaps == true) {
        $string[0] = strtoupper($string[0]);
    }
    $func = create_function('$c', 'return strtoupper($c[1]);');
    return preg_replace_callback('/_([a-z])/', $func, $string);
}

/**
 * String camel case to spaces
 * 
 * @param string $string String input
 * @return string String output
 */
function sstring_camelcase_to_space($string) {
    $string = preg_replace('/(?<=\\w)(?=[A-Z])/', " $1", $string);
    return ucfirst($string);
}

?>
