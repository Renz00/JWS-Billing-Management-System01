<!DOCTYPE html>

<?php include "nav_top_menu_admin.php";?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#"><i class="icon-home"></i> Manage Billing</a><a href="#" class="current">Loans</a> </div>
    <?php echo $this->session->flashdata("info");?>
    <?php echo $this->session->flashdata("error");?>
    <h1>Manage Loans</h1>
  </div>
  <div class="container-fluid">
  
	<div class="row-fluid">
      <div class="span12">

       <a href="#myAlert3" class="btn btn-warning" data-toggle="modal" ><i class="icon-plus-sign"></i> Add Consumer Loan</a>

        <div id="myAlert3" class="modal hide">
                   <form action="<?php echo base_url('index.php/main/addLoan')?>" method="post" class="form-horizontal">
                    <div class="modal-header">
                        <button data-dismiss="modal" class="close" type="button">×</button>
                        <h3>Add Consumer Loan</h3>
                      </div>
                      <div class="modal-body">   

                      <div class="control-group">
                        <label class="control-label"><strong>Name: </strong></label>
                        <div class="controls">
                          <select name="consumer_id" class="span11">
                            <?php
                              foreach ($consumers as $row)
                                        
                                echo "<option>".substr($row['date_installed'],0,4).substr($row['date_installed'],4,4).str_pad($row['account_no'], 4, '0', STR_PAD_LEFT)." | ".$row['lastname'].", ".$row['firstname']." ".substr($row['middlename'],0,1)."."."</option>";
                            ?>

                          </select>
                        </div>
                      </div>

                        <div class="control-group">
                            <label class="control-label"><strong>Loan Type :</strong></label>
                            <div class="controls">
                              <label>
                                <input type="checkbox" name="loan[]" value="Material Loan" />
                                Material Loan Program</label>                    
                            </div>

                             <label class="control-label"><strong>Loan Amount :</strong></label>
                              <div class="controls">
                                 <input type="number" min="0" step=".01" name="loan_amt1" class="span11" placeholder="Material Loan Amount" value="0" />
                               </div>

                               <div class="control-group">
                                <label class="control-label"><strong>Remarks :</strong></label>
                                <div class="controls">
                                 <textarea class="span11" name="remarks" value=""></textarea>
                                </div>
                              </div>
                         </div>

                         <div class="control-group">

                            <label class="control-label"><strong>Loan Type :</strong></label>
                              <div class="controls">
                                <label>
                                 <input type="checkbox" name="loan[]" value="Meter Fee" />
                                Meter Rental</label>                    
                            </div>

                            <label class="control-label"><strong>Loan Amount :</strong></label>
                               <div class="controls">
                                 <input type="number" name="loan_amt2" min="0" step=".01" class="span11" placeholder="Meter Rental Amount" value="0"/>
                             </div>
                         </div>

                </div> 
                  <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></a>&nbsp;<a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
                </form>
        </div>

        <div class="widget-box">
          <div class="widget-title">
             <span class="icon"><i class="icon-th"></i></span> 
			 	  
            <h5>Consumer Loan</h5>
          </div>
	
          <div class="widget-content nopadding">		   
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Loan ID</th>
                  <th>Loan Type</th>
                  <th>Account No</th>
                  <th>Full Name</th>
                  <th>Start Date</th>
                   <th>Loan Status</th>
                   <th>Actions</th>
                </tr>
              </thead>
              <tbody>

              <?php

                $ctr1=0;
                $ctr2=0;
                $ctr3=0;

                foreach ($loan as $row){

                  $start_date = $row['start_date'];
                  $new_startd = date('Y-F-d', strtotime($start_date));

                 echo "<tr class='gradeA'>
                       <td>LOAN-".str_pad($row['loan_id'], 4, '0', STR_PAD_LEFT)."</td>
                      <td>".$row['loan_type']."</td>

                      <td>".substr($row['date_installed'],0,4).substr($row['date_installed'],4,4).str_pad($row['account_no'], 4, '0', STR_PAD_LEFT)."</td>

                      <td>".$row['lastname'].", ".$row['firstname']." ".substr($row['middlename'],0,1)."."."</td>
                      
                      <td>".$new_startd."</td>"; 

                      if ($row['loan_status'] == "Active"){
                        echo "<td><center><span class='label label-warning'>".$row['loan_status']."</span></td>";

                        $ctr2++;
                      }
                      else if ($row['loan_status'] == "Paid"){
                        echo "<td><center><span class='label label-success'>".$row['loan_status']."</span></td>";

                        $ctr3++;
                      }

                     echo "<td><center><a href='".base_url('index.php/main/viewLoanDetails/'.$row['loan_id'])."'<button class='btn btn-primary btn-lg'><i class='icon icon-eye-open'></i>&nbsp;View Details</button></a>
                    </tr>";

                    $ctr1++;
                }
              
              
              echo "</tbody>
            </table>
            </div>
          </div>";
           echo "<font color='gray'><h6>Total Number of Loans: ".$ctr1."</h6>";
          echo "<h6>Number of <b>Paid</b>: ".$ctr3." | Number of <b>Active</b>: ".$ctr2."</h6></font>";
             ?>
      </div>
     </div>  
   </div> 
  </div> 

<?php include "footer.php";?>

<?php include "form_scripts.php";?>
</body>
</html>
