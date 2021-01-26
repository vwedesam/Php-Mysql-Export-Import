<?php 

require "../vendor/autoload.php";

	$host = "localhost";
	$user = "root";
	$pass = "";
	$name = "empty";

	// Connection Parameters
	// value key pair array
	$connection_params = [
							'host' => $host, 
							'name' => $name, 
							'user' => $user, 
							'pass' => $pass
						 ];

	if( isset($_POST) && isset($_FILES['db_file']) ){

		$sql_content = $_FILES['db_file']['tmp_name'];

		if( MysqlImporter(null, $sql_content, $connection_params) ){
			echo "import";
		};
	}
	elseif ( array_key_exists('export', $_GET) ) {
		
		MysqlExporter(null, false, false, $connection_params);
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title> Mysql Export and Import Database and Table </title>
</head>
<body>
	<h1> Mysql Export and Import Database and Table  </h1>
	<h2> Using Helpers with Connection Parameters  </h2>

	<pre> [ 'host' => host_name, 'name' => database_name, 'user' => user_name, 'pass' => password ] </pre>

	<form enctype="multipart/form-data" method="POST">
		<input type="file" name="db_file" />
		<button type="submit" name="import">Go</button>
	</form>

	<form method="GET" >
	<button name="export"> Export </button>
    </form>

</body>
</html>
