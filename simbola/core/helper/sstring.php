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
        $string = preg_replace('/(?<=\\w)(?=[A-Z])/',"_$1", $string);
        //$string = preg_replace('/\B([A-Z])/', '_$1',$string);
        return strtolower($string);
    }
    
    function sstring_camelcase_to_space($string) {
        $string = preg_replace('/(?<=\\w)(?=[A-Z])/'," $1", $string);
        return ucfirst($string);
    }
?>
