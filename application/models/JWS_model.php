<?php
	class JWS_model extends CI_Model{
	    
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//		    
	    public function getConsumerList(){ 

			$query = $this->db->query("SELECT * FROM consumers"); 
			
			$result=$query->result_array();

			return $result;
			
		}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//	MANAGE BILLS

        public function getPhoneNum($bill_id){ 

			$query = $this->db->query("SELECT * FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no LEFT JOIN readings c ON a.reading_id=c.reading_id WHERE a.bill_id='$bill_id'"); 
			
			$result=$query->result_array();

			return $result;
			
		}
		
		public function getPhoneNum2($bill_id){ 

			$query = $this->db->query("SELECT * FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no LEFT JOIN readings c ON a.reading_id=c.reading_id LEFT JOIN invoice d ON a.bill_id=d.bill_id WHERE a.bill_id='$bill_id'"); 
			
			$result=$query->result_array();

			return $result;
			
		}
		
		public function setMsgStatus($bill_id){ 
		    
		    $check_query = $this->db->query("SELECT * FROM message_status WHERE bill_id='$bill_id'"); 
		    
		    $result=$check_query->result_array();
		    
		    if (count($result)==0){
		        $query = $this->db->query("INSERT INTO message_status value (null,'$bill_id')"); 
		    }
		    
		}
		
		public function getMsgStatus($bill_id){ 
		    
		    $feedback="";

			$query = $this->db->query("SELECT * FROM message_status WHERE bill_id='$bill_id'"); 
			
			$result=$query->result_array();
			
			if (count($result)>0){
			    $feedback="true";
			}
			else {
			    $feedback="false";
			}
			
			return $feedback;
			
		}
		
		public function getBilledConsumers(){ // gets consumers with bill

			$query = $this->db->query("SELECT * FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no LEFT JOIN readings c ON a.reading_id=c.reading_id"); // tables bills, consumers, and readings are joined to access all of the data in each table

			$result=$query->result_array();

			return $result;
			
		}

		public function computeBill($water_usage, $rate_id){ 

			$rate_query=$this->db->query("SELECT * FROM rates WHERE connection_type='$rate_id'"); // gets the water rate used for computation

				if ($water_usage != 0 && $water_usage <= 10){ // computations for water bill
					
					$rate = $rate_query->row('first_10_CU'); //gets the value of first_10_CU collumn

					$water_fee = $rate ;

				}

				else if ($water_usage > 10 && $water_usage <= 20){

					$rate = $rate_query->row('11_CU');

					$water_fee = $rate * $water_usage;

					
				}

				else if ($water_usage > 20 && $water_usage <= 30){

					$rate = $rate_query->row('21_CU');

					$water_fee = $rate * $water_usage;
					
				}

				else if ($water_usage > 31){

					$rate = $rate_query->row('31_CU');

					$water_fee = $rate * $water_usage;
					
				}
				else {
				    $water_fee=0;
				    $water_usage=0;
				    $rate = 0;
				}


			return array(
				'water_fee' => $water_fee,
				'rate_amt' => $rate 
			);
			
		}

		public function addConsumerBill($bill_details, $reading_date, $curr_reading, $account_no){// adding the water bill of consumer

				$query2= $this->db->query("SELECT * FROM consumers WHERE account_no='$account_no'");

				$meter_id = $query2->row('meter_id'); // gets the value of meter_id collumn

				$rate_id = $query2->row('rate_id');

				$barangay = $query2->row('address');

				$consumer_status = $query2->row('status');

				$this->db->query("INSERT into readings values (null, '$account_no', '$meter_id', '$curr_reading', '$reading_date')");// inserts new water reading to database

				$curr = strtotime($reading_date); // current date of reading
		        $prev_m = date("m", strtotime("-1 month", $curr)); // gets the year and month of 
		        $prev_y = date("Y", strtotime("-1 month", $curr)); // previous reading 

		        $prev_read_query=$this->db->query("SELECT * FROM readings WHERE year(reading_date)='$prev_y' AND month(reading_date)='$prev_m' AND consumer_id='$account_no' AND meter_id = '$meter_id'"); // gets the previous reading from reading table based of the prev year and prev month

					$prev_reading = $prev_read_query->row('meter_reading');

					$reading_id = $prev_read_query->row('reading_id');

					$water_usage = $curr_reading - $prev_reading;

					$compute_array = $this->computeBill($water_usage, $rate_id);

					$loan_array = $this->retrieveLoan($account_no);
					
					if ($curr_reading==0 || $prev_reading==0){
					    $compute_array['water_fee'] = 0;
					    $loan_array['meter_fee'] = 0;
					    $loan_array['mlp'] = 0;
					    $loan_array['interest'] = 0;
					}
					
			$total_amount = $compute_array['water_fee'] + $bill_details['installation_fees'] + $loan_array['meter_fee'] + $bill_details['penalty'] + $loan_array['mlp'] + $loan_array['interest'] + $bill_details['reconnection_fee'] + $bill_details['others']; // computes the total amount to be paid

			$senior = $query2->row('senior_citizen');

				if ($senior == 1){

					$discount = 20;

					$twenty = $total_amount * ($discount / 100);

					$total_amount = $total_amount - $twenty;
				} 
				else 
					$discount = 0;

			$curr_m = date("m", strtotime('-0 months', $curr)); 
		    $curr_y = date("Y", strtotime('-0 months', $curr)); 

			$curr_reading_id= $this->db->query("SELECT * FROM readings WHERE consumer_id='$account_no' and meter_reading='$curr_reading' and year(reading_date)='$curr_y' AND month(reading_date)='$curr_m'");

			$reading_id = $curr_reading_id->row('reading_id');

			$sched_query =  $this->db->query("SELECT * FROM billing_schedule WHERE barangay = '$barangay'");

			$grace_period = $sched_query->row('grace_period');

			$disconnection = $sched_query->row('disconnection');

			$due_date= date("Y-m", strtotime("+1 month", $curr))."-".$grace_period;

			$disco_date= date("Y-m", strtotime("+1 month", $curr))."-".$disconnection;
			
// 			if ($consumer_status == "For Disconnection"){
// 			    $bill_details['bill_status']="For Disconnection";
// 			}
			if ($water_usage==0 || $compute_array['water_fee']==0 && $consumer_status=="Disconnected"){
			    $due_date="0000-00-00";
			    $disco_date="0000-00-00";
			}

			$this->db->query("INSERT into bills values (null, '$account_no', '$reading_id', '$bill_details[bill_status]', '$bill_details[bill_creator]', '$prev_reading', '$curr_reading', '$rate_id', '$compute_array[rate_amt]', '$water_usage', '$compute_array[water_fee]', '$bill_details[installation_fees]', '$loan_array[meter_fee]', '$bill_details[penalty]', '$loan_array[mlp]', '$loan_array[interest]', '$bill_details[reconnection_fee]', '$bill_details[others]', '$total_amount', '$bill_details[date_created]', '$due_date', '$disco_date', '$bill_details[date_paid]', '$discount')"); // adds bill to database

			$bill_id_query = $this->db->query("SELECT * from bills WHERE consumer_id='$account_no' AND reading_id='$reading_id' AND bill_status='$bill_details[bill_status]' AND bill_creator='$bill_details[bill_creator]' AND prev_reading='$prev_reading' AND curr_reading='$curr_reading' AND rate='$rate_id' AND total_amount='$total_amount' AND date_created='$bill_details[date_created]'"); 

			if ($consumer_status=="For Disconnection"){

				$this->db->query("UPDATE consumers set status='Disconnected' WHERE account_no='$account_no'");

			}

			$bill_id = $bill_id_query->row('bill_id');

			return $bill_id;

		}

		public function saveBillDetails($bill_id, $bill_details){

			$bill_query=$this->db->query("SELECT * FROM bills WHERE bill_id = '$bill_id'");

			$consumer_id = $bill_query->row('consumer_id');
			$reading_id = $bill_query->row('reading_id');

			$get_rate_query = $this->db->query("SELECT * FROM consumers WHERE account_no = '$consumer_id'");

			$rate_id = $get_rate_query->row('rate_id');

			$prev_bill = $bill_details['prev_reading'];
			$curr_bill = $bill_details['curr_reading'];
			
			$this->db->query("UPDATE readings set meter_reading='$curr_bill' WHERE reading_id = '$reading_id'");

			$water_usage = $curr_bill - $prev_bill;

			$compute_array = $this->computeBill($water_usage, $rate_id);
			
			if ($bill_details['curr_reading']==0 || $bill_details['prev_reading']==0){
					    $compute_array['water_fee'] = 0;
					    $water_usage=0;
			}
			
			$full_inst = $bill_details['install_fees'];
			
			if ($bill_details['install_fees']!=0){
			    
			     $in = $bill_details['install_fees'];
			    
			    $discount = $bill_details['discount'];
			    
				$discount_amt = round($in * ($discount / 100), 2);

				$in = round($in - $discount_amt, 2);
				
				$bill_details['install_fees'] = $in;
				
			}

			$total_amount = round($compute_array['water_fee'] + $bill_details['install_fees'] + $bill_details['meter_fee'] + $bill_details['penalty'] + $bill_details['mlp'] + $bill_details['interest'] + $bill_details['reconnection_fee'] + $bill_details['others'], 2); // computes the total amount to be paid
			
			$bill_details['install_fees'] = $full_inst;

			if ($bill_details['discount'] != 0 && $bill_details['install_fees']==0){

				$discount = $bill_details['discount'];

				$discount_amt = $total_amount * ($discount / 100);

				$total_amount = $total_amount - $discount_amt;
			} 
			else 
				$discount = $bill_details['discount'];

			$this->db->query("UPDATE bills set curr_reading='$bill_details[curr_reading]', prev_reading='$bill_details[prev_reading]', rate_amt='$compute_array[rate_amt]', water_usage='$water_usage', water_fee='$compute_array[water_fee]', installation_fees='$bill_details[install_fees]', meter_fee='$bill_details[meter_fee]', penalty='$bill_details[penalty]', mlp='$bill_details[mlp]', interest='$bill_details[interest]', reconnection_fee='$bill_details[reconnection_fee]', others='$bill_details[others]', total_amount='$total_amount', discount='$discount', bill_status='$bill_details[bill_status]', bill_creator='$bill_details[prepared_by]', date_created='$bill_details[date_created]', due_date='$bill_details[due_date]', date_paid='$bill_details[date_paid]' WHERE bill_id = '$bill_id'");

		}

		public function deleteBillDetails($bill_id){

			$bill_query=$this->db->query("SELECT * FROM bills WHERE bill_id = '$bill_id'");

			$reading_id = $bill_query->row('reading_id');

			$this->db->query("DELETE FROM readings WHERE reading_id = '$reading_id'");

			$this->db->query("DELETE FROM invoice WHERE bill_id = '$bill_id'");

			$this->db->query("DELETE FROM bills WHERE bill_id = '$bill_id'");
		}


		public function getBillDetails($bill_id){// gets the bill details from database

			$bill_query=$this->db->query("SELECT * FROM bills WHERE bill_id = '$bill_id'");

			$consumer_id = $bill_query->row('consumer_id');

			$reading_id = $bill_query->row('reading_id');

			$due_date = $bill_query->row('due_date');

			$disco_date = $bill_query->row('disconnection_date');

			$curr_penalty = $bill_query->row('penalty');

			$water_fee = $bill_query->row('water_fee');

			$bill_status = $bill_query->row('bill_status');

			$curr_date = date('Y-m-d');

			if ($curr_date > $due_date && $curr_penalty == 0 && $bill_status != "Paid"){

				$total = $bill_query->row('total_amount');

				$penalty = $water_fee * 0.10;

				$total = $total + $penalty;

				$this->db->query("UPDATE bills set penalty = '$penalty', total_amount = '$total' WHERE bill_id = '$bill_id'");
			}

			else if ($curr_date > $disco_date && $bill_status != "Paid"){

				$this->db->query("UPDATE bills set bill_status = 'For Disconnection' WHERE bill_id = '$bill_id'");

				$this->db->query("UPDATE consumers set status = 'For Disconnection' WHERE account_no = '$consumer_id'");
			}
			

			$query = $this->db->query("SELECT * FROM readings a LEFT JOIN bills b ON a.reading_id=b.reading_id LEFT JOIN consumers c ON b.consumer_id=c.account_no LEFT JOIN rates d ON c.rate_id=d.connection_type WHERE a.reading_id='$reading_id' and b.bill_id='$bill_id'"); // readings, bills, and consumers table are joined

			$rate=$query->row('rate_id');

			$result=$query->result_array();

			return $result;
			
		}


//	MANAGE CONSUMERS

		public function computeMeterLoan($meter_fee){

			$monthly = round($meter_fee / 3, 2);

			$start_date = date('Y-m-d');

			$string_date = strtotime($start_date);

			$end_date = date("Y-m-d", strtotime("+2 month", $string_date));

			return array( 
			    'monthly' => $monthly,
			    'interest' => 0,
			    'start_date' => $start_date,
			    'end_date' => $end_date
			);
		
		}

		public function computeMaterialLoan($loan_amount){

			$start_date = date('Y-m-d');

			$string_date = strtotime($start_date);

            $end_date = date("Y-m-d", strtotime("+11 month", $string_date));

            $P = $loan_amount;
            $r = 0.01;
            $n = 12;

            $A = round($P * (($r * (pow(1 + $r, $n))) / ((pow(1 + $r, $n))- 1)), 2);

            $interest = round($P * 0.01, 2);

            $mlp = $A;

            return array( 
			    'monthly' => $mlp,
			    'interest' => $interest,
			    'start_date' => $start_date,
			    'end_date' => $end_date
			);  
		}


		public function addRecord($inspection_details, $consumer_details, $meter_details, $reading_details, $bill_details, $loan_details){ // adds consumer to database

				$meter_query = $this->db->query("SELECT * from water_meters where meter_serial_no = '$meter_details[meter_serial_no]'");

				$result=$meter_query->result_array();
			
				if(count($result)==0){

					$this->db->insert("consumers",$consumer_details); // inserts consumer details to table

					$this->db->insert("water_meters",$meter_details);

					$query = $this->db->query("SELECT * from consumers where meter_id = '$consumer_details[meter_id]' and firstname='$consumer_details[firstname]' and middlename='$consumer_details[middlename]' and lastname='$consumer_details[lastname]' and birth_date='$consumer_details[birth_date]' and  phonenumber='$consumer_details[phonenumber]' and date_installed='$consumer_details[date_installed]'");

						$consumer_id=$query->row('account_no'); 

						$this->db->query("INSERT INTO readings VALUES (null,'$consumer_id', '$consumer_details[meter_id]', '$reading_details[meter_reading]', '$reading_details[reading_date]')");

						$query2 = $this->db->query("SELECT * from readings where consumer_id = '$consumer_id' and meter_id = '$consumer_details[meter_id]' and meter_reading='$reading_details[meter_reading]' and reading_date='$reading_details[reading_date]'");

						$meter_fee = $bill_details['meter_fee'];

						$orig_amt = $bill_details['meter_fee'];

						$total_amount = $bill_details['total_amount'];

						if ($bill_details['discount']==0){ // checks if paymethod is installment

							$meter_array = $this->computeMeterLoan($meter_fee);

							$bill_details['total_amount'] = round($bill_details['total_amount'] + $meter_array['monthly'], 2);

							$bill_details['meter_fee'] = $meter_array['monthly'];

							$this->db->query("INSERT INTO loan VALUES (null,'$consumer_id', 'Meter Fee', 'Active', '$orig_amt', '$meter_array[monthly]', '$meter_array[interest]', 0, '$meter_array[start_date]', '$meter_array[end_date]', 'none')");
						}

						if ($bill_details['discount']!=0){ // checks if paymethod is cash

							$bill_details['total_amount'] = $total_amount + $meter_fee;
							$total_amount = $bill_details['total_amount'];
						}

						if ($loan_details['loan_amt']!=0){

							$material_array = $this->computeMaterialLoan($loan_details['loan_amt']);

							$bill_details['mlp'] = $material_array['monthly'];

				            $bill_details['interest'] = $material_array['interest'];

				            $bill_details['total_amount'] = round($bill_details['total_amount'] + $material_array['monthly'] + $material_array['interest'], 2);

							if ($loan_details['remarks'] == ""){
				            	$loan_details['remarks'] = "none";
				            }

							$this->db->query("INSERT INTO loan VALUES (null,'$consumer_id', '$loan_details[loan_type]', 'Active', '$loan_details[loan_amt]', '$material_array[monthly]', '$material_array[interest]', 0, '$material_array[start_date]', '$material_array[end_date]', '$loan_details[remarks]')");
						}

						$reading_id=$query2->row('reading_id'); 

						$this->db->query("INSERT INTO bills VALUES (null,'$consumer_id', '$reading_id', '$bill_details[bill_status]', '$bill_details[bill_creator]', 0, '$reading_details[meter_reading]', '$consumer_details[rate_id]', 0, '$bill_details[water_usage]', '$bill_details[water_fee]', '$bill_details[installation_fees]', '$bill_details[meter_fee]', '$bill_details[penalty]', '$bill_details[mlp]', '$bill_details[interest]', '$bill_details[reconnection_fee]', '$bill_details[others]', '$bill_details[total_amount]', '$bill_details[date_created]', '0000-00-00', '0000-00-00', '$bill_details[date_paid]', '$bill_details[discount]')");

						$this->db->query("INSERT INTO inspections VALUES (null,'$consumer_id', '$inspection_details[name]', '$inspection_details[inspection_date]')"); // inserts inspector name and inspection date to table
						
						$date_added = date('Y-m-d'); 

						$username = substr($consumer_details['date_installed'],0,4).substr($consumer_details['date_installed'],4,4).str_pad($consumer_id, 4, '0', STR_PAD_LEFT);

						$this->db->query("INSERT INTO users VALUES ('$username', '$username', 'Consumer', 'Offline', '$date_added')");

						return array( 
					    'result1' => "success",
					    'consumer_id' => $consumer_id);
				}

				else {

					return array( 
					    'result1' => "error",
					    'consumer_id' => "");
				}
				
		}

		public function getConsumerInfo($consumer_id){ // gets the data of consumers

			$query=$this->db->query("SELECT * FROM consumers a LEFT JOIN water_meters b on a.meter_id=b.meter_serial_no LEFT JOIN inspections c on a.account_no=c.consumer_id WHERE a.account_no='$consumer_id'");

			$result=$query->result_array();

			return $result;
			
		}


		public function getConsumerData(){ // gets all consumers from database
			
			$query=$this->db->query("SELECT * from consumers"); // query

			$result=$query->result_array();

			return $result; // passes the data to the controller
			
		}


		public function deleteConsumer($consumer_id){ // removes consumer from database

			$query=$this->db->query("SELECT * from consumers WHERE account_no='$consumer_id'");

			$result=$query->result_array();

			$meter_id=$query->row('meter_id');
			
			$install=$query->row('date_installed');
			
			$username = substr($install,0,4).substr($install,4,4).str_pad($consumer_id, 4, '0', STR_PAD_LEFT);

				$this->db->query("DELETE from readings WHERE consumer_id='$consumer_id'");
				$this->db->query("DELETE from bills WHERE consumer_id='$consumer_id'");
				$this->db->query("DELETE from loan WHERE consumer_id='$consumer_id'");
				$this->db->query("DELETE from water_meters WHERE meter_serial_no='$meter_id'");
				$this->db->query("DELETE from inspections WHERE consumer_id='$consumer_id'");
				$this->db->query("DELETE from invoice WHERE user_id='$meter_id'");
				$this->db->query("DELETE from users WHERE username='$username'");

			$this->db->query("DELETE from consumers WHERE account_no='$consumer_id'");// removes consumer
		}

		public function updateConsumerData($consumer_details, $inspection_details, $consumer_id){ // updates the data of consumer

			$this->db->query("UPDATE inspections SET name='$inspection_details[name]', inspection_date='$inspection_details[inspection_date]' WHERE consumer_id='$consumer_id'"); //updates inspections table

			$this->db->query("UPDATE consumers SET rate_id='$consumer_details[rate_id]', firstname='$consumer_details[firstname]', middlename='$consumer_details[middlename]', lastname='$consumer_details[lastname]', birth_date='$consumer_details[birth_date]', address='$consumer_details[address]', specific_address='$consumer_details[specific_address]', phonenumber='$consumer_details[phonenumber]', status='$consumer_details[status]', senior_citizen='$consumer_details[senior_citizen]' WHERE account_no='$consumer_id'"); // updates consumer table

		}

		public function getConsumerLedgerRecords($consumer_id){ // gets the data of consumer ledger records. these records are the payments made by the consumer.

			$query = $this->db->query("SELECT  * FROM bills a LEFT JOIN readings b ON a.consumer_id=b.consumer_id AND a.reading_id=b.reading_id LEFT JOIN invoice c ON a.bill_id=c.bill_id WHERE b.consumer_id='$consumer_id' AND a.bill_status='Paid'");

			$result=$query->result_array();

			return $result;

		}

		public function getConsumerLedgerData($consumer_id, $month1, $year1, $month2, $year2){ // data for printing

	        $query = $this->db->query("SELECT  * FROM bills a LEFT JOIN readings b ON a.consumer_id=b.consumer_id AND a.reading_id=b.reading_id LEFT JOIN invoice c ON a.bill_id=c.bill_id WHERE b.consumer_id='$consumer_id' AND year(c.or_date)>='$year1' AND month(c.or_date)>='$month1' AND year(c.or_date)<='$year2' AND month(c.or_date)<='$month2'");
			
			$result=$query->result_array();

			return $result;

		}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//	MANAGE USERS


		public function checkUser($usern,$passw){ // checks the user previlege and if user exists. 

			$query=$this->db->query("select * from users where username = '$usern' and password='$passw'");

			$result=$query->result_array();
			
			if(count($result)!=0){

				$this->db->query("UPDATE users set status = 'Online' where username = '$usern' and password='$passw'");

				$user_t=$query->row('user_type');
				
				return $user_t;
				
			}
			else 
				return "invalid";
		}

		public function getUsers(){

			$query=$this->db->query("select * from users");

			$result=$query->result_array();

			return $result;

		}

		public function saveUser($user_details){

			$query=$this->db->query("select * from users where username='$user_details[username]' AND password='$user_details[password]' AND status='Offline'");

			$result=$query->result_array();

			if(count($result)==0){

					$this->db->query("INSERT INTO users values ('$user_details[username]', '$user_details[password]', '$user_details[user_type]', '$user_details[status]', '$user_details[date_added]')");
			
					return "success";
		
			}
			else return "fail";
		}

		public function updateUserStatus($usern){
		    
		  
			$this->db->query("UPDATE users set status='Offline' where username='$usern'");
	
		}

		public function saveUserDetails($usern,$user_details){
		    
		    $feedback = "none";
		    
		    $query=$this->db->query("select * from users where username='$usern'");

			$result=$query->result_array();

			if(count($result)==1){

		    	$this->db->query("UPDATE users set username='$user_details[username]', password='$user_details[password]', user_type='$user_details[user_type]' , date_added = '$user_details[date_added]' where username='$usern'");
			}
			else {
			    $feedback = "error";
			}
			
			return $feedback;

		}

		public function deleteUserDetails($usern){
		    
		    $feedback = "none";
		    
		    $query=$this->db->query("select * from users where username='$usern' AND status='Offline'");

			$result=$query->result_array();
			
			if(count($result)==1){

			    $this->db->query("DELETE from users where username='$usern'");
			}
			else {
			    $feedback = "error";
			}
			
			return $feedback;

		}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//	MANAGE INVOICE


		public function deductLoan($consumer_id){

			$query = $this->db->query("SELECT * FROM loan WHERE consumer_id='$consumer_id'");

			$result=$query->result_array();

			$loan_status = $query->row('loan_status');

			if(count($result)!=0 && $loan_status=="Active"){

				foreach ($result as $row){

					if ($row['loan_type'] == "Material Loan"){

						$mlp = $row['monthly_payment'];

						$end_date = strtotime($row['end_date']);
						$end_m = date("m", strtotime('-0 months', $end_date)); 
	    				$end_y = date("Y", strtotime('-0 months', $end_date)); 

						$curr_date = strtotime(date('Y-m-d'));
						$curr_m = date("m", strtotime('-0 months', $curr_date)); 
	    				$curr_y = date("Y", strtotime('-0 months', $curr_date)); 

						$amt_paid = $row['amount_paid'];

						$new_amtpaid = $amt_paid + $mlp;

						if ($curr_m == $end_m && $curr_y == $end_y){

							$this->db->query("UPDATE loan set loan_status = 'Paid' WHERE consumer_id='$consumer_id' AND loan_type='Material Loan'");

						}

						$this->db->query("UPDATE loan set amount_paid = '$new_amtpaid' WHERE consumer_id='$consumer_id' AND loan_type='Material Loan'");

					}

					else if ($row['loan_type'] == "Meter Fee"){

						$meter_fee = $row['monthly_payment'];

						$end_date = strtotime($row['end_date']);
						$end_m = date("m", strtotime('-0 months', $end_date)); 
	    				$end_y = date("Y", strtotime('-0 months', $end_date)); 

						$curr_date = strtotime(date('Y-m-d'));
						$curr_m = date("m", strtotime('-0 months', $curr_date)); 
	    				$curr_y = date("Y", strtotime('-0 months', $curr_date)); 
						
						$amt_paid = $row['amount_paid'];

						$new_amtpaid = $amt_paid + $meter_fee;

						if ($curr_m == $end_m && $curr_y == $end_y){

							$this->db->query("UPDATE loan set loan_status = 'Paid' WHERE consumer_id='$consumer_id' AND loan_type='Meter Fee'");

						}

						$this->db->query("UPDATE loan set amount_paid = '$new_amtpaid' WHERE consumer_id='$consumer_id' AND loan_type='Meter Fee'");

					}

				}

			}

		}		

		public function addBillInvoice($bill_id, $or_no, $remarks){ // creates invoice

			$feedback = "none";

			$check_query = $this->db->query("SELECT * FROM invoice WHERE OR_no='$or_no'");

			$check_result=$check_query->result_array();

			if (count($check_result) == 0){

				$query=$this->db->query("SELECT * FROM bills WHERE bill_id='$bill_id'"); //gets the bill to used to create invoice

				$consumer_id = $query->row('consumer_id');

				$result=$query->result_array();

				$user_id = $this->session->userdata('username');
				$date = date('Y-m-d');
				$amount_due = $query->row('total_amount');

				$this->deductLoan($consumer_id);

				$this->db->query("INSERT INTO invoice values ('$or_no', '$bill_id', '$user_id', '$date', '$remarks')"); // inserts data to invoice table

				$this->db->query("UPDATE bills SET bill_status='Paid', date_paid='$date' WHERE bill_id='$bill_id'");

				$consumer_id=$query->row('consumer_id');

				$this->db->query("UPDATE consumers SET status='Active' WHERE account_no='$consumer_id'"); // sets consumer status to paid
			}
			else {
				$feedback = "or_error";
			}

			return $feedback;
		}

		public function getInvoiceDetails($bill_id){ // gets the details of invoice

			$query = $this->db->query("SELECT * FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no LEFT JOIN invoice c ON a.bill_id=c.bill_id WHERE c.bill_id='$bill_id'");

			$result=$query->result_array();

			return $result;

		}

		public function saveInvoice($bill_id, $invoice_details){ 
		    
		    $feedback = "none";

			$check_query = $this->db->query("SELECT * FROM invoice WHERE OR_no='$invoice_details[or_no]'");

			$check_result=$check_query->result_array();

			if (count($check_result) <= 1){

				$this->db->query("UPDATE invoice set OR_no = '$invoice_details[or_no]', user_id = '$invoice_details[prepared_by]', or_date = '$invoice_details[date]', remarks = '$invoice_details[remarks]' WHERE bill_id='$bill_id'");
			}
			else {
				$feedback = "or_error";
			}

			return $feedback;
			
		}

		public function deleteInvoiceDetails($or_no){ 

			$query = $this->db->query("SELECT * FROM invoice WHERE OR_no='$or_no'");

			$bill_id=$query->row('bill_id');		

			$this->db->query("DELETE from invoice WHERE OR_no='$or_no'");

			$this->db->query("UPDATE bills set bill_status = 'Billed' WHERE bill_id='$bill_id'");

			return $bill_id;

		}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//	MANAGE LOANS

		public function getLoan(){ 

			$query1 = $this->db->query("SELECT * FROM loan a LEFT JOIN consumers b ON a.consumer_id=b.account_no");
			
			$result1=$query1->result_array();

			$query2 = $this->db->query("SELECT * FROM consumers");
			
			$result2=$query2->result_array();

				return array( 
			    'result1' => $result1,
			    'result2' => $result2);

		}

		public function addConsumerLoan($account_no, $loan1, $loan2, $loan_amt1, $loan_amt2, $remarks){ 
		    
		    $feedback1="";
			$feedback2="";

			if ($loan1 != "none"){
			    
				$query = $this->db->query("SELECT * FROM loan WHERE consumer_id='$account_no' AND loan_type='$loan1' AND loan_status='Active'");
			
				$result=$query->result_array();

				if (count($result) == 0){

					$result_array1 = $this->computeMaterialLoan($loan_amt1);

					$this->db->query("INSERT INTO loan values (null, '$account_no', '$loan1', 'Active', '$loan_amt1', '$result_array1[monthly]', '$result_array1[interest]', 0, '$result_array1[start_date]', '$result_array1[end_date]', '$remarks')");

					$feedback1 = "success";

				}

				else $feedback1 = "materialLoan_error";

			}

			else if ($loan2 != "none"){
			    
				$query = $this->db->query("SELECT * FROM loan WHERE consumer_id='$account_no' AND loan_type='$loan2' AND loan_status='Active'");
			
				$result=$query->result_array();

				if (count($result) == 0){

					$result_array2 = $this->computeMeterLoan($loan_amt2);

					$this->db->query("INSERT INTO loan values (null, '$account_no', '$loan2', 'Active', '$loan_amt2','$result_array2[monthly]', '$result_array2[interest]', 0, '$result_array2[start_date]', '$result_array2[end_date]', 'none')");

					$feedback2 = "success";
					
				}

				else $feedback2 = "meterLoan_error";

			}

			return array( 
			    'feedback1' => $feedback1,
			    'feedback2' => $feedback2);

		}

		public function saveLoan($loan_id, $loan_details){ 

			if ($loan_details['loan_type'] == "Material Loan"){
				$result_array = $this->computeMaterialLoan($loan_details['loan_amt']);
			}
			else if ($loan_details['loan_type'] == "Meter Fee"){
				$result_array = $this->computeMeterLoan($loan_details['loan_amt']);
			}

			$this->db->query("UPDATE loan set loan_type='$loan_details[loan_type]', loan_status='$loan_details[loan_status]', loan_amount='$loan_details[loan_amt]', monthly_payment='$result_array[monthly]', interest='$result_array[interest]', amount_paid='$loan_details[amt_paid]', start_date='$loan_details[start_date]', end_date='$loan_details[end_date]', remarks='$loan_details[remarks]' WHERE loan_id = '$loan_id'");
		}


		public function getLoanDetails($loan_id){ 

			$query = $this->db->query("SELECT  * FROM loan a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE loan_id='$loan_id'");
			
				$result=$query->result_array();

				return $result;

		}

		public function deleteConsumerLoan($loan_id){ 

			$this->db->query("DELETE from loan WHERE loan_id='$loan_id'");
		}



		public function retrieveLoan($account_no){ 

			$query = $this->db->query("SELECT * FROM loan WHERE consumer_id='$account_no'");

			$result=$query->result_array();

			$mlp = 0;
			$interest = 0;
			$meter_fee = 0;

			if(count($result)!=0){

				foreach ($result as $row){

					if ($row['loan_type'] == 'Material Loan'){

						$mlp = $row['monthly_payment'];
						$interest = $row['interest'];

					}
					else if ($row['loan_type'] == 'Meter Fee'){

						$meter_fee = $row['monthly_payment'];

					}

				}

			}

			return array(
				'mlp' => $mlp,
				'interest' => $interest,
				'meter_fee' => $meter_fee 
			);
			
		}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//	BILLING SETTINGS


		public function getRates(){ 

			$query = $this->db->query("SELECT  * FROM rates");
			
			$result=$query->result_array();

			return $result;

		}

		public function getReadingStatus(){ 

			$query = $this->db->query("SELECT * FROM reading_status");
			
			$result=$query->result_array();

			return $result;

		}

		public function saveReadingStatus($brgy){ 

			if ($brgy == "ENABLE ALL"){
				$this->db->query("UPDATE reading_status set reading='0', date_finished='null'");
			}
			else if ($brgy == "DISABLE ALL"){

				$date_finished = date('Y-m-d H:i');
				$this->db->query("UPDATE reading_status set reading='1', date_finished='$date_finished'");

			}
			else {
				$query = $this->db->query("SELECT * FROM reading_status WHERE barangay='$brgy'");

				$reading=$query->row('reading');

				if ($reading == 1){
					$this->db->query("UPDATE reading_status set reading='0', date_finished='null' WHERE barangay='$brgy'");
				}
				else {
					$date_finished = date('Y-m-d H:i');
					$this->db->query("UPDATE reading_status set reading='1', date_finished='$date_finished' WHERE barangay='$brgy'");
				}
			}
		}

		public function saveRateDetails($connection_type, $rate_details){ 

			$this->db->query("UPDATE rates set first_10_CU='$rate_details[first10cu]', 11_CU='$rate_details[_11to20cu]', 21_CU='$rate_details[_21to30cu]', 31_CU='$rate_details[_31abovecu]' where connection_type='$connection_type'");
		}

		public function getSched(){ 

			$query = $this->db->query("SELECT  * FROM billing_schedule");
			
			$result=$query->result_array();

			return $result;

		}

		public function saveSched($sched_id, $sched_details){ 

			$this->db->query("UPDATE billing_schedule set meter_reading='$sched_details[reading]', distribution='$sched_details[distribution]', grace_period='$sched_details[grace_period]', extension_with_penalty='$sched_details[penalty]', disconnection='$sched_details[disconnection]' where sched_id='$sched_id'");

		}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// 	MANAGE METERS


		public function getMeters(){ 

			$query = $this->db->query("SELECT  * FROM water_meters a LEFT JOIN consumers b ON a.meter_serial_no=b.meter_id");
			
				$result=$query->result_array();

				return $result;
		}

		public function saveMeterDetails($meter_id, $meter_details, $date_installed){ 


			$this->db->query("UPDATE consumers set meter_id='$meter_details[meter_serial_no]', date_installed='$date_installed' where meter_id='$meter_id'");

			$query=$this->db->query("SELECT * from consumers where meter_id = '$meter_details[meter_serial_no]'");

			$consumer_id=$query->row('account_no');

			$checkmeter_query=$this->db->query("SELECT * from water_meters where meter_serial_no = '$meter_details[meter_serial_no]'");

			$result=$checkmeter_query->result_array();
			
			if(count($result)==0){

				$this->db->query("INSERT into readings values (null,'$consumer_id', '$meter_details[meter_serial_no]', '$meter_details[initial_reading]', '$date_installed')");
				
			}

			$checkloan_query=$this->db->query("SELECT * from loan where consumer_id = '$consumer_id' AND loan_type='Meter Fee' AND loan_status='Active'");

			$result2=$checkloan_query->result_array();
			
			if(count($result2)!=0){

				$this->db->query("DELETE from loan where consumer_id = '$consumer_id' AND loan_type='Meter Fee' AND loan_status='Active'");
				
			}

			$this->db->query("UPDATE water_meters set meter_serial_no='$meter_details[meter_serial_no]', meter_brand='$meter_details[meter_brand]', meter_size='$meter_details[meter_size]', initial_reading='$meter_details[initial_reading]' where meter_serial_no='$meter_id'");

			return $consumer_id;
		
		}

		public function deleteWaterMeter($meter_id){ 

			$checker_query= $this->db->query("SELECT  * FROM consumers where meter_id='$meter_id'");

			$result=$checker_query->result_array();
			
			if(count($result)==0){

				$this->db->query("DELETE from water_meters where meter_serial_no='$meter_id'");

				return array( //passes assiociative array to controller
			    'feedback' => "deleted");
			}
			else return array( //passes assiociative array to controller
			    'feedback' => "error",
			    'consumer_id' => $checker_query->row('account_no'),
			    'date_installed' => $checker_query->row('date_installed'),
				'meter_id' => $checker_query->row('meter_id'));
		}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// 	MANAGE METER READINGS

		public function getReadingData($barangay, $month, $year){ 
		    
		    $barangay = str_replace("_"," ",$barangay);

			if ($month - 1 != 0){

	        	$prev_m = $month - 1;

	        	$prev_y = $year;

	        }
	        else {

	        	$prev_m = 12;

	        	$prev_y = $year - 1;
	        }

			$query = $this->db->query("SELECT  * FROM readings a LEFT JOIN consumers b ON a.consumer_id=b.account_no LEFT JOIN bills c ON a.reading_id=c.reading_id WHERE b.address='$barangay' AND year(a.reading_date)='$prev_y' AND month(a.reading_date)='$prev_m'");
			
			$result=$query->result_array();

			return $result;

		}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//	SYSTEM LOGS


	public function getLogs(){ 

		$curr = strtotime(date('Y-m-d')); 

	    $prev_m = date("m", strtotime("-1 month", $curr)); 
	    $prev_y = date("Y", strtotime("-1 month", $curr)); 

	    $curr_m = date("m", strtotime('-0 months', $curr)); 
	    $curr_y = date("Y", strtotime('-0 months', $curr)); 
	 
	    $this->db->query("DELETE FROM system_logs WHERE month(log_time) <> '$curr_m' AND year(log_time) <> '$curr_y'");

	    $query = $this->db->query("SELECT * FROM system_logs");

	    $result=$query->result_array();

		return $result;

	}


	public function getLogData($day1, $day2){ 

	    $query = $this->db->query("SELECT * FROM system_logs WHERE day(log_time)>='$day1' AND day(log_time)<='$day2'");

	    $result=$query->result_array();

		return $result;

	}


	public function saveLogs($type, $id, $desc){ 


		$date_time = date('Y-m-d H:i');

		$this->db->query("INSERT INTO system_logs values (null, '$date_time', '$id', '$type', '$desc')");


	}
	
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// REPORTS


        public function getReportData($curr_m, $curr_y){
            
            $query = $this->db->query("SELECT * FROM summary WHERE month(report_date)='$curr_m' AND year(report_date)='$curr_y'");

	        $result=$query->result_array();
	        
	        return $result;
	        
        }
        
        
        public function updateConCount(){
            
            $curr_m = date('m');
            $curr_y = date('Y');
            
            //Registered Consumers
            
            $query = $this->db->query("SELECT * FROM consumers WHERE address='Binanuahan'");

	        $result1=$query->result_array();
	        $count1 = count($result1);
	        
	         $query = $this->db->query("SELECT * FROM consumers WHERE address='Biriran'");

	        $result2=$query->result_array();
	         $count2 = count($result2);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='Catanagan'");

	        $result3=$query->result_array();
	         $count3 = count($result3);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='Cogon'");

	        $result4=$query->result_array();
	         $count4 = count($result4);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='Embarcadero'");

	        $result5=$query->result_array();
	         $count5 = count($result5);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='North Poblacion'");

	        $result6=$query->result_array();
	         $count6 = count($result6);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='South Poblacion'");

	        $result7=$query->result_array();
	         $count7 = count($result7);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='Taboc'");

	        $result8=$query->result_array();
	         $count8 = count($result8);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='Tughan'");

	        $result9=$query->result_array();
	         $count9 = count($result9);
	         
	         
	        ////////////////////////////////////////////////////////////////////
	        // Active Consumers
	        
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='Binanuahan' AND status='Active'");

	        $act_result1=$query->result_array();
	         $act_count1 = count($act_result1);
	        
	         $query = $this->db->query("SELECT * FROM consumers WHERE address='Biriran' AND status='Active'");

	        $act_result2=$query->result_array();
	        $act_count2 = count($act_result2);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='Catanagan' AND status='Active'");

	        $act_result3=$query->result_array();
	        $act_count3 = count($act_result3);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='Cogon' AND status='Active'");

	        $act_result4=$query->result_array();
	        $act_count4 = count($act_result4);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='Embarcadero' AND status='Active'");

	        $act_result5=$query->result_array();
	        $act_count5 = count($act_result5);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='North Poblacion' AND status='Active'");

	        $act_result6=$query->result_array();
	        $act_count6 = count($act_result6);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='South Poblacion' AND status='Active'");

	        $act_result7=$query->result_array();
	        $act_count7 = count($act_result7);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='Taboc' AND status='Active'");

	        $act_result8=$query->result_array();
	        $act_count8 = count($act_result8);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='Tughan' AND status='Active'");

	        $act_result9=$query->result_array();
	        $act_count9 = count($act_result9);
	        
	        ////////////////////////////////////////////////////////////////////
	        // Disconnected Consumers
	        
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='Binanuahan' AND status='Disconnected'");

	        $d_result1=$query->result_array();
	         $d_count1 = count($d_result1);
	        
	         $query = $this->db->query("SELECT * FROM consumers WHERE address='Biriran' AND status='Disconnected'");

	        $d_result2=$query->result_array();
	         $d_count2 = count($d_result2);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='Catanagan' AND status='Disconnected'");

	       $d_result3=$query->result_array();
	         $d_count3 = count($d_result3);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='Cogon' AND status='Disconnected'");

	        $d_result4=$query->result_array();
	         $d_count4 = count($d_result4);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='Embarcadero' AND status='Disconnected'");

	        $d_result5=$query->result_array();
	         $d_count5 = count($d_result5);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='North Poblacion' AND status='Disconnected'");

	         $d_result6=$query->result_array();
	         $d_count6 = count($d_result6);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='South Poblacion' AND status='Disconnected'");

	         $d_result7=$query->result_array();
	         $d_count7 = count($d_result7);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='Taboc' AND status='Disconnected'");

	          $d_result8=$query->result_array();
	         $d_count8 = count($d_result8);
	        
	        $query = $this->db->query("SELECT * FROM consumers WHERE address='Tughan' AND status='Disconnected'");

	        $d_result9=$query->result_array();
	         $d_count9 = count($d_result9);
	        
	        
	       ////////////////////////////////////////////////////////////////////
	       // Adding data to Database
	        
            
            $curr_date = date('Y-m-d');
            
            $check_query = $this->db->query("SELECT * FROM summary WHERE month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
            
            
            $check_result=$check_query->result_array();
            
            if (count($check_result)>0){
                //update
                $this->db->query("UPDATE summary SET total_consumers='$count1', active_consumers='$act_count1', disconnected_consumers='$d_count1' WHERE barangay='Binanuahan' AND month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
                
                 $this->db->query("UPDATE summary SET total_consumers='$count2', active_consumers='$act_count2', disconnected_consumers='$d_count2' WHERE barangay='Biriran' AND month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
                 
                 $this->db->query("UPDATE summary SET total_consumers='$count3', active_consumers='$act_count3', disconnected_consumers='$d_count3' WHERE barangay='Catanagan' AND month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
                 
                 $this->db->query("UPDATE summary SET total_consumers='$count4', active_consumers='$act_count4', disconnected_consumers='$d_count4' WHERE barangay='Cogon' AND month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
                 
                 $this->db->query("UPDATE summary SET total_consumers='$count5', active_consumers='$act_count5', disconnected_consumers='$d_count5' WHERE barangay='Embarcadero' AND month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
                 
                 $this->db->query("UPDATE summary SET total_consumers='$count6', active_consumers='$act_count6', disconnected_consumers='$d_count6' WHERE barangay='North Poblacion' AND month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
                 
                 $this->db->query("UPDATE summary SET total_consumers='$count7', active_consumers='$act_count7', disconnected_consumers='$d_count7' WHERE barangay='South Poblacion' AND month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
                 
                 $this->db->query("UPDATE summary SET total_consumers='$count8', active_consumers='$act_count8', disconnected_consumers='$d_count8' WHERE barangay='Taboc' AND month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
                 
                 $this->db->query("UPDATE summary SET total_consumers='$count9', active_consumers='$act_count9', disconnected_consumers='$d_count9' WHERE barangay='Tughan' AND month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
            }
            else {
                //insert
                
                 $this->db->query("INSERT INTO summary VALUES(null, 'Binanuahan', '$count1', '$act_count1', '$d_count1', null, null, null, null, null, null, null, null, '$curr_date')");
                 
                 $this->db->query("INSERT INTO summary VALUES(null, 'Biriran', '$count2', '$act_count2', '$d_count2', null, null, null, null, null, null, null, null, '$curr_date')");
                 
                 $this->db->query("INSERT INTO summary VALUES(null, 'Catanagan', '$count3', '$act_count3', '$d_count3', null, null, null, null, null, null, null, null, '$curr_date')");
                 
                  $this->db->query("INSERT INTO summary VALUES(null, 'Cogon', '$count4', '$act_count4', '$d_count4', null, null, null, null, null, null, null, null, '$curr_date')");
                 
                 $this->db->query("INSERT INTO summary VALUES(null, 'Embarcadero', '$count5', '$act_count5', '$d_count5', null, null, null, null, null, null, null, null, '$curr_date')");
                 
                 $this->db->query("INSERT INTO summary VALUES(null, 'North Poblacion', '$count6', '$act_count6', '$d_count6', null, null, null, null, null, null, null, null, '$curr_date')");
                 
                 $this->db->query("INSERT INTO summary VALUES(null, 'South Poblacion', '$count7', '$act_count7', '$d_count7', null, null, null, null, null, null, null, null, '$curr_date')");
                 
                 $this->db->query("INSERT INTO summary VALUES(null, 'Taboc', '$count8', '$act_count8', '$d_count8', null, null, null, null, null, null, null, null, '$curr_date')");
                 
                  $this->db->query("INSERT INTO summary VALUES(null, 'Tughan', '$count9', '$act_count9', '$d_count9', null, null, null, null, null, null, null, null, '$curr_date')");
                 
            }
            
            
        }
        

        public function updateSummary($curr_m,$curr_y){
            
	        ///////////////////////////////////////////////////////////////////
	        //Water Usage (CU) and Collectible
	
	        
	        $query = $this->db->query("SELECT SUM(water_usage), SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Binanuahan' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $cu1=$query->row('SUM(water_usage)');
	        $cb1=$query->row('SUM(total_amount)');
	        
	        
	        $query = $this->db->query("SELECT SUM(water_usage), SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Biriran' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");

	        $cu2=$query->row('SUM(water_usage)');
	        $cb2=$query->row('SUM(total_amount)');
	       
	        $query = $this->db->query("SELECT SUM(water_usage), SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Catanagan' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");

	        $cu3=$query->row('SUM(water_usage)');
	        $cb3=$query->row('SUM(total_amount)');
	        
	         $query = $this->db->query("SELECT SUM(water_usage), SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Cogon' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");

	       $cu4=$query->row('SUM(water_usage)');
	       $cb4=$query->row('SUM(total_amount)');
	        
	        $query = $this->db->query("SELECT SUM(water_usage), SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Embarcadero' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");

	        $cu5=$query->row('SUM(water_usage)');
	        $cb5=$query->row('SUM(total_amount)');
	        
	        $query = $this->db->query("SELECT SUM(water_usage), SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='North Poblacion' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");

	        $cu6=$query->row('SUM(water_usage)');
	        $cb6=$query->row('SUM(total_amount)');
	        
	        $query = $this->db->query("SELECT SUM(water_usage), SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='South Poblacion' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");

	        $cu7=$query->row('SUM(water_usage)');
	        $cb7=$query->row('SUM(total_amount)');
	        
	        $query = $this->db->query("SELECT SUM(water_usage), SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Taboc' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");

	        $cu8=$query->row('SUM(water_usage)');
	        $cb8=$query->row('SUM(total_amount)');
	        
	        $query = $this->db->query("SELECT SUM(water_usage), SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Tughan' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");

	       $cu9=$query->row('SUM(water_usage)');
	       $cb9=$query->row('SUM(total_amount)');
	       
	        
	       /////////////////////////////////////////////////////////////////////
	       // Pecentage of CU Used and Collectible
	       
	           $per_cu1 = 0;
    	       $per_cu2 = 0;
    	       $per_cu3 = 0;
    	       $per_cu4 = 0;
    	       $per_cu5 = 0;
    	       $per_cu6 = 0;
    	       $per_cu7 = 0;
    	       $per_cu8 = 0;
    	       $per_cu9 = 0;
    	       
    	       $per_cb1 = 0;
    	       $per_cb2 = 0;
    	       $per_cb3 = 0;
    	       $per_cb4 = 0;
    	       $per_cb5 = 0;
    	       $per_cb6 = 0;
    	       $per_cb7 = 0;
    	       $per_cb8 = 0;
    	       $per_cb9 = 0;
    	       
    	       
	       if ($cu1!=0 || $cu2!=0 || $cu3!=0 || $cu4!=0 || $cu5!=0 || $cu6!=0 || $cu7!=0 || $cu8!=0 || $cu9!=0 || $cb1!=0 || $cb2!=0 || $cb3!=0 || $cb4!=0 || $cb5!=0 || $cb6!=0 || $cb7!=0 || $cb8!=0 || $cb9!=0){
	           
	           $total_cu = $cu1 + $cu2 + $cu3 + $cu4 + $cu5 + $cu6 + $cu7 + $cu8 + $cu9;
	           
	           if ($total_cu!=0){
	               //getting precentage of cu used
        	       $per_cu1 = round(($cu1 / $total_cu) * 100);
        	       $per_cu2 = round(($cu2 / $total_cu) * 100);
        	       $per_cu3 = round(($cu3 / $total_cu) * 100);
        	       $per_cu4 = round(($cu4 / $total_cu) * 100);
        	       $per_cu5 = round(($cu5 / $total_cu) * 100);
        	       $per_cu6 = round(($cu6 / $total_cu) * 100);
        	       $per_cu7 = round(($cu7 / $total_cu) * 100);
        	       $per_cu8 = round(($cu8 / $total_cu) * 100);
        	       $per_cu9 = round(($cu9 / $total_cu) * 100);
	           }
	       
    	       
    	       
    	       //getting percentage of collectibles
    	       $total_cb = $cb1 + $cb2 + $cb3 + $cb4 + $cb5 + $cb6 + $cb7 + $cb8 + $cb9; 
    	       
    	       if ($total_cb!=0){
    	           $per_cb1 = round(($cb1 / $total_cb) * 100);
        	       $per_cb2 = round(($cb2 / $total_cb) * 100);
        	       $per_cb3 = round(($cb3 / $total_cb) * 100);
        	       $per_cb4 = round(($cb4 / $total_cb) * 100);
        	       $per_cb5 = round(($cb5 / $total_cb) * 100);
        	       $per_cb6 = round(($cb6 / $total_cb) * 100);
        	       $per_cb7 = round(($cb7 / $total_cb) * 100);
        	       $per_cb8 = round(($cb8 / $total_cb) * 100);
        	       $per_cb9 = round(($cb9 / $total_cb) * 100);
    	       }
    	       
	       }
    	       
    	       
    	    ////////////////////////////////////////////////////////////////////
	       // Total Amount Collected 
	       
	       
	       $query1 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Binanuahan' AND a.bill_status='Paid' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $total1=$query1->row('SUM(total_amount)');
	        
	         $query1 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Biriran' AND a.bill_status='Paid' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $total2=$query1->row('SUM(total_amount)');
	        
	        $query1 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Catanagan' AND a.bill_status='Paid' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $total3=$query1->row('SUM(total_amount)');
	        
	        $query1 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Cogon' AND a.bill_status='Paid' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $total4=$query1->row('SUM(total_amount)');
	        
	        $query1 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Embarcadero' AND a.bill_status='Paid' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $total5=$query1->row('SUM(total_amount)');
	        
	        $query1 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='North Poblacion' AND a.bill_status='Paid' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $total6=$query1->row('SUM(total_amount)');
	        
	        $query1 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='South Poblacion' AND a.bill_status='Paid' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $total7=$query1->row('SUM(total_amount)');
	        
	        $query1 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Taboc' AND a.bill_status='Paid' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $total8=$query1->row('SUM(total_amount)');
	        
	        $query1 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Tughan' AND a.bill_status='Paid' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $total9=$query1->row('SUM(total_amount)');
	        
	        
	        ////////////////////////////////////////////////////////////////////
	       // Total Amount Percentage
	       
	           $perc_total1 = 0;
    	       $perc_total2 = 0;
    	       $perc_total3 = 0;
    	       $perc_total4 = 0;
    	       $perc_total5 = 0;
    	       $perc_total6 = 0;
    	       $perc_total7 = 0;
    	       $perc_total8 = 0;
    	       $perc_total9 = 0;
	       
	       if ($total1!=0 || $total2!=0 || $total3!=0 || $total4!=0 || $total5!=0 || $total6!=0 || $total7!=0 || $total8!=0 || $total9!=0){
	           
	           $total_collected = $total1 + $total2 + $total3 + $total4 + $total5 + $total6 + $total7 + $total8 + $total9;
	           
	           if ($total_collected!=0){
	                //getting precentage of total amount
        	       $perc_total1 = round(($total1 / $total_collected) * 100);
        	       $perc_total2 = round(($total2 / $total_collected) * 100);
        	       $perc_total3 = round(($total3 / $total_collected) * 100);
        	       $perc_total4 = round(($total4 / $total_collected) * 100);
        	       $perc_total5 = round(($total5 / $total_collected) * 100);
        	       $perc_total6 = round(($total6 / $total_collected) * 100);
        	       $perc_total7 = round(($total7 / $total_collected) * 100);
        	       $perc_total8 = round(($total8 / $total_collected) * 100);
        	       $perc_total9 = round(($total9 / $total_collected) * 100);
	           }
	       
    	      
	       }
	       

	       ////////////////////////////////////////////////////////////////////
	       // Collected within due and after due 
	       
	       
	       $query1 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Binanuahan' AND a.bill_status='Paid' AND a.penalty='0' AND a.reconnection_fee='0' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	       
	       $query2 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Binanuahan' AND a.bill_status='Paid' AND (a.penalty<>'0' OR a.reconnection_fee<>'0') AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $win_due1=$query1->row('SUM(total_amount)');
	        $after_due1=$query2->row('SUM(total_amount)');
	        
	         $query1 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Biriran' AND a.bill_status='Paid' AND a.penalty='0' AND a.reconnection_fee='0' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	       
	       $query2 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Biriran' AND a.bill_status='Paid' AND (a.penalty<>'0' OR a.reconnection_fee<>'0') AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $win_due2=$query1->row('SUM(total_amount)');
	        $after_due2=$query2->row('SUM(total_amount)');
	        
	        $query1 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Catanagan' AND a.bill_status='Paid' AND a.penalty='0' AND a.reconnection_fee='0' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	       
	       $query2 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Catanagan' AND a.bill_status='Paid' AND (a.penalty<>'0' OR a.reconnection_fee<>'0') AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $win_due3=$query1->row('SUM(total_amount)');
	        $after_due3=$query2->row('SUM(total_amount)');
	        
	        $query1 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Cogon' AND a.bill_status='Paid' AND a.penalty='0' AND a.reconnection_fee='0' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	       
	       $query2 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Cogon' AND a.bill_status='Paid' AND (a.penalty<>'0' OR a.reconnection_fee<>'0') AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $win_due4=$query1->row('SUM(total_amount)');
	        $after_due4=$query2->row('SUM(total_amount)');
	        
	        $query1 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Embarcadero' AND a.bill_status='Paid' AND a.penalty='0' AND a.reconnection_fee='0' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	       
	       $query2 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Embarcadero' AND a.bill_status='Paid' AND (a.penalty<>'0' OR a.reconnection_fee<>'0') AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $win_due5=$query1->row('SUM(total_amount)');
	        $after_due5=$query2->row('SUM(total_amount)');
	        
	        $query1 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='North Poblacion' AND a.bill_status='Paid' AND a.penalty='0' AND a.reconnection_fee='0' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	       
	       $query2 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='North Poblacion' AND a.bill_status='Paid' AND (a.penalty<>'0' OR a.reconnection_fee<>'0') AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $win_due6=$query1->row('SUM(total_amount)');
	        $after_due6=$query2->row('SUM(total_amount)');
	        
	        $query1 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='South Poblacion' AND a.bill_status='Paid' AND a.penalty='0' AND a.reconnection_fee='0' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	       
	       $query2 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='South Poblacion' AND a.bill_status='Paid' AND (a.penalty<>'0' OR a.reconnection_fee<>'0') AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $win_due7=$query1->row('SUM(total_amount)');
	        $after_due7=$query2->row('SUM(total_amount)');
	        
	        $query1 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Taboc' AND a.bill_status='Paid' AND a.penalty='0' AND a.reconnection_fee='0' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	       
	       $query2 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Taboc' AND a.bill_status='Paid' AND (a.penalty<>'0' OR a.reconnection_fee<>'0') AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $win_due8=$query1->row('SUM(total_amount)');
	        $after_due8=$query2->row('SUM(total_amount)');
	        
	        $query1 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Tughan' AND a.bill_status='Paid' AND a.penalty='0' AND a.reconnection_fee='0' AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	       
	       $query2 = $this->db->query("SELECT SUM(total_amount) FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no WHERE b.address='Tughan' AND a.bill_status='Paid' AND (a.penalty<>'0' OR a.reconnection_fee<>'0') AND month(a.date_created)='$curr_m' AND year(a.date_created)='$curr_y'");
	        
	        $win_due9=$query1->row('SUM(total_amount)');
	        $after_due9=$query2->row('SUM(total_amount)');
	        
	        
	       ////////////////////////////////////////////////////////////////////
	       // Adding data to Database
	        
            
            $curr_date = date('Y-m-d');
            
            $check_query = $this->db->query("SELECT * FROM summary WHERE month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
            
            
            $check_result=$check_query->result_array();
            
            if (count($check_result)>0){
                //update
                $this->db->query("UPDATE summary SET cu_used='$cu1', percent_cu='$per_cu1', collectible='$cb1', percent_collectible='$per_cb1', within_due='$win_due1', after_due='$after_due1', total='$total1', percent_collected='$perc_total1' WHERE barangay='Binanuahan' AND month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
                
                 $this->db->query("UPDATE summary SET cu_used='$cu2', percent_cu='$per_cu2', collectible='$cb2', percent_collectible='$per_cb2', within_due='$win_due2', after_due='$after_due2', total='$total2', percent_collected='$perc_total2' WHERE barangay='Biriran' AND month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
                 
                 $this->db->query("UPDATE summary SET cu_used='$cu3', percent_cu='$per_cu3', collectible='$cb3', percent_collectible='$per_cb3', within_due='$win_due3', after_due='$after_due3', total='$total3', percent_collected='$perc_total3' WHERE barangay='Catanagan' AND month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
                 
                 $this->db->query("UPDATE summary SET cu_used='$cu4', percent_cu='$per_cu4', collectible='$cb4', percent_collectible='$per_cb4', within_due='$win_due4', after_due='$after_due4', total='$total4', percent_collected='$perc_total4' WHERE barangay='Cogon' AND month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
                 
                 $this->db->query("UPDATE summary SET cu_used='$cu5', percent_cu='$per_cu5', collectible='$cb5', percent_collectible='$per_cb5', within_due='$win_due5', after_due='$after_due5', total='$total5', percent_collected='$perc_total5' WHERE barangay='Embarcadero' AND month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
                 
                 $this->db->query("UPDATE summary SET cu_used='$cu6', percent_cu='$per_cu6', collectible='$cb6', percent_collectible='$per_cb6', within_due='$win_due6', after_due='$after_due6', total='$total6', percent_collected='$perc_total6' WHERE barangay='North Poblacion' AND month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
                 
                 $this->db->query("UPDATE summary SET cu_used='$cu7', percent_cu='$per_cu7', collectible='$cb7', percent_collectible='$per_cb7', within_due='$win_due7', after_due='$after_due7', total='$total7', percent_collected='$perc_total7' WHERE barangay='South Poblacion' AND month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
                 
                 $this->db->query("UPDATE summary SET cu_used='$cu8', percent_cu='$per_cu8', collectible='$cb8', percent_collectible='$per_cb8', within_due='$win_due8', after_due='$after_due8', total='$total8', percent_collected='$perc_total8' WHERE barangay='Taboc' AND month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
                 
                 $this->db->query("UPDATE summary SET cu_used='$cu9', percent_cu='$per_cu9', collectible='$cb9', percent_collectible='$per_cb9', within_due='$win_due9', after_due='$after_due9', total='$total9', percent_collected='$perc_total9' WHERE barangay='Tughan' AND month(report_date)='$curr_m' AND year(report_date)='$curr_y'");
            }
            
        }

        public function getConBills($consumer_id, $y){
           $query = $this->db->query("SELECT * FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no LEFT JOIN readings c ON a.reading_id=c.reading_id WHERE b.account_no='$consumer_id' AND a.consumer_id='$consumer_id' AND year(a.date_created)='$y'"); // tables bills, consumers, and readings are joined to access all of the data in each table

			$result=$query->result_array();

			return $result;
        }
    
        public function getAbstractReports($curr_yr, $curr_mon){ 

			$query = $this->db->query("SELECT * FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no LEFT JOIN invoice c ON a.bill_id=c.bill_id WHERE month(c.or_date)='$curr_mon' AND year(c.or_date)='$curr_yr'");

			$result=$query->result_array();

			return $result;
		}
		
		public function getAbstractReportsPrint($curr_yr, $curr_mon, $brgy){ 
		    
		    if ($brgy=="All"){
		        $query = $this->db->query("SELECT * FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no LEFT JOIN invoice c ON a.bill_id=c.bill_id WHERE month(c.or_date)='$curr_mon' AND year(c.or_date)='$curr_yr'");
		    }
		    else {
		        $query = $this->db->query("SELECT * FROM bills a LEFT JOIN consumers b ON a.consumer_id=b.account_no LEFT JOIN invoice c ON a.bill_id=c.bill_id WHERE b.address='$brgy' AND month(c.or_date)='$curr_mon' AND year(c.or_date)='$curr_yr'");
		    }

			$result=$query->result_array();

			return $result;
		}
		
		public function getDateCount($type){ 
		    
		    if ($type=="abstract"){
		        
		        $query = $this->db->query("SELECT min(or_date) from invoice");

			    $result=$query->result_array();
			    
		    }
		    
		    else if ($type=="bill_history"){
		        
		        $query = $this->db->query("SELECT min(date_created) from bills");

			    $result=$query->result_array();
			    
		    }
		    
		    else if ($type=="summary"){
		        
		        $query = $this->db->query("SELECT min(report_date) from summary");

			    $result=$query->result_array();
			    
		    }

			return $result;
		}
		

	}
?>