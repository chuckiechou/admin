<?php

namespace proton\lib\response;


use proton\lib\Factory;
use proton\lib\Response;

class View extends Response
{
    // 输出参数
    protected $options = [];
    protected $vars = [];
    protected $contentType = 'text/html';

    /**
     * 处理数据
     * @access protected
     * @param mixed $data 要处理的数据
     * @return mixed
     */
    protected function output($data)
    {
        // 渲染模板输出
        return Factory::view()->fetch($data, $this->vars);
    }

    public function assign($name, $value = '')
    {
        if (is_array($name)) {
            $this->vars = array_merge($this->vars, $name);
            return $this;
        } else {
            $this->vars[$name] = $value;
        }
        return $this;
    }
}
