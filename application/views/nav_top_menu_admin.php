<html lang="en">
<head>
<title>JWS Billing</title>
<link rel='shortcut icon' href='<? echo base_url();?>/favicon.ico' type='image/x-icon' /> 
<meta charset="UTF-8" />
<?php include "header1.php";?>

</head>
<body>

<!--Header-part-->
<div id="header" style="padding-top: 1px; padding-left: 5px;">
  <a href="<?php echo base_url()?>"><h4><font color="white"><img src="<?php echo base_url()?>assets/img/Juban_Sorsogon.png" width="50px" height="50px">&nbsp;Juban Water System Billing Management</font></h4></a>
</div>
<!--close-Header-part--> 

<!--top-Header-messaages-->

<!--close-top-Header-messaages--> 

<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li class=" dropdown" id="menu-messages2"><a href="#" data-toggle="dropdown" data-target="#menu-messages2" class="dropdown-toggle"><i class="icon icon-cog"></i> <span class="text">&nbsp;Settings</span>&nbsp;&nbsp;&nbsp;&nbsp;<b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a class="sInbox" title="<?php echo base_url('index.php/main/backupDB');?>" href="">Backup Database</a></li>
        <li><a class="sInbox" title="" href="<?php echo base_url('index.php/main/backupWebsite');?>">Backup System</a></li>
      </ul>
    </li>

    <li class=" dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-user"></i> <span class="text">&nbsp;<?php echo $this->session->userdata('username');?></span>&nbsp;&nbsp;&nbsp;&nbsp;<b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a class="sInbox" title="" href="<?php echo base_url('index.php/main/logout')?>">Logout</a></li>
      </ul>
    </li>
  </ul>
</div>

<!--close-top-Header-menu-->

<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
  <ul>
    <li class="active"><a href="<?php echo base_url('index.php/main/applicationForm');?>"><i class="icon icon-file"></i> <span>Register Applicant</span></a> </li>
    <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Manage Billing</span></a>
      <ul>
        <li><a href="<?php echo base_url('index.php/main/viewConsumers');?>">Consumers</a></li>
        <li><a href="<?php echo base_url('index.php/main/viewBilledConsumers');?>">Bills</a></li>
        <li><a href="<?php echo base_url('index.php/main/viewConsumerLoan');?>">Loans</a></li>
		<li><a href="<?php echo base_url('index.php/main/BillingSettings');?>">Billing Settings</a></li>
      </ul>
    </li>
    <li class="active"> <a href="<?php echo base_url('index.php/main/viewWaterMeters');?>"><i class="icon icon-time"></i> <span>Manage Water Meters</span></a>
    </li>

    <li class="submenu"> <a href="#"><i class="icon icon-signal"></i> <span>Reports</span></a>
      <ul>
         <li><a href="<?php echo base_url('index.php/main/viewTableSummary');?>">Summary Reports</a></li>
        <li><a href="<?php echo base_url('index.php/main/viewAbstractReports');?>">Abstract Reports</a></li>
        <li><a href="<?php echo base_url('index.php/main/viewBillHistory');?>">Bill History</a></li>
    </ul>
    <li class="active"> <a href="<?php echo base_url('index.php/main/viewManageUsers');?>"><i class="icon icon-user"></i> <span>Manage Users</span></a></li>
    <li><a href="<?php echo base_url('index.php/main/viewLogs');?>"><i class="icon icon-book"></i> <span>System Logs</span></a></li>
  </ul>
</div>	 

            <div id="myAlert72" class="modal hide">
                
                  <form action="<?php echo base_url('index.php/main/restoreDB');?>" method='post' class='form-horizontal'>

                      <div class="modal-header">
                        <button data-dismiss="modal" class="close" type="button">×</button>
                        <h3>Upload file</h3>
                      </div>
                      <div class="modal-body">

                         <div class="control-group">
                            <label class="control-label">Database 1: </label>
                            <div class="controls">
                              <input type="file" accept=".sql"/>
                            </div>
                          </div>
                          
                          <div class="control-group">
                            <label class="control-label">Database 2: </label>
                            <div class="controls">
                              <input type="file" accept=".sql"/>
                            </div>
                          </div>

                 </div> 
                      <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></a>&nbsp;<a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
                </form>
                </div>