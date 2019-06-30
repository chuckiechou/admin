<?php

namespace proton\lib\exception;


use Exception;
use proton\core\App;
use proton\core\Log;
use proton\lib\AppException;
use proton\lib\Response;

class Handle
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

    public function report(AppException $e)
    {
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

    public function render($e)
    {
        if ($e instanceof HttpException) {
            return $this->renderHttpException($e);
        } else {
            return $this->convertExceptionToResponse($e);
        }
    }

    protected function renderHttpException(HttpException $e)
    {
        $status = $e->getStatusCode();
        $template = Config::get('http_exception_template');
        if (App::$env == 'product' && !empty($template[$status])) {
            return Response::create($template[$status], 'view', $status)->assign(['e' => $e]);
        } else {
            return $this->convertExceptionToResponse($e);
        }
    }

    protected function convertExceptionToResponse(Exception $exception)
    {
        $data = [
            'code' => $this->getCode($exception),
            'message' => $this->getMessage($exception),
        ];

        //保留一层
        while (ob_get_level() > 1) {
            ob_end_clean();
        }
        $data['echo'] = ob_get_clean();
        ob_start();
        extract($data);
        include Config::get('exception_tmpl');
        // 获取并清空缓存
        $content = ob_get_clean();
        $response = new Response($content, 'html');

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
            $response->header($exception->getHeaders());
        }

        if (!isset($statusCode)) {
            $statusCode = 500;
        }
        $response->code($statusCode);
        return $response;
    }

    /**
     * 获取错误信息
     * ErrorException则使用错误级别作为错误编码
     * @param  \Exception $exception
     * @return string                错误信息
     */
    protected function getMessage(Exception $exception)
    {
        $message = $exception->getMessage();
        return $message;
    }

    /**
     * 获取错误编码
     * ErrorException则使用错误级别作为错误编码
     * @param  \Exception $exception
     * @return integer                错误编码
     */
    protected function getCode(Exception $exception)
    {
        $code = $exception->getCode();
        return $code;
    }
}
