<?php

namespace proton\controller;

use proton\lib\MyPDO;
use proton\lib\Request;

class User{
	public function index(){
		/*$db = config('@core', 'db.main');
		$result = new MyPDO($db);
		$sql = "select * from user limit 1";
		print_r($result->getOne($sql));*/
		$res = (Request::getInstance())->get('action');
		print_r($res);
	}
}
