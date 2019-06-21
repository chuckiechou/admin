<?php
function config($section, $key){
	return \proton\lib\Config::getInstance()->get($section, $key);
}

/**
 * 转义字符串
 * @param string $str 要转义的字符串
 * @return string 转义后的字符串
 */
function esc($str){
	return addslashes(strval($str));
}

/**
 * 取消转义
 * @param mixed $val 要转义的变量
 * @return mixed 转义后的结果
 */
function unesc($val){
	if (is_array($val)) {
		foreach ($val as &$item) {
			$item = unesc($item);
		}
	} elseif (is_string($val)) {
		$val = stripslashes($val);
	}
	return $val;
}
