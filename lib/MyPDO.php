<?php

namespace proton\lib;

class MyPDO{
	private $pdo; // pdo对象
	private $pdoStat; //PDOStatement对象
	private $settings; //配置信息
	private $connected = false;
	private $dbname;
	private $contime   = 0;
	private $reconnect = false;
	private $cli_rtime = 14400;

	public function __construct($config){
		$this->settings = $config;
		$this->connect();
		$this->database_name = $this->dbname = $this->settings[3];
	}

	public function connect(){
		$dsn = 'mysql:dbname=' . $this->settings[3] . ';host=' . $this->settings[0] . '';
		try {
			$options = array(
				\PDO::ATTR_TIMEOUT            => 3,
				\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
				\PDO::MYSQL_ATTR_LOCAL_INFILE => true,
				\PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_EMULATE_PREPARES   => false,
			);
			$this->pdo = new \PDO($dsn, $this->settings[1], $this->settings[2], $options);

			$this->connected = true;
			$this->contime = time();
		} catch (\PDOException $e) {
			print_r($e->getMessage());
		}
	}
}
