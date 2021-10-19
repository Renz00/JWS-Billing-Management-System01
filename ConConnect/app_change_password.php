<?php

//9E8c0es8M6fkHBr2

class app_change_password{
    
    function changePassword(){
        
        require "app_conn.php";
    	$conn2 = mysqli_connect($server_name, $mysql_username, $mysql_password, $db_name2);
    
    	$username = $_GET['username'];
    	$password = $_GET['password'];
    	$new_pass = $_GET['new_pass'];
    
    	$check_qry = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    
    	$result = mysqli_query($conn2, $check_qry);
    
    	$row=mysqli_fetch_assoc($result);
    
    
    	if ($row['password'] == $password){
    		
    		$update_qry="UPDATE users set password='$new_pass' WHERE username='$username' AND password='$password'";
    
    		mysqli_query($conn2, $update_qry);
    
    		echo "success";
    
    	}
    	else {
    		echo "failed";
    	}
    
    	mysqli_close($conn2); 
    }
}
	
$obj = new app_change_password();
$obj->changePassword();

?>