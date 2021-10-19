<!DOCTYPE html>

<?php include "nav_top_menu_admin.php";?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#"><i class="icon-home"></i> Reports</a><a href="#" class="current">Abstract</a></div>

     <?php
            $new_date = $this->session->flashdata("date");
            echo "<h1>Abstract Report for ".$new_date."-All Days</h1>";

    ?>
  </div>
  <div class="container-fluid">
    
    <div class="row-fluid">
      <div class="span12">  
       <div class="buttons" style="padding-left:10px;padding-top:10px;">
                    <a href="#myAlert6"class="btn btn-primary" data-toggle="modal"><i class="icon-print"></i> Download Form</a>
                     <a href="#myAlert7" class="btn btn-info" data-toggle="modal" ><i class="icon-calendar"></i> Report Period</a>
                  </div>
        <div class="widget-box">
          <div class="widget-title">
          <span class="icon"><i class="icon-th"></i></span> 
			 	  
            <h5>Total</h5>
          </div>
	
          <div class="widget-content nopadding">
		   <div class="widget-content">
							   <div class="row-fluid">
							        <font color="black">
            					    <div class="span3"></div>
                 <?php
                                    $application_fee=0;
                                    $meter_fee=0;
                                    $mlp=0;
                                    $water_fee=0;
                                    $penalty=0;
                                    $reconnection_fee=0;
                                    $total=0;
                                    foreach ($abstract as $row){
                                        
                                        $application_fee+=$row['installation_fees'];
                                        $meter_fee+=$row['meter_fee'];
                                        $mlp+=$row['mlp'];
                                        $water_fee+=$row['water_fee'];
                                        $penalty+=$row['penalty'];
                                        $reconnection_fee+=$row['reconnection_fee'];
                                        $total+=$row['total_amount'];
                                        
                                    }
            
                                    echo "<div class='span4'>
            
                                          <p><b>APPLICATION FEE : </b>".number_format($application_fee, 2, '.', ',')."</p>
                                          <p><b>METER FEE : </b>".number_format($meter_fee, 2, '.', ',')."</p>
                                          <p><b>MATERIAL LOAN : </b>".number_format($mlp, 2, '.', ',')."</p>
    
                                     </div>";   
            
                                    echo "<div class='span4'>
            
                                          <p><b>WATER BILL : </b>".number_format($water_fee, 2, '.', ',')."</p>
                                          <p><b>PENALTY FEE : </b>".number_format($penalty, 2, '.', ',')."</p>
                                          <p><b>RECONNECTION FEE : </b>".number_format($reconnection_fee, 2, '.', ',')."</p>
    
                                     </div>";
            
            
                                  
                                  echo "<div class='span3'></div>
                                  </div>
                              
                              <hr><center><h4>AMOUNT COLLECTED: <font color='blue'>".number_format($total, 2, '.', ',')."</b></h4></center><hr>";
                              ?>
                              </font>
							</div>
						</div>
        </div>
     </div>   
  </div>

  
	<div class="row-fluid">
      <div class="span12">  
      
            
        <div id="myAlert6" class="modal hide">
                <form action="<?php echo base_url('index.php/main/printAbstract/'.$new_date);?>" method="post" class="form-horizontal">
                      <div class="modal-header">
                        <button data-dismiss="modal" class="close" type="button">×</button>
                        <h3>Select No. of Records</h3>
                      </div>
                      <div class="modal-body">
                          
                           <div class="control-group">
                        <label class="control-label"><Strong>Barangay: </strong></label>
                          <div class="controls">
                             <select name="brgy">
                                 <option>All</option>
                                  <option>Binanuahan</option>
                                    <option>Biriran</option>
                                    <option>Catanagan</option>
                                    <option>Cogon</option>
                                    <option>Embarcadero</option>
                                    <option>North Poblacion</option>
                                    <option>South Poblacion</option>
                                    <option>Taboc</option>
                                    <option>Tughan</option> 
                        
                            </select>
                          </div>
                        </div>
                         
                         
                       <div class="control-group">
                        <label class="control-label"><Strong>Records: </strong></label>
                          <div class="controls">
                             <select name="records">
                                 <option>All Records</option>
                                  <?php
                                
                                $count = count($abstract);
                                $count--;
                                while ($count>=1){
                                    echo "<option>".$count."</option>";
                                    $count--;
                                }
                                     
                                ?>
                        
                            </select>
                          </div>
                        </div>

                 </div> 
                      <div class="modal-footer"><button type="submit" class="btn btn-primary">Confirm</button></a>&nbsp;<a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
                </form>
                </div> 

       
      <div id="myAlert7" class="modal hide">
                <form action="<?php echo base_url('index.php/main/getAbstract');?>" method="post" class="form-horizontal">
                      <div class="modal-header">
                        <button data-dismiss="modal" class="close" type="button">×</button>
                        <h3>Select Month</h3>
                      </div>
                      <div class="modal-body">
                         
                       <div class="control-group">
                        <label class="control-label"><Strong>Month: </strong></label>
                          <div class="controls">
                             <select name="period">
                        <?php
                             foreach ($date_count as $row){
                                
                                $curr_m = date('m');
                                $curr_y = date('Y');
                                
                                $abstract_date = $row['min(or_date)'];
                               
                                $mon = date('m', strtotime($abstract_date));
                                $yr = date('Y', strtotime($abstract_date));
                                
                                while (intval($curr_m) >= intval($mon) && intval($curr_y) >= intval($yr)){
                                    
                                    if ($curr_m==1){
                                        $month="January";
                                    }
                                    else if ($curr_m==2){
                                        $month="February";
                                    }
                                    else if ($curr_m==3){
                                        $month="March";
                                    }
                                    else if ($curr_m==4){
                                        $month="April";
                                    }
                                    else if ($curr_m==5){
                                        $month="May";
                                    }
                                    else if ($curr_m==6){
                                        $month="June";
                                    }
                                    else if ($curr_m==7){
                                        $month="July";
                                    }
                                    else if ($curr_m==8){
                                        $month="August";
                                    }
                                    else if ($curr_m==9){
                                        $month="September";
                                    }
                                    else if ($curr_m==10){
                                        $month="October";
                                    }
                                    else if ($curr_m==11){
                                        $month="November";
                                    }
                                    else if ($curr_m==12){
                                        $month="December";
                                    }
                                    
                                     echo "
                              <option>".$curr_y." - ".$month."</option>";
                              
                                    if ($curr_m-1==0){
                                        
                                        $curr_m=12;
                                        $curr_y=$curr_y-1;
                                        
                                    }
                                    else {
                                        $curr_m=$curr_m-1;
                                    }
                                    
                                }
                                
                            }
                            
                        ?>
                            </select>
                          </div>
                        </div>

                 </div> 
                      <div class="modal-footer"><button type="submit" class="btn btn-primary">Confirm</button></a>&nbsp;<a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
                </form>
                </div> 

     </div>   
  </div>
  
  <div class="row-fluid">

      <div class="span12">
        
          <div class="widget-box">
            <div class="widget-title">
               <span class="icon"><i class="icon-th"></i></span> 
            
              <h5>Invoice</h5>
            </div>
    
            <div class="widget-content nopadding">
         
               <div class="widget-content nopadding">
  		   
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>OR Number</th>
                    <th>Payee</th>
                     <th>Address</th>
                     <th>Application Fee</th>
                     <th>Meter Fee</th>
                     <th>MLP</th>
                     <th>Water Bill</th>
                     <th>Penalty</th>
                     <th>Reconnection Fee</th>
                     <th>Total</th>
                     <th>Remarks</th>
                     <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
            <?php
                $ctr1=0;
                foreach ($abstract as $row){
                    
                    echo "<tr class='gradeA'>
            
                          <td>".$row['or_date']."</td>
                          <td>".$row['OR_no']."</td>
                          <td>".$row['lastname'].", ".$row['firstname']." ".substr($row['middlename'],0,1)."."."</td>
                          <td>".$row['address']."</td>
                          <td>".$row['installation_fees']."</td>
                          <td>".$row['meter_fee']."</td>
                          <td>".$row['mlp']."</td>
                          <td>".$row['water_fee']."</td>
                          <td>".$row['penalty']."</td>
                          <td>".$row['reconnection_fee']."</td>
                          <td>".$row['total_amount']."</td>
                         <td>".$row['remarks']."</td>
                         <td><center><a href='".base_url('index.php/main/viewInvoiceDetails/'.$row['bill_id'].'')."' class='btn btn-primary btn-lg' target='_blank'><i class='icon icon-eye-open'></i>&nbsp;View</button></a></center></td>
                        </tr>";
                        
                        $ctr1++;
                    
                }
                    
                
                echo "</tbody>
              </table>
            </div>
          </div>";
          echo "<font color='gray'><h6>Total Number of Records: ".$ctr1."</h6>";
          ?>
      </div>
      </div>
      </div>
      </div>
  </div>
</div>

<?php include "footer.php";?>

<?php include "form_scripts.php";?>

</body>
</html>
