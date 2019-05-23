<?php
function config($section, $key){
	return \proton\lib\Config::getInstance()->get($section, $key);
}
