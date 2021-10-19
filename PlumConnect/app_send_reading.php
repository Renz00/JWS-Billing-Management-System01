<?php

class app_send_reading{
    
    function sendReading(){
        
        require "app_conn.php";

    	$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
    
    	$id = $_GET['id'];
    	$user = $_GET['user'];
    
    	$mysql_qry = "SELECT * FROM consumers WHERE id='$id'";
    
    	$result = mysqli_query($conn, $mysql_qry);
    
    	$row=mysqli_fetch_assoc($result);
    
    	$barangay = $row['address'];
    	
    	$consumer_status = $row['status'];
    
    	$mysql_qry2 = "UPDATE consumers set reading_status='SENT' WHERE id='$id'";
    	mysqli_query($conn, $mysql_qry2);
    
    
    	$check_qry1="SELECT * FROM consumers WHERE address='$barangay'";
    
    	$check_result1= mysqli_query($conn,$check_qry1);
    
    	$count1=mysqli_num_rows($check_result1);
    
    
    	$check_qry2="SELECT * FROM consumers WHERE address='$barangay' AND reading_status='SENT'";
    
    	$check_result2= mysqli_query($conn,$check_qry2);
    
    	$count2=mysqli_num_rows($check_result2);
    
    	if ($count1==$count2){
    
    		$conn2 = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name2);
    
    		$date_finished = date('Y-m-d H:i');
    
    		$update_qry="UPDATE reading_status set reading='1', date_finished='$date_finished' WHERE barangay='$barangay'";
    
    		mysqli_query($conn2,$update_qry);
    
    
    		$conn = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name);
    
    		$delete_qry="DELETE from consumers WHERE address='$barangay'";
    
