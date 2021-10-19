<?php

class app_logout{
    
    function logout(){
        require "app_conn.php";

	    $conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name2);

		$username = $_GET["username"];

		$date_time = date('Y-m-d H:i');

		$mysql_qry2 = "INSERT INTO system_logs values (null, '$date_time', 'System', 'ALERT', '".$username." has Logged Out.')";

		mysqli_query($conn, $mysql_qry2);

		$mysql_qry3 = "UPDATE users set status='Offline' WHERE username='$username'";

		mysqli_query($conn, $mysql_qry3);

    	echo "successsuccess";
    
    	mysqli_close($conn);
    }
}

$obj = new app_logout();
$obj->logout();

?>