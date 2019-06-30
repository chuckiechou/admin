<?php

namespace proton\lib;


use proton\lib\exception\ErrorException;
use proton\lib\exception\JsonExceptionHandler;

class Error
{

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
    public static function register()
    {
        error_reporting(E_ALL);
        set_error_handler([__CLASS__, 'appError']);
        set_exception_handler([__CLASS__, 'appException']);
        register_shutdown_function([__CLASS__, 'appShutdown']);
    }

    /**
     * Exception Handler
     * @param  \Exception|\Throwable $e
     */
    public static function appException($e)
    {
//        if (!$e instanceof \Exception) {
//            $e = new ThrowableError($e);
//        }
        self::getExceptionHandler()->report($e);
        self::getExceptionHandler()->render($e)->send();
    }

    /**
     * Error Handler
     * @param  integer $errno 错误编号
     * @param  integer $errstr 详细错误信息
     * @param  string $errfile 出错的文件
     * @param  integer $errline 出错行号
     * @param array $errcontext
     * @throws ErrorException
     */
    public static function appError($errno, $errstr, $errfile = '', $errline = 0, $errcontext = [])
    {
        $exception = new ErrorException($errno, $errstr, $errfile, $errline, $errcontext);
        if (error_reporting() & $errno) {
            throw $exception;
        } else {
            self::getExceptionHandler()->report($exception);
        }
    }

    /**
     * Shutdown Handler
     */
    public static function appShutdown()
    {
        if (!is_null($error = error_get_last()) && self::isFatal($error['type'])) {
            // 将错误信息托管至think\ErrorException
            $exception = new ErrorException($error['type'], $error['message'], $error['file'], $error['line']);

            self::appException($exception);
        }
    }

    /**
     * 确定错误类型是否致命
     *
     * @param  int $type
     * @return bool
     */
    protected static function isFatal($type)
    {
        return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
    }

    public static function getExceptionHandler()
    {
        return new JsonExceptionHandler();
    }
}
