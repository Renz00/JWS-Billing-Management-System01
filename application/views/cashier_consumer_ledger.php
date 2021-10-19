<!DOCTYPE html>

<?php include "nav_top_menu_cashier.php";?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#"><i class="icon-home"></i> Manage Billing</a><a href="<?php echo base_url('index.php/main/viewConsumers')?>" title="Go to Manage Consumers" class="tip-bottom">Manage Consumers</a> <a href="#" class="current">Customer Ledger</a> </div>
    
    <h1>Customer Ledger</h1>
  </div>

  <div class="container-fluid">
  
        <div class="row-fluid">
            <div class="span12">
                <div class="buttons" style="padding-left:10px;padding-top:10px;">
                
                  <div class="btn-group">
                  </div>
                </div>
                  <a href="<?php echo base_url('index.php/main/viewConsumers')?>" class="btn btn-warning" style="float:right;"><i class="icon-arrow-left"></i>&nbsp;Previous Page</a><br><br>

              </div>
              <div class="widget-box">
                <div class="widget-title">
                   <span class="icon"><i class="icon-th"></i></span> 
                
                  <h5>Basic Information</h5>

                </div>

                 
                 
              <div class='widget-content'>  

                       <div class="row-fluid">
                        <div class="span3"></div>

                      <?php
                        foreach ($consumer_details as $row)

                              $birthyr = substr($row['birth_date'],0,4);
                              $curryr = date('Y');
                              $age = ($curryr - $birthyr);

                              $bdate = $row['birth_date'];
                              $new_bdate = date('Y-F-d', strtotime($bdate));

                              $date_installed = $row['date_installed'];
                              $new_dinstalled = date('Y-F-d', strtotime($date_installed));

                              $inspection_date = $row['inspection_date'];
                              $new_inspectdate = date('Y-F-d', strtotime($inspection_date));

                        echo "<div class='span4'>
                             <p><b>ACCOUNT NO : </b>".substr($row['date_installed'],0,4).substr($row['date_installed'],4,4).str_pad($row['account_no'], 4, '0', STR_PAD_LEFT)."</p>

                             <p><b>NAME : </b>".$row['lastname'].", ".$row['firstname']." ".substr($row['middlename'],0,1)."."."</p>
                             <p><b>BIRTHDATE : </b>".$new_bdate."</p>
                             <p><b>ADDRESS : </b>".$row['address'].", <em>".$row['specific_address']."</em></p>
                              <p><b>PHONE NUMBER : </b>".$row['phonenumber']."</p>";

                              if ($row['status'] == "Active"){

                               echo "<p><b>STATUS : </b><span class='label label-success'>".$row['status']."</span></div>";
                              }

                              else if ($row['status'] == "Pending"){

                               echo "<p><b>STATUS : </b><span class='label label-warning'>".$row['status']."</span></div>";
                              }

                              else if ($row['status'] == "Disconnected"  || $row['status'] == "For Disconnection"){

                               echo "<p><b>STATUS : </b><span class='label label-important'>".$row['status']."</span></div>";
                              }


                        echo "<div class='span4'>

                              <p><b>DATE INSTALLED : </b>".$new_dinstalled."</p>
                              <p><b>CONNECTION TYPE : </b>".$row['rate_id']."</p>
                              <p><b>METER SERIAL NO : </b>".$row['meter_id']."</p>
                              <p><b>BRAND : </b>".$row['meter_brand']."</p>
                              <p><b>INSPECTOR : </b>".$row['name']."</p>
                              <p><b>INSPECTION DATE : </b>".$new_inspectdate."</p>
                         </div>";

                      ?>
                      <div class="span3"></div>
                      <hr>
                    </div>

          </div>
        </div>

        <div class="row-fluid">
          <div class="span12">
            <div class="widget-box">
              <div class="widget-title"> <span class="icon"> <i class="icon-signal"></i> </span>
                <h5>Ledger Records</h5>
              </div>
              <div class="widget-content nopadding">
                   <table class="table table-bordered data-table">
                      <thead>
                         <tr style="font-family: arial;">
                          <th>Count</th>
                          <th>Reading Date</th>
                          <th>Meter Reading</th>
                           <th>CU Used</th>
                           <th>Penalty</th>
                           <th>Bill Amount</th>
                           <th>Material Loan</th>
                           <th>Meter Fee</th>
                           <th>Misc</th>
                           <th>Total</th>
                           <th>OR No</th>
                           <th>OR Date</th>
                           <th>Remarks</th>
                        </tr>
                      </thead>
                        <tbody>
                        <?php
                            $count=1;
                            foreach ($ledger_data as $row){

                                if ($row['bill_status'] == "Paid"){

                                  $reading_date = $row['reading_date'];
                                  $new_readdate = date('Y-F-d', strtotime($reading_date));

                                  echo "<tr class='gradeA'>
                                    <td>".$count."</td>
                                    <td>".$new_readdate."</td>
                                    <td>".$row['meter_reading']."</td>
                                    <td>".$row['water_usage']."</td>
                                    <td>".$row['penalty']."</td>
                                    <td>".$row['water_fee']."</td>
                                    <td>".$row['mlp']."</td>
                                    <td>".$row['meter_fee']."</td>
                                    <td>".$row['others']."</td>";

                                  echo "<td>".$row['total_amount']."</td>
                                    <td>".$row['OR_no']."</td>
                                    <td>".$row['or_date']."</td>
                                    <td>".$row['remarks']."</td>";

                                  // echo "<td><center><a href='#edit_".$row['OR_no']."' data-toggle='modal'><button class='btn btn-success btn-lg'><i class='icon icon-edit'></i>&nbsp;Edit Row</button></a>&nbsp;<a href='#''><button class='btn btn-danger btn-lg'><i class='icon icon-remove-circle'></i>&nbsp;Delete</button></a></td>
                                  // </tr>

                                  // <div id='edit_".$row['OR_no']."' class='modal hide'>
                                  //    <form action='".base_url("index.php/main/editLedgerRecord/".$row['OR_no'])."' method='post' class='form-horizontal'>
                                  //       <div class='modal-header'>
                                  //         <button data-dismiss='modal' class='close' type='button'>×</button>
                                  //         <h3>Edit Ledger Record</h3>
                                  //       </div>
                                  //       <div class='modal-body'>

                                  //       <div class='control-group'>
                                  //         <label class='control-label'><strong>Reading Date :</strong></label>
                                  //         <div class='controls'>
                                  //            <input type='date' name='reading_date' class='span11' value='".$row['reading_date']."' required/>
                                  //          </div>
                                  //      </div>
                                       
                                  //       <div class='control-group'>
                                  //         <label class='control-label'><strong>Meter Reading:</strong></label>
                                  //         <div class='controls'>
                                  //            <input type='number' name='meter_reading' class='span11' placeholder='Meter Reading' value='".$row['meter_reading']."' required/>
                                  //          </div>
                                  //      </div>
                                       
                                  //      <div class='control-group'>
                                  //         <label class='control-label'><strong>CU Used:</strong></label>
                                  //         <div class='controls'>
                                  //            <input type='number' name='water_usage' class='span11' placeholder='CU Used' value='".$row['water_usage']."' required/>
                                  //          </div>
                                  //      </div>
                                       
                                  //      <div class='control-group'>
                                  //         <label class='control-label'><strong>Penalty Fee:</strong></label>
                                  //         <div class='controls'>
                                  //            <input type='number' name='penalty' class='span11' placeholder='Penalty Fee' value='".$row['penalty']."' required/>
                                  //          </div>
                                  //      </div>
                                       
                                  //       <div class='control-group'>
                                  //         <label class='control-label'><strong>Bill Amount:</strong></label>
                                  //         <div class='controls'>
                                  //            <input type='number' name='water_fee' class='span11' placeholder='Bill Amount' value='".$row['water_fee']."' required/>
                                  //          </div>
                                  //      </div>
                                      
                                  //      <div class='control-group'>
                                  //         <label class='control-label'><strong>Loan MLP:</strong></label>
                                  //         <div class='controls'>
                                  //            <input type='number' name='mlp' class='span11' placeholder='Loan MLP' value='".$row['mlp']."' required/>
                                  //          </div>
                                  //      </div>
                                      
                                  //      <div class='control-group'>
                                  //         <label class='control-label'><strong>Loan MF:</strong></label>
                                  //         <div class='controls'>
                                  //            <input type='number' name='meter_fee' class='span11' placeholder='Loan MF' value='".$row['meter_fee']."' required/>
                                  //          </div>
                                  //      </div>

                                  //      <div class='control-group'>
                                  //         <label class='control-label'><strong>Miscellaneous:</strong></label>
                                  //         <div class='controls'>
                                  //            <input type='number' name='others' class='span11' placeholder='Miscellaneous Fees' value='".$row['others']."' required/>
                                  //          </div>
                                  //      </div>
                                      
                                  //      <div class='control-group'>
                                  //         <label class='control-label'><strong>Total Amount:</strong></label>
                                  //         <div class='controls'>
                                  //            <input type='number' name='amount_due' class='span11' placeholder='Total' value='".$row['amount_due']."' required/>
                                  //          </div>
                                  //      </div>
                                       
                                  //      <div class='control-group'>
                                  //         <label class='control-label'><strong>OR Date:</strong></label>
                                  //         <div class='controls'>
                                  //            <input type='date' name='date' class='span11' placeholder='OR Date' value='".$row['date']."' required/>
                                  //          </div>
                                  //      </div>
                                        
                                  //     </div> 
                                  //             <div class='modal-footer'><button type='submit' class='btn btn-primary'>Save</button></a>&nbsp;<a data-dismiss='modal' class='btn' href='#''>Cancel</a> </div>
                                  //       </form>
                                  //     </div>
                                  // ";
                                  $count+=1;
                                }
                            }

                       ?>
               </tbody>
            </table>  
              </div>
            </div>
        </div>
      </div>

     </div>  

    </div>
  </div>
</div>      
      
<?php include "footer.php";?>

<?php include "form_scripts.php";?>
<style>
  .modal{
    z-index: 99999;
  }
</style>
</body>
</html>
