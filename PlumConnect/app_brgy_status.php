<?php

class app_brgy_status{
    
    function brgyStatus(){
        
        require "app_conn.php";

		$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name2);

		$response = array();

		$mysql_qry = "SELECT * FROM reading_status";

		$result = mysqli_query($conn, $mysql_qry);

		if (mysqli_num_rows($result) > 0){

			$response['success'] = 1;
			$consumers = array();
			while ($row = mysqli_fetch_assoc($result)) {
				
				array_push($consumers, $row);
			}
			$response['consumers'] = $consumers;

			echo json_encode($response);

		}
		else {
			$response['success'] = 0;
			$response['message'] = "No data";

			echo "failed";
		}

		mysqli_close($conn);
        
    }
    
}

$obj = new app_brgy_status();
$obj->brgyStatus();
	    
?>