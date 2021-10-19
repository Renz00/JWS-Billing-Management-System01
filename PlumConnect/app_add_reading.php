<?php

class app_add_reading{
    function addReading(){
        
        require "app_conn.php";

		$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);

		$consumer_id = $_GET['id'];
		$reading = $_GET['reading'];
		
		$date=date('Y-m-d');

		$mysql_qry = "UPDATE consumers set curr_reading='$reading', curr_date='$date', reading_status='DONE' WHERE id='$consumer_id'";

		mysqli_query($conn, $mysql_qry);

		echo "success";

		mysqli_close($conn);
        
    }
}

$obj = new app_add_reading();
$obj->addReading();
	    
?>