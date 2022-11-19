<?php

$dbhost = "mysql-server";
$dbname = "dbs";
$username = "webuser";
$password = "webuser";

$mysqli = new mysqli(hostname: $dbhost, username: $username, password: $password, database: $dbname);

// check error
if($mysqli->connect_errno)
{
	die("failed to connect database!");
}
return $mysqli
?>
