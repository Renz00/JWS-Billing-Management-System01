<!DOCTYPE html>

<?php include "nav_top_menu_acc.php";?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"><a href="#"><i class="icon-home"></i> Manage Billing</a><a href="<?php echo base_url('index.php/main/viewConsumers')?>" title="Go to Manage Consumers" class="tip-bottom">Manage Consumers</a> <a href="#" class="current">Customer Ledger</a> </div>
    <?php echo $this->session->flashdata("info");?>
    <h1>Customer Ledger</h1>
  </div>

  <div class="container-fluid">
  
        <div class="row-fluid">
            <div class="span12">
                <div class="buttons" style="padding-left:10px;padding-top:10px;">
                  <a href="#myAlert5" class="btn btn-primary" data-toggle="modal"><i class="icon-print"></i> Print Ledger</a>
                  <div class="btn-group">
                    <button data-toggle="dropdown" class="btn dropdown-toggle"><i class="icon-cog"></i>Options <span class="caret"></span></button>
                    <ul class="dropdown-menu">          
                        <li><a href="#myAlert2" data-toggle="modal"><i class="icon-edit"></i> Edit Details </a></li>
                  </ul>
                  </div>

                  <div id="myAlert5" class="modal hide">
                
                <?php
                  foreach ($consumer_details as $row){
                    echo "<form action='".base_url('index.php/main/printConsumerLedger/'.$row['account_no'])."' method='post' class='form-horizontal'>";
                  }
                ?>

                      <div class="modal-header">
                        <button data-dismiss="modal" class="close" type="button">×</button>
                        <h3>Print Customer Ledger</h3>
                      </div>
                      <div class="modal-body">

                        <?php

                          $curr_month = date('m');
                          $curr_year = date('Y');

                         echo "<div class='control-group'>

                          <label class='control-label'><strong><em>FROM</em> Month :</strong></label>
                          <div class='controls'>
                            <input type='number' name='month1' class='span11' min='1' max='".$curr_month."' placeholder='Month' value='".$curr_month."' required/>
                          </div>

                          <label class='control-label'><strong>Year :</strong></label>
                          <div class='controls'>
                            <input type='number' name='year1' class='span11' min='1799' max='".$curr_year."' placeholder='Year' value='".$curr_year."' required/>
                          </div>
                        </div>";

                        echo "<div class='control-group'>

                          <label class='control-label'><strong><em>TO</em> Month :</strong></label>
                          <div class='controls'>
                            <input type='number' name='month2' class='span11' min='1' max='".$curr_month."' placeholder='Month' value='".$curr_month."' required/>
                          </div>

                          <label class='control-label'><strong>Year :</strong></label>
                          <div class='controls'>
                            <input type='number' name='year2' class='span11' min='1799' max='".$curr_year."' placeholder='Year' value='".$curr_year."' required/>
                          </div>
                        </div>";
                        
                      ?>
                        <div class="control-group">
                            <label class="control-label"><strong>Records :</strong></label>
                            <div class="controls">
                              <select name="records" class="span11">
                                  <option>All Records</option>
                                <?php
                                
                                $count = count($ledger_data);
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
                      <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></a>&nbsp;<a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
                </form>
                </div>

                 
                  <a href="<?php echo base_url('index.php/main/viewConsumers')?>" class="btn btn-warning" style="float:right;"><i class="icon-arrow-left"></i>&nbsp;Previous Page</a>
                </div>
              <div class="widget-box">
                <div class="widget-title">
                   <span class="icon"><i class="icon-th"></i></span> 
                
                  <h5>Basic Information</h5>

                </div>

                 <?php echo $this->session->flashdata("bill_info");?>
                 
              <div class='widget-content'>  

                 <div id="myAlert2" class="modal hide">

                  <?php
                    foreach ($consumer_details as $row){
                      echo "<form class='form-horizontal' method='post' action='".base_url("index.php/main/editConsumerDetails/".$row['account_no'])."'>";
                     }
                   ?>

                      <div class="modal-header">
                        <button data-dismiss="modal" class="close" type="button">×</button>
                        <h3>Edit Consumer Details</h3>
                      </div>
                      <div class="modal-body">
                      <?php
                          foreach ($consumer_details as $row){

                              echo  "<div class='control-group'>
                                  <label class='control-label'><strong>First Name :</strong></label>
                                  <div class='controls'>
                                    <input type='text' name='fname' class='span11' placeholder='First Name' value='".$row['firstname']."' required/>
                                  </div>
                                </div>

                                 <div class='control-group'>
                                  <label class='control-label'><strong>Middle Name :</strong></label>
                                  <div class='controls'>
                                    <input type='text' name='mname' class='span11' placeholder='Middle Name' value='".$row['middlename']."'/>
                                  </div>
                                </div>

                                 <div class='control-group'>
                                  <label class='control-label'><strong>Last Name :</strong></label>
                                  <div class='controls'>
                                    <input type='text' name='lname' class='span11' placeholder='Last Name' value='".$row['lastname']."' required/>
                                  </div>
                                </div>

                                 <div class='control-group'>
                                  <label class='control-label'><strong>Type of Connection :</strong></label>";

                                if ($row['rate_id'] == "Residential_Institutional"){
                                    echo "<div class='controls'>
                                        <label>
                                          <input type='radio' name='radios2' value='Residential_Institutional' checked/>
                                         Residential / Institutional</label>
                                        <label>
                                          <input type='radio' name='radios2' value='Commercial'/>
                                         Commercial</label>
                                      </div>";
                                }

                                else if ($row['rate_id'] == "Commercial"){
                                    echo "<div class='controls'>
                                        <label>
                                          <input type='radio' name='radios2' value='Residential/Institutional'/>
                                         Residential / Institutional</label>
                                        <label>
                                          <input type='radio' name='radios2' value='Commercial' checked/>
                                         Commercial</label>
                                      </div>";
                                }

                               echo  "</div>
                               <div class='control-group'>
                                  <label class='control-label'><strong>Date of Birth :</strong></label>
                                  <div class='controls'>
                                    <input type='date' name='bdate' class='span11' placeholder='Birth Date' value='".$row['birth_date']."' required/>
                                  </div>
                                </div>

                                 <div class='control-group'>
                                    <label class='control-label'><strong>Address (Barangay) :</strong></label>
                                    <div class='controls'>
                                      <select name='address' class='span11' style='z-index:99999 !important; overflow-y: inherit;'>";
                                      
                                      if ($row['address'] == "Binanuahan")
                                       echo "<option selected>Binanuahan</option>";
                                     else echo "<option>Binanuahan</option>";

                                     if ($row['address'] == "Biriran")
                                        echo "<option selected>Biriran</option>";
                                    else echo "<option>Biriran</option>";

                                     if ($row['address']== "Catanagan")
                                        echo "<option selected>Catanagan</option>";
                                    else echo "<option>Catanagan</option>";

                                     if ($row['address'] == "Cogon")
                                        echo "<option selected>Cogon</option>";
                                    else echo "<option>Cogon</option>";

                                     if ($row['address'] == "Embarcadero")
                                        echo "<option selected>Embarcadero</option>";
                                    else echo "<option>Embarcadero</option>";

                                    if ($row['address'] == "North Poblacion")
                                        echo "<option selected>North Poblacion</option>";
                                    else echo "<option>North Poblacion</option>";

                                     if ($row['address'] == "South Poblacion")
                                        echo "<option selected>South Poblacion</option>";
                                    else echo "<option>South Poblacion</option>";

                                    if ($row['address'] == "Taboc")
                                        echo "<option selected>Taboc</option>";
                                    else echo "<option>Taboc</option>";

                                     if ($row['address'] == "Tughan")
                                        echo "<option selected>Tughan</option>";
                                    else echo "<option>Tughan</option>";


                                     echo "</select>
                                    </div>
                                  </div>
                                  
                                <div class='control-group'>
                                  <label class='control-label'><strong>Specific Address :</strong></label>
                                  <div class='controls'>
                                    <input type='text' name='specific' class='span11' placeholder='Specific Address' value='".$row['specific_address']."' required/>
                                  </div>
                                </div>

                                <div class='control-group'>
                                    <label class='control-label'><strong>Phone Number :</strong></label>
                                    <div class='controls'>
                                      <div class='input-prepend'> <span class='add-on'>(+63)</span>
                                  <input type='text' name='phonenum' placeholder='Phone Number' value='".$row['phonenumber']."' class='span12' required>
                                  </div>
                                </div>
                               

                              <div class='control-group'>
                                <label class='control-label'><strong>Status :</strong></label>
                                <div class='controls'>";

                                if ($row['status'] == "Active"){
                                   echo "<label>
                                      <input type='radio' name='radios' value='Active' checked/>
                                      Active</label>
                                    <label>
                                      <input type='radio' name='radios' value='Pending'/>
                                      Pending</label>
                                    <label>
                                      <input type='radio' name='radios' value='For Disconnection'/>
                                      For Disconnection</label>
                                    <label>
                                      <input type='radio' name='radios' value='Disconnected'/>
                                      Disconnected</label>";
                                }

                                else if ($row['status'] == "Pending"){
                                   echo "<label>
                                      <input type='radio' name='radios' value='Active'/>
                                      Active</label>
                                    <label>
                                      <input type='radio' name='radios' value='Pending' checked/>
                                      Pending</label>
                                    <label>
                                      <input type='radio' name='radios' value='For Disconnection'/>
                                      For Disconnection</label>
                                    <label>
                                      <input type='radio' name='radios' value='Disconnected'/>
                                      Disconnected</label>";
                                }

                                else if ($row['status'] == "For Disconnection"){
                                   echo "<label>
                                      <input type='radio' name='radios' value='Active'/>
                                      Active</label>
                                    <label>
                                      <input type='radio' name='radios' value='Pending'/>
                                      Pending</label>
                                    <label>
                                      <input type='radio' name='radios' value='For Disconnection' checked/>
                                      For Disconnection</label>
                                    <label>                                   
                                      <input type='radio' name='radios' value='Disconnected'/>
                                      Disconnected</label>";
                                }

                                else if ($row['status'] == "Disconnected"){
                                   echo "<label>
                                      <input type='radio' name='radios' value='Active'/>
                                      Active</label>
                                    <label>
                                      <input type='radio' name='radios' value='Pending'/>
                                      Pending</label>
                                    <label>
                                      <input type='radio' name='radios' value='For Disconnection'/>
                                      For Disconnection</label>
                                    <label>
                                      <input type='radio' name='radios' value='Disconnected' checked/>
                                      Disconnected</label>";
                                }


                          echo   "
                                </div>
                             </div>
                             <div class='control-group'>
                                  <label class='control-label'><strong>Inspector :</strong></label>
                                  <div class='controls'>
                                    <input type='text' name='inspector' class='span11' placeholder='Inspector' value='".$row['name']."' required/>
                                  </div>
                                </div>


                               <div class='control-group'>
                                  <label class='control-label'><strong>Date of Inspection :</strong></label>
                                  <div class='controls'>
                                    <input type='date' name='inspection_date' class='span11' placeholder='Date of Inpection' value='".$row['inspection_date']."' required/>
                                  </div>
                                </div>

                          
                        </div>";

                            
                       }
                     ?>
                 </div> 
                      <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></a>&nbsp;<a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
                </form>
                </div>

                
                 

                <div id="myAlert7" class="modal hide">

                  <?php
                    foreach ($consumer_details as $row){
                      echo "<form action='".base_url("index.php/main/updateMeterDetails/".$row['meter_id'])."' method='post' class='form-horizontal'>";
                     }
                   ?>

                      <div class="modal-header">
                        <button data-dismiss="modal" class="close" type="button">×</button>
                        <h3>Edit Meter Details</h3>
                      </div>
                      <div class="modal-body">
                      <?php
                          foreach ($consumer_details as $row){
                              echo  "<div class='control-group'>
                                  <label class='control-label'><strong>Meter Serial No :</strong></label>
                                  <div class='controls'>
                                    <input type='text' name='meter_serial_no' class='span11' placeholder='First Name' value='".$row['meter_serial_no']."' required/>
                                  </div>
                                </div>

                                 <div class='control-group'>
                                  <label class='control-label'><strong>Meter Brand :</strong></label>
                                  <div class='controls'>
                                    <input type='text' name='meter_brand' class='span11' placeholder='Middle Name' value='".$row['meter_brand']."' required/>
                                  </div>
                                </div>

                                 <div class='control-group'>
                                  <label class='control-label'><strong>Meter Size :</strong></label>
                                  <div class='controls'>
                                    <input type='text' name='meter_size' class='span11' placeholder='Last Name' value='".$row['meter_size']."' required/>
                                  </div>
                                </div>";

                               echo  "
                               <div class='control-group'>
                                  <label class='control-label'><strong>Date Installed :</strong></label>
                                  <div class='controls'>
                                    <input type='date' name='date_installed' class='span11' placeholder='Birth Date' value='".$row['date_installed']."' required/>
                                  </div>
                                </div>

                                <div class='control-group'>
                                  <label class='control-label'><strong>Initial Reading :</strong></label>
                                  <div class='controls'>
                                    <input type='text' name='initial_reading' class='span11' placeholder='Initial Reading' value='".$row['initial_reading']."' required/>
                                  </div>
                                </div>";
                            
                       }
                     ?>
                 </div> 
                      <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></a>&nbsp;<a data-dismiss="modal" class="btn" href="#">Cancel</a> 
                  </div>
                </form>
                </div>  

       
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

                              else if ($row['status'] == "Disconnected" || $row['status'] == "For Disconnection"){

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
