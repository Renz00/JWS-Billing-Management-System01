<!DOCTYPE html>

<?php include "nav_top_menu_admin.php";?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#"><i class="icon-home"></i> Manage Billing</a><a href="#" class="current">Bills</a> </div>

    <?php echo $this->session->flashdata("info");?>

    <h1>Manage Bills</h1>
  </div>
  
            <div id="myAlert6" class="modal hide">
                
                  <form action="<?php echo base_url('index.php/main/printReadingSheet');?>" method='post' class='form-horizontal'>

                      <div class="modal-header">
                        <button data-dismiss="modal" class="close" type="button">×</button>
                        <h3>Print Reading Sheet</h3>
                      </div>
                      <div class="modal-body">

                         <div class="control-group">
                          <label class="control-label"><strong>Select Barangay :</strong></label>
                          <div class="controls">
                            <select name="address" class="span11">                                       
                              <option>Binanuahan</option>
                              <option>Biriran</option>
                              <option>Catanagan</option>
                              <option>Cogon</option>
                              <option>Embarcadero</option>
                              <option>North_Poblacion</option>
                              <option>South_Poblacion</option>
                              <option>Taboc</option>
                              <option>Tughan</option>                           
                            </select>
                          </div>
                        </div>

                        <?php

                          $curr_month = date('m');
                          $curr_year = date('Y');

                         echo "<div class='control-group'>
                          <label class='control-label'><strong>Month :</strong></label>
                          <div class='controls'>
                            <input type='number' name='month' class='span3' min='1' max='".$curr_month."' placeholder='Month' value='".$curr_month."' required/>
                          </div>

                          <label class='control-label'><strong>Year :</strong></label>
                          <div class='controls'>
                            <input type='number' name='year' class='span3' min='1799' max='".$curr_year."' placeholder='Year' value='".$curr_year."' required/>
                          </div>
                        </div>";
                      ?>

                 </div> 
                      <div class="modal-footer"><button type="submit" class="btn btn-primary" >Save</button></a>&nbsp;<a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
                </form>
                </div>
                
                <div id="myAlert5" class="modal hide">
                
                  <form action="<?php echo base_url('index.php/main/addBill');?>" method='post' class='form-horizontal'>

                      <div class="modal-header">
                        <button data-dismiss="modal" class="close" type="button">×</button>
                        <h3>Create Bill</h3>
                      </div>
                      <div class="modal-body">

                         <div class="control-group">
                          <label class="control-label"><strong>Name :</strong></label>
                          <div class="controls">
                            <select name="consumer_id" class="span11">

                            <?php
                                
                              $account_no ="";
                              foreach ($consumer_list as $row){
                                  
                                  if ($account_no!=$row['account_no']){
                                       echo "<option>".substr($row['date_installed'],0,4).substr($row['date_installed'],4,4).str_pad($row['account_no'], 4, '0', STR_PAD_LEFT)." | ".$row['lastname'].", ".$row['firstname']." ".substr($row['middlename'],0,1)."."."</option>";
                                  } 
                                  
                                   $account_no = $row['account_no'];
                              }
                                        
                                
                            ?>

                            </select>
                          </div>
                        </div>

                        <div class="control-group">
                          <label class="control-label"><strong>Current Meter Reading :</strong></label>
                          <div class="controls">
                            <input type="text" name="curr_reading" class="span3" placeholder="Current Meter Reading" value="0" required/>
                          </div>
                        </div>

                        <div class='control-group'>
                          <label class='control-label'><strong>Reading Date:</strong></label>
                          <div class='controls'>
                            <input type='date' name='reading_date' class='span3' required/>
                          </div>
                        </div>

                        <div class="control-group">
                          <label class="control-label"><strong>Others :</strong></label>
                          <div class="controls">
                            <input type="text" name="others" class="span3" placeholder="Others" value="0"/>
                          </div>
                        </div>

                 </div> 
                      <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></a>&nbsp;<a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
                </form>
            </div>
                
  <form method='post' action="<?php echo base_url('index.php/main/sendSMS1');?>" class='form-horizontal' id='form1'>
      
  <div class="container-fluid">
  
  	<div class="row-fluid">
        <div class="span12">
          <div class="buttons" style="padding-left:10px;padding-top:10px;">
               <a href="#myAlert6" class="btn btn-primary" data-toggle="modal"><i class="icon-print"></i> Print Reading Sheet</a>
               
                    
                    <div class="btn-group">
                    <button data-toggle="dropdown" class="btn dropdown-toggle"><i class="icon-cog"></i>Options <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                      <li>  <button type="submit" form='form1'><i class="icon-envelope "></i> Send SMS Notification</button></li>
                    <li>  <a href="#myAlert5" data-toggle="modal"><i class="icon-check "></i> Create Water Bill</a></li>
                  
                    </ul>
                  </div>
                
            </div>

          <div class="widget-box">
            <div class="widget-title">
               <span class="icon"><i class="icon-th"></i></span> 
  			 	  
              <h5>Consumers</h5>
            </div>
            
  	       
  	           
            <div class="widget-content nopadding">
  		   
              <table class="table table-bordered data-table with-check">
                <thead>
                  <tr>
                    <th><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" /></th>
                    <th>ID</th>
                    <th>Account No.</th>
                    <th>Full Name</th>
                    <th>Address</th>
                    <th>Bill Status</th>
                    <th>Date Created</th>
                    <th>Due Date</th>
                     <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                
                  <?php
                    $ctr1=0;
                    $ctr2=0;
                    $ctr3=0;
                    $ctr4=0;
                    foreach ($billed_consumers as $row){

                      $new_datecreate = date('Y-F-d', strtotime($row['date_created']));

                      if ($row['due_date']==0000-00-00){

                        $new_due = "NONE";

                      }
                      else {

                        $new_due = date('Y-F-d', strtotime($row['due_date']));
                         
                      }

                     echo "<tr class='gradeA'>
                          <td><input type='checkbox' name='bills[]' value='".$row['bill_id']."'/></td>
                          <td>WB-".str_pad($row['bill_id'], 4, '0', STR_PAD_LEFT)."</td>
                          
                          <td>".substr($row['date_installed'],0,4).substr($row['date_installed'],4,4).str_pad($row['account_no'], 4, '0', STR_PAD_LEFT)."</td>

                          <td>".$row['lastname'].", ".$row['firstname']." ".substr($row['middlename'],0,1)."."."</td>
                          <td>".$row['address']."</td>";

                        if ($row['bill_status']=="Paid"){
                            echo "<td><center><span class='label label-success'>".$row['bill_status']."</span></center</td>";
                            $ctr2++;
                        }
                         else if ($row['bill_status']=="Billed"){
                           echo  "<td><center><span class='label label-warning'>".$row['bill_status']."</span></center></td>";
                           $ctr3++;
                        }
                         else if ($row['bill_status']=="For Disconnection"){
                           echo  "<td><center><span class='label label-important'>".$row['bill_status']."</span></center></td>";
                           $ctr4++;
                        }
                         
                        echo "<td>".$new_datecreate."</td>
                        <td>".$new_due."</td>
                         <td><center><a href='".base_url('index.php/main/viewBill/'.$row['bill_id'].'')."' class='btn btn-primary btn-lg'><i class='icon icon-eye-open'></i>&nbsp;View Bill</a></td>
                        </tr>";
                        $ctr1++;
                     }

                    
               echo "</tbody>
              </table>
            </div>
          </div>";
          echo "<font color='gray'><h6>Total Number of Bills: ".$ctr1."</h6>";
          echo "<h6>Number of <b>Billed</b>: ".$ctr3." | Number of <b>Paid</b>: ".$ctr2." | Number of <b>For Disconnection</b>: ".$ctr4."</h6></font>";
          ?>
         
      </div>
     </div>  
   </div>
   
   
  </div> 

</form>
<?php include "footer.php";?>

<?php include "form_scripts.php";?>
</body>
</html>
