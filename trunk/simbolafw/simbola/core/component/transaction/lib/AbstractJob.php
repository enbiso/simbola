<?php
namespace simbola\core\component\transaction\lib;
/**
 * Description of AbstractJob
 *
 * @author Faraj
 */
abstract class AbstractJob {
    protected $dbObj;
    public function __construct($jobDbObj) {
        $this->dbObj = $jobDbObj;
    }
}
