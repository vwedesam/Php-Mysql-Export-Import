<?php

use MysqlEIport\Commands\MysqlExport;
use MysqlEIport\Commands\MysqlImport;

	function parseConnectionArray($cmd_obj, $connection)
	{
		if($connection !== false){
			$valid = ['host', 'name', 'user', 'pass'];

			if( sizeof(array_intersect(array_keys($connection), $valid)) == 4 ){
				$host = $connection["host"];
				$name = $connection["name"];
				$user = $connection["user"];
				$pass = $connection["pass"];

				$cmd_obj->connection($host, $name, $user, $pass);
			}
		}
	}

	function MysqlExporter($db_instance=null, $tables=false, $backup_name=false, $connection=false)
	{
		$cmd = new MysqlExport($db_instance);
		$cmd->tables = $tables;
		$cmd->backup_name = $backup_name;
		parseConnectionArray($cmd, $connection);
		$cmd->execute();
	}

	function MysqlImporter($db_instance, $sql_content, $connection=false)
	{
		if(file_exists($sql_content))
		{
			$cmd  = new MysqlImport($db_instance, $sql_content);
			parseConnectionArray($cmd, $connection);
			return $cmd->execute();

		}else{
			throw new \Exception(" $sql_content does not exits", 1);
		}

	}


?>