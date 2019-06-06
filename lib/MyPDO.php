<?php

namespace proton\lib;

use proton\core\App;
use proton\core\Log;

class MyPDO{
	private $pdo; // pdo对象
	private $pdoStat; //PDOStatement对象
	private $settings; //配置信息
	private $connected = false;
	private $dbname;
	private $contime   = 0;
	private $reconnect = false;

	// mysql 的wait_timeout 时间为28800（8个小时）
	// cli 模式下4个小时强制重连一次mysql
	private $cli_rtime = 3600 * 4;

	//兼容老的代码
	public $database_name;

	public function __construct($config){
		$this->settings = $config;
		$this->database_name = $this->dbname = $this->settings[3];
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
			// $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

			$this->connected = true;
			$this->contime = time();
		} catch (\PDOException $e) {
			$this->errorlog($e->getMessage(), '', true);
		}

	}

	public function __destruct(){
		$this->pdo = null;
	}

	/**
	 * 连接.
	 */
	private function connect(){
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
			// $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

			$this->connected = true;
			$this->contime = time();
		} catch (\PDOException $e) {
			$this->errorlog($e->getMessage(), '', true);
		}
	}

	public function getPdo(){
		return $this->pdo;
	}

	/**
	 * 关闭连接.
	 */
	public function close(){
		$this->pdo = null;
	}

	/**
	 * 进行查询.
	 */
	private function execute($query, $params = array()){

		$query = str_replace('#_DB_#', $this->dbname, $query);

		//验证是否需要重新连接
		$this->reconnect();

		try {
			if (stristr($query, 'LOAD DATA')) {
				$this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
				$this->pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
			} else {
				$this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
				$this->pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
			}
			$this->pdoStat = $this->pdo->prepare($query);
			if ($params && is_array($params)) {
				foreach ($params as $key => $param) {
					$key = ":{$key}";
					$this->pdoStat->bindParam($key, $param);
				}
			}
			return $this->pdoStat->execute();
		} catch (\PDOException $e) {
			return $this->errorlog($e->getMessage(), $query);
		} catch (\Exception $e) {
			return $this->errorlog($e->getMessage(), $query);
		}
	}

	//重新连接mysql
	public function reconnect(){
		//php cli脚本让其每4个小时强制重连一次
		if (PHP_SAPI == 'cli') {
			$nrtime = $this->contime + $this->cli_rtime;
			if ($nrtime < time()) {
				$this->reconnect = true;
				$this->connect();
//				fc::debug(date('Y-m-d H:i:s') . ' function:' . init::$fc . " php cli mode mysql reconnect \n", 'mysql_reconnect.txt');
				return;
			}
		}

		if (!$this->connected || !is_object($this->pdo) || !$this->ping()) {
			$this->reconnect = true;
			$this->connect();
		}
	}

	//应对mysql server go away
	public function ping(){
		try {
			$this->pdo->query('DO 1');
		} catch (\PDOException $e) {
//			fc::debug(date('Y-m-d H:i:s') . ' function:' . init::$fc . " |message: " . $e->getMessage() . " \n", 'mysql_reconnect.txt');
			if (strpos($e->getMessage(), 'MySQL server has gone away') !== false) {
				return false;
			}
		}
		return true;
	}

	/**
	 * 取出所有数据作为数组返回.
	 */
	public function getAll($query, $params = array(), $fetchmode = \PDO::FETCH_ASSOC){
		$query = trim($query);
		if (!$this->execute($query, $params)) {
			return array();
		}
		return $this->pdoStat->fetchAll($fetchmode);
	}

	/**
	 * 查询.
	 */
	public function query($query, $params = array()){
		if (!$query) return false;

		$query = trim($query);
		if (!$this->execute($query, $params)) {
			return false;
		}
		$rawStatement = explode(' ', $query);
		$statement = strtolower($rawStatement[0]);
		if (in_array($statement, array('drop', 'create'))) {
			return true;
		}
		//增删改操作，返回影响行数
		if (in_array($statement, array('insert', 'update', 'delete'))) {
			return $this->pdoStat->rowCount();
		}
		//查询语句，返回PDOStatment对象
		// if(in_array($statement, array('select', 'show'))) {
		// 	return $this->pdoStat;
		// }
		return $this->pdoStat;
	}

	/**
	 * 最后插入ID.
	 */
	public function insertID(){
		return intval($this->pdo->lastInsertId());
	}

	/**
	 * 'SELECT id, name FROM xxx'
	 * 返回 array(
	 *    'id' => 'id1',
	 *    'name' => 'name1',
	 * );
	 */
	public function getOne($query, $params = array(), $fetchmode = \PDO::FETCH_ASSOC){
		$query = str_replace(';', '', $query);
		if (stripos($query, 'LIMIT') === false) {
			$query .= ' LIMIT 1';
		}
		if (!$this->execute($query, $params)) {
			return array();
		}
		$result = $this->pdoStat->fetchAll($fetchmode);
		return $result ? $result[0] : array();
	}

	/**
	 * 异常处理.
	 */
	private function errorlog($message, $sql = '', $die = false){
		$exception = date('Y-m-d H:i:s') . ' SQL Exception. ' . "\r\n";
		$exception .= "Last connected time: " . date('Y-m-d H:i:s', $this->contime) . "\r\n";
		$this->reconnect && $exception .= "This link has been reconnected \r\n";
		$exception .= "[{$message}]";
		if (!empty($sql)) {
			$exception .= "\r\nSQL : " . $sql;
		}
		$track = debug_backtrace();
		$exception .= "\r\n";
		$i = 0;
		foreach ($track as $one) {
			$args = '';
			if (!isset($one['class'])) {
				$one['class'] = $one['type'] = '';
			}
			if (!isset($one['file'])) {
				$one['file'] = 'Unknown';
				$one['line'] = '0';
			}
			$exception .= '#' . $i . ' ' . $one['file'] . '(' . $one['line'] . '): ' . $one['class'] . $one['type'] . $one['function'] . '(' . $args . ')' . "\r\n";
			$i++;
		}

		App::$log->add(Log::ERROR, $exception);
		if ($die) {
			if ($this->pdo) $message = json_encode($this->pdo->errorInfo());
			trigger_error("Query: {$sql} // #" . $message, E_USER_ERROR);
		}
		return false;
	}

	//----------------下面的所以方法是为了适配老的逻辑----------------------------
	public function affectedRows(){
		//原来的方法返回-1, 很多逻辑没有严谨的判断，这里直接返回0兼容
		return is_object($this->pdoStat) ? $this->pdoStat->rowCount() : 0;
	}

	public function escape($unescaped_string){
		return addslashes(trim($unescaped_string));
	}
}
