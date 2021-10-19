<?php

class app_login{
    
    function login(){
        require "app_conn.php";
    
    	$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name2);

		$username = $_GET["username"];
		$userpass = $_GET["password"];

		$mysql_qry = "SELECT * FROM users WHERE username = '$username' AND password = '$userpass' AND user_type='Consumer'";

		$result = mysqli_query($conn, $mysql_qry);
		$value = mysqli_fetch_assoc($result);

		if (mysqli_num_rows($result) > 0){

			$date_time = date('Y-m-d H:i');

			$mysql_qry2 = "INSERT INTO system_logs values (null, '$date_time', 'CONAPP', 'ALERT', '".$value['username']." has Logged In.')";

			mysqli_query($conn, $mysql_qry2);

			$mysql_qry3 = "UPDATE users set status='Online' WHERE username='$username'";

			mysqli_query($conn, $mysql_qry3);


			$consumer_id = substr($username, 8);

			$mysql_qry = "SELECT * FROM consumers a LEFT JOIN bills b ON a.account_no=b.consumer_id WHERE a.account_no='$consumer_id'";

			$result = mysqli_query($conn, $mysql_qry);

			if (mysqli_num_rows($result) > 0){
				$response['success'] = $username;
				$consumers = array();
				while ($row = mysqli_fetch_assoc($result)) {
					
					array_push($consumers, $row);
				}
				$response['consumers'] = $consumers;		

			}
			else {
				$response['success'] = 0;
				$response['message'] = "No data";
			}

		}
		else {
			$response['success'] = 2;
			$response['message'] = "invalid";
		}

    	echo json_encode($response);
    
    	mysqli_close($conn);    
    }
}

$obj = new app_login();
$obj->login();

?>