<?php

namespace proton\lib;

use proton\core\App;

class Config{
	private static   $instance;
	protected static $config = array();

	private function __construct(){
	}

	public static function getInstance(){
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function get($section, $key = ''){
		$env = ($section{0} == '@' ? true : false);
		$section = $env ? substr($section, 1) : $section;
		$val = null;
		$fields = $key ? explode('.', $key) : array();
		if (!array_key_exists($section, self::$config)) {
			$this->loadConfig($section, $env);
		}
		$val = self::$config[$section];
		if (!empty($fields) && is_array($fields)) {
			foreach ($fields as $field) {
				if (isset($val[$field])) {
					$val = $val[$field];
				} else {
					return null;
				}
			}
		}
		return $val;
	}

	public function getFile($name, $env = ''){
		$prefix = App::$path;
		$name = $name . ($env ? '.' . App::$env : '');
		$file = "{$prefix}config/{$name}.php";

		$files = array();
		if (file_exists($file)) {
			$files[] = $file;
		}
		return $files;
	}

	public function loadConfig($name, $env = false){
		self::$config[$name] = array();
		$files = (array)$this->getFile($name, $env);
		if ($files) {
			foreach ($files as $file) {
				$overwrites = (array)@include($file);
				foreach ($overwrites as $k => $v) {
					self::$config[$name][$k] = $v;
				}
				unset($overwrites);
			}
		}
		return;
	}
}
