<?php

namespace proton\core;


class AppException extends \Exception{
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
	 * 构造函数
	 * @param string $message 错误消息
	 * @param int $code 错误类型
	 * @param mixed $previous 未知
	 */
	public function __construct($message = null, $code = null, $previous = null){
		parent::__construct($message, $code);
	}

	/**
	 * 错误捕获处理
	 * @param $code
	 * @param $error
	 * @param string $file
	 * @param string $line
	 * @return bool
	 */
	public static function errorHandler($code, $error, $file = null, $line = null){
		AppException::exceptionHandler(new \ErrorException($error, $code, 0, $file, $line));
		return !in_array($code, self::$fatalErrors);
	}

	/**
	 * shutdown处理函数，获取fatal错误
	 */
	public static function shutdownHandler(){
		$error = error_get_last();
		if ($error) {
			AppException::exceptionHandler(new \ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));
		}
	}

	/**
	 * 异常处理函数
	 */
	public static function exceptionHandler($e){
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
	}

	public static function register(){
		set_error_handler([__CLASS__, 'errorHandler']);
		set_exception_handler([__CLASS__, 'exceptionHandler']);
		register_shutdown_function([__CLASS__, 'shutdownHandler']);
	}
}
