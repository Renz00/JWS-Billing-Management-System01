<!DOCTYPE html>

<?php include "nav_top_menu_cashier.php";?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#"><i class="icon-home"></i> Manage Billing</a><a href="<?php echo base_url('index.php/main/viewBilledConsumers')?>" title="Go to Bills" class="tip-bottom">Manage Bills</a> <a href="#" class="current">Bill Details</a> </div>
    <?php echo $this->session->flashdata("info");?>
    <h1>Manage Bills</h1>
  </div>
  <div class="container-fluid">
  
        <div class="row-fluid">
        
            <div class="span12">
                <div class="buttons" style="padding-left:10px;padding-top:10px;">
                  <div class="btn-group">
                  </div>
                </div>
                 <a href="<?php echo base_url('index.php/main/viewBilledConsumers')?>" class="btn btn-warning" style="float:right;"><i class="icon-arrow-left"></i>&nbsp;Previous Page</a><br>
              <div class="widget-box">
                <div class="widget-title">
                   <span class="icon"><i class="icon-th"></i></span> 
                
                  <h5>Water Bill</h5>

                </div>
                
                <div id="myAlert3" class="modal hide">
                <?php
                  foreach ($bill_details as $row){
                  echo "<form action='". base_url('index.php/main/addInvoice/'.$row['bill_id'])."' method='post' class='form-horizontal'>";
                  }
                ?>
                      <div class="modal-header">
                        <button data-dismiss="modal" class="close" type="button">×</button>
                        <h3>Create Invoice</h3>
                      </div>
                      <div class="modal-body">
                       
                        <div class="control-group">
                          <label class="control-label"><strong>OR No. :</strong></label>
                          <div class="controls">
                            <input type="number" name="or_no" class="span11" placeholder="OR Number" />
                          </div>
                        </div>

                        <div class="control-group">
                          <label class="control-label"><strong>Remarks :</strong></label>
                          <div class="controls">
                           <textarea class="span11" name="remarks" value=""></textarea>
                          </div>
                        </div>

                 </div> 
                      <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></a>&nbsp;<a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
                </form>
                </div>               
                 
              <div class='widget-content'>  
         <?php
                foreach ($bill_details as $row){

                  $date = strtotime($row['date_created']); 
                  $bill_month = date("F Y", strtotime("-0 month", $date));

                 echo "
                 <center><h4>BILLING MONTH: <font color='blue'>".$bill_month."</font></h4></center>
                 <div class='widget-box'>
                           
                  <div class='widget-content'>";          
                       
                        echo "<div class='row-fluid'>
                            <div class='span3'></div>

                            <div class='span4'>
                              <p><b>ACCOUNT NO : </b>".substr($row['date_installed'],0,4).substr($row['date_installed'],4,4).str_pad($row['account_no'], 4, '0', STR_PAD_LEFT)."</p>

                                 <p><b>NAME : </b>".$row['lastname'].", ".$row['firstname']." ".substr($row['middlename'],0,1)."."."</p>

                                 <p><b>ADDRESS : </b>".$row['address']."</p>";

                            echo "</div>
                            <div class='span4'>";

                              
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


                                echo "<p><b>CONNECTION TYPE : </b>".$row['rate_id']."</p>
                                  <p><b>METER SERIAL NO : </b>".$row['meter_id']."</p>
                                 
        
                           </div> 

                              
                          <div class='span3'></div>
                          
                        </div>
                    </div>
                 </div>";
                     }
      ?>
              
                <hr>

                <div class="row-fluid">
                  <div class="span2">
                  </div>
                  <div class="span8">
             <?php
                foreach ($bill_details as $row){
                       
                        echo "<div class='row-fluid'>
                            <div class='span3'></div>
                            <div class='span4'>
                            <p><b>INSTALLATION FEES : </b>".$row['installation_fees']."</p>                   
                                  <p><b>METER FEE : </b>".$row['meter_fee']."</p>
                                   <p><b>PENALTY : </b>".$row['penalty']."</p>
                           </div> 
                              <div class='span4'>
                                 
                                  <p><b>MATERIAL LOAN PROGRAM : </b>".$row['mlp']."</p>
                                  <p><b>INTEREST : </b>".$row['interest']."</p>
                                  <p><b>RECONNECTION FEE : </b>".$row['reconnection_fee']."</p>
                                   <p><b>OTHERS : </b>".$row['others']."</p>
            
                            </div>
                          <div class='span4'></div>";

                         
                        echo "</div>
                    
                      <div class='span3'></div>
                     
                      <hr> ";
                     break;
                     }
          ?>
                  </div>
                   <div class="span2">
                  </div>
              </div>

                <div class="row-fluid">
                  <div class="span2">
                  </div>
                  <div class="span8">
                    <table class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>Connection Type</th>
                          <th>Charge</th>
                        </tr>
                      </thead>
                      <tbody>
                    <?php
                        foreach ($bill_details as $row){                          

                          $water_usage = $row['water_usage'];

                           echo "<tr class='odd gradeX'>
                              <td><center>".$row['rate']."</td>";

                         if ($water_usage != 0 && $water_usage <= 10){ 

                           echo "<td><center>".$row['rate_amt']."</td></tr>";

                          }

                          else if ($water_usage > 10){

                           echo "<td><center><b>".$row['rate_amt']." "."<em>per Cubic Meter</em>"."</td></tr>";
                            
                          }
                          else echo "<td><center><em>none</emp</td></tr>";
                         
                            break;

                          }
                        ?>
                      </tbody>
                    </table>

                    <table class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>Present</th>
                          <th>Previous Bill</th>
                          <th>Cubic Meter Used</th>
                          <th>Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                    <?php
                        foreach ($bill_details as $row){

                           echo "<tr class='odd gradeX'>
                              <td>".$row['curr_reading']."</td>";

                        
                            echo "<td>".$row['prev_reading']."</td>";
                  

                             echo "<td>".$row['water_usage']."</td>
                              <td class='center'>".number_format($row['water_fee'], 2, '.', ',')."</td>
                            </tr>";

                            break;

                          }
                        ?>
                      </tbody>
                    </table>

                  </div>
                   <div class="span2">
                  </div>
              </div>

                <center>
                  <hr>
                   
                     <?php

                        foreach ($bill_details as $row){

                          $date_created = $row['date_created'];
                          $new_datecreate = date('Y-F-d', strtotime($date_created));

                           if ($row['due_date']==0000-00-00){

                            $new_due = "NONE";

                          }
                          else {

                            $new_due = date('Y-F-d', strtotime($row['due_date']));
                             
                          }

                          $date_paid = $row['date_paid'];
                          $new_dpaid = date('Y-F-d', strtotime($date_paid));

                           echo "
                           <h4>AMOUNT DUE (Php) : ".number_format($row['total_amount'], 2, '.', ',')."</h4>
                           <p><strong>DISCOUNT :</strong> ".$row['discount']."%</p><hr>";

                            echo  "<div class='text' style='font-family: consolas;''>
                              <p>Prepared By : <font color='blue'>".$row['bill_creator']."</font></p>
                              <p>DATE CREATED : <b>".$new_datecreate."</b></p>                            
                               <p>DUE DATE : <font color='red'>".$new_due."</font></p>";
                            
                             if ($row['bill_status']=='Paid'){
                              echo "<p>DATE PAID : <b><font color='green'>".$new_dpaid."</font></b></p>";
                            }
                            
                             echo "</div><hr>";

                          if ($row['bill_status'] == "Billed"){ 
                          echo "<h4 class='center'>Bill Status : <font color='orange'>".$row['bill_status']."</font></h4>
            
                            <a href='#myAlert3' data-toggle='modal' class='btn btn-warning btn-large'><i class='icon-print'></i> Create Invoice </a>";
                              break;
                            }

                          else if ($row['bill_status'] == "Paid"){ 
                          echo "<h4 class='center'>Bill Status : <font color='green'>".$row['bill_status']."</font></h4>

                           <a href='".base_url('index.php/main/viewInvoiceDetails/'.$row['bill_id'])."' class='btn btn-success btn-large'><i class='icon-eye-open'></i> View Invoice </a>
                           ";
                             break;
                            }

                          else if ($row['bill_status'] == "For Disconnection"){ 
                          echo "<h4 class='center'>Bill Status : <font color='red'>".$row['bill_status']."</font></h4>

                           <a href='#myAlert3' data-toggle='modal' class='btn btn-warning btn-large'><i class='icon-print'></i> Create Invoice </a>";
                              break;
                            }

                          }
                        ?>   
                  
                
              </center>
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
