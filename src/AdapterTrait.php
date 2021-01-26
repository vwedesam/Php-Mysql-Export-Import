<?php 

namespace MysqlEIport;

/**
 *  Adapter Trait
 */
Trait AdapterTrait
{
	
	/**
	 * @var database connection object
	 */
	public $sql;

	/** 
	 * check if database is empty
	 */
	public function isEmpty($db_name)
	{
		$stm = $this->exec("SELECT COUNT(DISTINCT `table_name`) FROM `information_schema`.`columns` WHERE `table_schema` = '$db_name' ");
		if( $stm->fetch_row()[0] == 0 ){
			throw new \Exception(" Cannot export empty Mysql database");
		}
	}


}