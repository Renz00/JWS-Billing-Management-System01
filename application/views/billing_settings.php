<!DOCTYPE html>
<?php include "nav_top_menu_admin.php";?>

<div id="content">
  <div id="content-header">
        <div id="breadcrumb"> <a href="#"><i class="icon-home"></i> Manage Billing</a><a href="#" class="current">Billing Settings</a></div>
        <?php echo $this->session->flashdata("info");?>
        <h1>Billing Settings</h1>

  <div class="container-fluid">

    <div class="row-fluid">
      <div class="span12"> 

        <div class="widget-box">

          <div class="widget-title"> <span class="icon"> <i class="icon-tasks"></i> </span>

            <h5>Reading Options</h5>
             <div class="buttons"><a href="<?php echo base_url('index.php/main/updateReadingStatus/ENABLE-ALL');?>" class="btn btn-mini btn-primary"><i class="icon-ok"></i> Enable All</a>
             <a href="<?php echo base_url('index.php/main/updateReadingStatus/DISABLE-ALL');?>" class="btn btn-mini btn-danger"><i class="icon-ban-circle"></i> Disable All</a></div>

          </div>
            <div class="widget-content nopadding">

                 <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Barangay</th>
                    <th>Reading Status</th>
                    <th>Date Finished</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>

                  <?php
                    foreach ($reading_status as $row){
                      echo "<tr>
                   
                      <td>".$row['barangay']."</td>";

                      $barangay = $row['barangay'];

                      $brgy = str_replace(" ","-",$barangay);

                      $date = strtotime($row['date_finished']);

                      if ($row['date_finished']=="0000-00-00" || $row['date_finished']==null || $row['reading']!=1){
                       $date_finished="--";
                      }
                      else {
                        $date_finished = date("Y-F-d H:i", strtotime("-0 month",$date)); 
                      }                     

                      if ($row['reading']==1){
                      
                        echo "<td><center><span class='label label-success'>DONE</span></center></td>
                        <td>".$date_finished."</td>
                         <td><center><a href='".base_url('index.php/main/updateReadingStatus/'.$brgy)."'><button class='btn btn-warning btn-lg'><i class='icon icon-ok-circle'></i>&nbsp;Enable</button></a></td>";
                      }
                      else {
                        echo "<td><center><span class='label label-warning'>PENDING</span></center></td>
                        <td>".$date_finished."</td>
                        <td><center><a href='".base_url('index.php/main/updateReadingStatus/'.$brgy)."'><button class='btn btn-success btn-lg'><i class='icon icon-ban-circle'></i>&nbsp;Disable</button></a></td>";
                      }               
                      echo "</tr>";
                    }
                    
                  ?>
                </tbody>
              </table>

              

             </div>
              
       </div>

      </div>
    </div>

    <div class="row-fluid">
       <div class="span12">  

          <?php

              foreach ($rates as $row){
                if ($row['connection_type']=="Residential_Institutional")
                echo "<div id='myAlert2' class='modal hide'>
                     <form action='".base_url('index.php/main/updateRates/'.$row['connection_type'])."' method='post' class='form-horizontal'>
                        <div class='modal-header'>
                          <button data-dismiss='modal' class='close' type='button'>×</button>
                          <h3>Edit Residential/Institutional Rates</h3>
                        </div>
                        <div class='modal-body'>

                          <div class='control-group'>
                            <label class='control-label'><strong>First 10 Cubic Meters :</strong></label>
                            <div class='controls'>
                              <input type='text' name='first10cu' class='span11' placeholder='First 10 Cubic Meters Rate' value='".$row['first_10_CU']."' required/>
                            </div>
                          </div>

                            <div class='control-group'>
                        
                            <label class='control-label'><strong>11 to 20 Cubic Meters :</strong></label>
                            <div class='controls'>
                              <div class='input-prepend'> 
                                <input type='text' name='_11to20cu' class='span12' placeholder='11 to 20 Cubic Meters Rate' value='".$row['11_CU']."' required/>
                                <span class='add-on'>per CU</span>
                              </div>
                            </div>
                          </div>

                            <div class='control-group'>
                            <label class='control-label'><strong>21 to 30 Cubic Meters :</strong></label>
                            <div class='controls'>
                               <div class='input-prepend'> 
                                  <input type='text' name='_21to30cu' class='span12' placeholder='21 to 30 Cubic Meters Rate' value='".$row['21_CU']."' required/>
                                  <span class='add-on'>per CU</span>
                              </div>
                            </div>
                          </div>

                            <div class='control-group'>
                            <label class='control-label'><strong>31 and Above Cubic Meters :</strong></label>
                            <div class='controls'>
                              <div class='input-prepend'> 
                                <input type='text' name='_31abovecu' class='span12' placeholder='31 and Above Cubic Meters' value='".$row['31_CU']."' required/>
                                  <span class='add-on'>per CU</span>
                              </div>
                            </div>
                          </div>

                        </div>
                        <div class='modal-footer'><button type='submit' class='btn btn-primary'>Save</button></a>&nbsp;<a data-dismiss='modal' class='btn' href='#'>Cancel</a> </div>
                  </form>
                  </div>";
                }
              ?>


          <?php

              foreach ($rates as $row){
                if ($row['connection_type']=="Commercial")
                echo "<div id='myAlert3' class='modal hide'>
                     <form action='".base_url('index.php/main/updateRates/'.$row['connection_type'])."' method='post' class='form-horizontal'>
                        <div class='modal-header'>
                          <button data-dismiss='modal' class='close' type='button'>×</button>
                          <h3>Edit Commercial Rates</h3>
                        </div>
                        <div class='modal-body'>

                          <div class='control-group'>
                            <label class='control-label'><strong>First 10 Cubic Meters :</strong></label>
                            <div class='controls'>
                              <input type='text' name='first10cu' class='span11' placeholder='First 10 Cubic Meters Rate' value='".$row['first_10_CU']."' required/>
                            </div>
                          </div>

                            <div class='control-group'>
                        
                            <label class='control-label'><strong>11 to 20 Cubic Meters :</strong></label>
                            <div class='controls'>
                              <div class='input-prepend'> 
                                <input type='text' name='_11to20cu' class='span12' placeholder='11 to 20 Cubic Meters Rate' value='".$row['11_CU']."' required/>
                                <span class='add-on'>per CU</span>
                              </div>
                            </div>
                          </div>

                            <div class='control-group'>
                            <label class='control-label'><strong>21 to 30 Cubic Meters :</strong></label>
                            <div class='controls'>
                               <div class='input-prepend'> 
                                  <input type='text' name='_21to30cu' class='span12' placeholder='21 to 30 Cubic Meters Rate' value='".$row['21_CU']."' required/>
                                  <span class='add-on'>per CU</span>
                              </div>
                            </div>
                          </div>

                            <div class='control-group'>
                            <label class='control-label'><strong>31 and Above Cubic Meters :</strong></label>
                            <div class='controls'>
                              <div class='input-prepend'> 
                                <input type='text' name='_31abovecu' class='span12' placeholder='31 and Above Cubic Meters' value='".$row['31_CU']."' required/>
                                  <span class='add-on'>per CU</span>
                              </div>
                            </div>
                          </div>

                        </div>
                        <div class='modal-footer'><button type='submit' class='btn btn-primary'>Save</button></a>&nbsp;<a data-dismiss='modal' class='btn' href='#'>Cancel</a> </div>
                  </form>
                  </div>";
                }
              ?>

        <div class="widget-box">

          <div class="widget-title"> <span class="icon"> <i class="icon-tasks"></i> </span>

            <h5>Current Water Rates</h5>
          </div>
            <div class="widget-content nopadding">

                 <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Classification</th>
                    <th><a href="#myAlert3" data-toggle="modal" class="btn btn-primary btn-mini" style="float:left;"><i class="icon-pencil"></i>Edit</a>Commercial</th>
                    <th><a href="#myAlert2" data-toggle="modal" class="btn btn-primary btn-mini" style="float:left;"><i class="icon-pencil"></i>Edit</a>Residential&nbsp;/&nbsp;Institutional</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                 
                    <td>First 10 Cubic Meters</td>

                    <?php foreach ($rates as $row){

                      if ($row['connection_type']=="Residential_Institutional"){

                           echo "<td>".number_format($row['first_10_CU'], 2, '.', ',')."</td>";
                         }

                      if ($row['connection_type']=="Commercial"){
                          echo "<td>".number_format($row['first_10_CU'], 2, '.', ',')."</td>";
                        }
                    
                      }                     
                    ?>
                 
                  </tr>
                  <tr>
                   
                    <td>11-20</td>

                     <?php foreach ($rates as $row){

                      if ($row['connection_type']=="Residential_Institutional"){

                           echo "<td>".number_format($row['11_CU'], 2, '.', ',')." <em>per cubic meter</em></td>";
                         }

                      if ($row['connection_type']=="Commercial"){
                          echo "<td>".number_format($row['11_CU'], 2, '.', ',')." <em>per cubic meter</em></td>";
                        }
                    
                      }                     
                    ?>
         
                  </tr>
                   <tr>
                   
                    <td>21-30</td>
                    <?php foreach ($rates as $row){

                      if ($row['connection_type']=="Residential_Institutional"){

                           echo "<td>".number_format($row['21_CU'], 2, '.', ',')." <em>per cubic meter</em></td>";
                         }

                      if ($row['connection_type']=="Commercial"){
                          echo "<td>".number_format($row['21_CU'], 2, '.', ',')." <em>per cubic meter</em></td>";
                        }
                    
                      }                     
                    ?>
         
                  </tr>
                   <tr>
                   
                    <td>30 and Above</td>
                  <?php foreach ($rates as $row){

                      if ($row['connection_type']=="Residential_Institutional"){

                           echo "<td>".number_format($row['31_CU'], 2, '.', ',')." <em>per cubic meter</em></td>";
                         }

                      if ($row['connection_type']=="Commercial"){
                          echo "<td>".number_format($row['31_CU'], 2, '.', ',')." <em>per cubic meter</em></td>";
                        }
                    
                      }                     
                    ?>
         
                  </tr>
                 
                </tbody>
              </table>

              

             </div>
              
       </div>

      </div>

      
    </div>


    <div class="row-fluid">

      <div class="span12">  
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-tasks"></i> </span>
            <h5>Change Schedule</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
                <thead>
                  <tr>
                  
                    <th>Barangay</th>
                    <th>Meter Reading</th>
                    <th>Payment/Billing Delivery</th>
                     <th>15 day Grace Period</th>
                     <th>Extension w/ Penalty</th>
                     <th>Disconnection Date</th>
                     <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    foreach ($schedule as $row){

                     echo "<tr class='gradeA'>
                       
                          <td>".$row['barangay']."</td>
                          <td>".$row['meter_reading']."</td>
                          <td>".$row['distribution']."</td>
                          <td>".$row['grace_period']."</td>
                          <td>".$row['extension_with_penalty']."</td>
                          <td>".$row['disconnection']."</td>

                          <td><center><a href='#edit_".$row['sched_id']."' data-toggle='modal' class='btn btn-primary btn-lg'><i class='icon icon-pencil'></i>&nbsp;Edit</a></td>
                          </tr>

                          <div id='edit_".$row['sched_id']."' class='modal hide'>
                             <form class='form-horizontal' method='post' action='".base_url('index.php/main/updateSched/'.$row['sched_id'])."'>
                                <div class='modal-header'>
                                  <button data-dismiss='modal' class='close' type='button'>×</button>
                                  <h3>Edit Schedule for ".$row['barangay']."</h3>
                                </div>

                                <div class='modal-body'>

                                  <div class='control-group'>
                                    <label class='control-label'><strong>Meter Reading Day :</strong></label>
                                    <div class='controls'>
                                      <input type='number' name='reading_day' class='span11' placeholder='Meter Reading Day' value='".$row['meter_reading']."' required/>
                                    </div>
                                  </div>

                                  <div class='control-group'>
                                    <label class='control-label'><strong>Bill Distribution :</strong></label>
                                    <div class='controls'>
                                      <input type='number' name='distribution' class='span11' placeholder='Distribution' value='".$row['distribution']."' required/>
                                    </div>
                                  </div>
                                  
                                  <div class='control-group'>
                                    <label class='control-label'><strong>Grace Period: </strong></label>
                                    <div class='controls'>
                                      <input type='number' name='grace_period' class='span11' placeholder='Grace Period' value='".$row['grace_period']."' required/>
                                    </div>
                                  </div>

                                  <div class='control-group'>
                                    <label class='control-label'><strong>Extension w/ Penalty: </strong></label>
                                    <div class='controls'>
                                      <input type='number' name='with_penalty' class='span11' placeholder='Extension with Penalty'  value='".$row['extension_with_penalty']."' required/>
                                    </div>
                                  </div>

                                   <div class='control-group'>
                                    <label class='control-label'><strong>Disconnection Date: </strong></label>
                                    <div class='controls'>
                                      <input type='number' name='disconnection' class='span11' placeholder='Disconnection Date'  value='".$row['disconnection']."' required/>
                                    </div>
                                  </div>

                           </div> 
                                <div class='modal-footer'><button type='submit' class='btn btn-primary'>Save</button></a>&nbsp;<a data-dismiss='modal' class='btn' href='#'>Cancel</a> </div>
                          </form>
                          </div>";
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
</div>

    <?php include "footer.php";?>
    
            <script src="<?php echo base_url() ?>assets/js/jquery.min.js"></script>
            <script src="<?php echo base_url() ?>assets/js/jquery.ui.custom.js"></script>
            <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
            <script src="<?php echo base_url() ?>assets/js/fullcalendar.min.js"></script>
            <script src="<?php echo base_url() ?>assets/js/maruti.js"></script>
            <script src="<?php echo base_url() ?>assets/js/maruti.calendar.js"></script>
            <script src="<?php echo base_url() ?>assets/js/bootstrap-datepicker.js"></script> 
            <script src="<?php echo base_url() ?>assets/js/jquery.uniform.js"></script> 
            <script src="<?php echo base_url() ?>assets/js/select2.min.js"></script> 
            <script src="<?php echo base_url() ?>assets/js/maruti.form_common.js"></script>
  </body>
</body>

</html>
