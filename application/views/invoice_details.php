<!DOCTYPE html>

<?php 
  if ($this->session->userdata('logged_in') == "admin"){
    include "nav_top_menu_admin.php";
  }
  else if ($this->session->userdata('logged_in') == "cashier"){
    include "nav_top_menu_cashier.php";
  }
  else if ($this->session->userdata('logged_in') == "accounting"){
    include "nav_top_menu_acc.php";
  }
?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href=""><i class="icon-home"></i>Manage Billing<a href="<?php echo base_url('index.php/main/viewBilledConsumers');?>" title="Go to Manage Bills" class="tip-bottom">Manage Bills</a>
    <?php

      foreach ($invoice_details as $row){
        echo "<a href='".base_url('index.php/main/viewBill/'.$row['bill_id'])."' title='Go to Bill Details' class='tip-bottom'>Bill Details</a>";
      }

    ?>
      <a href="#" class="current">Invoice</a> </div>
    <?php echo $this->session->flashdata("info");
            echo $this->session->flashdata("msg_info");?>
    <h1>Invoice</h1>
  </div>
  <div class="container-fluid">
  
      	<div class="row-fluid">
        
            <div class="span12">
                
                <div class="buttons" style="padding-left:10px;padding-top:10px;">
                  <div class="btn-group">
                   <button data-toggle="dropdown" class="btn dropdown-toggle"><i class="icon-cog"></i>Options <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                    <li><a href="#myAlert2" data-toggle="modal"><i class="icon-edit"></i> Edit Invoice </a></li>
                    <li>  <a href="#myAlert3" data-toggle="modal"><i class="icon-trash "></i> Delete Invoice</a></li>
                    </ul>
                  </div>
                  <?php
                      foreach ($invoice_details as $row){


                        echo "<a href='".base_url('index.php/main/viewBill/'.$row['bill_id'])."' class='btn btn-warning' style='float:right;'><i class='icon-arrow-left'></i>&nbsp;Previous Page</a>";

                      }
                  ?>
                </div>
              <div class="widget-box">
                <div class="widget-title">
                   <span class="icon"><i class="icon-th"></i></span> 
      			 	  
                  <h5>Invoice Details</h5>

                </div>
                
      	         
              <div class='widget-content'>  

                  <div id="myAlert2" class="modal hide">
                  <?php
                    foreach ($invoice_details as $row){
                     echo "<form action='".base_url('index.php/main/updateInvoice/'.$row['bill_id'])."' method='post' class='form-horizontal'>
                        <div class='modal-header'>
                          <button data-dismiss='modal' class='close' type='button'>×</button>
                          <h3>Edit Invoice</h3>
                        </div>
                        <div class='modal-body'>
                         
                          <div class='control-group'>
                            <label class='control-label'><strong>OR No. :</strong></label>
                            <div class='controls'>
                              <input type='number' name='or_no' class='span11' placeholder='OR Number' value='".$row['OR_no']."'/>
                            </div>
                          </div>

                           <div class='control-group'>
                            <label class='control-label'><strong>OR Date :</strong></label>
                            <div class='controls'>
                              <input type='date' name='or_date' class='span11' value='".$row['or_date']."'/>
                            </div>
                          </div>

                          <div class='control-group'>
                            <label class='control-label'><strong>Prepared By :</strong></label>
                            <div class='controls'>
                              <input type='text' name='prepared_by' class='span11' placeholder='Prepared By' value='".$row['user_id']."'/>
                            </div>
                          </div>

                          <div class='control-group'>
                            <label class='control-label'><strong>Remarks :</strong></label>
                            <div class='controls'>
                             <input class='span11' name='remarks' value='".$row['remarks']."'></input>
                            </div>
                          </div>
                   </div> 
                        <div class='modal-footer'><button type='submit' class='btn btn-primary'>Save</button></a>&nbsp;<a data-dismiss='modal' class='btn' href='#'>Cancel</a> </div>
                  </form>
                  </div>";
                }
              ?>

                 <?php
                foreach ($invoice_details as $row){

                 echo "<div class='widget-box'>
                           
                  <div class='widget-content'>";      

                  echo "
                     
                      <div class='row-fluid'>
                        <div class='span3'></div>

                   
                         <div class='span4'>

                              <p><b>ACCOUNT NO : </b>".substr($row['date_installed'],0,4).substr($row['date_installed'],4,4).str_pad($row['account_no'], 4, '0', STR_PAD_LEFT)."</p>

                               <p><b>NAME  OF PAYEE : </b>".$row['lastname'].", ".$row['firstname']." ".substr($row['middlename'],0,1).".".".</p>";

                           echo "</div>
                    
                      <div class='span4'>

                                 <p><b>ADDRESS : </b>".$row['address']."</p>";

                                 if ($row['status'] == "Active"){
                                  echo "<p><b>STATUS : </b><span class='label label-success'>".$row['status']."</span></p>";
                                }
                                else if ($row['status'] == "Pending"){
                                  echo "<p><b>STATUS : </b><span class='label label-warning'>".$row['status']."</span></p>";
                                }
                                else if ($row['status'] == "Disconnected"){
                                  echo "<p><b>STATUS : </b><span class='label label-important'>".$row['status']."</span></p>";
                                }
                                else if ($row['status'] == "For Disconnection"){
                                  echo "<p><b>STATUS : </b><span class='label label-important'>".$row['status']."</span></p>";
                                }    

                     echo "

                      </div>

                      <div class='span3'></div>
                      
                    </div>";    
                       
                        
                 echo   "</div>
                 </div>

                         <div id='myAlert3' class='modal hide'>
                           <form action='".base_url("index.php/main/deleteInvoice/".$row['OR_no'])."' method='post' class='form-horizontal'>
                              <div class='modal-header'>
                                <button data-dismiss='modal' class='close' type='button'>×</button>
                                <h3>Delete Invoice</h3>
                              </div>
                              <div class='modal-body'>
                                  <center>

                                  <div class='alert alert-error alert-block'> 
                                  <h4 class='alert-heading'>Warning!</h4>
                                  <br>
                                  Deleting Invoice for Consumer : <b>".substr($row['date_installed'],0,4).substr($row['date_installed'],4,4).str_pad($row['account_no'], 4, '0', STR_PAD_LEFT)."</b> with OR No.: <b>".$row['OR_no']."</b><br>

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
                <div class="row-fluid">
                  <div class="span2">
                  </div>
                  <div class="span8">
                    <table class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>Nature of Collection</th>
                          <th>Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          foreach ($invoice_details as $row){

                            $or_date = $row['or_date'];
                            $new_ordate = date('Y-F-d', strtotime($or_date));
                            
                            echo "<tr class='odd gradeX'>
                                    <td>Water Fee</td>
                                    <td>".number_format($row['water_fee'], 2, '.', ',')."</td>
                                  </tr>
                                   <tr class='odd gradeX'>
                                    <td>Installation Fees</td>
                                    <td>".number_format($row['installation_fees'], 2, '.', ',')."</td>
                                  </tr>
                                 
                                 <tr class='odd gradeX'>
                                    <td>Meter Fee</td>
                                    <td>".number_format($row['meter_fee'], 2, '.', ',')."</td>
                                  </tr>               
                                  <tr class='odd gradeX'>
                                    <td>Penalty Fee</td>
                                    <td>".number_format($row['penalty'], 2, '.', ',')."</td>
                                  </tr>
                                  <tr class='odd gradeX'>
                                    <td>Material Loan Program</td>
                                    <td>".number_format($row['mlp'], 2, '.', ',')."</td>
                                  </tr>
                                   <tr class='odd gradeX'>
                                    <td>Reconnection Fee</td>
                                    <td>".number_format($row['reconnection_fee'], 2, '.', ',')."</td>
                                  </tr>
                                  <tr class='odd gradeX'>
                                    <td>Others</td>
                                    <td>".number_format($row['others'], 2, '.', ',')."</td>
                                  </tr>
                                   <tr class='odd gradeX'>
                                    <td>Discount</td>
                                    <td>".$row['discount']."%"."</td>
                                  </tr>
                                     <tr class='odd gradeX'>
                                      <td><h4>TOTAL : </h4></td>
                                      <td><h4>".number_format($row['total_amount'], 2, '.', ',')."</h4></td>
                                  </tr>";

                                  echo "<center><p><b>OR No. : </b>".$row['OR_no']."</p>
                                  <p><b>OR Date : </b>".$new_ordate."</p>
                                   <div class='text' style='font-family: consolas;''>
                                   <p>Prepared By : <font color='blue'>".$row['user_id']."</font></p>
                                </div></center><hr>";
                                
                                break;

                        }
                      
                      echo " 
                      </tbody>
                    </table>
                    <hr>
                    <center>
                     <p><b>REMARKS : </b><font color='green'>".$row['remarks']."</font></p>
                     </center>";
                    ?>
                  </div>
                   <div class="span2">
                  </div>
              </div>                
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
