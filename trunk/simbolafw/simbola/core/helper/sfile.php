<?php

function sfile_copy_recursive($source, $dest) {
    if (is_dir($source)) {
        $dir_handle = opendir($source);
        $sourcefolder = basename($source);
        if(!is_dir($dest . DIRECTORY_SEPARATOR . $sourcefolder)){
            mkdir($dest . DIRECTORY_SEPARATOR . $sourcefolder);
        }
        while ($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (is_dir($source . DIRECTORY_SEPARATOR . $file)) {
                    sfile_copy_recursive($source . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $sourcefolder);
                } else {
                    copy($source . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file);
                }
            }
        }
        closedir($dir_handle);
    } else {        
        copy($source, $dest);
    }
}
