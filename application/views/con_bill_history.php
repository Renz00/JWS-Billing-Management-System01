<!DOCTYPE html>

<?php include "nav_top_menu_admin.php";?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#"><i class="icon-home"></i> Reports</a><a href="#" class="current">Bill History</a></div>

    <?php echo $this->session->flashdata("info");
    foreach ($consumer_bills as $row){
        
        $bill_date = $row['date_created'];
        $yr = date('Y', strtotime($bill_date));
        echo "<h1><b>".$row['lastname'].", ".$row['firstname']." ".substr($row['middlename'],0,1).".</b> Bill History ".$yr."</h1>";
        break;
    }
    ?>
  </div>
  <div class="container-fluid">
      
       <div class="row-fluid">
				<div class="span12">
				    <div class="buttons" style="padding-left:10px;padding-top:10px;">
				        <a href="#myAlert7" class="btn btn-info" data-toggle="modal" ><i class="icon-calendar"></i> Report Period</a>
                  <div class="btn-group">
                  </div>
                  <a href="<?php echo base_url('index.php/main/viewBillHistory')?>" class="btn btn-warning" style="float:right;"><i class="icon-arrow-left"></i>&nbsp;Previous Page</a>
                </div>
						<div class="widget-box">
						    
                            <div class="widget-title">
                              <span class="icon"><i class="icon-th"></i></span> 
                    			 	  
                                <h5>Graph</h5>
                              </div>
                            <div class="widget-content nopadding">
		                        <div class="widget-content">
							   <div class="row-fluid">
							       
							       <div class="span6">
							        <canvas id="bar-chart2" width="400" height="250"></canvas>
						    	    </div>
						    	    
						    	     <div class="span6">
							            <canvas id="bar-chart1" width="400" height="250"></canvas>
						    	    </div>
							     
							     
							</div>
						</div>
                        </div>
                        </div>
    </div>
    </div>
    
    <div class="row-fluid">
      <div class="span12">  
      
        <div class="widget-box">
          <div class="widget-title">
          <span class="icon"><i class="icon-th"></i></span> 
			 	  
            <h5>Total</h5>
          </div>
	
          <div class="widget-content nopadding">
		   <div class="span3"></div></br>

                      <?php
                      
                      $amount=0;
                      $water_usage=0;
                      
                      foreach ($consumer_bills as $row){
                          
                          if ($row['bill_status']=="Paid"){
                               $amount+=$row['total_amount'];
                          }
                          $water_usage+=$row['water_usage'];
                          
                      }
                      
                        echo "<div class='span4'>

                              <p><b>WATER USAGE : </b>".number_format($water_usage, 2, '.', ',')."</p>

                         </div>";
                         
                        echo "<div class='span4'>

                              <p><b>AMOUNT PAID : </b>".number_format($amount, 2, '.', ',')."</p>

                         </div>";

                      ?>
                      <div class="span3"></div>
                      <br>
                       <br>
          </div>
        </div>
     </div>   
  </div>

  
	<div class="row-fluid">
      <div class="span12">  
      
      <div id="myAlert7" class="modal hide">
          <?php
            foreach ($consumer_bills as $row){
                  echo "<form action='".base_url('index.php/main/getConsumerBills/'.$row['account_no'])."' method='post' class='form-horizontal'>";
                break;
            }
              
            ?>
                      <div class="modal-header">
                        <button data-dismiss="modal" class="close" type="button">×</button>
                        <h3>Select Year</h3>
                      </div>
                      <div class="modal-body">
                         
                       <div class="control-group">
                        <label class="control-label"><Strong>Year: </strong></label>
                          <div class="controls">
                             <select name="period">
                        <?php
    
                            foreach ($date_count as $row){
                                
                                $curr_y = date('Y');
                                
                                $bill_date = $row['min(date_created)'];
                               
                                $yr = date('Y', strtotime($bill_date));
                           
                                while (intval($curr_y) >= intval($yr)){
                                    
                                     echo "
                                <option>".$curr_y."</option>";
                                
                                 $curr_y=$curr_y-1;
                                    
                                }
                                
                            }
                            
                        ?>
                            </select>
                          </div>
                        </div>

                 </div> 
                      <div class="modal-footer"><button type="submit" class="btn btn-primary">Confirm</button></a>&nbsp;<a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
                </form>
                </div> 
                
        <hr>
      
        <div class="widget-box">
          <div class="widget-title">
          <span class="icon"><i class="icon-th"></i></span> 
			 	  
            <h5>Bills</h5>
          </div>
	
          <div class="widget-content nopadding">
		   
            <table class="table table-bordered data-table with-check">
              <thead>
                <tr>
                  <th>Bill ID</th>
        		  <th>Previous Reading</th>
        		  <th>Current Reading</th>
        		  <th>CU Used</th>
                  <th>Amount Due</th>
                  <th>Status</th>
                  <th>Date Created</th>
				  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
			
              <?php

                        $ctr1=0;
                        $ctr2=0;
                        $ctr3=0;
                        $ctr4=0;
                
                        $cu1 = 0;
                        $cu2 = 0;
                        $cu3 = 0;
                        $cu4 = 0;
                        $cu5 = 0;
                        $cu6 = 0;
                        $cu7 = 0;
                        $cu8 = 0;
                        $cu9 = 0;
                        $cu10 = 0;
                        $cu11 = 0;
                        $cu12 = 0;
                        
                        $total1 = 0;
                        $total2 = 0;
                        $total3 = 0;
                        $total4 = 0;
                        $total5 = 0;
                        $total6 = 0;
                        $total7 = 0;
                        $total8 = 0;
                        $total9 = 0;
                        $total10 = 0;
                        $total11 = 0;
                        $total12 = 0;

                foreach ($consumer_bills as $row){
                    
                     $new_datecreate = date('Y-F-d', strtotime($row['date_created']));

          				echo	"<tr class='gradeA'>
            						  <td>WB-".str_pad($row['bill_id'], 4, '0', STR_PAD_LEFT)."</td>
            						  <td>".$row['prev_reading']."</td>
            						   <td>".$row['curr_reading']."</td>
            						    <td>".$row['water_usage']."</td>
            						  <td>".$row['total_amount']."</td>";
            						  
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


            			echo     "<td>".$new_datecreate."</td>
                           <td><center><a href='".base_url('index.php/main/viewBill/'.$row['bill_id'].'')."' class='btn btn-primary btn-lg' target='_blank'><i class='icon icon-eye-open'></i>&nbsp;View</button></a></center></td>
            						    </tr>";
            						    
            			 $bill_date = $row['date_created'];
                         $m = date('F', strtotime($bill_date));
                         
                            if ($m=='January'){
                                $cu1 = $row['water_usage'];
                                
                                if ($row['bill_status']=='Paid'){
                                    $total1 = $row['total_amount'];
                                }
                                
                            }
                            
                            else if ($m=='February'){
                                $cu2 = $row['water_usage'];
                                
                                if ($row['bill_status']=='Paid'){
                                    $total2 = $row['total_amount'];
                                }
                            }
                            
                            else if ($m=='March'){
                                $cu3 = $row['water_usage'];
                                
                                if ($row['bill_status']=='Paid'){
                                    $total3 = $row['total_amount'];
                                }
                            }
                            
                            else if ($m=='April'){
                                $cu4 = $row['water_usage'];
                                if ($row['bill_status']=='Paid'){
                                    $total4 = $row['total_amount'];
                                }
                            }
                            
                            else if ($m=='May'){
                                $cu5 = $row['water_usage'];
                                if ($row['bill_status']=='Paid'){
                                    $total5 = $row['total_amount'];
                                }
                            }
                            
                            else if ($m=='June'){
                                $cu6 = $row['water_usage'];
                                if ($row['bill_status']=='Paid'){
                                    $total6 = $row['total_amount'];
                                }
                            }
                            
                            else if ($m=='July'){
                                $cu7 = $row['water_usage'];
                                if ($row['bill_status']=='Paid'){
                                    $total7 = $row['total_amount'];
                                }
                            }
                            
                            else if ($m=='August'){
                                $cu8 = $row['water_usage'];
                               if ($row['bill_status']=='Paid'){
                                    $total8 = $row['total_amount'];
                                }
                            }
                            
                            else if ($m=='September'){
                                $cu9 = $row['water_usage'];

                                if ($row['bill_status']=='Paid'){
                                    $total9 = $row['total_amount'];
                                }
                            }
                            else if ($m=='October'){
                                $cu10 = $row['water_usage'];

                                if ($row['bill_status']=='Paid'){
                                    $total10 = $row['total_amount'];
                                }
                            }
                            else if ($m=='November'){
                                $cu11 = $row['water_usage'];
                                if ($row['bill_status']=='Paid'){
                                    $total11 = $row['total_amount'];
                                }
                            }
                            else if ($m=='December'){
                                $cu12 = $row['water_usage'];
                                if ($row['bill_status']=='Paid'){
                                    $total12 = $row['total_amount'];
                                }
                            }
                        

                        $ctr1++;
                }
                
              echo "</tbody>
            </table>
          </div>
        </div>";
        echo "<font color='gray'><h6>Total Number of Records: ".$ctr1."</h6>";
        echo "<h6>Number of <b>Billed</b>: ".$ctr3." | Number of <b>Paid</b>: ".$ctr2." | Number of <b>For Disconnection</b>: ".$ctr4."</h6></font>";
                ?>
     </div>   
  </div>

     </div>   
  </div>
  </div>
