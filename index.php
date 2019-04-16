<?php
define('APP_ENV', 'dev');
define('APP_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);


require './core/Loader.php';

$loader = new \zerg\core\Loader();
$loader->register();

$app = \zerg\core\App::run();
