<!DOCTYPE html>

<?php include "nav_top_menu_admin.php";?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#"><i class="icon-home"></i> Reports</a><a href="#" class="current">Bill History</a></div>

    <?php echo $this->session->flashdata("info");?>

    <h1>Bill History</h1>
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
                  <th>Meter No.</th>
				  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
			
              <?php

                $ctr1=0;

                foreach ($consumer_data as $row){

          				echo	"<tr class='gradeA'>
            						  <td>".substr($row['date_installed'],0,4).substr($row['date_installed'],4,4).str_pad($row['account_no'], 4, '0', STR_PAD_LEFT)."</td>

            						  <td>".$row['lastname'].", ".$row['firstname']." ".substr($row['middlename'],0,1)."."."</td>
            						  <td>".$row['address']."</td>";

            			echo     "<td>".$row['meter_id']."</td>
                           <td><center><a href='".base_url('index.php/main/viewConsumerBills/'.$row['account_no'].'')."' class='btn btn-primary btn-lg'><i class='icon icon-eye-open'></i>&nbsp;View Bills</button></a></center></td>
            						    </tr>";

                        $ctr1++;
                }
                
              echo "</tbody>
            </table>
          </div>
        </div>";
        echo "<font color='gray'><h6>Total Number of Records: ".$ctr1."</h6>";
                ?>
     </div>   
  </div>
  </div>
</div>

<?php include "footer.php";?>

<?php include "form_scripts.php";?>
</body>
</html>