    		mysqli_query($conn,$delete_qry);
    	}
    	
    
    	if (mysqli_num_rows($result) > 0){
    
    		$curr_reading=$row['curr_reading'];
    
    		$curr_date = $row['curr_date'];
    
    		$prev_reading=$row['prev_reading'];
    
    		$prev_date = $row['prev_date'];
    
    		$account_no = $id;
    
    		$account_no = round($account_no);
    
    
    		$conn2 = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name2);
    
    		$query2= "SELECT * FROM consumers WHERE account_no='$account_no'";
    
    		$query2_result = mysqli_query($conn2,$query2);
    
    		$result_row = mysqli_fetch_assoc($query2_result);
    
    
    		$meter_id = $result_row['meter_id']; // gets the value of meter_id collumn
    
    		$rate_id = $result_row['rate_id'];
    
    		$barangay = $result_row['address'];
    
    		$consumer_status = $result_row['status'];
    
    
    		$insert_query = "INSERT into readings values (null, '$account_no', '$meter_id', '$curr_reading', '$curr_date')";// inserts new water reading to database
    
    		$insert_result = mysqli_query($conn2,$insert_query);
    		
    		    
    		$water_usage = $curr_reading - $prev_reading;
    
    
    		$rate_query = "SELECT * FROM rates WHERE connection_type='$rate_id'"; // gets the water rate used for computation
    
    		$rate_result = mysqli_query($conn2,$rate_query);
    
    		$rate_row = mysqli_fetch_assoc($rate_result);
    
    
    		if ($water_usage != 0 && $water_usage <= 10){ // computations for water bill
    					
    			$rate = $rate_row['first_10_CU']; //gets the value of first_10_CU collumn
    
    			$water_fee = $rate ;
    
    		}
    
    		else if ($water_usage > 10 && $water_usage <= 20){
    
    			$rate = $rate_row['11_CU'];
    
    			$water_fee = $rate * $water_usage;
    
    					
    		}
    
    		else if ($water_usage > 20 && $water_usage <= 30){
    
    			$rate = $rate_row['21_CU'];
    
    			$water_fee = $rate * $water_usage;
    					
    		}
    
    		else if ($water_usage > 31){
    
    			$rate = $rate_row['31_CU'];
    
    			$water_fee = $rate * $water_usage;
    					
    		}
    
    
    		$conn2 = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name2);
    
    		$loan_query = "SELECT * FROM loan WHERE consumer_id='$account_no'";
    
    		$loan_result = mysqli_query($conn2,$loan_query);
    
    			$mlp = 0;
    			$interest = 0;
    			$meter_fee = 0;
    
    			if(mysqli_num_rows($loan_result)!=0){
    
    				foreach ($loan_result as $row){
    
    					if ($row['loan_type'] == 'Material Loan'){
    
    						$mlp = $row['monthly_payment'];
    						$interest = $row['interest'];
    
    					}
    					else if ($row['loan_type'] == 'Meter Fee'){
    
    						$meter_fee = $row['monthly_payment'];
    
    					}
    
    				}
    		}
    		
    		if ($prev_reading <= 0 || $curr_reading <= 0){
    		    
    		    $water_fee = 0;
    		    $meter_fee = 0;
    		    $mlp = 0;
    		    $interest = 0;
    		    $water_usage=0;
    		    $rate=0;
    		}
    					
    		$total_amount = round($water_fee + 0 + $meter_fee + 0 + $mlp + $interest + 0 + 0, 2); // computes the total amount to be paid
    
    		$senior = $result_row['senior_citizen'];
    
    			if ($senior == 1){
    
    				$discount = 20;
    				$twenty = $total_amount * ($discount / 100);
    
    				$total_amount = $total_amount - $twenty;
    			} 
    			else 
    				$discount = 0;
    
    			$curr_reading_qry = "SELECT * FROM readings WHERE consumer_id='$account_no' and meter_reading='$curr_reading' and reading_date='$curr_date'";
    
    			$curr_reading_result = mysqli_query($conn2,$curr_reading_qry);
    
    			$curr_reading_row = mysqli_fetch_assoc($curr_reading_result);
    
    			$reading_id = $curr_reading_row['reading_id'];
    
    
    			$sched_query =  "SELECT * FROM billing_schedule WHERE barangay = '$barangay'";
    
    			$sched_result = mysqli_query($conn2,$sched_query);
    
    			$sched_row = mysqli_fetch_assoc($sched_result);
    
    
    			$grace_period = $sched_row['grace_period'];
    
    			$disconnection = $sched_row['disconnection'];
    
    			$date_created = date('Y-m-d');
    
    			$curr = strtotime($date_created);
    
    			$due_date= date("Y-m", strtotime("+1 month", $curr))."-".$grace_period;
    
    			$disco_date= date("Y-m", strtotime("+1 month", $curr))."-".$disconnection;
    			
    			if ($consumer_status == "For Disconnection"){
    			    $bill_status = "For Disconnection";
    			}
    			else if ($water_usage==0 || $water_fee==0 && $consumer_status=="Disconnected"){
			        $due_date="0000-00-00";
			        $disco_date="0000-00-00";
			    }
    			else{
    			    $bill_status = "Billed";
    			}
    
    			$insert_query2 = "INSERT into bills values (null, '$account_no', '$reading_id', '$bill_status', 'PLUMAPP', '$prev_reading', '$curr_reading', '$rate_id', '$rate', '$water_usage', '$water_fee', '0', '$meter_fee', '0', '$mlp', '$interest', '0', '0', '$total_amount', '$date_created', '$due_date', '$disco_date', '0000-00-00', '$discount')"; // adds bill to database
                echo "success";
    			mysqli_query($conn2,$insert_query2);
    
    			$log_desc = "Meter Reading ".$curr_reading." with Reading Date ".$curr_date." was sent by USER: ".$user.". Water Bill has been Created for Consumer: ".$account_no;
    
    			if ($consumer_status=="For Disconnection"){
    
    				$update_query2 = "UPDATE consumers set status='Disconnected' WHERE account_no='$account_no'";
    
    				mysqli_query($conn2,$update_query2);
    
    				$log_desc = "Meter Reading ".$curr_reading." with Reading Date ".$curr_date." was sent by USER: ".$user.". Water Bill has been Created for Consumer: ".$account_no.". Consumer: ".$account_no." has been Disconnected.";
    
    			}
    
    			$date_time = date('Y-m-d H:i');
    
    			$insert_query3 = "INSERT INTO system_logs values (null, '$date_time', 'PLUMAPP','$log_desc')";
    		    
    		
    
    	}
    
    	
    
    	mysqli_close($conn);
    	mysqli_close($conn2);
    }
}

$obj = new app_send_reading();
$obj->sendReading();
	

?>