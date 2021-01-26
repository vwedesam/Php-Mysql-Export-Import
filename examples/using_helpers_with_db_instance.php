<?php 

require "../vendor/autoload.php";

	$host = "localhost";
	$user = "root";
	$pass = "";
	$name = "empty";

	// PDO instance
	$db_instance = new \PDO($dsn, $user, $pass); 

	if( isset($_POST) && isset($_FILES['db_file']) ){

		$sql_content = $_FILES['db_file']['tmp_name'];

		if( MysqlImporter($db_instance, $sql_content) ){
			echo "import";
		};
	}
	elseif ( array_key_exists('export', $_GET) ) {
		
		MysqlExporter($db_instance);
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title> Mysql Export and Import Database and Table </title>
</head>
<body>
	<h1> Mysql Export and Import Database and Table  </h1>
	<h2> Using Helpers with Mysql PDO Instance</h2>

	<form enctype="multipart/form-data" method="POST">
		<input type="file" name="db_file" />
		<button type="submit" name="import">Go</button>
	</form>

	<form method="GET" >
	<button name="export"> Export </button>
    </form>

</body>
</html>
