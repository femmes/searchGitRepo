<?php

class Database{
	private $host = 'localhost';
	private $user = 'insert personal db username';
	private $pass = 'insert personal db password';
	private $dbname = 'insert personal db name';
	
	public $dbh;
	public $error;

	public function __construct(){
		$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
		$options = array(
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);
		try {
			$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
		}
		catch (PDOException $e) {
			$this->error = $e->getMessage();
		}
	}
	public function close(){
		$this->dbh= null;
		return true;
	}
}