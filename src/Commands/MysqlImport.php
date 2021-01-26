<?php	  

namespace MysqlEIport\Commands;

use MysqlEIport\CommandInterface;
use MysqlEIport\CommandTrait;



/**
  *  Import Mysql db and Table
  */
class MysqlImport implements CommandInterface
{

	use CommandTrait;

	/**
	 * File may be compressed (gzip, bzip2, zip) or uncompressed.
	 * @var A compressed file's name must end in .[format].[compression]. Example: .sql.zip
	 */
	public $sql_file_OR_content;

	function __construct($db_instance = null, $sql_file_OR_content)
	{
		if( $db_instance !== null )
		{
			$this->db_instance = $db_instance;
			$this->validateDbInstance();
		}

		$this->sql_file_OR_content = $sql_file_OR_content;
	}

	/**
	 * 
	 */
	public function valiateSqlFileContent()
	{
		if(!is_file($this->sql_file_OR_content)){
			throw new \Exception(" {$this->sql_file_OR_content} is not a regular file");
		}elseif ($this->sql_file_OR_content == "") {
			throw new \Exception(" {$this->sql_file_OR_content} cannot be empty");
		}
	}

	/** 
	  * execute db import command
	**/
	public function execute()
	{
		set_time_limit(3000);

		// check null db instance before running Command
		$this->checkNullDbInstance();
		$this->valiateSqlFileContent();

		$SQL_CONTENT = (strlen($this->sql_file_OR_content) > 300 ?  $this->sql_file_OR_content : file_get_contents($this->sql_file_OR_content)  ); 

		$allLines = explode("\n", $SQL_CONTENT);

		if ( $this->con->errno())
		{
			echo "Failed to connect to MySQL: " . $this->con->error();
		} 
		
		$mysql_command = $this->con->query('SET foreign_key_checks = 0');	        

		preg_match_all("/\nCREATE TABLE(.?)\`(.?)\`/si", "\n". $SQL_CONTENT, $target_tables); 

		foreach ( $target_tables[2] as $table )
		{
			$this->con->query('DROP TABLE IF EXISTS '.$table);
		}         

		$mysql_command = $this->con->query('SET foreign_key_checks = 1'); 

		$this->con->query("SET NAMES 'utf8'");	

		$templine = '';	// Temporary variable, used to store current query
		foreach ($allLines as $line)
		{ // Loop through each line
			if ( substr($line, 0, 2) != '--' && $line != '' )
			{
				$templine .= $line; // (if it is not a comment..) Add this line to the current segment
				if (substr(trim($line), -1, 1) == ';') 
				{// If it has a semicolon at the end, it's the end of the query
					if(!$this->con->query($templine))
					{ 
						$this->exec_status = false;
						
						throw new \Exception('Error performing query \'<strong>' . $templine . '\': ' . $this->con->error() . '<br /><br />');  
					}  

					$templine = ''; 
						// set variable to empty, to start picking up the lines after ";"
				}
			}
		}
		
		$this->exec_status = true;
		return true;
	}   

}