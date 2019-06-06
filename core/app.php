<?php

namespace proton\core;

class App{
	public static $path;
	public static $env;
	public static $log;
	public static $action;

	public static function run($path, $env){
		//初始化变量
		self::$path = $path;
		self::$env = $env;
		self::_requireFiles();
		try {
			self::$log = Log::instance();
			self::$log->attach(new Logger(self::$path . 'data/log/'), Log::STRACE);
			self::$log->attach(new Logger(self::$path . 'data/debug/'), array(Log::DEBUG));
			AppException::register();
			self::_dispatch();
			$controller = "\\proton\\controller\\" . self::$action[0] . "";
			if (class_exists($controller) && method_exists($controller, self::$action[1])) {
				$ret = call_user_func(array(new $controller, self::$action[1]));
			} else {
				throw new \Exception('Controller is not Found');
			}
		} catch (\Exception $e) {
			$code = $e->getCode();
			if (self::$log && $code !== null) {
				//self::$log->add(LOG_ERR, $e->getMessage()." \r\nTrace:".$e->getTraceAsString());
				AppException::exceptionHandler($e);
			}
		}
	}

	public static function cli(){

	}

	protected static function _requireFiles(){
		require_once(self::$path . 'lib/functions.php');
	}

	protected static function _dispatch(){
		$ret = isset($_REQUEST['fc']) ? explode('.', $_REQUEST['fc'], 2) : array();
		$ret[0] = isset($ret[0]) ? preg_replace('/\W/', '', $ret[0]) : 'index';
		$ret[1] = isset($ret[1]) ? preg_replace('/\W/', '', $ret[1]) : 'index';
		self::$action = array($ret[0], $ret[1]);
	}
}
