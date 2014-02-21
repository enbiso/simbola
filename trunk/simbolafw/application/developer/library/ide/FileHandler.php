<?php

namespace application\developer\library\ide;

/**
 * Description of FileLoader
 *
 * @author Faraj
 */
class FileHandler {

    static function ReadFile($path) {
        if (file_exists($path)) {
            $data = file_get_contents($path);
            $fi = new finfo(FILEINFO_MIME,$path);
            $mime_type = $fi->buffer($data);
            return array('status' => true, 'data64' => base64_encode($data), 'mime' => $mime_type);
        }
        return array('status' => false);
    }

    static function RemoveFile($path) {
        if (file_exists($path)) {
            if (is_dir($path)) {
                self::recursive_remove_directory($path);
            } else {
                unlink($path);
            }
        }
        return array('status' => true);
    }

    static function SaveFile($path, $data) {
        if (file_exists($path)) {
            file_put_contents($path, $data);
            return array('status' => true);
        }
        return array('status' => false);
    }

    static function LoadPath($path) {
        $files = array();
        $folders = array();
        foreach (glob($path) as $file_path) {
            $fileObj = new FileObject($file_path);
            if ($fileObj->isFolder) {
                $folders[] = $fileObj;
            } else {
                $files[] = $fileObj;
            }
        }
        return array_merge($folders, $files);
    }

    static function MoveFile($source, $dest, $method) {
        if (file_exists($source) && !file_exists($dest)) {
            if (is_dir($source)) {
                $dest = dirname($dest);
                if ($method == 'copy') {
                    self::recursive_copy($source, $dest);
                } else {
                    self::recursive_move($source, $dest);
                }
            } else {
                if ($method == 'copy') {
                    copy($source, $dest);
                } else {                    
                    rename($source, $dest);
                }
            }
            return array('status' => true);
        } else {
            return array('status' => false);
        }
    }

    private static function recursive_copy($dirsource, $dirdest) {
        // recursive function to copy
        // all subdirectories and contents:
        if (is_dir($dirsource))
            $dir_handle = opendir($dirsource);
        $dirname = substr($dirsource, strrpos($dirsource, "/") + 1);

        mkdir($dirdest . "/" . $dirname, 0750);
        while ($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirsource . "/" . $file))
                    copy($dirsource . "/" . $file, $dirdest . "/" . $dirname . "/" . $file);
                else {
                    $dirdest1 = $dirdest . "/" . $dirname;
                    self::recursive_copy($dirsource . "/" . $file, $dirdest1);
                }
            }
        }
        closedir($dir_handle);
        return true;
    }

    private static function recursive_move($dirsource, $dirdest) {
        // recursive function to copy
        // all subdirectories and contents:
        if (is_dir($dirsource))
            $dir_handle = opendir($dirsource);
        $dirname = substr($dirsource, strrpos($dirsource, "/") + 1);

        mkdir($dirdest . "/" . $dirname, 0750);
        while ($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirsource . "/" . $file)) {
                    copy($dirsource . "/" . $file, $dirdest . "/" . $dirname . "/" . $file);
                    unlink($dirsource . "/" . $file);
                } else {
                    $dirdest1 = $dirdest . "/" . $dirname;
                    self::recursive_move($dirsource . "/" . $file, $dirdest1);
                }
            }
        }
        closedir($dir_handle);
        rmdir($dirsource);
    }

    static function recursive_remove_directory($directory, $empty = FALSE) {
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
                        self::recursive_remove_directory($path);
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

}

?>
