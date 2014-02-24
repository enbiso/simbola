<?php

namespace application\developer\service;

/**
 * Description of IdeService
 *
 * @author farflk
 */
class IdeService extends \simbola\core\application\AppService {

    public $schema_loadResourceCache = array(
        'req' => array('params' => array()),
        'res' => array('status'),
        'err' => array('')
    );

    public function actionLoadResourceCache() {
        \simbola\Simbola::app()->resource->loadCache();
        $this->_res('status', true);
    }

    public $schema_promoteFile = array(
        'req' => array('params' => array('path', 'promotePath')),
        'res' => array('status'),
        'err' => array('PROMOTE_FAILED')
    );

    public function actionPromoteFile() {
        $path = \simbola\Simbola::app()->basepath('app') . DIRECTORY_SEPARATOR . $this->_req_params('path');
        $promotePath = $this->_req_params('promotePath');
        if (!sstring_starts_with(DIRECTORY_SEPARATOR, $promotePath)) {
            $promotePath = \simbola\Simbola::app()->basepath('app') . DIRECTORY_SEPARATOR . $promotePath;
        }
        $dest = $promotePath . DIRECTORY_SEPARATOR . $this->_req_params('path');
        sfile_recursive_copy($path, $dest);
        $this->_res('status', true);
    }

    public $schema_createFile = array(
        'req' => array('params' => array('path', 'name', 'type')),
        'res' => array('status'),
        'err' => array('FILE_EXIST')
    );

    public function actionCreateFile() {
        $path = $oldPath = \simbola\Simbola::app()->basepath('app') . DIRECTORY_SEPARATOR
                . $this->_req_params('path') . DIRECTORY_SEPARATOR
                . $this->_req_params('name');
        if (file_exists($path)) {
            $this->_err('FILE_EXIST');
        } else {
            $type = $this->_req_params('type');
            if ($type == "file") {
                touch($path);
            } else if ($type == "dir") {
                mkdir($path);
            }
            $this->_res('status', true);
        }
    }

    public $schema_renameFile = array(
        'req' => array('params' => array('key', 'new_name')),
        'res' => array('status'),
        'err' => array('FILE_NOT_EXIST', 'TARGET_EXIST')
    );

    public function actionRenameFile() {
        new \simbola\core\component\url\lib\Page;
        $key = $this->_req_params('key');
        $key = str_replace("/", DIRECTORY_SEPARATOR, $key);
        $oldPath = \simbola\Simbola::app()->basepath('app') . DIRECTORY_SEPARATOR . $key;
        if (file_exists($oldPath)) {
            $newPath = dirname($oldPath) . DIRECTORY_SEPARATOR . $this->_req_params('new_name');
            if (file_exists($newPath)) {
                $this->_err('TARGET_EXIST');
            } else {
                rename($oldPath, $newPath);
                $this->_res('status', true);
            }
        } else {
            $this->_err('FILE_NOT_EXIST');
        }
    }

    public $schema_getFileList = array(
        'req' => array('params' => array('path')),
        'res' => array('files'),
        'err' => array()
    );

    public function actionGetFileList() {
        if ($this->_req_params('path') != "") {
            $path = $this->_req_params("path");
            if (sstring_starts_with(".", $path)) {
                $path = substr($path, 1);
            }
            if (!sstring_ends_with(DIRECTORY_SEPARATOR, $path)) {
                $path = DIRECTORY_SEPARATOR . $path;
            }
            $path = \simbola\Simbola::app()->basepath('app') . $path . DIRECTORY_SEPARATOR . "*";
            $files = \application\developer\library\ide\FileHandler::LoadPath($path);
        } else {
            $files = array(
                'title' => \simbola\Simbola::app()->getParam("APPNAME"),
                'isFolder' => true,
                'key' => '.',
                'isLazy' => true,
            );
        }
        $this->_res('files', $files);
    }

    public $schema_moveFile = array(
        'req' => array('params' => array('source_path', 'dest_path', 'method')),
        'res' => array('status'),
        'err' => array('FILE_NOT_EXIST', 'TARGET_EXIST')
    );

    public function actionMoveFile() {
        $source = \simbola\Simbola::app()->basepath('app') . DIRECTORY_SEPARATOR . $this->_req_params('source_path');
        $dest = \simbola\Simbola::app()->basepath('app') . DIRECTORY_SEPARATOR
                . $this->_req_params('dest_path') . DIRECTORY_SEPARATOR
                . basename($source);
        if (!file_exists($source)) {
            $this->_err('FILE_NOT_EXIST');
        } else if (file_exists($dest)) {
            $this->_err('TARGET_EXIST');
        } else {
            $out = \application\developer\library\ide\FileHandler::MoveFile($source, $dest, $this->_req_params('method'));
            $this->_res('status', $out['status']);
        }
    }

    public $schema_removeFile = array(
        'req' => array('params' => array('path')),
        'res' => array('status'),
        'err' => array('FILE_NOT_EXIST')
    );

    public function actionRemoveFile() {
        $path = \simbola\Simbola::app()->basepath('app') . DIRECTORY_SEPARATOR . $this->_req_params('path');
        if (file_exists($path)) {
            $out = \application\developer\library\ide\FileHandler::RemoveFile($path);
            $this->_res('status', $out['status']);
        } else {
            $this->_err('FILE_NOT_EXIST');
        }
    }

    public $schema_getFileContent = array(
        'req' => array('params' => array('path')),
        'res' => array('status', 'mime', 'data'),
        'err' => array('FILE_NOT_EXIST', 'FILE_IS_DIR')
    );

    public function actionGetFileContent() {
        $path = \simbola\Simbola::app()->basepath('app') . DIRECTORY_SEPARATOR . $this->_req_params('path');
        if(is_dir($path)){
            $this->_err('FILE_IS_DIR');
        }elseif (file_exists($path)) {
            $out = \application\developer\library\ide\FileHandler::ReadFile($path);
            $this->_res('status', $out['status']);
            $this->_res('mime', $out['mimeType']);
            $this->_res('data', $out['dataB64']);
        } else {
            $this->_err('FILE_NOT_EXIST');
        }
    }

    public $schema_setFileContent = array(
        'req' => array('params' => array('path', 'data')),
        'res' => array('status'),
        'err' => array('FILE_NOT_EXIST')
    );

    public function actionSetFileContent() {
        $path = \simbola\Simbola::app()->basepath('app') . DIRECTORY_SEPARATOR . $this->_req_params('path');
        if (file_exists($path)) {
            $data = base64_decode($this->_req_params('data'));
            $out = \application\developer\library\ide\FileHandler::SaveFile($path, $data);
            $this->_res('status', $out['status']);
        } else {
            $this->_err('FILE_NOT_EXIST');
        }
    }

}

?>
