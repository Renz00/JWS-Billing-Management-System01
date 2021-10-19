<!DOCTYPE html>

<?php include "nav_top_menu_cashier.php";?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#"><i class="icon-home"></i> Manage Billing</a><a href="#" title="Go to Loans" class="tip-bottom">Loans</a><a href="#" class="current">Loan Details</a> </div>
    <?php echo $this->session->flashdata("info");?>
    <h1>Consumer Loan</h1>
  </div>
  <div class="container-fluid">
  
        <div class="row-fluid">
        
            <div class="span12">
                <div class="buttons" style="padding-left:10px;padding-top:10px;">
                  <div class="btn-group">
                    <button data-toggle="dropdown" class="btn dropdown-toggle"><i class="icon-cog"></i>Options <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                     <li><a href="#myAlert2" data-toggle="modal"><i class="icon-edit"></i> Edit Loan</a></li>
                      <li><a href="#myAlert4" data-toggle="modal"><i class="icon-trash "></i> Delete Loan</a></li>
                    </ul>
                  </div>
                 
                 
                 
                  <a href="<?php echo base_url('index.php/main/viewConsumerLoan')?>"class="btn btn-warning" style="float:right;"><i class="icon-arrow-left"></i>&nbsp;Previous Page</a>
                </div>
              <div class="widget-box">
                <div class="widget-title">
                   <span class="icon"><i class="icon-th"></i></span> 
                
                  <h5>Loan Details</h5>

                </div>
                
                 
              <div class='widget-content'>  

              <?php

                  foreach ($loan_details as $row){

                      echo "<div id='myAlert2' class='modal hide'>
                       <form action='".base_url('index.php/main/updateLoan/'.$row['loan_id'])."' method='post' class='form-horizontal'>
                          <div class='modal-header'>
                            <button data-dismiss='modal' class='close' type='button'>×</button>
                            <h3>Edit Loan Details</h3>
                          </div>
                          <div class='modal-body'>
                           
                            <div class='control-group'>

                            <label class='control-label'><strong>Loan Type :</strong></label>";

                            if ($row['loan_type'] == "Material Loan"){

                               echo "<div class='controls'>
                                      <label>
                                        <input type='radio' name='radios' value='Material Loan' checked/>
                                        Material Loan</label>
                                      <label>
                                        <input type='radio' name='radios' value='Meter Fee'/>
                                        Meter Fee</label>
                                     </div>";

                            }
                            else if ($row['loan_type'] == "Meter Fee"){

                               echo "<div class='controls'>
                                      <label>
                                        <input type='radio' name='radios' value='Material Loan'/>
                                        Material Loan</label>
                                      <label>
                                        <input type='radio' name='radios' value='Meter Fee' checked/>
                                        Meter Fee</label>
                                     </div>";

                            }

                           echo "</div>

                             <div class='control-group'>
                              <label class='control-label'><strong>Loan Amount :</strong></label>
                              <div class='controls'>
                                <input type='number' name='loan_amt' min='0' step='.01' class='span11' placeholder='Loan Amount' value='".$row['loan_amount']."'/>
                              </div>
                            </div>

                            <div class='control-group'>
                              <label class='control-label'><strong>Amount Paid :</strong></label>
                              <div class='controls'>
                                <input type='number' name='amt_paid' min='0' step='.01' class='span11' placeholder='Address' value='".$row['amount_paid']."'/>
                              </div>
                            </div>

                          <div class='control-group'>
                            <label class='control-label'><strong>Loan Status :</strong></label>";

                            if ($row['loan_status'] == "Active"){

                               echo "<div class='controls'>
                                      <label>
                                        <input type='radio' name='radios1' value='Active' checked/>
                                        Active</label>
                                      <label>
                                        <input type='radio' name='radios1' value='Paid'/>
                                        Paid</label>
                                     </div>";

                            }
                            else if ($row['loan_status'] == "Paid"){

                               echo "<div class='controls'>
                                      <label>
                                        <input type='radio' name='radios1' value='Active'/>
                                        Active</label>
                                      <label>
                                        <input type='radio' name='radios1' value='Paid' checked/>
                                        Paid</label>
                                     </div>";

                            }

                      echo "
                          </div>
                         <div class='control-group'>
                              <label class='control-label'><strong>Start Date :</strong></label>
                              <div class='controls'>
                                <input type='date' name='start_date' class='span11' placeholder='Address' value='".$row['start_date']."'/>
                              </div>
                            </div>

                          <div class='control-group'>
                              <label class='control-label'><strong>End Date :</strong></label>
                              <div class='controls'>
                                <input type='date' name='end_date' class='span11' placeholder='Address' value='".$row['end_date']."'/>
                              </div>
                          </div>

                            <div class='control-group'>
                              <label class='control-label'><strong>Remarks :</strong></label>
                              <div class='controls'>
                               <input type='text' class='span11' name='remarks' value='".$row['remarks']."'></input>
                              </div>
                            </div>

                     </div> 
                          <div class='modal-footer'><button type='submit' class='btn btn-primary'>Save</button></a>&nbsp;<a data-dismiss='modal' class='btn' href='#''>Cancel</a> </div>
                    </form>
                    </div>";
                  }

                ?>
                   
                  <?php
                      
                      foreach ($loan_details as $row){

                          $loan_amount = $row['loan_amount'];
                          $amount_paid = $row['amount_paid'];

                          if ($amount_paid != 0){
                             $loan_percentage = round(($amount_paid / $loan_amount) * 100);
                          }
                          else $loan_percentage = 0;

                         $start_date = $row['start_date'];
                         $new_startd = date('Y-F-d', strtotime($start_date));

                         $end_date = $row['end_date'];
                         $new_endd = date('Y-F-d', strtotime($end_date));

                          echo "<div class='row-fluid'>
                            <div class='span3'></div>
                            <div class='span4'>

                                <p><b>ACCOUNT NO : </b>".substr($row['date_installed'],0,4).substr($row['date_installed'],4,4).str_pad($row['account_no'], 4, '0', STR_PAD_LEFT)."</p>
                                 <p><b>NAME : </b>".$row['lastname'].", ".$row['firstname']." ".substr($row['middlename'],0,1)."."."</p>
                                 <p><b>ADDRESS : </b>".$row['address']."</p>";

                                if ($row['status'] == "Pending"){
                                  echo "<p><b>STATUS : </b><span class='label label-warning'>".$row['status']."</span></p>";
                                }
                                else if ($row['status'] == "Active"){
                                  echo "<p><b>STATUS : </b><span class='label label-success'>".$row['status']."</span></p>";
                                }
                                else if ($row['status'] == "Disconnected"){
                                  echo "<p><b>STATUS : </b><span class='label label-danger'>".$row['status']."</span></p>";
                                }

                          echo "<p><b>START DATE : </b>".$new_startd."</p>
                              <p><b>END DATE : </b>".$new_endd."</p>
                              </div> 
                              <div class='span4'>
                              
                                  <p><b>LOAN TYPE : </b>".$row['loan_type']."</p>
                                  <p><b>LOAN AMOUNT : </b>".$row['loan_amount']."</p>
                                  <p><b>MONTHLY : </b>".$row['monthly_payment']."</p>
                                  <p><b>INTEREST : </b>".$row['interest']."</p>
                                   <p><b>AMOUNT PAID : </b>".$row['amount_paid']."</p>";

                                   if ($row['loan_status'] == "Active"){
                                    echo "<p><b>LOAN STATUS : </b><span class='label label-warning'>".$row['loan_status']."</span></p>";
                                  }
                                  else if ($row['loan_status'] == "Paid"){
                                    echo "<p><b>LOAN STATUS : </b><span class='label label-success'>".$row['loan_status']."</span></p>";
                                  }
                                  
                          echo "
                            </div>
                          <div class='span3'></div>
                            
                          </div>
                      
                         <div class='progress progress-striped progress-success'>
                                  <div class='bar' style='width: ".$loan_percentage."%;'></div>
                          </div>
                          <center><h4>Loan Progress: ".$loan_percentage."%</h4>
                          </br>
                            <p><b>REMARKS : </b><font color='green'>".$row['remarks']."</font></p></center>

                          <div id='myAlert4' class='modal hide'>
                           <form action='".base_url("index.php/main/deleteLoan/".$row['loan_id'])."' method='post' class='form-horizontal'>
                              <div class='modal-header'>
                                <button data-dismiss='modal' class='close' type='button'>×</button>
                                <h3>Delete Loan</h3>
                              </div>
                              <div class='modal-body'>
                                  <center>

                                  <div class='alert alert-error alert-block'> 
                                  <h4 class='alert-heading'>Warning!</h4>
                                  <br>
                                  Deleting Loan for Consumer : <b>".substr($row['date_installed'],0,4).substr($row['date_installed'],4,4).str_pad($row['account_no'], 4, '0', STR_PAD_LEFT)."</b><br>

                                   Are you sure?

                                  </div>
                                 
                                  </center>

                               </div> 
                                    <div class='modal-footer'><button type='submit' class='btn btn-primary'>Confirm</button></a>&nbsp;<a data-dismiss='modal' class='btn' href='#''>Cancel</a> </div>
                              </form>
                            </div>";
                      }
                
                  ?>
              <hr>        
          </div>
        </div>
     </div>  
       
    </div>
  </div>
</div>      
      
<?php include "footer.php";?>

<?php include "form_scripts.php";?>
</body>
</style>
</html>
