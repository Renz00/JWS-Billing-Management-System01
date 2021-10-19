<!DOCTYPE html>

<?php include "nav_top_menu_cashier.php";?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#"><i class="icon-home"></i> Manage Billing</a><a href="#" class="current">Manage Bills</a> </div>

    <?php echo $this->session->flashdata("info");?>

    <h1>Manage Bills</h1>
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
                    <th>ID</th>
                    <th>Account No.</th>
                    <th>Full Name</th>
                    <th>Address</th>
                    <th>Bill Status</th>
                    <th>Date Created</th>
                    <th>Due Date</th>
                     <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $ctr1=0;
                    $ctr2=0;
                    $ctr3=0;
                    $ctr4=0;
                    foreach ($billed_consumers as $row){

                        $new_datecreate = date('Y-F-d', strtotime($row['date_created']));

                        if ($row['due_date']==0000-00-00){

                          $new_due = "NONE";

                        }
                        else {

                          $new_due = date('Y-F-d', strtotime($row['due_date']));
                           

                        }

                       echo "<tr class='gradeA'>
                            <td>WB-".str_pad($row['bill_id'], 4, '0', STR_PAD_LEFT)."</td>
                            <td>".substr($row['date_installed'],0,4).substr($row['date_installed'],4,4).str_pad($row['account_no'], 4, '0', STR_PAD_LEFT)."</td>

                            <td>".$row['lastname'].", ".$row['firstname']." ".substr($row['middlename'],0,1)."."."</td>
                            <td>".$row['address']."</td>";

                          if ($row['bill_status']=="Paid"){
                              echo "<td><center><span class='label label-success'>".$row['bill_status']."</span></center</td>";
                              $ctr2++;
                          }
                           else if ($row['bill_status']=="Billed"){
                             echo  "<td><center><span class='label label-warning'>".$row['bill_status']."</span></center></td>";
                             $ctr3++;
                          }
                           else if ($row['bill_status']=="For Disconnection"){
                             echo  "<td><center><span class='label label-important'>".$row['bill_status']."</span></center></td>";
                             $ctr4++;
                          }
                           
                          echo "<td>".$new_datecreate."</td>
                          <td>".$new_due."</td>
                           <td><center><a href='".base_url('index.php/main/viewBill/'.$row['bill_id'].'')."' class='btn btn-primary btn-lg'><i class='icon icon-eye-open'></i>&nbsp;View Bill</a></td>
                          </tr>";
                          $ctr1++;
                     }

                    
               echo "</tbody>
              </table>
            </div>
          </div>";
          echo "<font color='gray'><h6>Total Number of Bills: ".$ctr1."</h6>";
          echo "<h6>Number of <b>Billed</b>: ".$ctr3." | Number of <b>Paid</b>: ".$ctr2." | Number of <b>For Disconnection</b>: ".$ctr4."</h6></font>";
          ?>
      </div>
     </div>  
   </div> 
  </div> 


<?php include "footer.php";?>

<?php include "form_scripts.php";?>
</body>
</html>
