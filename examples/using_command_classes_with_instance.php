<?php 

require "../vendor/autoload.php";

use MysqlEIport\Commands\MysqlExport;
use MysqlEIport\Commands\MysqlImport;


	$host = "localhost";
	$user = "root";
	$pass = "";
	$name = "empty";

	// Mysql Instance
	$db_instance = new mysqli($host,$user,$pass,$name); 

	if( isset($_POST) && isset($_FILES['db_file']) ){

		$sql_content = $_FILES['db_file']['tmp_name'];

		if(file_exists($sql_content))
		{
			$cmd = new MysqlImport($db_instance, $sql_content);
			$cmd->execute();
		}else{
			throw new \Exception(" $sql_content does not exits", 1);
			
		}

	}
	elseif ( array_key_exists('export', $_GET) ) {
		
		$export = new MysqlExport($db_instance);
		$export->tables = 'migrations';
		$export->backup_name = "test";
		$export->execute();
		
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title> Mysql Export and Import Database and Table </title>
</head>
<body>
	<h1> Mysql Export and Import Database and Table  </h1>
	<h2> Using Mysql Command Classes with Mysql Db Instance </h2>

	<form enctype="multipart/form-data" method="POST">
		<input type="file" name="db_file" />
		<button type="submit" name="import">Go</button>
	</form>

	<form method="GET" >
	<button name="export"> Export </button>
    </form>

</body>
</html>
