<?php

namespace MysqlEIport;

use MysqlEIport\Adapter\MysqliAdapter;
use MysqlEIport\Adapter\PDOAdapter;


/**
 * Trait for Adapters
 */
trait CommandTrait
{
	
	public $db_instance = null;
	public $con;
	/**
	 * @var status of command executed
	 */
	public $exec_status = false;
	
	function validateDbInstance()
	{
		// is_a(), get_class()
		if( $this->db_instance instanceof \PDO || $this->db_instance instanceof \mysqli ){
			$this->resolveAdapter();
			return true;
		}else
		{
			$this->db_instance = null;
			throw new \Exception("wrong Mysql Database Instance");
		}
	}

	function resolveAdapter()
	{
		if( get_class($this->db_instance) === 'mysqli'){
			$this->con = new MysqliAdapter($this->db_instance);
		}else{
			$this->con = new PDOAdapter($this->db_instance);
		}
	}

	/**
	 *  Creates a PDO instance representing a connection to a database 
	 */
	public function connection($host, $db_name, $user, $pass): void
	{

		$dsn = "mysql:dbname=$db_name;host=$host";

		$mysqli = new \PDO($dsn, $user, $pass);
		// set db instance
		$this->db_instance = $mysqli; 

		$this->validateDbInstance();
	}

	/**
	 * check for null database instance
	 */
	public function checkNullDbInstance()
	{
		if( $this->db_instance == null ){
			throw new \Exception("Database Mysql instance cannot be Null");
		}
	}


}
