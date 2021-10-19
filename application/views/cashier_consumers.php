<!DOCTYPE html>

<?php include "nav_top_menu_cashier.php";?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#"><i class="icon-home"></i> Manage Billing</a><a href="#" class="current">Consumers</a></div>

    <?php echo $this->session->flashdata("info");?>

    <h1>Manage Consumers</h1>
  </div>
  <div class="container-fluid">
  
  <div class="row-fluid">
      <div class="span12">  
        <div class="widget-box">
          <div class="widget-title">
          <span class="icon"><i class="icon-th"></i></span> 
          
            <h5>Consumers</h5>
          </div>
  
          <div class="widget-content nopadding">
       
            <table class="table table-bordered data-table with-check">
              <thead>
                <tr>
                  <th>Account No.</th>
                  <th>Full Name</th>
                  <th>Address</th>
                  <th>Rate</th>
                   <th>Status</th>
                   <th>Meter No</th>
                   <th>Actions</th>
                </tr>
              </thead>
              <tbody>
      
              <?php

                $ctr1=0;
                $ctr2=0;
                $ctr3=0;
                $ctr4=0;

                foreach ($consumer_data as $row){

                  echo  "<tr class='gradeA'>
                          <td>".substr($row['date_installed'],0,4).substr($row['date_installed'],4,4).str_pad($row['account_no'], 4, '0', STR_PAD_LEFT)."</td>

                          <td>".$row['lastname'].", ".$row['firstname']." ".substr($row['middlename'],0,1)."."."</td>
                          <td>".$row['address']."</td>
                           <td>".$row['rate_id']."</td>";

                        if ($row['status']=="Active"){
                            echo "<td><center><span class='label label-success'>".$row['status']."</span></center></td>";

                            $ctr2++;
                        }
                         else if ($row['status']=="Pending"){
                           echo  "<td><center><span class='label label-warning'>".$row['status']."</span></center></td>";

                           $ctr3++;
                        }
                         else if ($row['status']=="Disconnected"  || $row['status'] == "For Disconnection"){
                           echo  "<td><center><span class='label label-important'>".$row['status']."</span></center></td>";

                           $ctr4++;
                        }

                  echo     "<td>".$row['meter_id']."</td>
                           <td><center><a href='".base_url('index.php/main/viewConsumerLedger/'.$row['account_no'].'')."' class='btn btn-primary btn-lg'><i class='icon icon-eye-open'></i>&nbsp;View Ledger</button></a></center></td>
                            </tr>";

                        $ctr1++;
                }
                
              echo "</tbody>
            </table>
          </div>
        </div>";
        echo "<font color='gray'><h6>Total Number of Consumers: ".$ctr1."</h6>";
        echo "<h6>Number of <b>Pending</b>: ".$ctr3." | Number of <b>Active</b>: ".$ctr2." | Number of <b>Disconnected</b>: ".$ctr4."</h6></font>";
                ?>
     </div>   
  </div>
  </div>
</div>

<?php include "footer.php";?>

<?php include "form_scripts.php";?>
</body>
</html>
