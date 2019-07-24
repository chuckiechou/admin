<?php

namespace proton\lib;


use proton\core\App;

class Factory{
	protected static $instance = [];

	public static function view(){
		if (empty(self::$instance) || (!array_key_exists(__FUNCTION__, self::$instance) && !is_object(self::$instance[__FUNCTION__]))) {
			$filename = App::$path . 'lib/smarty/libs/Smarty.class.php';
			if (file_exists($filename)) {
				include_once $filename;
				$smarty = new \Smarty();
				$cache_dir = App::$path . 'views/caches/'; //模板缓存目录
				is_dir($cache_dir) || mkdir($cache_dir, 0777, true);
				$smarty->caching = false;
				$smarty->cache_lifetime = 1000;
				$smarty->left_delimiter = '<*'; //开始符
				$smarty->right_delimiter = '*>'; //结束符
				$smarty->force_compile = App::$env == 'product' ? false : true; //强制重编译,上线后改为false
				$smarty->compile_check = App::$env == 'product' ? false : true; //检查模板改动,上线后改为false
				$smarty->setTemplateDir(App::$path . 'views/templates')->setCompileDir(App::$path . 'views/templates_c')->setCacheDir($cache_dir);
				$smarty->debugging = false; //打开调试
				$smarty->debugging_ctrl = 'URL'; //调试方法
				$smarty->use_sub_dirs = false; //编译和缓存可以分子目录
				self::$instance[__FUNCTION__] = $smarty;
			}
		}

		return self::$instance[__FUNCTION__];
	}
}
