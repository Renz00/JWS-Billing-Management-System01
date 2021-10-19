<!DOCTYPE html>

<?php include "nav_top_menu_admin.php";?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#" class="current"><i class="icon-home"></i> Manage Users</a></div>

    <?php echo $this->session->flashdata("info");?>
    <h1>Manage Users</h1>
  </div>
  <div class="container-fluid">
  
	<div class="row-fluid">
      <div class="span12">
         
          <a href="<?php echo base_url('index.php/main/viewManageUsers')?>" class="btn btn-success" ><i class="icon-refresh"></i></a>
          <a href='#myAlert1' data-toggle='modal'><button class="btn btn-warning"><i class="icon-plus-sign"></i> Add User</button></a>      

        <div class="widget-box">
          <div class="widget-title">
          <span class="icon"><i class="icon-th"></i></span> 
			 	  
            <h5>Users</h5>

          </div>

          <div id="myAlert1" class="modal hide">

                     <form class="form-horizontal" method="post" action="<?php echo base_url('index.php/main/addUser')?>">

                      <div class="modal-header">
                        <button data-dismiss="modal" class="close" type="button">×</button>
                        <h3>Add User</h3>
                      </div>
                      <div class="modal-body">

                      <div class="control-group">
                        <label class="control-label"><strong>Username :</strong></label>
                        <div class="controls">
                           <input type="text" name="uname" class="span11" placeholder="Username" required/>
                         </div>
                     </div>

                      <div class="control-group">
                        <label class="control-label"><strong>Password:</strong></label>
                        <div class="controls">
                           <input type="password" name="pword" class="span11" placeholder="Password" required/>
                         </div>
                </div>

              <div class="control-group">
                <label class="control-label"><strong>Account Type :</strong></label>
                <div class="controls">
                  <select name="acc_type" class="span6">
                    <option>Administrator</option>
                    <option>Accounting</option>             
                    <option>Cashier</option>
                    <option>Plumber</option>
                    <option>Consumer</option>
                  </select>
                </div>
              </div>

                 </div> 
                      <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></a>&nbsp;<a data-dismiss="modal" class="btn" href="#">Cancel</a> </div>
                </form>
                </div>  

          <div class="widget-content nopadding">
		   
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Username</th>
        				  <th>Password</th>
                  <th>Account Type</th>
                   <th>Status</th>
                   <th>Date Added</th>
                   <th>Actions</th>
                </tr>
              </thead>
              <tbody>
			
              <?php

                $ctr1=0;
                $ctr2=0;
                $ctr3=0;

                foreach ($users as $row){

                  $new_dateadd = date('Y-F-d', strtotime($row['date_added'])); 

          				echo	"<tr class='gradeA'>
            						  <td>".$row['username']."</td>

            						  <td>".$row['password']."</td>
            						  <td>".$row['user_type']."</td>";

                        if ($row['status']=="Online"){
                            echo "<td><center><span class='label label-success'>".$row['status']."</span></center></td>";

                            $ctr2++;
                        }
                         else if ($row['status']=="Offline"){
                           echo  "<td><center><span class='label label-warning'>".$row['status']."</span></center></td>";

                           $ctr3++;
                        }

                    echo  "

                      <td>".$new_dateadd."</td>

                    <td>
                    <center>
                    <a href='#edit_".$row['username']."' data-toggle='modal' class='btn btn-success btn-lg'><i class='icon icon-edit'></i>&nbsp;Edit</a>

                    &nbsp;

                       <a href='#delete_".$row['username']."' data-toggle='modal' class='btn btn-danger btn-lg'><i class='icon icon-remove'></i>&nbsp;Delete</a>
                   
                      </center>
                      </td>
                      </tr>

                      <div id='edit_".$row['username']."' class='modal hide'>
                       <form class='form-horizontal' method='post' action='".base_url("index.php/main/updateUserDetails/".$row['username'])."'>

                          <div class='modal-header'>
                            <button data-dismiss='modal' class='close' type='button'>×</button>
                            <h3>Edit User</h3>
                          </div>
                          <div class='modal-body'>

                          <div class='control-group'>
                            <label class='control-label'><strong>Username :</strong></label>
                            <div class='controls'>
                               <input type='text' name='uname' class='span11' placeholder='Username' value='".$row['username']."' required/>
                             </div>
                         </div>
                          
                          <div class='control-group'>
                            <label class='control-label'><strong>Password:</strong></label>
                            <div class='controls'>
                               <input type='password' name='pword' class='span11' placeholder='Password' value='".$row['password']."' required/>
                             </div>
                         </div>

                           <div class='control-group'>
                            <label class='control-label'><strong>Date Added :</strong></label>
                            <div class='controls'>
                               <input type='date' name='date_added' class='span11' placeholder='Date' value='".$row['date_added']."' required/>
                             </div>
                         </div>
                     
                           <div class='control-group'>
                            <label class='control-label'><strong>Account Type:</strong></label>
                            <div class='controls'>
                              <select class='span4' name='acc_type'>";

                            if ($row[user_type] == "Administrator"){
                                echo "<option selected>Administrator</option>";
                            }
                            else echo "<option>Administrator</option>";

                             if ($row[user_type] == "Accounting"){
                                echo "<option selected>Accounting</option>";
                            }
                            else echo "<option>Accounting</option>";

                             if ($row[user_type] == "Cashier"){
                                echo "<option selected>Cashier</option>";
                            }
                            else echo "<option>Cashier</option>";

                             if ($row[user_type] == "Plumber"){
                                echo "<option selected>Plumber</option>";
                            }
                            else echo "<option>Plumber</option>";

                             if ($row[user_type] == "Consumer"){
                                echo "<option selected>Consumer</option>";
                            }
                            else echo "<option>Consumer</option>";
                             echo "</select>
                            </div>
                          </div>
                        </div> 
                          <div class='modal-footer'><button type='submit' class='btn btn-primary'>Save</button></a>&nbsp;<a data-dismiss='modal' class='btn' href='#''>Cancel</a> </div>
                          </form>
                        </div>

                        <div id='delete_".$row['username']."' class='modal hide'>
                       <form action='".base_url("index.php/main/deleteUser/".$row['username'])."' method='post' class='form-horizontal'>
                          <div class='modal-header'>
                            <button data-dismiss='modal' class='close' type='button'>×</button>
                            <h3>Delete User</h3>
                          </div>
                          <div class='modal-body'>
                              <center>

                              <div class='alert alert-error alert-block'> 
                              <h4 class='alert-heading'>Warning!</h4>
                              <br>
                              Deleting User: <b>".$row['username']."</b><br>

                               Are you sure?

                              </div>
                             
                              </center>

                           </div> 
                                <div class='modal-footer'><button type='submit' class='btn btn-primary'>Confirm</button></a>&nbsp;<a data-dismiss='modal' class='btn' href='#''>Cancel</a> </div>
                          </form>
                        </div>";

                        $ctr1++;
                        
                }
                
              echo "</tbody>
            </table>
          </div>
        </div>";
          echo "<font color='gray'><h6>Total Number of Users: ".$ctr1."</h6>";
          echo "<h6>Number of <b>Offline</b>: ".$ctr3." | Number of <b>Online</b>: ".$ctr2."</h6></font>";
            ?>
     </div>   
  </div>
  </div>
</div>
<?php include "footer.php";?>

<?php include "form_scripts.php";?>
</body>

</html>
