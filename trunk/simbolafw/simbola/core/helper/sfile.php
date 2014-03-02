<?php

/**
 * Find the MIME of the given filename
 * 
 * @param string $filename File path
 * @return string
 */
function sfile_mime_type($filename) {
    $mime_types = array(
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',
        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',
        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',
        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',
        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',
        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    );

    $arr = explode('.', $filename);
    $ext = strtolower(array_pop($arr));    
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME);
        $mimetype = finfo_file($finfo, $filename);
        finfo_close($finfo);
        return $mimetype;
    } elseif (array_key_exists($ext, $mime_types)) {
        return $mime_types[$ext];
    } else {
        return 'application/octet-stream';
    }
}

/**
 * Recursive copy
 * 
 * @param string $src Source path
 * @param string $dst Destination path
 */
function sfile_recursive_copy($src, $dst) {
    if (is_dir($src)) {
        $dir = opendir($src);
        if (!is_dir($dst)) {
            mkdir($dst);
        }
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir($src . '/' . $file)) {
                    sfile_recursive_copy($src . '/' . $file, $dst . '/' . $file);
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

/**
 * Recursive move
 * 
 * @param string $src Source path
 * @param string $dst Destination path
 */
function sfile_recursive_move($src, $dst) {
    if (is_dir($src))
        $dir_handle = opendir($src);
    $dirname = substr($src, strrpos($src, "/") + 1);

    mkdir($dst . "/" . $dirname, 0750);
    while ($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($src . "/" . $file)) {
                copy($src . "/" . $file, $dst . "/" . $dirname . "/" . $file);
                unlink($src . "/" . $file);
            } else {
                $dirdest1 = $dst . "/" . $dirname;
                sfile_recursive_move($src . "/" . $file, $dirdest1);
            }
        }
    }
    closedir($dir_handle);
    rmdir($src);
}

/**
 * Recursive remove directory
 * 
 * @param string $directory Directory path
 * @param boolean $empty Removes the empty directory
 */
function sfile_recursive_remdir($directory, $empty = FALSE) {
    if (substr($directory, -1) == '/') {
        $directory = substr($directory, 0, -1);
    }
    if (!file_exists($directory) || !is_dir($directory)) {
        return FALSE;
    } elseif (is_readable($directory)) {
        $handle = opendir($directory);
        while (FALSE !== ($item = readdir($handle))) {
            if ($item != '.' && $item != '..') {
                $path = $directory . '/' . $item;
                if (is_dir($path)) {
                    sfile_recursive_remdir($path);
                } else {
                    unlink($path);
                }
            }
        }
        closedir($handle);
        if ($empty == FALSE) {
            if (!rmdir($directory)) {
                return FALSE;
            }
        }
    }
    return TRUE;
}
