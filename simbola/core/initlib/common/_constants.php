<?php
/**
 * Used to define NO error reporting. Used in the application setup parameters
 */
define("E_PROD", false);
define("SIMBOLA_CLI_MODE", (php_sapi_name() == "cli"));
define("SIMBOLA_CRON_MODE", $_SERVER['argc'] >= 2 && strtolower($_SERVER['argv'][1]) == 'cron');