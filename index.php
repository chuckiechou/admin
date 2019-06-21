<?php
define('APP_ENV', 'dev');
define('APP_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
require './core/Loader.php';
$loader = new \proton\core\Loader();
$loader->register();
$app = \proton\core\App::run(APP_PATH, APP_ENV);
