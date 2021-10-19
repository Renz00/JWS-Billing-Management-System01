<?php
header('Content-Type: application/json; charset=utf-8');
class app_search_consumer{
    
    function searchConsumer(){
        
        require "app_conn.php";

    	$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

		$response = array();

		$keyword = $_GET['keyword'];
		$keyword = str_replace("-", " ", $keyword);

		$barangay = $_GET['barangay'];
		$barangay = str_replace("-", " ", $barangay);

		if ($keyword == ""){
			$keyword = "<>";
		}

		$mysql_qry = "SELECT * FROM consumers WHERE address='$barangay' AND (firstname='$keyword' OR middlename='$keyword' OR lastname='$keyword' OR id = '$keyword' OR meter_id = '$keyword' OR reading_status='$keyword')";

		$result = mysqli_query($conn, $mysql_qry);

		if (mysqli_num_rows($result) > 0){
			$response['success'] = 1;
			$consumers = array();
			while ($row = mysqli_fetch_assoc($result)) {
				
				$consumers[] = array_map('utf8_encode', $row);
			}
			$response['consumers'] = $consumers;

			

		}
		else {
			$response['success'] = 0;
			$response['message'] = "No data.";

		}

		echo json_encode($response);

		mysqli_close($conn);
		mysqli_close($conn2);
    }
}
	
$obj = new app_search_consumer();
$obj->searchConsumer();
	    

?>