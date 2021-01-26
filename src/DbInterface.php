<?php 

namespace MysqlEIport;

/**
 * Interface for mysqli and PDO Adapter
 */
interface DbInterface 
{

	public function getDbName(): string;

	public function query($query);

	public function exec($query);

	public function select_db($name); 

	public function fetch_row();

	public function escape($string): string;

	public function errno();

	public function error();

	
}