</div>

<?php include "footer.php";?>



<script src="<?php echo base_url() ?>assets/js/excanvas.min.js"></script> 
<script src="<?php echo base_url() ?>assets/js/jquery.ui.custom.js"></script> 
<script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script> 
<script src="<?php echo base_url() ?>assets/js/jquery.flot.min.js"></script> 
<script src="<?php echo base_url() ?>assets/js/jquery.flot.pie.min.js"></script> 
<script src="<?php echo base_url() ?>assets/js/jquery.flot.resize.min.js"></script> 
<script src="<?php echo base_url() ?>assets/js/maruti.js"></script> 
<script src="<?php echo base_url()?>assets/js/maruti.dashboard.js"></script>  
<script src="<?php echo base_url() ?>assets/js/jquery.peity.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/Chart.bundle.js"></script>
<script src="<?php echo base_url() ?>assets/js/Chart.bundle.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/Chart.js"></script>
<script src="<?php echo base_url() ?>assets/js/Chart.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/utils.js"></script>

<?php include "form_scripts.php";?>


<script type="text/javascript">


    var total1 = <?php echo $total1; ?>;
    var total2 = <?php echo $total2; ?>;
    var total3 = <?php echo $total3; ?>;
    var total4 = <?php echo $total4; ?>;
    var total5 = <?php echo $total5; ?>;
    var total6 = <?php echo $total6; ?>;
    var total7 = <?php echo $total7; ?>;
    var total8 = <?php echo $total8; ?>;
    var total9 = <?php echo $total9; ?>;
    var total10 = <?php echo $total10; ?>;
    var total11 = <?php echo $total11; ?>;
    var total12 = <?php echo $total12; ?>;
    
    var cu1 = <?php echo $cu1; ?>;
    var cu2 = <?php echo $cu2; ?>;
    var cu3 = <?php echo $cu3; ?>;
    var cu4 = <?php echo $cu4; ?>;
    var cu5 = <?php echo $cu5; ?>;
    var cu6 = <?php echo $cu6; ?>;
    var cu7 = <?php echo $cu7; ?>;
    var cu8 = <?php echo $cu8; ?>;
    var cu9 = <?php echo $cu9; ?>;
    var cu10 = <?php echo $cu10; ?>;
    var cu11 = <?php echo $cu11; ?>;
    var cu12 = <?php echo $cu12; ?>;
    
   new Chart(document.getElementById("bar-chart1"), {
    type: 'bar',
    data: {
      labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September","October","November","December"],
      datasets: [
        {
          label: "Amount Collected (Pesos)",
          backgroundColor: ["#3cba9f","#3cba9f","#3cba9f","#3cba9f","#3cba9f","#3cba9f","#3cba9f","#3cba9f","#3cba9f","#3cba9f","#3cba9f","#3cba9f"],
          data: [total1,total2,total3,total4,total5,total6,total7,total8,total9,total10,total11,total12]
        }
      ]
    },
    options: {
      legend: { display: false },
      title: {
        display: true,
        text: 'Total Amount Paid'
      }
    }
});


