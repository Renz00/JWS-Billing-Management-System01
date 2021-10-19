<?php

class app_login{
    
    function login(){
        
        require "app_conn.php";

	    $conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name2);

		$username = $_GET["username"];
		$userpass = $_GET["password"];
		
		$mysql_qry = "SELECT * FROM users WHERE username = '$username' AND password = '$userpass' AND user_type='Plumber'";
		$result = mysqli_query($conn, $mysql_qry);
		$value = mysqli_fetch_assoc($result);

		if (mysqli_num_rows($result) > 0){

			$date_time = date('Y-m-d H:i');

			$mysql_qry2 = "INSERT INTO system_logs values (null, '$date_time', 'PLUMAPP', 'ALERT', '".$value['username']." has Logged In.')";

			mysqli_query($conn, $mysql_qry2);

			$mysql_qry3 = "UPDATE users set status='Online' WHERE username='$username'";

			mysqli_query($conn, $mysql_qry3);

			echo $value['username'];

		}
		else {
			echo "invalid";
		}

	mysqli_close($conn);
        
    }
    
	
}

$obj = new app_login();
$obj->login();

?>