<?php

namespace proton\lib;

class Request{

	/**
	 * 当前请求参数
	 * @var array
	 */
	protected $param = [];

	/**
	 * 当前GET参数
	 * @var array
	 */
	protected $get = [];

	/**
	 * 当前POST参数
	 * @var array
	 */
	protected $post = [];

	/**
	 * 当前REQUEST参数
	 * @var array
	 */
	protected $request = [];

	/**
	 * 当前PUT参数
	 * @var array
	 */
	protected $put;

	/**
	 * 当前FILE参数
	 * @var array
	 */
	protected $file = [];

	/**
	 * 当前COOKIE参数
	 * @var array
	 */
	protected $cookie = [];
	/**
	 * 当前SERVER参数
	 * @var array
	 */
	protected $server = [];

	/**
	 * 资源类型定义
	 * @var array
	 */
	protected $mimeType = [
		'xml'   => 'application/xml,text/xml,application/x-xml',
		'json'  => 'application/json,text/x-json,application/jsonrequest,text/json',
		'js'    => 'text/javascript,application/javascript,application/x-javascript',
		'css'   => 'text/css',
		'rss'   => 'application/rss+xml',
		'yaml'  => 'application/x-yaml,text/yaml',
		'atom'  => 'application/atom+xml',
		'pdf'   => 'application/pdf',
		'text'  => 'text/plain',
		'image' => 'image/png,image/jpg,image/jpeg,image/pjpeg,image/gif,image/webp,image/*',
		'csv'   => 'text/csv',
		'html'  => 'text/html,application/xhtml+xml,*/*',
	];

	/**
	 * php://input内容
	 * @var string
	 */
	protected $input;

	/**
	 * 是否合并Param
	 * @var bool
	 */
	protected $mergeParam = false;

	protected static $instance = null;

	public function __construct(){
		$this->input = file_get_contents('php://input');
	}

	public function get($name, $default = null){
		if (empty($this->get)) {
			$this->get = $_GET;
		}
		return $this->input($this->get, $name, $default);
	}

	public static function getInstance(){
		if (is_null(static::$instance)) {
			static::$instance = new static;
		}
		return static::$instance;
	}

	public function input($data = [], $name = '', $default = null){
		if (!$name) {
			// 获取原始数据
			return $data;
		}
		if ($name != '') {
			if (is_null($data)) {
				return $default;
			}
		}
		$value = $data[$name];
		$filter = 'htmlentities';
		if (is_callable($filter)) {
			$value = call_user_func($filter, $value);
		}
		return $value;
	}

	/**
	 * 当前请求 HTTP_CONTENT_TYPE
	 * @access public
	 * @return string
	 */
	public function contentType(){
		$contentType = $_SERVER['CONTENT_TYPE'];

		if ($contentType) {
			if (strpos($contentType, ';')) {
				list($type) = explode(';', $contentType);
			} else {
				$type = $contentType;
			}
			return trim($type);
		}

		return '';
	}

	/**
	 * 获取POST请求参数
	 * @param string $name
	 * @param null $default
	 * @return array|mixed|null
	 */
	public function post($name = '', $default = null){
		if (empty($this->post)) {
			$this->post = !empty($_POST) ? $_POST : $this->getInputData($this->input);
		}

		return $this->input($this->post, $name, $default);
	}

	/**
	 * 获取PUT参数
	 * @access public
	 * @param  string|false $name 变量名
	 * @param  mixed $default 默认值
	 * @param  string|array $filter 过滤方法
	 * @return mixed
	 */
	public function put($name = '', $default = null, $filter = ''){
		if (is_null($this->put)) {
			$this->put = $this->getInputData($this->input);
		}

		return $this->input($this->put, $name, $default, $filter);
	}

	/**
	 * 获取当前请求参数
	 * @param string $name
	 * @param null $default
	 */
	public function params($name = '', $default = null){
		$this->param = array_merge($this->param, $this->get(false), $this->post(false));
		return $this->input($this->param, $name, $default);
	}

	/**
	 * @param $content
	 * @return array
	 */
	protected function getInputData($content){
		if (false !== strpos($this->contentType(), 'application/json') || 0 === strpos($content, '{"')) {
			return (array)json_decode($content, true);
		} elseif (strpos($content, '=')) {
			parse_str($content, $data);
			return $data;
		}
		return [];
	}

	public function ajax(){
		if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
			return true;
		} else {
			return false;
		}
	}

}
