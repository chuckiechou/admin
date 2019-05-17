<?php

namespace proton\core;

use proton\lib\Config;

class App{
	public static $path;
	public static $env;

	public static function run($path, $env){
		//初始化变量
		self::$path = $path;
		self::$env = $env;
		$res = Config::getInstance()->get('system', 'clients');

	}

	public static function cli(){
		
	}
}
