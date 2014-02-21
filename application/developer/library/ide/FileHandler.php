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
            return array(
                'status' => true, 
                'mimeType' => sfile_mime_type($path),
                'dataB64' => base64_encode(file_get_contents($path))
            );
        }
        return array('status' => false);
    }

    static function RemoveFile($path) {
        if (file_exists($path)) {
            if (is_dir($path)) {
                sfile_recursive_remdir($path);
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
                    sfile_recursive_copy($source, $dest);
                } else {
                    sfile_recursive_move($source, $dest);
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
}

?>
