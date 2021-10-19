<?php

class app_get_bills{
    
    function getBills(){
        
        require "app_conn.php";

		$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name2);

		$username = $_GET['username'];

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

	    echo json_encode($response);

		//echo $consumer_id;

	    mysqli_close($conn);
        
    }
}

$obj = new app_get_bills();
$obj->getBills();
	    
?>