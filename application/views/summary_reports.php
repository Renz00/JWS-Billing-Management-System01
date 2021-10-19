<!DOCTYPE html>

<?php include "nav_top_menu_admin.php";?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#"><i class="icon-home"></i> Reports</a><a href="#" class="current">Summary Reports</a></div>

    <?php
        $new_date = $this->session->flashdata("date");
        echo "<h1>Summary Report for ".$new_date."</h1>";
 
    ?>
  </div>
  <div class="container-fluid">
      
       <div class="row-fluid">
				<div class="span12">
				    <div class="buttons" style="padding-left:10px;padding-top:10px;">
				         <a href="<?php echo base_url('index.php/main/printSummary/'.$new_date);?>"class="btn btn-primary"><i class="icon-print"></i> Download Form</a>
				        <a href="#myAlert7" class="btn btn-info" data-toggle="modal" ><i class="icon-calendar"></i> Report Period</a>
                  <div class="btn-group">
                  </div>
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
		   <div class="widget-content">
							   <div class="row-fluid">
							        <font color="black">
            					    <div class="span3"></div>
            
                                  <?php
                                    $cu=0;
                                    $r_con=0;
                                    $a_con=0;
                                    $d_con=0;
                                    $cb=0;
                                    $w_due=0;
                                    $a_due=0;
                                    $total=0;
                                    foreach ($report_data as $row){
                                        
                                        $cu+=$row['cu_used'];
                                        $r_con+=$row['total_consumers'];
                                        $a_con+=$row['active_consumers'];
                                        $d_con+=$row['disconnected_consumers'];
                                        $cb+=$row['collectible'];
                                        $w_due+=$row['within_due'];
                                        $a_due+=$row['after_due'];
                                        $total+=$row['total'];
                                        
                                    }
            
                                    echo "<div class='span4'>
            
                                          <p><b>CU USED : </b>".$cu."</p>
                                          <p><b>REGISTERED CONSUMERS : </b>".$r_con."</p>
                                          <p><b>ACTIVE CONSUMERS : </b>".$a_con."</p>
                                          <p><b>DISCONNECTED CONSUMERS : </b>".$d_con."</p>
    
                                     </div>";   
            
                                    echo "<div class='span4'>
            
                                          <p><b>COLLECTIBLES : </b>".number_format($cb, 2, '.', ',')."</p>
                                          <p><b>WITHIN DUE DATE : </b>".number_format($w_due, 2, '.', ',')."</p>
                                          <p><b>AFTER DUE DATE : </b>".number_format($a_due, 2, '.', ',')."</p>
    
                                     </div>";
            
                                  
                                  echo "<div class='span3'></div>
                                  </div>
                              
                              <hr><center><h4>AMOUNT COLLECTED: <font color='blue'>".number_format($total, 2, '.', ',')."</b></h4></center><hr>";
                              ?>
                              </font>
							</div>
						</div>
        </div>
     </div>   
  </div>

  
	<div class="row-fluid">
      <div class="span12">  
      
      <div id="myAlert7" class="modal hide">
                <form action="<?php echo base_url('index.php/main/getTableSummary')?>" method="post" class="form-horizontal">
                      <div class="modal-header">
                        <button data-dismiss="modal" class="close" type="button">×</button>
                        <h3>Select Month</h3>
                      </div>
                      <div class="modal-body">
                         
                       <div class="control-group">
                        <label class="control-label"><Strong>Month: </strong></label>
                          <div class="controls">
                             <select name="period">
                        <?php
                             foreach ($date_count as $row){
                                
                                $curr_m = date('m');
                                $curr_y = date('Y');
                                
                                $summary_date = $row['min(report_date)'];
                               
                                $mon = date('m', strtotime($summary_date));
                                $yr = date('Y', strtotime($summary_date));
                                
                                while (intval($curr_y) >= intval($yr)){
                                    
                                    if ($curr_m==1){
                                        $month="January";
                                    }
                                    else if ($curr_m==2){
                                        $month="February";
                                    }
                                    else if ($curr_m==3){
                                        $month="March";
                                    }
                                    else if ($curr_m==4){
                                        $month="April";
                                    }
                                    else if ($curr_m==5){
                                        $month="May";
                                    }
                                    else if ($curr_m==6){
                                        $month="June";
                                    }
                                    else if ($curr_m==7){
                                        $month="July";
                                    }
                                    else if ($curr_m==8){
                                        $month="August";
                                    }
                                    else if ($curr_m==9){
                                        $month="September";
                                    }
                                    else if ($curr_m==10){
                                        $month="October";
                                    }
                                    else if ($curr_m==11){
                                        $month="November";
                                    }
                                    else if ($curr_m==12){
                                        $month="December";
                                    }
                                    
                                     echo "
                              <option>".$curr_y." - ".$month."</option>";
                              
                                    if ($curr_m-1==0){
                                        
                                        $curr_m=12;
                                        $curr_y=$curr_y-1;
                                        
                                    }
                                    else {
                                        $curr_m=$curr_m-1;
                                    }
                                    
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

     </div>   
  </div>
  
  <div class="row-fluid">

      <div class="span7">
        
          <div class="widget-box">
            <div class="widget-title">
               <span class="icon"><i class="icon-th"></i></span> 
            
              <h5>Summary</h5>
            </div>
    
            <div class="widget-content nopadding">
         
              <table class="table table-bordered">
                <thead>
                  <tr>

                    <th>Barangay</th>
                    <th>Total Con</th>
                    <th>Active Con</th>
                    <th>Disconnected Con</th>
                     <th>CU Used</th>
                     <th>% of CU Used</th>
                     <th>Collectible</th>
                     <th>% of Collectibles</th>
                  </tr>
                </thead>
                <tbody>
                    
                        
                    <?php
                        
                    foreach ($report_data as $row){
                        
                      
                        echo "<tr class='gradeA'>
                                <td>".$row['barangay']."</td>
                                  <td>".$row['total_consumers']."</td>
                                  <td>".$row['active_consumers']."</td>
                                  <td>".$row['disconnected_consumers']."</td>
                                  <td>".$row['cu_used']."</td>
                                 
                                  <td><b>".$row['percent_cu']."%</b></td>
                                  <td>".number_format($row['collectible'], 2, '.', ',')."</td>
                                  <td><b>".$row['percent_collectible']."%</b></td>
                        </tr>";
                       
                    }
                         
                        
                         
                    ?>
                </tbody>
              </table>
            </div>
          </div>
      </div>
      <div class="span5">
          <div class="widget-box">

            <div class="widget-title">
               <span class="icon"><i class="icon-th"></i></span> 
            
              <h5>Amount Collected</h5>
            </div>
    
            <div class="widget-content nopadding">
         
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Within Due</th>
                    <th>After Due</th>
                    <th>Total</th>
                     <th>% of Collected</th>
                  
                  </tr>
                </thead>
                <tbody>
                    <?php
                    
                        $cu1 = 0;
                        $cu2 = 0;
                        $cu3 = 0;
                        $cu4 = 0;
                        $cu5 = 0;
                        $cu6 = 0;
                        $cu7 = 0;
                        $cu8 = 0;
                        $cu9 = 0;
                        
                        // $pcu1 = 0;
                        // $pcu2 = 0;
                        // $pcu3 = 0;
                        // $pcu4 = 0;
                        // $pcu5 = 0;
                        // $pcu6 = 0;
                        // $pcu7 = 0;
                        // $pcu8 = 0;
                        // $pcu9 = 0;
                        
                        $total1 = 0;
                        $total2 = 0;
                        $total3 = 0;
                        $total4 = 0;
                        $total5 = 0;
                        $total6 = 0;
                        $total7 = 0;
                        $total8 = 0;
                        $total9 = 0;
                        
                        // $ptotal1 = 0;
                        // $ptotal2 = 0;
                        // $ptotal3 = 0;
                        // $ptotal4 = 0;
                        // $ptotal5 = 0;
                        // $ptotal6 = 0;
                        // $ptotal7 = 0;
                        // $ptotal8 = 0;
                        // $ptotal9 = 0;
                    
                        foreach ($report_data as $row){
                            
                          
                            echo "<tr class='gradeA'>
                                    <td>".number_format($row['within_due'], 2, '.', ',')."</td>
                                      <td>".number_format($row['after_due'], 2, '.', ',')."</td>
                                      <td>".number_format($row['total'], 2, '.', ',')."</td>
                                      <td><b>".$row['percent_collected']."%</b></td>
                            </tr>";
                            
                            if ($row['barangay']=='Binanuahan'){
                                $cu1 = $row['cu_used'];
                                $total1 = $row['total'];
                                $pcu1 = $row['percent_cu'];
                                $ptotal1 = $row['percent_collected'];
                            }
                            
                            else if ($row['barangay']=='Biriran'){
                                $cu2 = $row['cu_used'];
                                $total2 = $row['total'];
                                 $pcu2 = $row['percent_cu'];
                                $ptotal2 = $row['percent_collected'];
                            }
                            
                            else if ($row['barangay']=='Catanagan'){
                                $cu3 = $row['cu_used'];
                                $total3 = $row['total'];
                                 $pcu3 = $row['percent_cu'];
                                $ptotal3 = $row['percent_collected'];
                            }
                            
                            else if ($row['barangay']=='Cogon'){
                                $cu4 = $row['cu_used'];
                                $total4 = $row['total'];
                                 $pcu4 = $row['percent_cu'];
                                $ptotal4 = $row['percent_collected'];
                            }
                            
                            else if ($row['barangay']=='Embarcadero'){
                                $cu5 = $row['cu_used'];
                                $total5 = $row['total'];
                                 $pcu5 = $row['percent_cu'];
                                $ptotal5 = $row['percent_collected'];
                            }
                            
                            else if ($row['barangay']=='North Poblacion'){
                                $cu6 = $row['cu_used'];
                                $total6 = $row['total'];
                                 $pcu6 = $row['percent_cu'];
                                $ptotal6 = $row['percent_collected'];
                            }
                            
                            else if ($row['barangay']=='South Poblacion'){
                                $cu7 = $row['cu_used'];
                                $total7 = $row['total'];
                                 $pcu7 = $row['percent_cu'];
                                $ptotal7 = $row['percent_collected'];
                            }
                            
                            else if ($row['barangay']=='Taboc'){
                                $cu8 = $row['cu_used'];
                                $total8 = $row['total'];
                                 $pcu8 = $row['percent_cu'];
                                $ptotal8 = $row['percent_collected'];
                            }
                            
                            else if ($row['barangay']=='Tughan'){
                                $cu9 = $row['cu_used'];
                                $total9 = $row['total'];
                                 $pcu9 = $row['percent_cu'];
                                $ptotal9 = $row['percent_collected'];
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
    
    // var ptotal1 = <?php echo $ptotal1; ?>;
    // var ptotal2 = <?php echo $ptotal2; ?>;
    // var ptotal3 = <?php echo $ptotal3; ?>;
    // var ptotal4 = <?php echo $ptotal4; ?>;
    // var ptotal5 = <?php echo $ptotal5; ?>;
    // var ptotal6 = <?php echo $ptotal6; ?>;
    // var ptotal7 = <?php echo $ptotal7; ?>;
    // var ptotal8 = <?php echo $ptotal8; ?>;
    // var ptotal9 = <?php echo $ptotal9; ?>;
    
    
    var cu1 = <?php echo $cu1; ?>;
    var cu2 = <?php echo $cu2; ?>;
    var cu3 = <?php echo $cu3; ?>;
    var cu4 = <?php echo $cu4; ?>;
    var cu5 = <?php echo $cu5; ?>;
    var cu6 = <?php echo $cu6; ?>;
    var cu7 = <?php echo $cu7; ?>;
    var cu8 = <?php echo $cu8; ?>;
    var cu9 = <?php echo $cu9; ?>;
    
    // var pcu1 = <?php echo $pcu1; ?>;
    // var pcu2 = <?php echo $pcu2; ?>;
    // var pcu3 = <?php echo $pcu3; ?>;
    // var pcu4 = <?php echo $pcu4; ?>;
    // var pcu5 = <?php echo $pcu5; ?>;
    // var pcu6 = <?php echo $pcu6; ?>;
    // var pcu7 = <?php echo $pcu7; ?>;
    // var pcu8 = <?php echo $pcu8; ?>;
    // var pcu9 = <?php echo $pcu9; ?>;
    
   new Chart(document.getElementById("bar-chart1"), {
    type: 'bar',
    data: {
      labels: ["Binanuahan", "Biriran", "Catanagan", "Cogon", "Embarcadero", "North Poblacion", "South Poblacion", "Taboc", "Tughan"],
      datasets: [
        {
          label: "Amount Collected (Pesos)",
          backgroundColor: ["#3cba9f","#3cba9f","#3cba9f","#3cba9f","#3cba9f","#3cba9f","#3cba9f","#3cba9f","#3cba9f"],
          data: [total1,total2,total3,total4,total5,total6,total7,total8,total9]
        }
      ]
    },
    options: {
      legend: { display: false },
      title: {
        display: true,
        text: 'Total Amount Collected (Pesos)'
      }
    }
});


// new Chart(document.getElementById("pie-chart1"), {
//     type: 'pie',
//     data: {
//       labels: ["Binanuahan", "Biriran", "Catanagan", "Cogon", "Embarcadero", "North Poblacion", "South Poblacion", "Taboc", "Tughan"],
//       datasets: [{
//         label: "Amount Collected (%)",
//         backgroundColor: ["#d6b464","#d67c64","#ced664","#acd664","#75d664","#64d691","#64d6c3","#64bbd6","#6495d6"],
//         data: [ptotal1,ptotal2,ptotal3,ptotal4,ptotal5,ptotal6,ptotal7,ptotal8,ptotal9]
//       }]
//     },
//     options: {
//       title: {
//         display: true,
//         text: 'Total Amount Collected (%)'
//       }
//     }
// });

   new Chart(document.getElementById("bar-chart2"), {
    type: 'bar',
    data: {
      labels: ["Binanuahan", "Biriran", "Catanagan", "Cogon", "Embarcadero", "North Poblacion", "South Poblacion", "Taboc", "Tughan"],
      datasets: [
        {
          label: "CU Used",
          backgroundColor: ["#64bbd6","#64bbd6","#64bbd6","#64bbd6","#64bbd6","#64bbd6","#64bbd6","#64bbd6","#64bbd6"],
          data: [cu1,cu2,cu3,cu4,cu5,cu6,cu7,cu8,cu9]
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
//       labels: ["Binanuahan", "Biriran", "Catanagan", "Cogon", "Embarcadero", "North Poblacion", "South Poblacion", "Taboc", "Tughan"],
//       datasets: [{
//         label: "CU Used",
//         backgroundColor: ["#d6b464","#d67c64","#ced664","#acd664","#75d664","#64d691","#64d6c3","#64bbd6","#6495d6"],
//         data: [pcu1,pcu2,pcu3,pcu4,pcu5,pcu6,pcu7,pcu8,pcu9]
//       }]
//     },
//     options: {
//       title: {
//         display: true,
//         text: 'Total CU Used (%)'
//       }
//     }
// });



  </script>

</body>
</html>
