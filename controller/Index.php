<?php

namespace proton\controller;


class Index{
	public function index(){
		return view('index/index.html', array('data' => 111));
	}
}
