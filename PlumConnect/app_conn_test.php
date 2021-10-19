<?php
	$db_name = "jwsbmsin_db";
	$mysql_username = "jwsbmsin_admin";
	$mysql_password = "P]YYYdKdS7Pq";
	$server_name = "172.93.111.58";
	$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

	if ($conn){
		echo "connected";
	}
	else {
		echo "failed";
	}
?>