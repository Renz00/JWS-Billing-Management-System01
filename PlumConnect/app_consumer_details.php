<?php

class app_consumer_details{
    
    function consumerDetails(){
        require "app_conn.php";

		$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

		$response = array();

		$consumer_id = $_GET['id'];

		$mysql_qry = "SELECT * FROM consumers WHERE id='$consumer_id'";

		$result = mysqli_query($conn, $mysql_qry);

		if (mysqli_num_rows($result) > 0){
			$response['success'] = 1;
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

		echo json_encode($response);

		mysqli_close($conn);
    }
}

$obj = new app_consumer_details();
$obj->consumerDetails();
	    
?>