// new Chart(document.getElementById("pie-chart1"), {
//     type: 'pie',
//     data: {
//       labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September","October","November","December"],
//       datasets: [{
//         label: "Amount Paid",
//         backgroundColor: ["#d6b464","#d67c64","#ced664","#acd664","#75d664","#64d691","#64d6c3","#64bbd6","#6495d6","#8d64d6","#d664d4","#d6648b"],
//         data: [total1,total2,total3,total4,total5,total6,total7,total8,total9,total10,total11,total12]
//       }]
//     },
//     options: {
//       title: {
//         display: true,
//         text: ''
//       }
//     }
// });

   new Chart(document.getElementById("bar-chart2"), {
    type: 'bar',
    data: {
      labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September","October","November","December"],
      datasets: [
        {
          label: "CU Used",
          backgroundColor: ["#64bbd6","#64bbd6","#64bbd6","#64bbd6","#64bbd6","#64bbd6","#64bbd6","#64bbd6","#64bbd6","#64bbd6","#64bbd6","#64bbd6"],
          data: [cu1,cu2,cu3,cu4,cu5,cu6,cu7,cu8,cu9,cu10,cu11,cu12]
        }
      ]
    },
    options: {
      legend: { display: false },
      title: {
        display: true,
        text: 'Total CU Used'
      }
    }
});

// new Chart(document.getElementById("pie-chart2"), {
//     type: 'pie',
//     data: {
//       labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September","October","November","December"],
//       datasets: [{
//         label: "CU Used",
//         backgroundColor: ["#d6b464","#d67c64","#ced664","#acd664","#75d664","#64d691","#64d6c3","#64bbd6","#6495d6","#8d64d6","#d664d4","#d6648b"],
//         data: [cu1,cu2,cu3,cu4,cu5,cu6,cu7,cu8,cu9,cu10,cu11,cu12]
//       }]
//     },
//     options: {
//       title: {
//         display: true,
//         text: ''
//       }
//     }
// });



  </script>

</body>
</html>
