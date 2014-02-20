<?php

function sfile_copy_recursive($src, $dst) {
    if (is_dir($src)) {
        $dir = opendir($src);
        if(!is_dir($dst)){
            mkdir($dst);
        }
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir($src . '/' . $file)) {
                    sfile_copy_recursive($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    } else {
        copy($src, $dst);
    }
}
