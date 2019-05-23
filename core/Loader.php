<?php

namespace proton\core;

class Loader{
	const PREFIX = 'proton\\';
	protected $prefixes = array();

	public function register(){
		spl_autoload_register(array($this, 'loadClass'));
	}

	public function loadClass($class){
		$prefix = self::PREFIX;
		$base_dir = APP_PATH;
		$len = strlen($prefix);
		if (strncmp($prefix, $class, $len) !== 0) {
			return;
		}
		$relative_class = substr($class, $len);
		$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
		if (file_exists($file)) {
			require $file;
		}
	}
}
