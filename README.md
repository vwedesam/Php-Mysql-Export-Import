# Export MySQL database and Import it from a dump file in PHP

[![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)]

MySQL is a popular Linux-based database program. As a database, MySQL is a versatile application. It can be used for something as simple as a product database, or as complex as a Wordpress website..

This package will help you Export a MySQL database and Import it from a dump file in PHP.

# Features!

  - Export MySQL database and Tables to a file (.sql)
  - Import it from a file
 

> See [SQLITE Export And Import LIbraray ](https://github.com/vwedesam/Sqlite-Export-Import)  for SQLITE DB EXport and Import
 
### Installation

This Library requires [PHP](https://php.net/) to run.

```php
$ composer require vwedesam/mysql-export-import
```
### Example:1
> using helper function __mysqlExporter"__ and __MysqlImporter__ with connection "params"
```php
require "../vendor/autoload.php";

	$host = "localhost";
	$user = "root";
	$pass = "";
	$name = "my_mysql_db";
	
	// Connection Parameters
	$connection_params = [
						'host' => $host, 
						'name' => $name, 
						'user' => $user, 
						'pass' => $pass
                    ];

        // parameters
        // 1: Mysql database Instance <instance>
        // 2. mysql tables to be export <Array>
        // 3. backup name <String>
        // 4. connection parameters <Array>
        MysqlExporter(null, ['products'], false, $connection_params);


        // parameters
        // 1: Mysql database Instance <instance>
        // 2. full path eg "../filepath/db.sql" to mysql dump file (.sql, .zip)
        // 3. connectoin params <Array>
        MysqlImporter(null, $sql_content, $connection_params);
        // return: true / false
		
```````
### Example:2 
> using helper function __mysqlExporter__ and __MysqlImporter__ with "Mysql Instance"
```php
require "../vendor/autoload.php";

	$host = "localhost";
	$user = "root";
	$pass = "";
	$name = "my_mysql_db";
	
	$dsn = "mysql:dbname=$name;host=$host";

        // PDO instance
        $db_instance = new \PDO($dsn, $user, $pass);

        MysqlExporter($db_instance);

        MysqlImporter($db_instance, $sql_content);
        // return: true / false
    
		
```````

### More Examples
[Mysql Export Import with classes, functions and connection params](https://github.com/vwedesam/Mysql-Export-Import/blob/main/examples)

License
----
MIT
