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
    <div id="breadcrumb"><a href="#" class="current"><i class="icon-home"></i>System Logs</a></div>

    <?php echo $this->session->flashdata("info");?>

    <h1>System Logs</h1>
  </div>
  <div class="container-fluid">
  
	<div class="row-fluid">
      <div class="span12">  

        <a href="<?php echo base_url('index.php/main/viewLogs')?>" class="btn btn-success" ><i class="icon-refresh"></i></a>
        <a href="#myAlert5" class="btn btn-primary" data-toggle="modal"><i class="icon-print"></i> Print Logs</a>


          <div id="myAlert5" class="modal hide">

                <form action="<?php echo base_url('index.php/main/printLogs');?>" method='post' class='form-horizontal'>

                      <div class="modal-header">
                        <button data-dismiss="modal" class="close" type="button">×</button>
                        <h3>Print System Logs</h3>
                      </div>
                      <div class="modal-body">

                         <?php

                          $curr_day = date('d');

                         echo "<div class='control-group'>
                          <label class='control-label'><strong><em>FROM</em> Day :</strong></label>
                          <div class='controls'>
                            <input type='number' name='day1' class='span11' min='1' max='".$curr_day."' placeholder='Day' value='".$curr_day."' required/>
                          </div>

                          <label class='control-label'><strong><em>TO</em> Day :</strong></label>
                          <div class='controls'>
                            <input type='number' name='day2' class='span11' min='1' max='".$curr_day."' placeholder='Day' value='".$curr_day."' required/>
                          </div>
                        </div>";
                      ?>

                   <div class="control-group">
                        <label class="control-label"><Strong>Month: </strong></label>
                          <div class="controls">
                             <select name="records">
                                 <option>All Records</option>
                                  <?php
                                
                                $count = count($logs);
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

        <div class="widget-box">
          <div class="widget-title">
          <span class="icon"><i class="icon-th"></i></span> 
			 	  
            <h5>Logs</h5>
          </div>
	
          <div class="widget-content nopadding">
		   
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Time</th>
        				  <th>Originator</th>
                  <th>Type</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody>
			
              <?php

                $ctr1=0;

                foreach ($logs as $row){

                  $date = strtotime($row['log_time']); 
                  $log_time = date("Y-F-d H:i", strtotime("-0 month",$date)); 

          				echo	"<tr class='gradeA'>
                           <td>LOG-".str_pad($row['log_id'], 4, '0', STR_PAD_LEFT)."</td>
            						  <td>".$log_time."</td>";

                          echo "<td><center><font color='black'><b>".$row['originator']."</b></font></center></td>";
                        
                          if ($row['type'] == "ALERT"){
              						  echo "<td><center><span class='label label-important'>".$row['type']."</span></center></td>";
                          }

                          else if ($row['type'] == "MODIFICATION"){
                            echo "<td><center><span class='label label-info'>".$row['type']."</span></center></td>";
                          }

                          else if ($row['type'] == "EXPORT"){
                            echo "<td><center><span class='label label-inverse'>".$row['type']."</span></center></td>";
                          }

                          echo "<td>".$row['description']."</td>
                        </tr>";

                        $ctr1++;
                }
                
              echo "</tbody>
            </table>
          </div>
        </div>";
        echo "<font color='gray'><h6>Total Number of Logs: ".$ctr1."</h6>";
                ?>
     </div>   
  </div>
  </div>
</div>

<?php include "footer.php";?>

<?php include "form_scripts.php";?>
</body>
</html>
