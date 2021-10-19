<!DOCTYPE html>

<?php include "nav_top_menu_admin.php";?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#"><i class="icon-home"></i> Manage Water Meters</a><a href="#" class="current">Water Meters</a> </div>

    <?php echo $this->session->flashdata("info");?>
    
    <h1>Manage Water Meters</h1>
  </div>
  <div class="container-fluid">
  
	<div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title">
             <span class="icon"><i class="icon-th"></i></span> 
			 	  
            <h5>Meters</h5>
          </div>


	
          <div class="widget-content nopadding">		   

            
            <table class="table table-bordered table-striped data-table">
              <thead>
                <tr>
                  <th>Serial Number</th>
                  <th>Brand</th>
                  <th>Size</th>
                  <th>Owner</th>
                  <th>Address</th>               
                  <th>Date Installed</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>

              <?php
                $ctr=0;
                foreach ($meter_details as $row){

                  $new_dinstall = date('Y-F-d', strtotime($row['date_installed']));           
                  
                  echo "<tr class='gradeA'>
                     <td>".$row['meter_serial_no']."</td>
                      <td>".$row['meter_brand']."</td>
                      <td>".$row['meter_size']." mm</td>  
                      <td>".$row['lastname'].", ".$row['firstname']." ".substr($row['middlename'],0,1)."."."</td>
                      <td>".$row['address']."</td>                
                      <td>".$new_dinstall."</td>  
                     <td><center><a href='#edit_".$row['meter_serial_no']."' data-toggle='modal'><button class='btn btn-success btn-lg'><i class='icon icon-pencil'></i>&nbsp;Edit</button></a>&nbsp;<a href='#delete_".$row['meter_serial_no']."' data-toggle='modal'><button class='btn btn-danger btn-lg'><i class='icon icon-remove-circle'></i>&nbsp;Delete</button></a></td>
                    </tr>


                    <div id='edit_".$row['meter_serial_no']."' class='modal hide'>
                      <form action='". base_url('index.php/main/updateMeterDetails2/'.$row['meter_serial_no'])."' method='post' class='form-horizontal'>
                      <div class='modal-header'>
                        <button data-dismiss='modal' class='close' type='button'>×</button>
                        <h3>Edit Meter Details</h3>
                      </div>
                      <div class='modal-body'>
                    
                        <div class='control-group'>
                          <label class='control-label'><strong>Serial Number :</strong></label>
                          <div class='controls'>
                            <input type='text' name='meter_serial_no' class='span11' placeholder='Serial Number' value='".$row['meter_serial_no']."'/>
                          </div>
                        </div>

                        <div class='control-group'>
                          <label class='control-label'><strong>Brand :</strong></label>
                          <div class='controls'>
                            <input type='text' name='meter_brand' class='span11' placeholder='Brand' value='".$row['meter_brand']."'/>
                          </div>
                        </div>

                        <div class='control-group'>
                          <label class='control-label'><strong>Size :</strong></label>
                          <div class='controls'>
                            <input type='text' name='meter_size' class='span11' placeholder='Size' value='".$row['meter_size']."'/>
                          </div>
                        </div>

                        <div class='control-group'>
                          <label class='control-label'><strong>Date Installed :</strong></label>
                          <div class='controls'>
                            <input type='text' name='date_installed' class='span11' placeholder='Date Installed' value='".$row['date_installed']."'/>
                          </div>
                        </div>

                        <div class='control-group'>
                          <label class='control-label'><strong>Initial Reading :</strong></label>
                          <div class='controls'>
                            <input type='text' name='initial_reading' class='span11' placeholder='Initial Reading' value='".$row['initial_reading']."'/>
                          </div>
                        </div>
                          
                 </div> 
                      <div class='modal-footer'><button type='submit' class='btn btn-primary'>Save</button></a>&nbsp;<a data-dismiss='modal' class='btn' href='#'>Cancel</a> </div>
                </form>
                </div>

                <div id='delete_".$row['meter_serial_no']."' class='modal hide'>
                      <form action='". base_url('index.php/main/deleteMeter/'.$row['meter_serial_no'])."' method='post' class='form-horizontal'>
                      <div class='modal-header'>
                        <button data-dismiss='modal' class='close' type='button'>×</button>
                        <h3>Delete Meter</h3>
                      </div>
                      <div class='modal-body'>
                    
                        <center>

                                  <div class='alert alert-error alert-block'> 
                                  <h4 class='alert-heading'>Warning!</h4>
                                  <br>
                                  Deleting Meter: <b>".$row['meter_serial_no']."</b><br>

                                   Are you sure?

                                  </div>
                                 
                                  </center>
                   </div> 
                        <div class='modal-footer'><button type='submit' class='btn btn-primary'>Save</button></a>&nbsp;<a data-dismiss='modal' class='btn' href='#'>Cancel</a> </div>
                  </form>
                </div>";
                $ctr++;
                }
              

             echo "</tbody>
            </table>
            </div>
          </div>";
          echo "<font color='gray'><h6>Total Number of Meters: ".$ctr."</h6></font>";
          ?>
      </div>
     </div>  
   </div> 
  </div> 

<?php include "footer.php";?>

<?php include "form_scripts.php";?>
</body>
</html>
