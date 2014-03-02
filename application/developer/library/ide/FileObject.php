<?php

namespace application\developer\library\ide;

/**
 * Description of FileObject
 *
 * @author Faraj
 */
class FileObject {

    public $title;
    public $path;
    public $key;
    public $isFolder;
    public $isLazy;
    public $icon;    

    public function __construct($path) {
        $this->path = $path;
        $this->key = str_replace(\simbola\Simbola::app()->getParam("BASEPATH") . DIRECTORY_SEPARATOR, "", $path);
        $this->key = str_replace(DIRECTORY_SEPARATOR, "/", $this->key);
        $this->isLazy = $this->isFolder = is_dir($this->path);
        $this->title = basename($this->path);
        $ext = substr($path, strrpos($path, '.') + 1);
        if (strlen($ext) > 3) {
            $ext = "_blank";
        }
        $iconRes = new \simbola\core\component\resource\lib\ResItem;
        $iconRes->module = "developer";
        $iconRes->type = \simbola\core\component\resource\lib\ResItem::TYPE_IMAGE;
        if (!$this->isFolder) {
            $iconRes->name = "icons/{$ext}.png";
        } else {            
            $karr = explode('/', $this->key);
            if(count($karr) >= 2) {
                $karr[1] = "MODULE";
            }
            $key = implode('/', $karr);
            $iconRes->name = 'icons/folder_' . str_replace("/", "_", str_replace("./", "", $key)) . ".png";            
            if (!$iconRes->exist()) {
                $iconRes->name = "icons/folder.png";                
            }
        }
        $this->icon = $iconRes->getUrl();
    }

}

?>
