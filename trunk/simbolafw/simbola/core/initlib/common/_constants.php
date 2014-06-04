<?php
/**
 * Used to define NO error reporting. Used in the application setup parameters
 */
define("E_PROD", false);
define("SIMBOLA_CLI", (php_sapi_name() == "cli"));