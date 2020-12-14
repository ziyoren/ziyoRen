#!/usr/bin/env php
<?php
ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
error_reporting(E_ALL);

defined('BASE_PATH') or define('BASE_PATH', dirname(__DIR__) . '/');

defined('ZIYOREN_AT_SWOOLE') or define('ZIYOREN_AT_SWOOLE', true);

require BASE_PATH . 'app/bootstrap.php';

Swoole\Runtime::enableCoroutine();
//swoole_set_process_name("ZiyoREN");

Co\run(function () {
    ziyoren\Application::run();
});
