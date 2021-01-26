<?php 

namespace MysqlEIport\Adapter;

use MysqlEIport\AdapterTrait;
use MysqlEIport\DbInterface;


/**
 * 
 */
class MysqliAdapter implements DbInterface
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
	
	function __construct(\mysqli $mysqli)
	{
		$this->sql = $mysqli;
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

	public function query($query)
	{
		$this->result = $this->sql->query($query);

		if($this->result){
			// assing $affected_rows and $field_count 
			$this->affected_rows 	= $this->sql->affected_rows;
			$this->field_count 		= $this->sql->field_count;
		}

		return $this;
	}

	public function exec($query)
	{
		$this->result = $this->sql->query($query);

		return $this;
	}

	public function select_db($name)
	{
		return $this->sql->select_db($name);
	}

	public function fetch_row()
	{
		return $this->result->fetch_row();
	}

	/**
	 * @return Escapes special characters in a string for use in an SQL statement
	 */
	public function escape($string): string
	{
		return $this->sql->real_escape_string($string);
	}

	/**
	 * @return Returns the error code from last connect call
	 */
	public function errno()
	{
		return $this->sql->connect_errno;
	}

	/**
	 * @return Returns a string description of the last connect error
	 */
	public function error()
	{
		return $this->sql->connect_error;
	}


}