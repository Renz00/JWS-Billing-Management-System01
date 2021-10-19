<?php
header('Content-Type: application/json; charset=utf-8');

class app_show_consumers{
    
    function showConsumers(){
        require "app_conn.php";

		$conn2 = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name2);
		

		$response = array();

		$barangay = $_GET['barangay'];
		
		$barangay = str_replace("-", " ", $barangay);
        
		$check_qry2 = "SELECT * FROM reading_status WHERE barangay='$barangay'";
		$check_result2 = mysqli_query($conn2, $check_qry2);
		$check_assoc = mysqli_fetch_assoc($check_result2); 
		
		
		if ($check_assoc['reading']==0){

			$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

			$check_qry = "SELECT * FROM consumers WHERE address='$barangay'";

			$check_result = mysqli_query($conn, $check_qry);

			if (mysqli_num_rows($check_result) == 0){ // if number of result is zero, retrieve data from jws_db to plumapp_db

				$conn2 = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name2);

				$get_qry = "SELECT * FROM consumers WHERE address='$barangay' AND (status='Active' OR status='For Disconnection' OR status='Disconnected')";

				$get_result = mysqli_query($conn2, $get_qry);

					foreach($get_result as $row2){

						$curr_date=strtotime(date('Y-m-d'));

						$prev_m = date("m", strtotime("-1 month", $curr_date)); 
		       			$prev_y = date("Y", strtotime("-1 month", $curr_date)); 

						$reading_qry = "SELECT * FROM readings WHERE consumer_id='$row2[account_no]' AND year(reading_date)='$prev_y' AND month(reading_date)='$prev_m'";

						$reading_result = mysqli_query($conn2, $reading_qry);

						$readings = mysqli_fetch_assoc($reading_result); // puts prev readings data into assoc array
			
						$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

						if ($row2['status']=="For Disconnection"){

							$insert_qry = "INSERT INTO consumers values('$row2[account_no]', '$row2[firstname]', '$row2[middlename]', '$row2[lastname]', '$row2[status]', '$row2[address]', '$row2[specific_address]', '$row2[meter_id]', '$readings[meter_reading]', '$readings[reading_date]', '0', '000-00-00', 'FOR DISCONNECTION')";
						}
						else if ($row2['status']=="Disconnected"){

							$insert_qry = "INSERT INTO consumers values('$row2[account_no]', '$row2[firstname]', '$row2[middlename]', '$row2[lastname]', '$row2[status]', '$row2[address]', '$row2[specific_address]', '$row2[meter_id]', '$readings[meter_reading]', '$readings[reading_date]', '0', '000-00-00', 'DISCONNECTED')";
						}
						else {
							$insert_qry = "INSERT INTO consumers values('$row2[account_no]', '$row2[firstname]', '$row2[middlename]', '$row2[lastname]', '$row2[status]', '$row2[address]', '$row2[specific_address]', '$row2[meter_id]', '$readings[meter_reading]', '$readings[reading_date]', '0', '000-00-00', 'PENDING')";
						}

						mysqli_query($conn, $insert_qry);

					}
				
			}

			$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
			mysql_set_charset('utf8', $conn);
			
			$mysql_qry = "SELECT * FROM consumers WHERE address='$barangay'";

			$result = mysqli_query($conn, $mysql_qry);

			if (mysqli_num_rows($result) > 0){
				$response['success'] = 1;
				$consumers = array();
				while ($row = mysqli_fetch_assoc($result)) {
				    
					$consumers[] = array_map('utf8_encode', $row);
		
				}
				$response['consumers'] = $consumers;
				
				//$response['consumers'] = mb_convert_encoding($response['consumers'], 'UTF-8', 'UTF-8');	

			}
			else {
				$response['success'] = 0;
				$response['message'] = "No data";

				
			}

		}
		else {
			$response['success'] = 2;
			$response['message'] = "Reading complete";
		}
		
		
		
	    echo json_encode($response);

		mysqli_close($conn);
		mysqli_close($conn2);
    }
}

$obj = new app_show_consumers();
$obj->showConsumers();


?>