<?php

namespace proton\lib;

use Exception;

use proton\core\App;
use proton\core\Log;

class AppException extends Exception{
	/**
	 * 需要忽略掉的错误类型
	 * @var array
	 */
	public static $ignoreErrors = array(E_STRICT, E_ALL);

	/**
	 * 需要中止的错误
	 * @var array
	 */
	public static $fatalErrors = array(
		E_ERROR,
		E_PARSE,
		E_CORE_ERROR,
		E_COMPILE_ERROR,
		E_USER_ERROR,
		E_RECOVERABLE_ERROR
	);

	/**
	 * 注册异常处理
	 * @return void
	 */
	public static function register(){
		error_reporting(E_ALL);
		set_error_handler([__CLASS__, 'appError']);
		set_exception_handler([__CLASS__, 'appException']);
		register_shutdown_function([__CLASS__, 'appShutdown']);
	}

	/**
	 * @param $e
	 */
	public static function appException($e){
		$file = $e->getFile();
		$line = $e->getline();
		$code = $e->getCode();
		$message = $e->getMessage();

		if (!in_array($code, self::$ignoreErrors)) {
			//记录或输出异常信息
			if (App::$log != null) {
				Log::$writeOnAdd = true;
				App::$log->add(LOG_ERR, sprintf("[%s.%s@%s#%d] (%d) %s", App::$action[0], App::$action[1], $file, $line, $code, $message));
			}
		}
		//报错页面，json或者view
	}

	/**
	 * @param $code
	 * @param $error
	 * @param null $file
	 * @param null $line
	 * @return bool
	 */
	public static function appError($code, $error, $file = null, $line = null){
		self::appException(new \ErrorException($error, $code, 0, $file, $line));
		return !in_array($code, self::$fatalErrors);
	}

	/**
	 * Shutdown Handler
	 */
	public static function appShutdown(){
		$error = error_get_last();
		if ($error) {
			self::appException(new \ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));
		}
	}

	/**
	 * 确定错误类型是否致命
	 *
	 * @param  int $type
	 * @return bool
	 */
	protected static function isFatal($type){
		return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
	}
}

