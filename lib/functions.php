<?php

use proton\lib\Response;

function config($section, $key)
{
    return \proton\lib\Config::getInstance()->get($section, $key);
}

/**
 * 转义字符串
 * @param string $str 要转义的字符串
 * @return string 转义后的字符串
 */
function esc($str)
{
    return addslashes(strval($str));
}

/**
 * 取消转义
 * @param mixed $val 要转义的变量
 * @return mixed 转义后的结果
 */
function unesc($val)
{
    if (is_array($val)) {
        foreach ($val as &$item) {
            $item = unesc($item);
        }
    } elseif (is_string($val)) {
        $val = stripslashes($val);
    }
    return $val;
}

if (!function_exists('json')) {
    /**
     * 获取\think\response\Json对象实例
     * @param mixed $data 返回的数据
     * @param integer $code 状态码
     * @param array $header 头部
     * @param array $options 参数
     * @return \proton\lib\response\Json
     */
	function json($data = [], $code = 200, $header = [], $options = [])
	{
		return Response::create($data, 'json', $code, $header, $options);
	}
}

if (!function_exists('view')) {
    /**
     * 渲染模板输出
     * @param string $template 模板文件
     * @param array $vars 模板变量
     * @param integer $code 状态码
     * @return \proton\lib\response\View
     */
    function view($template = '', $vars = [], $code = 200)
    {
        return Response::create($template, 'view', $code)->assign($vars);
    }
}
