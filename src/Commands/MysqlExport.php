<?php

namespace MysqlEIport\Commands;

use MysqlEIport\CommandInterface;
use MysqlEIport\CommandTrait;


/**
  *  Export Mysql db and Table
  */
class MysqlExport implements CommandInterface
{

	use CommandTrait;

	public $name;
	public $content;
	/**
	 * @var table name
	 * to backup specific tables only,like: array("mytable1","mytable2",...)
	 */
	public $tables = false;
	/**
	 * @var custom backup file name
	 *  
	 */
	public $backup_name = false;

	function __construct($db_instance = null)
	{
		if( $db_instance !== null )
		{
			$this->db_instance = $db_instance;
			$this->validateDbInstance();
		}
	}

	/* 
     * IMPORTANT NOTE ! Many people replaces strings in SQL file, which is not recommended. 
	 *
	 * by https://github.com/ttodua/useful-php-scripts 
	 */
	public function execute(): void
	{ 
		set_time_limit(3000); 

		// check instance before running Command
		$this->checkNullDbInstance();

		$this->name = $this->con->getDbName();
		
		$this->con->isEmpty($this->name);

		$this->con->select_db($this->name); 

		$this->con->query("SET NAMES 'utf8'");

		$queryTables = $this->con->query('SHOW TABLES'); 

		while( $row = $queryTables->fetch_row() ) 
		{ 
			$target_tables[] = $row[0]; 
		}	

		if($this->tables !== false) 
		{ 
			$target_tables = array_intersect( $target_tables, $this->tables); 
		} 

		$content = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO \"; \r\nSET AUTOCOMMIT = 0; \r\nSTART TRANSACTION; \r\nSET time_zone = \"+00:00\"; \r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */; \r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */; \r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */; \r\n/*!40101 SET NAMES utf8 */; 
		    \r\n--\r\n-- Database: `".$this->name."`\r\n--\r\n\r\n\r\n";

		foreach( $target_tables as $table ){

			if (empty($table)){ continue; } 

			$result	= $this->con->query('SELECT * FROM `'.$table.'`');  	

			$fields_amount = $this->con->field_count;  
			$rows_num = $this->con->affected_rows; 

			$res = $this->con->query('SHOW CREATE TABLE '.$table);	
			$TableMLine = $res->fetch_row(); 

			$content .= "\n\n";
			$content .= "DROP TABLE IF EXISTS `". $table ."`;\n";
			$content .= $TableMLine[1].";\n\n";  
			$TableMLine[1] = str_ireplace('CREATE TABLE `','CREATE TABLE IF NOT EXISTS `', $TableMLine[1]);

			$result	= $this->con->query('SELECT * FROM `'.$table.'`');

			for ( $i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) {

				while($row = $result->fetch_row())
				{ //when started (and every after 100 command cycle):

					if ( $st_counter % 100 == 0 || $st_counter == 0 )	
					{
						$content .= "\nINSERT INTO ".$table." VALUES";}
						$content .= "\n(";    
						  for( $j = 0; $j < $fields_amount; $j++)
						  { 
						  	$row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); 
						  	if (isset($row[$j]))
						  	{
						  		$content .= '"'.$row[$j].'"' ;
						  	}else
						  	{
						  		$content .= '""';
						    }	   
						    if ($j<($fields_amount-1))
						    {
						    	$content.= ',';
						    } 
						}        
						$content .=")";
					//every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
					if ( (($st_counter+1) % 100 == 0 && $st_counter != 0) || $st_counter+1 == $rows_num) 
					{ 
						$content .= ";";
					}else 
					{
						$content .= ",";
					}	
					$st_counter = $st_counter+1;
				}
			} 
			$content .="\n\n\n";
		}

		$content .= "COMMIT;\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */; \r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */; \r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";

		$exted_name = '_('.date('H-i-s').'_'.date('d-m-Y').').sql';
		$this->backup_name = $this->backup_name ? $this->backup_name.$exted_name : $this->name.$exted_name;

		$this->content = $content;
		$this->exec_status = true;

		ob_get_clean(); 
		header('Content-Type: application/octet-stream');  
		header("Content-Transfer-Encoding: Binary");  
		header('Content-Length: '. (function_exists('mb_strlen') ? mb_strlen($this->content, '8bit'): strlen($this->content)) );    
		header("Content-disposition: attachment; filename=\"".$this->backup_name."\""); 

		echo $this->content; 
		exit;

	}

}


?>