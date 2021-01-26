<?php 

namespace MysqlEIport\Adapter;

use MysqlEIport\AdapterTrait;
use MysqlEIport\DbInterface;



/**
 * 
 */
class PDOAdapter implements DbInterface
{
	use AdapterTrait;

	/**
	 * @var Returns the number of columns for the most recent query
	 */
	public $field_count;
	/**
	 * @var return the number of affected rows
	 */
	public $affected_rows; 
	public $result;
	
	/**
	 *  @param $pdo PDO Database Instance
	 */
	function __construct(\PDO $pdo)
	{
		$this->sql = $pdo;
	}

	/**
     *  @return Name of the current default Database
     */	
	public function getDbName(): string
	{
		$this->result = $this->sql->query(" SELECT DATABASE() ");
		$row = $this->fetch_row();
		return $row[0];
	}

	/**
	 * @param $query SQL Statement
	 * Performs a query on the database
	 * @return PDOAdapter instance
	 */
	public function query($query)
	{
		$this->result = $this->sql->query($query);

		if($this->result){
			$this->affected_rows = $this->result->rowCount();
			$this->field_count = $this->result->columnCount();
		}

		return $this;
	}

	/**
	 * @param $query SQL Statement
	 * Performs a query on the database
	 * @return PDOAdapter instance
	 */
	public function exec($query)
	{
		$this->result = $this->sql->query($query);

		return $this;
	}

	/**
	 * @param $name database name
	 * @return result of 
	 */
	public function select_db($name)
	{
		return $this->sql->query(" USE $name ");
	}

	/**
	 *  @param 
	 *  @return array of 
	 */
	public function fetch_row()
	{
		return $this->result->fetch();
	}

	/**
	 * @return Quotes a string for use in a query
	 */
	public function escape($string): string
	{
		return $this->sql->quote($string);
	}

	/**
	 * @return Returns error codes for operations performed directly on the database handle
	 */
	public function errno()
	{
		return $this->sql->errorCode();
	}

	/**
	 * @return Fetch extended error information associated with the last operation on the database handle 
	 */
	public function error()
	{
		return $this->sql->errorInfo();
	}


}