<?php

function sstring_starts_with($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function sstring_ends_with($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function sstring_contains($haystack, $needle) {
    return (strpos($haystack, $needle) !== false);
}

function sstring_camelcase_to_underscore($string) {
    $string = preg_replace('/(?<=\\w)(?=[A-Z])/', "_$1", $string);
    return strtolower($string);
}

function sstring_underscore_to_space($string, $captialize = false) {
    $string = str_replace("_", " ", $string);
    return ($captialize) ? ucwords($string) : $string;
}

function sstring_underscore_to_camelcase($string, $first_char_caps = false) {
    if ($first_char_caps == true) {
        $string[0] = strtoupper($string[0]);
    }
    $func = create_function('$c', 'return strtoupper($c[1]);');
    return preg_replace_callback('/_([a-z])/', $func, $string);
}

function sstring_camelcase_to_space($string) {
    $string = preg_replace('/(?<=\\w)(?=[A-Z])/', " $1", $string);
    return ucfirst($string);
}

?>
