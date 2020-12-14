<?php

use function ziyoren\config;

defined('BASE_PATH')    or define('BASE_PATH',    dirname(__DIR__) . '/');
defined('CONF_PATH')    or define('CONF_PATH',    BASE_PATH. 'config/');
defined('RUNTIME_PATH') or define('RUNTIME_PATH', BASE_PATH. 'runtime/');
defined('CACHE_PATH')   or define('CACHE_PATH',   RUNTIME_PATH . 'cache/');
defined('LOG_PATH')     or define('LOG_PATH',     RUNTIME_PATH . 'logs/');

require( BASE_PATH. 'vendor/autoload.php');

$timezone = config('config.timezone', null);

if ($timezone) {

    date_default_timezone_set($timezone);

}
