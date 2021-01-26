<?php 

require "../vendor/autoload.php";

use MysqlEIport\Commands\MysqlExport;
use MysqlEIport\Commands\MysqlImport;


	$host = "localhost";
	$user = "root";
	$pass = "";
	$name = "empty";

	// Connection Parameters
	$connection_params = array($host, $name, $user, $pass);

	if( isset($_POST) && isset($_FILES['db_file']) ){

		$sql_content = $_FILES['db_file']['tmp_name'];

		if(file_exists($sql_content))
		{
			$cmd = new MysqlImport(null, $sql_content);
			$cmd->connection($connection_params);
			$cmd->execute();
		}else{
			throw new \Exception(" $sql_content does not exits", 1);
		}

	}
	elseif ( array_key_exists('export', $_GET) ) {
		
		$export = new MysqlExport(null);
		$export->tables = 'migrations';
		$export->backup_name = "test";
		$cmd->connection($connection_params);
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
	<h2> Using Mysql Command Classes with Connection Parameters </h2>
	<pre> array( host_name , database_name, user_name, password); </pre>

	<form enctype="multipart/form-data" method="POST">
		<input type="file" name="db_file" />
		<button type="submit" name="import">Go</button>
	</form>

	<form method="GET" >
	<button name="export"> Export </button>
    </form>

</body>
</html>
