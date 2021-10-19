<?php
	class Main extends CI_Controller{

		 public function __construct(){
		  parent::__construct();
		  
		   $this->db = $this->load->database('default',TRUE);
		   $this->load->model('JWS_model');
		   
		   $datab = new JWS_model();
		   
		   $datab->updateConCount();
  		}
  		
  		
//********************************************************************************************************************//
//-----SYSTEM METHODS-------------------------------------------------------------------------------------------------//
//********************************************************************************************************************//

    
	function backupDB($fileName='jws_db.zip'){
	    
	    $this->load->dbutil();
	
		$prefs = array(
        'format'        => 'zip',                       
        'filename'      => 'jwsbmsin_db.sql',              
        'add_drop'      => TRUE,                        
        'add_insert'    => TRUE,                        
        'newline'       => "\n"                         
		);

		$backup =& $this->dbutil->backup($prefs);

		$this->load->helper('file');
		write_file(FCPATH.'/downloads/'.$fileName, $backup);

		$this->load->helper('download');
		force_download($fileName, $backup);
		
	}
		
// 		public function restoreDB(){ 
		    
// 		    $path = 'assets/database_backup/';
//         	$sql_filename = 'crmdata.sql';
        
//         	$sql_contents = file_get_contents($path.$sql_filename);
//         	$sql_contents = explode(";", $sql_contents);
        
//         	foreach($sql_contents as $query)
//         	{
//         		$pos = strpos($query,'ci_sessions');
//         		var_dump($pos);
//         		if($pos == false)
//         		{
//         			$result = $this->db->query($query);
//         		}
//         		else 
//         		{
//         			continue;
//         		}
//         	}   
// 		}
		
		public function backupWebsite(){ 
		    
		    $this->load->helper('url');
		    $this->load->helper('file');
		    $this->load->helper('download');
		    
		    $this->load->library('zip');
		    
		     $date = date('Y-m-d');
		     
		    $random = rand(10000000,9999999);
		    
		    $this->zip->read_dir(FCPATH,FALSE);
		    $this->zip->archive("assets/web_backup/website_".$date."_".$random."zip");
		    $this->zip->download("jwsbms_webfiles_".$date.".zip");
		    
		    header('location:'.base_url("/index.php/main/viewConsumers"));
		    
		    //force_download($dbname,$backup);
		}

  		
  		
//********************************************************************************************************************//
//-----SMS METHODS--------------------------------------------------------------------------------------------------//
//********************************************************************************************************************//

    public function itexmo($number,$message,$apicode){
			$ch = curl_init();
			$itexmo = array('1' => $number, '2' => $message, '3' => $apicode);
			curl_setopt($ch, CURLOPT_URL,"https://www.itexmo.com/php_api/api.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			 curl_setopt($ch, CURLOPT_POSTFIELDS, 
			          http_build_query($itexmo));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			return curl_exec ($ch);
			curl_close ($ch);
    }

    public function sendSMS1(){ 
        
        $datab = new JWS_model();
            
        $bills = $this->input->post("bills");
        
        if ($bills != null && count($bills) != 0){
            
            $feedback="";
        
            for ($ctr=0;$ctr<count($bills);$ctr++){
                
               $bill_data=$datab->getPhoneNum($bills[$ctr]);
                    
        	    foreach ($bill_data as $row){
        	        
        	        $ddate = strtotime($row['date_created']);
        	        	
                  	$bill_month = date("F-Y", strtotime('-0 months', $ddate));
        	       
        	        $number = $row['phonenumber'];
                
                    $message = "Your bill for the month of ".$bill_month." has been created. Your total amount due is ".$row['total_amount'].", refer to your water bill for the full breakdown. Please pay the amount before ".$row['due_date'].". - JWS"; // message content
                    
                    $apicode = "ST-TLCS-886582_NRH76";
                
                    $result = $this->itexmo($number,$message,$apicode);
                    
                    if ($result == ""){
                        echo "iTexMo: No response from server!!!
                        Please check the METHOD used (CURL or CURL-LESS). If you are using CURL then try CURL-LESS and vice versa.	
                        Please CONTACT US for help. ";	
                    }
                    else if ($result == 0){
                       
            		  $feedback="sent";
            		  $datab->setMsgStatus($row['bill_id']);
            		   
                    }
                    else{	
                        echo "Error Num ". $result . " was encountered!";
                    }

                    break;
        	        
        	    }
            }
            
            if ($feedback=="sent"){
                
                $this->session->set_flashdata("info", "<div class='alert alert-info'>
            		              <button class='close' data-dismiss='alert'>×</button>
            		              <strong>Success!</strong> SMS Notification has been sent.</div>");
            		              
                $this->addLogs("ALERT", $this->session->userdata('username'), "Sent SMS Notifications for Water Bill.");
            }
            else {
                $this->session->set_flashdata("info", "<div class='alert alert-danger'>
            		              <button class='close' data-dismiss='alert'>×</button>
            		              <strong>Error!</strong> SMS Notification has not been sent.</div>");
            }
            
        }
        else {
            $this->session->set_flashdata("info", "<div class='alert alert-danger'>
            		              <button class='close' data-dismiss='alert'>×</button>
            		              <strong>Error!</strong> Please tick the box of the Water Bill before sending.</div>");
        }
        
         header('location:'.base_url("/index.php/main/viewBilledConsumers"));
            
	}
	
	public function sendSMS2($bill_id){
	
	    $datab = new JWS_model();
	
	    $bill_data=$datab->getPhoneNum2($bill_id);
	    
	    $feedback="none";
	    
	    foreach ($bill_data as $row){
	        
	         $ddate = strtotime($row['date_created']);
        	        	
            $bill_month = date("F-Y", strtotime('-0 months', $ddate));
	        
	        $number = $row['phonenumber'];
        
            $message = "Your bill for the month of ".$bill_month." has been paid. OR no. ".$row['OR_no'].". - JWS"; // message content
            
            $apicode = "ST-TLCS-886582_NRH76";
        
            $result = $this->itexmo($number,$message,$apicode);
            
            if ($result == ""){
                echo "iTexMo: No response from server!!!
                Please check the METHOD used (CURL or CURL-LESS). If you are using CURL then try CURL-LESS and vice versa.	
                        Please CONTACT US for help. ";	
            }
            else if ($result == 0){
                       
            	$feedback="sent";
            		   
            }
            else{	
                echo "Error Num ". $result . " was encountered!";
            }
            
            break;
            
	    }
	         if ($feedback=="sent"){
                
                $this->session->set_flashdata("msg_info", "<div class='alert alert-info'>
            		              <button class='close' data-dismiss='alert'>×</button>
            		              <strong>Info!</strong> SMS Notification has been sent for Invoice.</div>");
            		              
                $this->addLogs("ALERT", "System", "Sent SMS Notifications.");
            }
            else {
                $this->session->set_flashdata("msg_info", "<div class='alert alert-danger'>
            		              <button class='close' data-dismiss='alert'>×</button>
            		              <strong>Error!</strong> SMS Notification has not been sent.</div>");
            }
            
            return $feedback;
	}


//********************************************************************************************************************//
//-----LOGIN METHODS--------------------------------------------------------------------------------------------------//
//********************************************************************************************************************//
		

		public function index(){ // view the login page
		
		    $this->session->unset_userdata('username');
			$this->session->unset_userdata('logged_in');

			$this->load->view("index");
			$this->session->set_flashdata("error", "");
		}

		public function logout(){ // 

			$datab = new JWS_model();

			$datab->updateUserStatus($this->session->userdata('username'));

			if ($this->session->userdata('username') !=""){

				$this->addLogs("ALERT", "System", $this->session->userdata('username')." has Logged Out.");

			}
			
			$this->session->unset_userdata('username');
			$this->session->unset_userdata('logged_in');

			header('location:'.base_url());
		}

		public function checkLogin(){ // check the username and password

			$datab = new JWS_model();

			$usern = $this->input->post('username');
			$passw = $this->input->post('password');	
			
			$result=$datab->checkUser($usern,$passw);
			
			// determine the privilege of the user and if user does not exist
				if($result =="Administrator"){
					$session_data = array('username' => $usern , 'logged_in' => "admin"); 
					$this->session->set_userdata($session_data);
				
					header('location:'.base_url("/index.php/main/viewConsumers"));
				}
				else if($result=="Cashier"){
					$session_data = array( 'username' => $usern , 'logged_in' => "cashier"); 
					$this->session->set_userdata($session_data);
					
					header('location:'.base_url("/index.php/main/viewConsumers"));
				}
				else if($result=="Accounting"){
					$session_data = array( 'username' => $usern , 'logged_in' => "accounting"); 
					$this->session->set_userdata($session_data);

					header('location:'.base_url("/index.php/main/viewConsumers"));
				}
				else if($result=="invalid" || $result=="Plumber" || $result=="Consumer"){
					$this->session->set_flashdata("error", "<div class='alert alert-error'>
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Error!</strong> Invalid Username and Password.</div>");
					header('location:'.base_url());
				}
				else {
					header('location:'.base_url());
				}

				$this->addLogs("ALERT", "System", $this->session->userdata('username')." has Logged In.");
			
		}

//********************************************************************************************************************//
//-----ADMIN METHODS--------------------------------------------------------------------------------------------------//
//********************************************************************************************************************//

//	APPLICATION FORM

		public function applicationForm(){ // view application form page
			
			$this->load->view("application_form");
			$this->session->set_flashdata("info", "");	
		}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//	MANAGE CONSUMERS

		public function viewConsumers(){ // view all the consumers added
			
			$datab= new JWS_model();
			
			$data["consumer_data"]=$datab->getConsumerData();

			if ($this->session->userdata('logged_in') == "admin"){
			
				$this->load->view("consumers", $data);
			}
			else if ($this->session->userdata('logged_in') == "cashier"){
			
				$this->load->view("cashier_consumers", $data);
			}
			else if ($this->session->userdata('logged_in') == "accounting"){
				$this->load->view("acc_consumers", $data);
			}
			else {
				$this->logout();
			}

		}

		public function viewConsumerLedger($consumer_id){ // view the consumer ledger details using consumer id. Views the basic information of consumer
			
			$datab= new JWS_model();
			//consumer details and ledger data are separated into 2 $data arrays.

			$data["consumer_details"]=$datab->getConsumerInfo($consumer_id);

			$data["ledger_data"]=$datab->getConsumerLedgerRecords($consumer_id);

			if ($this->session->userdata('logged_in') == "admin"){
			
				$this->load->view("consumer_ledger", $data);
		
			}
			else if ($this->session->userdata('logged_in') == "cashier"){
			
				$this->load->view("cashier_consumer_ledger", $data);
			}
			else if ($this->session->userdata('logged_in') == "accounting"){
				$this->load->view("acc_consumer_ledger", $data);
			}
			else {
				$this->logout();
			}
			
			$this->session->set_flashdata("bill_info", ""); 
		}

		public function printConsumerLedger($consumer_id){
			
			$datab = new JWS_model();

			$records = $this->input->post('records');

			$month1 = $this->input->post('month1');

			$year1 = $this->input->post('year1');

			$month2 = $this->input->post('month2');

			$year2 = $this->input->post('year2');
			
			$this->load->library('excel'); 
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$objPHPExcel = $objReader->load(FCPATH."./forms/customer_ledger.xls");
			
			$objPHPExcel->setActiveSheetIndex(0);

			$curr_month = date('m');
			$curr_year = date('Y');

			if ($month1 <=$month2 && $year1<=$year2 && $month1<=$curr_month && $month2<=$curr_month && $year1<=$curr_year && $year2<=$curr_year){  

				$data["consumer_details"]=$datab->getConsumerInfo($consumer_id);

				$data2["ledger_data"]=$datab->getConsumerLedgerData($consumer_id, $month1, $year1, $month2, $year2);

				$ctr=1;
				$row_ctr=12;

						foreach ($data as $row){
						
							foreach ($row as $row2){

								$acc_no= substr($row2['date_installed'],0,4).substr($row2['date_installed'],4,4).str_pad($row2['account_no'], 4, '0', STR_PAD_LEFT);
								
								$objPHPExcel->getActiveSheet()->setCellValue('A5', "ACCOUNT NO.: ". substr($row2['date_installed'],0,4).substr($row2['date_installed'],4,4).str_pad($row2['account_no'], 4, '0', STR_PAD_LEFT)); 

								$objPHPExcel->getActiveSheet()->setCellValue('A6', "NAME: ".$row2['lastname'].", ".$row2['firstname']." ".substr($row2['middlename'],0,1).".");

								$objPHPExcel->getActiveSheet()->setCellValue('A7', "ADDRESS: ".$row2['address']);

								$objPHPExcel->getActiveSheet()->setCellValue('J5', "DATE INSTALLED: ".$row2['date_installed']);

								$objPHPExcel->getActiveSheet()->setCellValue('J6', "METER SERIAL NO.: ".$row2["meter_id"]);
									
								$objPHPExcel->getActiveSheet()->setCellValue('J7', "BRAND: ".$row2["meter_brand"]);
								
							}

						}

						foreach ($data2 as $row){
						
							foreach ($row as $row2){

								if ($ctr<=$records || $records == "All Records"){

									 $borderStyleArray = array(
									      'borders' => array(
									          'allborders' => array(
									              'style' => PHPExcel_Style_Border::BORDER_THIN
									          )
									      )
									  );

									$objPHPExcel->getActiveSheet()->setCellValue('A'.$row_ctr, $ctr); 
									$objPHPExcel->getActiveSheet()->getStyle('A'.$row_ctr)->applyFromArray($borderStyleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_ctr, $row2['reading_date']);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_ctr)->applyFromArray($borderStyleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_ctr, $row2['water_usage']); 
									$objPHPExcel->getActiveSheet()->getStyle('C'.$row_ctr)->applyFromArray($borderStyleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_ctr, $row2["penalty"]);
									$objPHPExcel->getActiveSheet()->getStyle('D'.$row_ctr)->applyFromArray($borderStyleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('E'.$row_ctr, $row2["water_fee"]);
									$objPHPExcel->getActiveSheet()->getStyle('E'.$row_ctr)->applyFromArray($borderStyleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('F'.$row_ctr, $row2["mlp"]);
									$objPHPExcel->getActiveSheet()->getStyle('F'.$row_ctr)->applyFromArray($borderStyleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('G'.$row_ctr, $row2["meter_fee"]);
									$objPHPExcel->getActiveSheet()->getStyle('G'.$row_ctr)->applyFromArray($borderStyleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('H'.$row_ctr, $row2["others"]);
									$objPHPExcel->getActiveSheet()->getStyle('H'.$row_ctr)->applyFromArray($borderStyleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('I'.$row_ctr, $row2["total_amount"]);
									$objPHPExcel->getActiveSheet()->getStyle('I'.$row_ctr)->applyFromArray($borderStyleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('J'.$row_ctr, $row2["OR_no"]);
									$objPHPExcel->getActiveSheet()->getStyle('J'.$row_ctr)->applyFromArray($borderStyleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('K'.$row_ctr, $row2["or_date"]);
									$objPHPExcel->getActiveSheet()->getStyle('K'.$row_ctr)->applyFromArray($borderStyleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('L'.$row_ctr, $row2["remarks"]);
									$objPHPExcel->getActiveSheet()->getStyle('L'.$row_ctr)->applyFromArray($borderStyleArray);
									
									$ctr++;
									$row_ctr++;

								}

								
							}

						}

					$filename = $acc_no."_CL.xls";
				    if (ob_get_contents()) ob_end_clean();
				    header( "Content-type: application/vnd.ms-excel" );
				    header('Content-Disposition: attachment;filename='.$filename .' ');
				    header("Pragma: no-cache");
				    header("Expires: 0");
				    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				    $objWriter->save('php://output');
				    if (ob_get_contents()) ob_end_clean();


					$this->addLogs("EXPORT", $this->session->userdata('username'), "Printed the Ledger of Consumer: ".str_pad($consumer_id, 4, '0', STR_PAD_LEFT).".");
				}
				else {

					$this->session->set_flashdata("info", "<div class='alert alert-danger'> 
					              <button class='close' data-dismiss='alert'>×</button>
					              <strong>Error!</strong> The <b>Dates</b> you have entered invalid. The dates may be greater than the current date or the <em>FROM</em> date is greater than the <em>TO</em>.</div>");

					$this->viewConsumerLedger($consumer_id);

				}
				
		}

		public function addConsumer(){ // add consumers to database

				$datab = new JWS_model();

				$fname = $this->input->post('fname');
				$mname = $this->input->post('mname');
				$lname = $this->input->post('lname');
				$bdate = $this->input->post('bdate');
				$address = $this->input->post('address');
				$specific_address = $this->input->post('specific');
				$phonenum = $this->input->post('phonenum');
				$rate = $this->input->post('radios');
				$inspector = $this->input->post('inspector');
				$inspect_date = $this->input->post('inspect_date');
				$meter_num = $this->input->post('meter_num');
				$meter_brand = $this->input->post('meter_brand');
				$meter_size = $this->input->post('meter_size');
				$date_install = $this->input->post('install_date');
				$int_reading = $this->input->post('initial_reading');
				$install_fees = $this->input->post('installation_fees');
				
				if ($mname=="" || $mname==null){
				    $mname=" ";
				}
				
				$meter_fee = $this->input->post('meter_fee');
				$others = $this->input->post('others');
				$paymethod = $this->input->post('radios2');
				$loan_amt = $this->input->post('loan_amt');
				$date_created = date('Y-m-d');
				$remarks = $this->input->post('remarks');

				if (ctype_alpha(str_replace(' ', '', $fname)) && ctype_alpha(str_replace(' ', '', $lname)) && ctype_digit($phonenum) || ctype_alpha(str_replace(' ', '', $mname))){

						$birthyr = substr($bdate,0,4); // gets birth year and current year
						$curryr = date('Y');
						$age = ($curryr - $birthyr); // compute age

						if ($age >= 60){ // checks whether consumer is a senior or not

							$senior = true;
						}
						else $senior = false;

					$consumer_details=array( 
						'meter_id'=>$meter_num,
						'rate_id'=>$rate,
						'firstname'=>$fname,
						'middlename'=>$mname,
						'lastname'=>$lname,
						'birth_date'=>$bdate,
						'address'=>$address,
						'specific_address'=>$specific_address,
						'phonenumber'=>$phonenum,
						'senior_citizen'=>$senior,
						'status'=>"Pending",
						'date_installed'=>$date_install);

					$inspection_details=array( 
						'name'=>$inspector,
						'inspection_date'=>$inspect_date);

					$meter_details=array( 
						'meter_serial_no'=>$meter_num,
						'meter_brand'=>$meter_brand,
						'meter_size'=>$meter_size,
						'initial_reading'=>$int_reading);

					$reading_details=array(
						'meter_reading'=>$int_reading,
						'reading_date'=>$date_install);

					$loan_details=array(
							'loan_type'=>'Material Loan',
							'loan_amt' =>$loan_amt,
							'remarks' => $remarks);

						$total=$install_fees + $others; // computes total amout for installation except for the meter fee

						if ($paymethod == "cash"){ // determines whether the amount will be discounted

							$discount = 10;

							$new_total = $total;

							$ten = round($new_total * ($discount / 100), 2);

							$total = round($new_total - $ten, 2);
			
						} 
						else $discount = 	0;

						$bill_details=array(
							'bill_status'=>"Billed",
							'bill_creator'=>$this->session->userdata('username'),
							'water_usage'=>0,
							'water_fee'=>0,
							'installation_fees'=>$install_fees,
							'meter_fee'=>$meter_fee,
							'penalty'=>0,
							'mlp'=>0,
							'interest'=>0,
							'reconnection_fee'=>0,
							'others'=>$others,
							'total_amount'=>$total,
							'date_created'=>$date_created,
							'date_paid'=>null,
							'discount'=>$discount
						);

						$result_array = $datab->addRecord($inspection_details, $consumer_details, $meter_details, $reading_details, $bill_details, $loan_details);

						if ($result_array['result1'] == "success"){

							$this->session->set_flashdata("info", "<div class='alert alert-success'> 
					              <button class='close' data-dismiss='alert'>×</button>
					              <strong>Success!</strong> Consumer has been added. See <b><a href='". base_url('index.php/main/viewConsumerLedger/'.$result_array['consumer_id'])."' target='_blank'> Consumer.</a></b></div>");
						// success notification for adding consumers

							$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Added Consumer: ".$lname.", ".$fname." ".substr($mname,0,1).".");
						}
						else{

							$this->session->set_flashdata("info", "<div class='alert alert-danger'> 
					              <button class='close' data-dismiss='alert'>×</button>
					              <strong>Error!</strong> Consumer has not been added. Meter is already registered to another consumer.</a></div>");

						}

				}
				else {

					$this->session->set_flashdata("info", "<div class='alert alert-danger'> 
					              <button class='close' data-dismiss='alert'>×</button>
					              <strong>Error!</strong> Consumer has not been added. Please make sure to provide the <b>correct type of data</b> for each input.</a></div>");

				}

				header('location:'.base_url("/index.php/main/applicationForm"));

		}

		public function editConsumerDetails($consumer_id){ // edit consumer details

			$datab = new JWS_model();

			$fname = $this->input->post('fname');
			$mname = $this->input->post('mname');
			$lname = $this->input->post('lname');
			$bdate = $this->input->post('bdate');
			$address = $this->input->post('address');
			$specific_address = $this->input->post('specific');
			$phonenum = $this->input->post('phonenum');
			$status = $this->input->post('radios');
			$rate = $this->input->post('radios2');
			$inspector = $this->input->post('inspector');
			$inspection_date = $this->input->post('inspection_date');
			
			if ($mname=="" || $mname==null){
				    $mname=" ";
			}
			
			$birthyr = substr($bdate,0,4); // gets birth year and current year
			$curryr = date('Y');
			$age = ($curryr - $birthyr); // compute age

			if ($age >= 60){ // checks whether consumer is a senior or not

			$senior = true;
			}
			else $senior = false;

			$consumer_details=array( // places all data into associative array
				'rate_id'=>$rate,
				'firstname'=>$fname,
				'middlename'=>$mname,
				'lastname'=>$lname,
				'birth_date'=>$bdate,
				'address'=>$address,
				'specific_address'=>$specific_address,
				'phonenumber'=>$phonenum,
				'status'=>$status,
				'senior_citizen'=>$senior);

			$inspection_details=array( // places all data into associative array
				'name'=>$inspector,
				'inspection_date'=>$inspection_date);

			$datab->updateConsumerData($consumer_details, $inspection_details, $consumer_id); // passes all arrays to model function

			$this->session->set_flashdata("info", "<div class='alert alert-success'> 
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> Consumer details have been changed.</a></div>");

			header('location:'.base_url("/index.php/main/viewConsumerLedger/".$consumer_id)); // view consumer ledger page

			$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Changed the Consumer Details of Consumer: ".str_pad($consumer_id, 4, '0', STR_PAD_LEFT).".");

		}

		public function updateMeterDetails($meter_id){ 

			$datab = new JWS_model();

			$meter_serial_no = $this->input->post('meter_serial_no');
			$meter_brand= $this->input->post('meter_brand');
			$meter_size= $this->input->post('meter_size');
			$date_installed= $this->input->post('date_installed');

			$initial_reading= $this->input->post('initial_reading');

			$meter_details=array(
				'meter_serial_no'=>$meter_serial_no,
				'meter_brand'=>$meter_brand,
				'meter_size'=>$meter_size,
				'initial_reading'=>$initial_reading);

			$consumer_id = $datab->saveMeterDetails($meter_id, $meter_details, $date_installed);

			$this->session->set_flashdata("info", "<div class='alert alert-success'> 
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> Meter details have been changed.</a></div>");

			header('location:'.base_url("/index.php/main/viewConsumerLedger/".$consumer_id));

			$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Changed the Meter Details of Meter: ".$meter_id.".");

		}

		public function deleteLedger($consumer_id){ // delete consumer

			$datab = new JWS_model();

			$datab->deleteConsumer($consumer_id);

			$this->session->set_flashdata("info", "<div class='alert alert-success'> 
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> Consumer has been deleted.</a></div>");

			header('location:'.base_url("/index.php/main/viewConsumers"));

			$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Deleted Consumer: ".str_pad($consumer_id, 4, '0', STR_PAD_LEFT).".");

		}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//	MANAGE USERS

		public function viewManageUsers(){ // view users

			$datab = new JWS_model();

			$data['users'] = $datab->getUsers();

			if ($this->session->userdata('logged_in') == "admin"){
			
				$this->load->view("manage_users",$data);
			}
			else {
			
				$this->logout();

			}

			$this->session->set_flashdata("info", "");

		}
		
		public function addUser(){

				$datab = new JWS_model();

				$username = $this->input->post('uname');
				$password = $this->input->post('pword');
				$user_type = $this->input->post('acc_type');
				$date = date('Y-m-d');
				$status = "Offline";

				$user_details=array( 
					'username'=>$username,
					'password'=>$password,
					'user_type'=>$user_type,
					'status'=>$status,
					'date_added'=>$date

				);

				$result = $datab->saveUser($user_details);

				if ($result == "success"){

					$this->session->set_flashdata("info", "<div class='alert alert-success'> 
			              <button class='close' data-dismiss='alert'>×</button>
			              <strong>Success!</strong> User has been added.</div>");

						header('location:'.base_url("/index.php/main/viewManageUsers"));

						$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Added User: ".$username.".");

					}
				else {

					$this->session->set_flashdata("info", "<div class='alert alert-danger'> 
			              <button class='close' data-dismiss='alert'>×</button>
			              <strong>Error!</strong> User was not added. <b>Username</b> already exists.</div>");

					header('location:'.base_url("/index.php/main/viewManageUsers"));

				}
			
		}


		public function updateUserDetails($usern){ 

			$datab = new JWS_model();

			$username = $this->input->post('uname');
			$password = $this->input->post('pword');
			$user_type = $this->input->post('acc_type');
			$date_added = $this->input->post('date_added');
			
			if (preg_match('/\s/',$username)==0){
			    
    			    $user_details=array( 
    				'username'=>$username,
    				'password'=>$password,
    				'user_type'=>$user_type,
    				'date_added'=>$date_added
    			);
    			
    			$result = $datab->saveUserDetails($usern, $user_details);
    			
    			if ($result != "error"){
    			    $this->session->set_flashdata("info", "<div class='alert alert-success'> 
    		              <button class='close' data-dismiss='alert'>×</button>
    		              <strong>Success!</strong> User details have been changed.</a></div>");
    		              
    		    $this->addLogs("MODIFICATION", $this->session->userdata('username'), "Changed the User Details of User: 
				".$usern.".");
    			}
    			else {
    			    $this->session->set_flashdata("info", "<div class='alert alert-danger'> 
    		              <button class='close' data-dismiss='alert'>×</button>
    		              <strong>Error!</strong> Username may already exists.</a></div>");
    			}

			    
			}
			else {
			    $this->session->set_flashdata("info", "<div class='alert alert-danger'> 
    		              <button class='close' data-dismiss='alert'>×</button>
    		              <strong>Error!</strong> Make sure username does not have a whitespace.</a></div>");
			}

			header('location:'.base_url("/index.php/main/viewManageUsers"));

		}

		public function deleteUser($usern){ // view users

			$datab = new JWS_model();

			$feedback = $datab->deleteUserDetails($usern);
			
			if ($feedback != "error"){
			    
			    $this->session->set_flashdata("info", "<div class='alert alert-success'> 
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> User has been deleted.</a></div>");
		              
		      $this->addLogs("MODIFICATION", $this->session->userdata('username'), "Deleted User: ".$usern.".");
			}
			else {
			    $this->session->set_flashdata("info", "<div class='alert alert-danger'> 
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Error!</strong> User was not deleted. User is currently <b>Online</b>.</a></div>");
			}

			header('location:'.base_url("/index.php/main/viewManageUsers"));

		}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//	MANAGE BILLS

		public function viewBilledConsumers(){

			$datab = new JWS_model();

			$data['billed_consumers']=$datab->getBilledConsumers();
			
			$data['consumer_list']=$datab->getConsumerList();
			
			if ($this->session->userdata('logged_in') == "admin"){
			
				$this->load->view("billed_consumers", $data);
		
			}
			else if ($this->session->userdata('logged_in') == "cashier"){
			
				$this->load->view("cashier_billed_consumers", $data);
			}
			else if ($this->session->userdata('logged_in') == "accounting"){
				$this->load->view("acc_billed_consumers", $data);
			}
			else {

				$this->logout();

			}

			$this->session->set_flashdata("info", "");
			
		}

		public function viewBill($bill_id){ // view the bill of consumer using the bill id

			$datab = new JWS_model();

			$data['bill_details']=$datab->getBillDetails($bill_id);// water bill details
			
			$feedback=$datab->getMsgStatus($bill_id);
			
			if ($feedback=="true"){
			    $this->session->set_flashdata("info", "<div class='alert alert-info'> 
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Info!</strong> An SMS Notification for this bill has already been sent.</a></div>");
			}
		
			if ($this->session->userdata('logged_in') == "admin"){
			
				$this->load->view("consumer_bills", $data);
		
			}
			else if ($this->session->userdata('logged_in') == "cashier"){
			
				$this->load->view("cashier_consumer_bills", $data);
			}
			else if ($this->session->userdata('logged_in') == "accounting"){

				$this->load->view("acc_consumer_bills", $data);

			}
			else {

				$this->logout();
				
			}

			$this->session->set_flashdata("info", "");
			
		}

		public function printBill($bill_id){

			$datab = new JWS_model();
			
			$this->load->library('excel'); 
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$objPHPExcel = $objReader->load(FCPATH."./forms/bill_template.xls");
			
			$objPHPExcel->setActiveSheetIndex(0);  

			$data["bill_data"] = $datab->getBillDetails($bill_id);

			foreach ($data as $row){
			
				foreach ($row as $row2){

					$date = strtotime($row2['date_created']); 
                  	$bill_month = date("F", strtotime('-0 months', $date));

                  	$date2 = strtotime($row2['due_date']); 
                  	$due_date = date("d-M-Y", strtotime('-0 months', $date2));

					$acc_no= substr($row2['date_installed'],0,4).substr($row2['date_installed'],4,4).str_pad($row2['account_no'], 4, '0', STR_PAD_LEFT);
					
					$objPHPExcel->getActiveSheet()->setCellValue('A9', $row2["curr_reading"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('B9', $row2["prev_reading"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D9', $row2["water_usage"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E9', $row2["water_fee"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('E11', $row2["meter_fee"]); 
					$objPHPExcel->getActiveSheet()->setCellValue('E12', $row2["penalty"]);   
					$objPHPExcel->getActiveSheet()->setCellValue('E13', $row2["mlp"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E14', $row2["interest"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E15', $row2["reconnection_fee"]);
					$objPHPExcel->getActiveSheet()->setCellValue('E16', $row2["others"]);
					$objPHPExcel->getActiveSheet()->setCellValue('A18', "AMOUNT DUE: ".$row2["total_amount"]);
					$objPHPExcel->getActiveSheet()->setCellValue('D20', $bill_month);
					
					if ($row2['bill_status'] == "For Disconnection"){
					    
					    $redfontArray = array(
										    'font'  => array(
									        'bold'  => true,
									        'color' => array('rgb' => 'FF0000'),
									        'size'  => 12,
									        'name'  => 'Arial'
											)
									);
									
                        $objPHPExcel->getActiveSheet()->setCellValue('B21', "FOR DISCONNECTION");
                        $objPHPExcel->getActiveSheet()->getStyle('B21')->applyFromArray($redfontArray);
					}
					
					$objPHPExcel->getActiveSheet()->setCellValue('D23', substr($row2['date_installed'],0,4).substr($row2['date_installed'],4,4).str_pad($row2['account_no'], 4, '0', STR_PAD_LEFT));
					$objPHPExcel->getActiveSheet()->setCellValue('D24', $row2['meter_id']);
					$objPHPExcel->getActiveSheet()->setCellValue('B27', $row2['lastname'].", ".$row2['firstname']." ".substr($row2['middlename'],0,1).".");
					$objPHPExcel->getActiveSheet()->setCellValue('C28', $row2['address']);
					$objPHPExcel->getActiveSheet()->setCellValue('E30', $due_date);
					$objPHPExcel->getActiveSheet()->setCellValue('A36', $row2['bill_creator']);
					$objPHPExcel->getActiveSheet()->setCellValue('E36', $row2['date_created']);
				}

			}

			$filename = $acc_no."_WB.xls";
		    if (ob_get_contents()) ob_end_clean();
		    header( "Content-type: application/vnd.ms-excel" );
		    header('Content-Disposition: attachment;filename='.$filename .' ');
		    header("Pragma: no-cache");
		    header("Expires: 0");
		    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		    $objWriter->save('php://output');
		    if (ob_get_contents()) ob_end_clean();

		    $this->addLogs("EXPORT", $this->session->userdata('username'), "Printed Water Bill: "."WB-".str_pad($bill_id, 4, '0', STR_PAD_LEFT).".");
		}	

		public function addBill(){ // adds water bill using consumer account no

			$datab = new JWS_model();

			$account_no = substr($this->input->post('consumer_id'), 8, 11);

			$account_no = round($account_no);

			$curr_reading=$this->input->post('curr_reading');
			$penalty=$this->input->post('penalty');			
			$reconnection_fee=$this->input->post('reconnection_fee');
			$others=$this->input->post('others');
			$reading_date=$this->input->post('reading_date');
			$date_created=date('Y-m-d');

			$bill_details=array(
				'bill_status'=>"Billed",
				'bill_creator'=>$this->session->userdata('username'),
				'installation_fees'=>0,
				'penalty'=>$penalty,
				'reconnection_fee'=>$reconnection_fee,
				'others'=>$others,
				'date_created'=>$date_created,
				'date_paid'=>null);

			$bill_id = $datab->addConsumerBill($bill_details, $reading_date, $curr_reading, $account_no);

			$consumer_id = $this->input->post('consumer_id');

			$this->session->set_flashdata("info", "<div class='alert alert-success'>
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> Bill has been created for Consumer: <b>".$consumer_id."</b>.  <b>See <a href='". base_url('index.php/main/viewBill/'.$bill_id)."' target='_blank' >Water Bill</a></b>.</div>"); 

			header('location:'.base_url("/index.php/main/viewBilledConsumers/"));

			$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Water Bill Created for Consumer: ".substr($this->input->post('consumer_id'), 0, 11).".");
		}

		public function updateBill($bill_id){ 

			$datab = new JWS_model();

			$curr_reading = $this->input->post('curr_reading');
			$prev_reading= $this->input->post('prev_reading');
			$install_fees= $this->input->post('install_fees');
			$meter_fee= $this->input->post('meter_fee');
			$penalty= $this->input->post('penalty');
			$mlp= $this->input->post('mlp');
			$interest= $this->input->post('interest');
			$recon_fee= $this->input->post('recon_fee');
			$others= $this->input->post('others');
			$discount= $this->input->post('discount');
			$bill_status= $this->input->post('radios');
			$prepared_by= $this->input->post('prepared_by');
			$date_created= $this->input->post('date_created');
			$due_date= $this->input->post('due_date');
			$date_paid= $this->input->post('date_paid');

			if ($bill_status == "Paid" && $date_paid == '0000-00-00'){
				$date_paid = date('Y-m-d');
			}

			$bill_details=array(
				'curr_reading'=>$curr_reading,
				'prev_reading'=>$prev_reading,
				'install_fees'=>$install_fees,
				'meter_fee'=>$meter_fee,
				'penalty'=>$penalty,
				'mlp'=>$mlp,
				'interest'=>$interest,
				'reconnection_fee'=>$recon_fee,
				'others'=>$others,
				'discount'=>$discount,
				'bill_status'=>$bill_status,
				'prepared_by'=>$prepared_by,
				'date_created'=>$date_created,
				'due_date'=>$due_date,
				'date_paid'=>$date_paid
			);

			$feedback = $datab->saveBillDetails($bill_id, $bill_details);

			if ($feedback != "or_error"){

				$this->session->set_flashdata("info", "<div class='alert alert-success'> 
			              <button class='close' data-dismiss='alert'>×</button>
			              <strong>Success!</strong> Bill details have been changed.</div>");

				$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Changed Bill Details for Water Bill: "."WB-".str_pad($bill_id, 4, '0', STR_PAD_LEFT).".");
			}
			else {
				$this->session->set_flashdata("info", "<div class='alert alert-danger'> 
			              <button class='close' data-dismiss='alert'>×</button>
			              <strong>Error!</strong> Bill details have not been updated. <b>OR No</b> already exists.</div>");
			}

				header('location:'.base_url("/index.php/main/viewBill/".$bill_id));

		}
	
		public function deleteBill($bill_id){ // creates invoice for bill using bill id

			$datab = new JWS_model();

			$datab->deleteBillDetails($bill_id);

			$this->session->set_flashdata("info", "<div class='alert alert-success'> 
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> Bill has been deleted.</a></div>");

			header('location:'.base_url("/index.php/main/viewBilledConsumers"));

			$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Deleted Water Bill: "."WB-".str_pad($bill_id, 4, '0', STR_PAD_LEFT).".");

		}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// MANAGE INVOICE


		public function viewInvoiceDetails($bill_id){

			$datab = new JWS_model();

			$data['invoice_details'] = $datab->getInvoiceDetails($bill_id);

			if ($this->session->userdata('logged_in') == "admin"){
			
				$this->load->view("invoice_details", $data);
		
			}
			else if ($this->session->userdata('logged_in') == "cashier"){

				$this->load->view("cashier_invoice_details", $data);

			}
			else if ($this->session->userdata('logged_in') == "accounting"){

				$this->load->view("acc_invoice_details", $data);

			}
			else {

				$this->logout();
				
			}

			$this->session->set_flashdata("info", "");
			$this->session->set_flashdata("msg_info", "");
			
		}

		public function addInvoice($bill_id){ // creates invoice for bill using bill id

			$datab = new JWS_model();

			$or_no = $this->input->post('or_no');
			$remarks = $this->input->post('remarks');

			if ($remarks == ""){
				$remarks = "none";
			}

			$feedback = $datab->addBillInvoice($bill_id, $or_no, $remarks);

			if ($feedback !="or_error"){
			    
			    $fb=$this->sendSMS2($bill_id);
			    
			    if ($fb=="sent"){
			         $this->session->set_flashdata("info", "<div class='alert alert-success'> 
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> Invoice has been created.</a></div>");

			    	$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Added Invoice for Water Bill: "."WB-".str_pad($bill_id, 4, '0', STR_PAD_LEFT).".");
			    }
		
				$this->viewInvoiceDetails($bill_id);
				
			}
			else {

				$this->session->set_flashdata("info", "<div class='alert alert-danger'> 
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Error!</strong> Invoice has not been created. <b>OR Number</b> already exists.</a></div>");

				$this->viewBill($bill_id);
			}

		}

		public function updateInvoice($bill_id){ 

			$datab = new JWS_model();

			$or_no = $this->input->post('or_no');
			$date = $this->input->post('or_date');
			$prepared_by = $this->input->post('prepared_by');
			$remarks = $this->input->post('remarks');

			if ($remarks == ""){
				$remarks = "none";
			}

			$invoice_details = array(
				"or_no" => $or_no,
				"date" => $date,
				"prepared_by" => $prepared_by,
				"remarks" => $remarks
			);

			$feedback = $datab->saveInvoice($bill_id, $invoice_details);
			
			if ($feedback !="or_error"){
			    
			    $this->session->set_flashdata("info", "<div class='alert alert-success'> 
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> Invoice details have been changed.</a></div>");
    
    			$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Changed Invoice Details for Water Bill: "."WB-".str_pad($bill_id, 4, '0', STR_PAD_LEFT).".");
			
			}
			else {
			    
			    $this->session->set_flashdata("info", "<div class='alert alert-danger'> 
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Error!</strong> Invoice has not been updated. <b>OR Number</b> already exists.</a></div>");
			    
			}
			
			header('location:'.base_url("/index.php/main/viewInvoiceDetails/".$bill_id));

		}

		public function deleteInvoice($or_no){

			$datab = new JWS_model();

			$bill_id = $datab->deleteInvoiceDetails($or_no);
			
			$this->session->set_flashdata("info", "<div class='alert alert-success'> 
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> Invoice has been deleted.</a></div>");

			header('location:'.base_url("/index.php/main/viewBill/".$bill_id));

			$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Deleted Invoice for Water Bill: "."WB-".str_pad($bill_id, 4, '0', STR_PAD_LEFT).".");
			
		}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// MANAGE LOANS

		public function viewConsumerLoan(){

			$datab = new JWS_model();

			$result_array = $datab->getLoan();

			$data["loan"] = $result_array['result1'];
			$data["consumers"] = $result_array['result2'];

			if ($this->session->userdata('logged_in') == "admin"){
			
				$this->load->view("consumer_loan", $data);
		
			}
			else if ($this->session->userdata('logged_in') == "cashier"){
			
				$this->load->view("cashier_consumer_loan", $data);

			}
			else if ($this->session->userdata('logged_in') == "accounting"){

				$this->load->view("acc_consumer_loan", $data);

			}
			else {
				$this->logout();
			}

			$this->session->set_flashdata("info", "");
			$this->session->set_flashdata("error", "");			
			
		}

			public function viewLoanDetails($loan_id){

			$datab = new JWS_model();

			$data['loan_details'] = $datab->getLoanDetails($loan_id);		

			if ($this->session->userdata('logged_in') == "admin"){
			
				$this->load->view("consumer_loan_details", $data);
		
			}
			else if ($this->session->userdata('logged_in') == "cashier"){
			
				$this->load->view("cashier_consumer_loan_details", $data);
			}
			else if ($this->session->userdata('logged_in') == "accounting"){

				$this->load->view("acc_consumer_loan_details", $data);

			}
			else {
				$this->logout();
			}

			$this->session->set_flashdata("info", "");	
			
		}

		public function addLoan(){

			$datab = new JWS_model();

			$account_no = substr($this->input->post('consumer_id'), 8, 11);

			$account_no = round($account_no);

			$loan_amt1 =$this->input->post('loan_amt1');			
			$loan_amt2 =$this->input->post('loan_amt2');
			$remarks =$this->input->post('remarks');

			$loan1 = "none";
			$loan2 = "none";

			if (empty($this->input->post('loan'))){

				$this->session->set_flashdata("error", "<div class='alert alert-danger'>
			              <button class='close' data-dismiss='alert'>×</button>
			              <strong>Error!</strong> Please select a <b>Loan Type</b> before proceeding.</div>");

				header('location:'.base_url("/index.php/main/viewConsumerLoan/"));

				

			}

			foreach ($this->input->post('loan') as $loan){

				if ($loan == "Material Loan"){

					$loan1 = "Material Loan";

					if ($loan_amt1 == 0){

						$this->session->set_flashdata("error", "<div class='alert alert-danger'>
			              <button class='close' data-dismiss='alert'>×</button>
			              <strong>Error!</strong> Please enter <b>Loan Amount</b> before proceeding.</div>");

						$loan1 = "none";

						header('location:'.base_url("/index.php/main/viewConsumerLoan/"));

					}

				}

				else if ($loan == "Meter Fee"){

					$loan2 = "Meter Fee";

					if ($loan_amt2 == 0){

						$this->session->set_flashdata("error", "<div class='alert alert-danger'>
			              <button class='close' data-dismiss='alert'>×</button>
			              <strong>Error!</strong> Please enter Loan Amount before proceeding.</div>");

						$loan2 = "none";

						header('location:'.base_url("/index.php/main/viewConsumerLoan/"));
						

					}

				}

			}

			if ($remarks == ""){
				$remarks = "none";
			}

			$feedback = $datab->addConsumerLoan($account_no, $loan1, $loan2, $loan_amt1, $loan_amt2, $remarks);

			$consumer_id = $this->input->post('consumer_id');

			if ($feedback['feedback1'] == "success" || $feedback['feedback2'] == "success"){

			 	$this->session->set_flashdata("info", "<div class='alert alert-success'>
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> Loan has been created for Consumer: <b>".$consumer_id."</b>.</div>");

			 	$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Added Loan for Consumer: ".substr($this->input->post('consumer_id'), 0, 11).".");
			}

			else if ($feedback['feedback1'] == "materialLoan_error" && $feedback['feedback2'] == "meterLoan_error"){

				 $this->session->set_flashdata("error", "<div class='alert alert-danger'>
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Error!</strong> Loans have not been added for Consumer: <b>".$consumer_id."</b>. Consumer currently has <b>ACTIVE</b> Loans</div>");
			}

			else if ($feedback['feedback1'] == "materialLoan_error" ){

				if ($feedback['feedback2'] == "success"){
					$this->session->set_flashdata("info", "<div class='alert alert-success'>
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> Meter Fee Loan has been created for Consumer: <b>".$consumer_id."</b>.</div>");

					$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Added Loan for Consumer: ".substr($this->input->post('consumer_id'), 0, 11).".");
				}

				$this->session->set_flashdata("error", "<div class='alert alert-danger'>
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Error!</strong> Material Loan has not been added for Consumer: <b>".$consumer_id."</b>. Consumer currently has an <b>ACTIVE</b> Material Loan</div>");

			}

			else if ($feedback['feedback2'] == "meterLoan_error" ){

				if ($feedback['feedback1'] == "success"){
					$this->session->set_flashdata("info", "<div class='alert alert-success'>
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> Material Loan has been created for Consumer: <b>".$consumer_id."</b>.</div>");

					$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Added Loan for Consumer: ".substr($this->input->post('consumer_id'), 0, 11).".");
				}

				$this->session->set_flashdata("error", "<div class='alert alert-danger'>
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Error!</strong> Meter Fee Loan has not been added for Consumer: <b>".$consumer_id."</b>. Consumer currently has an <b>ACTIVE</b> Meter Fee Loan</div>");

			}


			header('location:'.base_url("/index.php/main/viewConsumerLoan/"));
		}

		public function updateLoan($loan_id){ 

			$datab = new JWS_model();

			$loan_type = $this->input->post('radios');
			$loan_amt = $this->input->post('loan_amt');
			$amt_paid = $this->input->post('amt_paid');
			$loan_status = $this->input->post('radios1');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			$remarks = $this->input->post('remarks');

			if ($remarks == ""){
				$remarks = "none";
			}

			$loan_details = array(
				"loan_type" => $loan_type,
				"loan_amt" => $loan_amt,
				"amt_paid" => $amt_paid,
				"loan_status" => $loan_status,
				"start_date" => $start_date,
				"end_date" => $end_date,
				"remarks" => $remarks
			);

			$datab->saveLoan($loan_id, $loan_details);

			$this->session->set_flashdata("info", "<div class='alert alert-success'> 
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> Loan details have been changed.</a></div>");

			header('location:'.base_url("/index.php/main/viewLoanDetails/".$loan_id));

			$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Changed Loan Details for Loan: LOAN-".str_pad($loan_id, 4, '0', STR_PAD_LEFT).".");

		}

		public function deleteLoan($loan_id){
			
			$datab = new JWS_model();

			$datab->deleteConsumerLoan($loan_id);

			$this->session->set_flashdata("info", "<div class='alert alert-success'>
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> Loan has been deleted.</a></div>");

			header('location:'.base_url("/index.php/main/viewConsumerLoan/"));

			$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Deleted Loan: LOAN-".str_pad($loan_id, 4, '0', STR_PAD_LEFT).".");
			
		}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// BILLING SETTINGS

		Public function BillingSettings(){

			$datab = new JWS_model();

			$data['rates'] = $datab->getRates();

			$data['schedule'] = $datab->getSched();

			$data['reading_status'] = $datab->getReadingStatus();
			
			if ($this->session->userdata('logged_in') != ""){
			
				$this->load->view("billing_settings", $data);

			}
			else{
			
				$this->logout();
			}

			$this->session->set_flashdata("info", "");		
			
		}

		public function updateRates($connection_type){ 

			$datab = new JWS_model();

			$first10cu = $this->input->post('first10cu');
			$_11to20cu = $this->input->post('_11to20cu');
			$_21to30cu = $this->input->post('_21to30cu');
			$_31abovecu = $this->input->post('_31abovecu');

			$rate_details=array( 
				'first10cu'=>$first10cu,
				'_11to20cu'=>$_11to20cu,
				'_21to30cu'=>$_21to30cu,
				'_31abovecu'=>$_31abovecu);
			
			$datab->saveRateDetails($connection_type, $rate_details);

			$this->session->set_flashdata("info", "<div class='alert alert-success'> 
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> Rate has been changed.</a></div>");

			header('location:'.base_url("/index.php/main/BillingSettings"));

			$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Changed Rates for ".$connection_type.".");

		}

		public function updateSched($sched_id){ 

			$datab = new JWS_model();

			$reading = $this->input->post('reading_day');
			$distribution= $this->input->post('distribution');
			$grace_period= $this->input->post('grace_period');
			$penalty= $this->input->post('with_penalty');
			$disconnection= $this->input->post('disconnection');

			$sched_details=array(
				'reading'=>$reading,
				'distribution'=>$distribution,
				'grace_period'=>$grace_period,
				'penalty'=>$penalty,
				'disconnection'=>$disconnection
			);

			$datab->saveSched($sched_id, $sched_details);

			$this->session->set_flashdata("info", "<div class='alert alert-success'> 
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> Billing Schedule has been changed.</a></div>");

			header('location:'.base_url("/index.php/main/BillingSettings/"));

			$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Changed Billing Schedules.");


		}

		public function updateReadingStatus($brgy){ 

			$datab = new JWS_model();

			$barangay = str_replace("-"," ", $brgy);

			$datab->saveReadingStatus($barangay);

			$this->session->set_flashdata("info", "<div class='alert alert-success'> 
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> Reading Status has been changed.</a></div>");

			header('location:'.base_url("/index.php/main/BillingSettings/"));

			$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Changed Reading Status for Barangay: ".$barangay.".");


		}



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// MANAGE WATER METERS


		public function viewWaterMeters(){ // view users

			$datab = new JWS_model();

			$data['meter_details'] = $datab->getMeters();			

			if ($this->session->userdata('logged_in') == "admin"){
			
				$this->load->view("water_meters",$data);
				
			}
			else if ($this->session->userdata('logged_in') == "accounting"){
			
				$this->load->view("acc_water_meters",$data);
				
			}
			else{
			
				$this->logout();

			}

			$this->session->set_flashdata("info", "");

		}

		public function updateMeterDetails2($meter_id){ 

			$datab = new JWS_model();

			$meter_serial_no = $this->input->post('meter_serial_no');
			$meter_brand= $this->input->post('meter_brand');
			$meter_size= $this->input->post('meter_size');
			$date_installed= $this->input->post('date_installed');

			$initial_reading= $this->input->post('initial_reading');

			$meter_details=array(
				'meter_serial_no'=>$meter_serial_no,
				'meter_brand'=>$meter_brand,
				'meter_size'=>$meter_size,
				'initial_reading'=>$initial_reading);

			$consumer_id = $datab->saveMeterDetails($meter_id, $meter_details, $date_installed);


			$this->session->set_flashdata("info", "<div class='alert alert-success'> 
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> Meter details have been changed.</a></div>");

			header('location:'.base_url("/index.php/main/viewWaterMeters"));

			$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Changed Meter Details for Water Meter: ".$meter_id.".");

		}

		public function deleteMeter($meter_id){
			
			$datab = new JWS_model();

			$result_array = $datab->deleteWaterMeter($meter_id);

			if ($result_array['feedback'] == "deleted"){

				$this->session->set_flashdata("info", "<div class='alert alert-success'>
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Success!</strong> Water Meter has been deleted.</a></div>");

				$this->addLogs("MODIFICATION", $this->session->userdata('username'), "Deleted Water Meter: ".$meter_id.".");
			}
			else {
				$this->session->set_flashdata("info", "<div class='alert alert-danger'>
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Error!</strong> Water Meter has not been deleted. Water Meter: <b>".$result_array['meter_id']."</b> is currently registered to Consumer: <b>".substr($result_array['date_installed'],0,4).substr($result_array['date_installed'],4,4).str_pad($result_array['consumer_id'], 4, '0', STR_PAD_LEFT)."</b>.</a></div>");
			}

			header('location:'.base_url("/index.php/main/viewWaterMeters/"));
			
		}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// MANAGE METER READINGS

		public function printReadingSheet(){

			$datab = new JWS_model();

			$barangay = $this->input->post('address');

			$month = $this->input->post('month');

			$year = $this->input->post('year');

			$records = "All Records";
			
			$this->load->library('excel'); 
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$objPHPExcel = $objReader->load(FCPATH."./forms/reading_sheet.xls");
			
			$objPHPExcel->setActiveSheetIndex(0);  

			$data["reading_data"] = $datab->getReadingData($barangay, $month, $year);

			$selected_month = date('F', strtotime("2018-$month-01"));

			$curr_month = date('m');
			$curr_year = date('Y');

			$ctr=1;
			$row_ctr=7;

				if ($month <= $curr_month && $year <= $curr_year){

					foreach ($data as $row){
					
						foreach ($row as $row2){

							if ($ctr<=$records || $records == "All Records"){

								 $borderStyleArray = array(
								      'borders' => array(
								          'allborders' => array(
								              'style' => PHPExcel_Style_Border::BORDER_THIN
								          )
								      )
								  );

								$objPHPExcel->getActiveSheet()->setCellValue('A4', "Barangay: ".$row2["address"]); 
								$objPHPExcel->getActiveSheet()->setCellValue('D4', "Month: ".$selected_month." ".$year);

								$objPHPExcel->getActiveSheet()->setCellValue('A'.$row_ctr, $ctr);
								$objPHPExcel->getActiveSheet()->getStyle('A'.$row_ctr)->applyFromArray($borderStyleArray);

								$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_ctr, substr($row2['date_installed'],0,4).substr($row2['date_installed'],4,4).str_pad($row2['account_no'], 4, '0', STR_PAD_LEFT)); 
								$objPHPExcel->getActiveSheet()->getStyle('B'.$row_ctr)->applyFromArray($borderStyleArray);

								if ($row2['bill_status'] == "For Disconnection"){

									$styleArray = array(
										    'font'  => array(
									        'bold'  => false,
									        'color' => array('rgb' => 'FF0000'),
									        'size'  => 11,
									        'name'  => 'Calibri'
											),
											'borders' => array(
								          	'allborders' => array(
								              'style' => PHPExcel_Style_Border::BORDER_THIN
								          	)
								    		  )
									);

									$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_ctr, $row2['lastname'].", ".$row2['firstname']." ".substr($row2['middlename'],0,1).".");
									$objPHPExcel->getActiveSheet()->getStyle('C'.$row_ctr)->applyFromArray($styleArray);
								}
								else {

									$styleArray = array(
										    'font'  => array(
										    'bold'  => false,
									        'color' => array('rgb' => '000000'),
									        'size'  => 11,
										    'name'  => 'Calibri'
											),
											'borders' => array(
								          	'allborders' => array(
								              'style' => PHPExcel_Style_Border::BORDER_THIN
								          	)
								    		  )

									);

									$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_ctr, $row2['lastname'].", ".$row2['firstname']." ".substr($row2['middlename'],0,1).".");
									$objPHPExcel->getActiveSheet()->getStyle('C'.$row_ctr)->applyFromArray($styleArray);

								}

								$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_ctr, $row2["meter_id"]);
								$objPHPExcel->getActiveSheet()->getStyle('D'.$row_ctr)->applyFromArray($borderStyleArray);


								$objPHPExcel->getActiveSheet()->setCellValue('E'.$row_ctr, $row2["meter_reading"]);
								$objPHPExcel->getActiveSheet()->getStyle('E'.$row_ctr)->applyFromArray($borderStyleArray);

								$objPHPExcel->getActiveSheet()->getStyle('F'.$row_ctr)->applyFromArray($borderStyleArray);
								$objPHPExcel->getActiveSheet()->getStyle('G'.$row_ctr)->applyFromArray($borderStyleArray);
								$objPHPExcel->getActiveSheet()->getStyle('H'.$row_ctr)->applyFromArray($borderStyleArray);
								$ctr++;
								$row_ctr++;

							}

							
						}

					}

				$filename = $barangay."-".$selected_month."-".$year."_RS.xls";
			    if (ob_get_contents()) ob_end_clean();
			    header( "Content-type: application/vnd.ms-excel" );
			    header('Content-Disposition: attachment;filename='.$filename .' ');
			    header("Pragma: no-cache");
			    header("Expires: 0");
			    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			    $objWriter->save('php://output');
			    if (ob_get_contents()) ob_end_clean();

			    $this->addLogs("EXPORT", $this->session->userdata('username'), "Printed Reading Sheet for Barangay: ".$barangay.".");
			}
			else {

				$this->session->set_flashdata("info", "<div class='alert alert-danger'>
		              <button class='close' data-dismiss='alert'>×</button>
		              <strong>Error!</strong> The <b>Month</b> or <b>Year</b> you entered is greater than the current date.</div>");

				header('location:'.base_url("/index.php/main/viewWaterReadings/"));
			}
		}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// TABULAR SUMMARY REPORTS		

		public function viewTableSummary(){
		    
		    $datab = new JWS_model();
		    
		    $curr_m = date('m');
            $curr_y = date('Y');
            
            $full_date = date('Y-F');
            
            $datab->updateSummary($curr_m,$curr_y);
		    
		    $data['report_data']=$datab->getReportData($curr_m, $curr_y);
		    
		    $type="summary";
			
			$data["date_count"]=$datab->getDateCount($type);
			
		    
		    $this->session->set_flashdata("date", $full_date);

			if ($this->session->userdata('logged_in') == "admin"){
			
				$this->load->view("summary_reports", $data);
				
			}
			else{
			
				$this->logout();

			}
				
		}
		
		public function getTableSummary(){
		    
		    $datab = new JWS_model();
		    
		   $period = $this->input->post("period");
			
			$year = substr($period,0,4);
			
			$month = substr($period,7);
			
			$period = str_replace(" ","",$period);
			
            if ($month=="January"){
                $month=1;
            }
            else if ($month=="February"){
                 $month=2;
            }
            else if ($month=="March"){
                 $month=3;
            }
            else if ($month=="April"){
                $month=4;
            }
            else if ($month=="May"){
                $month=5;
            }
            else if ($month=="June"){
                $month=6;
            }
            else if ($month=="July"){
                $month=7;
            }
            else if ($month=="August"){
              $month=8;
            }
            else if ($month=="September"){
                $month=9;
            }
            else if ($month=="October"){
                $month=10;
            }
            else if ($month=="November"){
              $month=11;
            }
            else if ($month=="December"){
                $month=12;
            }
		    
		    $datab->updateSummary($month,$year);
		    
		    $data['report_data']=$datab->getReportData($month, $year);
		    
		    $type="summary";
			
			$data["date_count"]=$datab->getDateCount($type);
			
		    
		    $this->session->set_flashdata("date", $period);

			if ($this->session->userdata('logged_in') == "admin"){
			
				$this->load->view("summary_reports", $data);
				
			}
			else{
			
				$this->logout();

			}
				
		}
		
		public function printSummary($date){
			
			$datab = new JWS_model();
			
			$year = substr($date,0,4);
			
			$month = substr($date,5);
			
			$full_mon = $month;
			
            if ($month=="January"){
                $month=1;
            }
            else if ($month=="February"){
                 $month=2;
            }
            else if ($month=="March"){
                 $month=3;
            }
            else if ($month=="April"){
                $month=4;
            }
            else if ($month=="May"){
                $month=5;
            }
            else if ($month=="June"){
                $month=6;
            }
            else if ($month=="July"){
                $month=7;
            }
            else if ($month=="August"){
              $month=8;
            }
            else if ($month=="September"){
                $month=9;
            }
            else if ($month=="October"){
                $month=10;
            }
            else if ($month=="November"){
              $month=11;
            }
            else if ($month=="December"){
                $month=12;
            }
            
            $datab->updateSummary($month,$year);
			
			$this->load->library('excel'); 
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$objPHPExcel = $objReader->load(FCPATH."./forms/summary_form.xls");
			
			$objPHPExcel->setActiveSheetIndex(0);

				$data["summary"]=$datab->getReportData($month, $year);
				
				$row_ctr=8;
				
				$curr_date = date('Y-m-d | H:i');
				
				$tc=0;
				$ac=0;
				$cu=0;
				$cb1=0;
				$wd=0;
				$ad=0;
				$t=0;
				
				                     $borderStyleArray = array(
									      'borders' => array(
									          'allborders' => array(
									              'style' => PHPExcel_Style_Border::BORDER_THIN
									          )
									      )
									  );

									 $styleArray = array(
										    'font'  => array(
									        'bold'  => false,
									        'color' => array('rgb' => '000000'),
									        'size'  => 11,
									        'name'  => 'Calibri'
											)
									);
									
									$styleArray2 = array(
										    'font'  => array(
									        'bold'  => true,
									        'color' => array('rgb' => '000000'),
									        'size'  => 11,
									        'name'  => 'Calibri'
											)
									);

					foreach ($data as $row){
						
							foreach ($row as $row2){

									$objPHPExcel->getActiveSheet()->setCellValue('A2', "Filename: ".$full_mon."-".$year."_SUMMARY.xls"); 
									$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('H2', $curr_date);
									$objPHPExcel->getActiveSheet()->getStyle('H2')->applyFromArray($styleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('A6', "Report Period: ".$date);
									$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);

								    $objPHPExcel->getActiveSheet()->setCellValue('A'.$row_ctr, $row2['barangay']);
									$objPHPExcel->getActiveSheet()->getStyle('A'.$row_ctr)->applyFromArray($borderStyleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_ctr, $row2['total_consumers']);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_ctr)->applyFromArray($borderStyleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_ctr, $row2['active_consumers']);
									$objPHPExcel->getActiveSheet()->getStyle('C'.$row_ctr)->applyFromArray($borderStyleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_ctr, $row2['cu_used']);
									$objPHPExcel->getActiveSheet()->getStyle('D'.$row_ctr)->applyFromArray($borderStyleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('E'.$row_ctr, $row2['percent_cu']."%");
									$objPHPExcel->getActiveSheet()->getStyle('E'.$row_ctr)->applyFromArray($borderStyleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('F'.$row_ctr, $row2['collectible']);
									$objPHPExcel->getActiveSheet()->getStyle('F'.$row_ctr)->applyFromArray($borderStyleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('G'.$row_ctr, $row2['percent_collectible']."%");
									$objPHPExcel->getActiveSheet()->getStyle('G'.$row_ctr)->applyFromArray($borderStyleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('H'.$row_ctr, $row2['within_due']);
									$objPHPExcel->getActiveSheet()->getStyle('H'.$row_ctr)->applyFromArray($borderStyleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('I'.$row_ctr, $row2['after_due']);
									$objPHPExcel->getActiveSheet()->getStyle('I'.$row_ctr)->applyFromArray($borderStyleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('J'.$row_ctr, $row2['total']);
									$objPHPExcel->getActiveSheet()->getStyle('J'.$row_ctr)->applyFromArray($borderStyleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('K'.$row_ctr, $row2['percent_collected']."%");
									$objPHPExcel->getActiveSheet()->getStyle('K'.$row_ctr)->applyFromArray($borderStyleArray);

									$row_ctr++;
									
									$tc+=$row2['total_consumers'];
                    				$ac+=$row2['active_consumers'];
                    				$cu+=$row2['cu_used'];
                    				$cb1+=$row2['collectible'];
                    				$wd+=$row2['within_due'];
                    				$ad+=$row2['after_due'];
                    				$t+=$row2['total'];

							}
							
						        	$objPHPExcel->getActiveSheet()->setCellValue('A'.$row_ctr, "TOTAL: ");
									$objPHPExcel->getActiveSheet()->getStyle('A'.$row_ctr)->applyFromArray($styleArray2);

									$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_ctr, $tc);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_ctr)->applyFromArray($styleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_ctr, $ac);
									$objPHPExcel->getActiveSheet()->getStyle('C'.$row_ctr)->applyFromArray($styleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_ctr, $cu);
									$objPHPExcel->getActiveSheet()->getStyle('D'.$row_ctr)->applyFromArray($styleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('F'.$row_ctr, $cb1);
									$objPHPExcel->getActiveSheet()->getStyle('F'.$row_ctr)->applyFromArray($styleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('H'.$row_ctr, $wd);
									$objPHPExcel->getActiveSheet()->getStyle('H'.$row_ctr)->applyFromArray($styleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('I'.$row_ctr, $ad);
									$objPHPExcel->getActiveSheet()->getStyle('I'.$row_ctr)->applyFromArray($styleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('J'.$row_ctr, $t);
									$objPHPExcel->getActiveSheet()->getStyle('J'.$row_ctr)->applyFromArray($styleArray);

					$filename = $full_mon."-".$year."_SUMMARY.xls";
				    if (ob_get_contents()) ob_end_clean();
				    header( "Content-type: application/vnd.ms-excel" );
				    header('Content-Disposition: attachment;filename='.$filename .' ');
				    header("Pragma: no-cache");
				    header("Expires: 0");
				    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				    $objWriter->save('php://output');
				    if (ob_get_contents()) ob_end_clean();


				$this->addLogs("EXPORT", $this->session->userdata('username'), "Printed Summary Report for ".$full_mon."-".$year.".");
				}
				
				header('location:'.base_url("/index.php/main/viewTableSummary"));

		}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// ABSTRACT REPORTS & BILL HISTORY

        public function viewBillHistory(){
			
			$datab= new JWS_model();
			
			$data["consumer_data"]=$datab->getConsumerData();
			
			if ($this->session->userdata('logged_in') == "admin"){
			
				$this->load->view("bill_history", $data);
			}
			else {
				$this->logout();
			}
			
		}
		
		public function viewConsumerBills($consumer_id){
			
			$datab= new JWS_model();
			
			$y = date('Y');
			
			$data["consumer_bills"]=$datab->getConBills($consumer_id, $y);
			
			$type="bill_history";
			
			$data["date_count"]=$datab->getDateCount($type);
			
			if ($this->session->userdata('logged_in') == "admin"){
			
				$this->load->view("con_bill_history", $data);
			}
			else {
				$this->logout();
			}
			
		}
		
		public function getConsumerBills($consumer_id){
			
			$datab= new JWS_model();
			
			$period = $this->input->post("period");
			
			$data["consumer_bills"]=$datab->getConBills($consumer_id, $period);
			
			$type="bill_history";
			
			$data["date_count"]=$datab->getDateCount($type);
			
			if ($this->session->userdata('logged_in') == "admin"){
			
				$this->load->view("con_bill_history", $data);
			}
			else {
				$this->logout();
			}
			
		}


		public function viewAbstractReports(){
			
			$datab= new JWS_model();
			
			$curr_yr = date('Y');
			$curr_mon = date('m');
			
			$full_date = date('Y-F');
			
			$type="abstract";
			
			$data["abstract"]=$datab->getAbstractReports($curr_yr, $curr_mon);
			$data["date_count"]=$datab->getDateCount($type);
			
			$this->session->set_flashdata("date", $full_date);
			
			if ($this->session->userdata('logged_in') == "admin"){
			
				$this->load->view("abstract_reports", $data);
			}
			else if ($this->session->userdata('logged_in') == "accounting"){
			
				$this->load->view("acc_abstract_reports", $data);
			}
			else {
				$this->logout();
			}
			
		}
		
		public function getAbstract(){
			
			$datab= new JWS_model();
			
			$period = $this->input->post("period");
			
			$year = substr($period,0,4);
			
			$month = substr($period,7);
			
			$period = str_replace(" ","",$period);
			
            if ($month=="January"){
                $month=1;
            }
            else if ($month=="February"){
                 $month=2;
            }
            else if ($month=="March"){
                 $month=3;
            }
            else if ($month=="April"){
                $month=4;
            }
            else if ($month=="May"){
                $month=5;
            }
            else if ($month=="June"){
                $month=6;
            }
            else if ($month=="July"){
                $month=7;
            }
            else if ($month=="August"){
              $month=8;
            }
            else if ($month=="September"){
                $month=9;
            }
            else if ($month=="October"){
                $month=10;
            }
            else if ($month=="November"){
              $month=11;
            }
            else if ($month=="December"){
                $month=12;
            }
			
			$type="abstract";
			
			$data["abstract"]=$datab->getAbstractReports($year, $month);
			$data["date_count"]=$datab->getDateCount($type);
			
			$this->session->set_flashdata("date", $period);
			
			if ($this->session->userdata('logged_in') == "admin" || $this->session->userdata('logged_in') == "accounting"){
			
				$this->load->view("abstract_reports", $data);
			}
			else {
				$this->logout();
			}
			
		}
		
		public function printAbstract($date){
			
			$datab = new JWS_model();

			$records = $this->input->post('records');
			$brgy = $this->input->post('brgy');
			
			$year = substr($date,0,4);
			
			$month = substr($date,5);
			
			$full_mon = $month;
			
            if ($month=="January"){
                $month=1;
            }
            else if ($month=="February"){
                 $month=2;
            }
            else if ($month=="March"){
                 $month=3;
            }
            else if ($month=="April"){
                $month=4;
            }
            else if ($month=="May"){
                $month=5;
            }
            else if ($month=="June"){
                $month=6;
            }
            else if ($month=="July"){
                $month=7;
            }
            else if ($month=="August"){
              $month=8;
            }
            else if ($month=="September"){
                $month=9;
            }
            else if ($month=="October"){
                $month=10;
            }
            else if ($month=="November"){
              $month=11;
            }
            else if ($month=="December"){
                $month=12;
            }
			
			$this->load->library('excel'); 
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$objPHPExcel = $objReader->load(FCPATH."./forms/abstract_form.xls");
			
			$objPHPExcel->setActiveSheetIndex(0);

				$data["abstract"]=$datab->getAbstractReportsPrint($year, $month, $brgy);

				$ctr=1;
				$row_ctr=8;
				
				$curr_date = date('Y-m-d | H:i');
				
				$install=0;
				$meter=0;
				$mlp=0;
				$pen=0;
				$water=0;
				$recon=0;
				$total=0;
                
                $styleArray2 = array(
										    'font'  => array(
									        'bold'  => true,
									        'color' => array('rgb' => '000000'),
									        'size'  => 11,
									        'name'  => 'Calibri'
											)
									);
									
					foreach ($data as $row){
						
							foreach ($row as $row2){

								if ($ctr<=$records || $records == "All Records"){

									 $borderStyleArray = array(
									      'borders' => array(
									          'allborders' => array(
									              'style' => PHPExcel_Style_Border::BORDER_THIN
									          )
									      )
									  );

									 $styleArray = array(
										    'font'  => array(
									        'bold'  => false,
									        'color' => array('rgb' => '000000'),
									        'size'  => 11,
									        'name'  => 'Calibri'
											)
									);
									

										$objPHPExcel->getActiveSheet()->setCellValue('A2', "Filename: ".$full_mon."-".$year."_ABSTRACT.xls"); 
									$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('I2', $curr_date);
									$objPHPExcel->getActiveSheet()->getStyle('I2')->applyFromArray($styleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('A6', "Report Period: ".$date);
									$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('A'.$row_ctr, $row2['or_date']);
									$objPHPExcel->getActiveSheet()->getStyle('A'.$row_ctr)->applyFromArray($borderStyleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_ctr, $row2['OR_no']);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_ctr)->applyFromArray($borderStyleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_ctr, $row2['lastname'].", ".$row2['firstname']." ".substr($row2['middlename'],0,1));
									$objPHPExcel->getActiveSheet()->getStyle('C'.$row_ctr)->applyFromArray($borderStyleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_ctr, $row2['address']);
									$objPHPExcel->getActiveSheet()->getStyle('D'.$row_ctr)->applyFromArray($borderStyleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('E'.$row_ctr, $row2['installation_fees']);
									$objPHPExcel->getActiveSheet()->getStyle('E'.$row_ctr)->applyFromArray($borderStyleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('F'.$row_ctr, $row2['meter_fee']);
									$objPHPExcel->getActiveSheet()->getStyle('F'.$row_ctr)->applyFromArray($borderStyleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('G'.$row_ctr, $row2['mlp']);
									$objPHPExcel->getActiveSheet()->getStyle('G'.$row_ctr)->applyFromArray($borderStyleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('H'.$row_ctr, $row2['water_fee']);
									$objPHPExcel->getActiveSheet()->getStyle('H'.$row_ctr)->applyFromArray($borderStyleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('I'.$row_ctr, $row2['penalty']);
									$objPHPExcel->getActiveSheet()->getStyle('I'.$row_ctr)->applyFromArray($borderStyleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('J'.$row_ctr, $row2['reconnection_fee']);
									$objPHPExcel->getActiveSheet()->getStyle('J'.$row_ctr)->applyFromArray($borderStyleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('K'.$row_ctr, $row2['total_amount']);
									$objPHPExcel->getActiveSheet()->getStyle('K'.$row_ctr)->applyFromArray($borderStyleArray);
									
									$install+=$row2['installation_fees'];
                    				$meter+=$row2['meter_fee'];
                    				$mlp+=$row2['mlp'];
                    				$water+=$row2['water_fee'];
                    				$pen+=$row2['penalty'];
                    				$recon+=$row2['reconnection_fee'];
                    				$total+=$row2['total_amount'];

									
									$ctr++;
									$row_ctr++;

								}
                                    
								
							}
							
                                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$row_ctr, "TOTAL: ");
									$objPHPExcel->getActiveSheet()->getStyle('A'.$row_ctr)->applyFromArray($styleArray2);
									
									$objPHPExcel->getActiveSheet()->setCellValue('E'.$row_ctr, $install);
									$objPHPExcel->getActiveSheet()->getStyle('E'.$row_ctr)->applyFromArray($styleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('F'.$row_ctr, $meter);
									$objPHPExcel->getActiveSheet()->getStyle('F'.$row_ctr)->applyFromArray($styleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('G'.$row_ctr, $mlp);
									$objPHPExcel->getActiveSheet()->getStyle('G'.$row_ctr)->applyFromArray($styleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('H'.$row_ctr, $water);
									$objPHPExcel->getActiveSheet()->getStyle('H'.$row_ctr)->applyFromArray($styleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('I'.$row_ctr, $pen);
									$objPHPExcel->getActiveSheet()->getStyle('I'.$row_ctr)->applyFromArray($styleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('J'.$row_ctr, $recon);
									$objPHPExcel->getActiveSheet()->getStyle('J'.$row_ctr)->applyFromArray($styleArray);
									
									$objPHPExcel->getActiveSheet()->setCellValue('K'.$row_ctr, $total);
									$objPHPExcel->getActiveSheet()->getStyle('K'.$row_ctr)->applyFromArray($styleArray);

				    $this->addLogs("EXPORT", $this->session->userdata('username'), "Printed Abstract for ".$full_mon."-".$year."-All Days.");
				}
				
				
					$filename = $full_mon."-".$year."_ABSTRACT.xls";
				    if (ob_get_contents()) ob_end_clean();
				    header( "Content-type: application/vnd.ms-excel" );
				    header('Content-Disposition: attachment;filename='.$filename .' ');
				    header("Pragma: no-cache");
				    header("Expires: 0");
				    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				    $objWriter->save('php://output');
				    if (ob_get_contents()) ob_end_clean();
				    
				    header('location:'.base_url("/index.php/main/viewAbstractReports"));
				
		}
		


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// LOGS

		public function viewLogs(){
			
			if ($this->session->userdata('logged_in') == "admin" || $this->session->userdata('logged_in') == "cashier" || $this->session->userdata('logged_in') == "accounting"){


				$datab = new JWS_model();

				$data['logs'] = $datab->getLogs();
			
				$this->load->view("logs", $data);

			}
			else{
			
				$this->logout();

			}
			
		}
		
		public function printLogs(){
			
			$datab = new JWS_model();

			$records = $this->input->post('records');

			$day1 = $this->input->post('day1');

			$day2 = $this->input->post('day2');
			
			$this->load->library('excel'); 
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$objPHPExcel = $objReader->load(FCPATH."./forms/logs_form.xls");
			
			$objPHPExcel->setActiveSheetIndex(0);
				
			$curr_day = date('d');

			if ($curr_day>= $day1 && $curr_day>= $day2 && $day1<= $day2){  

				$data["log_data"]=$datab->getLogData($day1, $day2);

				$ctr=1;
				$row_ctr=8;

				$curr_date= date('Y-m-d');

				$curr_m = date("F", strtotime($curr_date));
				$curr_y = date("Y", strtotime($curr_date));

					foreach ($data as $row){
						
							foreach ($row as $row2){

								if ($ctr<=$records || $records == "All Records"){

									 $borderStyleArray = array(
									      'borders' => array(
									          'allborders' => array(
									              'style' => PHPExcel_Style_Border::BORDER_THIN
									          )
									      )
									  );

									 $styleArray = array(
										    'font'  => array(
									        'bold'  => false,
									        'color' => array('rgb' => '000000'),
									        'size'  => 11,
									        'name'  => 'Calibri'
											)
									);

									$objPHPExcel->getActiveSheet()->setCellValue('A6', "Log Session Start Date: ".$curr_y."-".$curr_m."-".$day1); 
									$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('A'.$row_ctr, $row2['log_time']);
									$objPHPExcel->getActiveSheet()->getStyle('A'.$row_ctr)->applyFromArray($borderStyleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('B'.$row_ctr, $row2['originator']);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_ctr)->applyFromArray($borderStyleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('C'.$row_ctr, $row2['type']);
									$objPHPExcel->getActiveSheet()->getStyle('C'.$row_ctr)->applyFromArray($borderStyleArray);

									$objPHPExcel->getActiveSheet()->setCellValue('D'.$row_ctr, $row2['description']);
									$objPHPExcel->getActiveSheet()->getStyle('D'.$row_ctr)->applyFromArray($borderStyleArray);

									
									$ctr++;
									$row_ctr++;

								}

								
							}

					

					$filename = $curr_m."-".$curr_y."_SL.xls";
				     if (ob_get_contents()) ob_end_clean();
				    header( "Content-type: application/vnd.ms-excel" );
				    header('Content-Disposition: attachment;filename='.$filename .' ');
				    header("Pragma: no-cache");
				    header("Expires: 0");
				    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				    $objWriter->save('php://output');
				    if (ob_get_contents()) ob_end_clean();


					$this->addLogs("EXPORT", $this->session->userdata('username'), "Printed System Logs for ".$curr_m."-".$curr_y.".");
				}
				
			}
			else {

					$this->session->set_flashdata("info", "<div class='alert alert-danger'> 
					              <button class='close' data-dismiss='alert'>×</button>
					              <strong>Error!</strong> The <b>Dates</b> you have entered invalid. The dates may be greater than the current date or the <em>FROM</em> date is greater than the <em>TO</em>.</div>");

					header('location:'.base_url("/index.php/main/viewLogs"));

			}
				
		}

		public function addLogs($type, $id, $desc){
		
			$datab = new JWS_model();

			$datab->saveLogs($type, $id, $desc);
			
		}


//********************************************************************************************************************//
//-----CASHIER METHODS------------------------------------------------------------------------------------------------//
//********************************************************************************************************************//


// DASHBOARD
		
		// public function cashierDashboard(){ // view dashboard for cashier
			
		// 	if ($this->session->userdata('logged_in')=="cashier"){
		// 	$this->load->view("dashboard_cashier");
		// 	}
		// 	else
		// 		header('location:'.base_url());
			
		// }


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// MANAGE BILLS		

		// public function cashier_viewBilledConsumers(){
			
		// 	$this->load->view("cashier_billed_consumers");
			
			
		// }

		// public function cashier_viewBill(){
			
		// 	$this->load->view("consumer_bills_cashier");
			
			
		// }


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// MANAGE CONSUMERS		


		// public function cashier_viewConsumers(){
			
		// 	$datab= new JWS_model();
			
		// 	$data["consumer_data"]=$datab->getConsumerData();
			
		// 	$this->load->view("cashier_consumers",$data);
		// }

		// public function cashier_viewConsumerLedger(){
			
		// 	$this->load->view("consumer_ledger_cashier");
		// }

		
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// MANAGE LOANS


		// public function cashier_viewConsumerLoan(){
			
		// 	$this->load->view("cashier_consumer_loan");
			
			
		// }

		// public function cashier_viewLoanDetails(){
			
		// 	$this->load->view("cashier_consumer_loan_details");
			
			
		// }


//********************************************************************************************************************//
//-----MOBILE APP PLUMBER METHODS------------------------------------------------------------------------------------//
//********************************************************************************************************************//

		// public function appAddBill($curr_reading=0,$curr_date="",$prev_reading=0,$prev_date="",$consumer_id="",$user=""){ // adds water bill using consumer account no

		// 	$datab = new JWS_model();

		// 	$account_no = $consumer_id;

		// 	$username = $user;

		// 	$date_created=date('Y-m-d');

		// 	$bill_details=array(
		// 		'bill_status'=>"Billed",
		// 		'bill_creator'=>"PLUMAPP",
		// 		'installation_fees'=>0,
		// 		'penalty'=>0,
		// 		'reconnection_fee'=>0,
		// 		'others'=>0,
		// 		'date_created'=>$date_created,
		// 		'date_paid'=>null);

		// 	$result_array = $datab->appAddConsumerBill($bill_details, $curr_reading, $curr_date, $prev_reading, $prev_date, $account_no);

		// 	$this->addLogs("MODIFICATION", "PLUMAPP", "Meter Reading ".$curr_reading." with Reading Date ".$curr_date." was sent by USER: ".$username.". Water Bill with Bill ID: ".$result_array['bill_id']." has been Created for Consumer: ".$account_no.".");

		// }

	
		//********************************************************************************************************************//
//-----MOBILE APP CONSUMER METHODS------------------------------------------------------------------------------------//
//********************************************************************************************************************//


// MANAGE BILLS	


// 		public function myBills($meter_id){

// 			$datab = new JWS_model();

// 			$bill_array=$datab->getMyBills($meter_id);

// 			$result=$datab->getMyNotif($meter_id);

// 			$data['bills'] = $bill_array['bills'];

// 			if ($result != "none"){

// 				$this->session->set_flashdata("newbill_info", "<div class='alert alert-success'> 
// 		              <button class='close' data-dismiss='alert'>×</button>
// 		              <strong>Notification!</strong> New bill has been added. See <a href='". base_url('index.php/main/myBillDetails/'.$result)."'> new Bill.</a></div>");
// 			}
			
// 			$this->load->view("consumer_billed_consumers", $data);
			
// 		}

// 		public function myBillDetails($bill_id){

// 			$datab = new JWS_model();

// 			$result_array=$datab->getBillDetails($bill_id);

// 			$data['bill_details'] = $result_array['bill_details']; // water bill details
// 			$data['previous_reading'] = $result_array['previous_reading']; //  previous reading

// 			$this->session->set_flashdata("newbill_info", "");
			
// 			$this->load->view("consumer_consumer_bills", $data);

// 		}

	}

?>