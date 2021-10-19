<!DOCTYPE html>
<html lang="en">
    
<head>
        <title>JWS Billing</title>
        <link rel='shortcut icon' href='favicon.ico' type='image/x-icon' /> 
        <meta charset="UTF-8" />
       <?php include "header2.php";?>
    </head>
    <body>

        <div id="loginbox">            
             <?php echo $this->session->flashdata("error");?>
            <form id="loginform" class="form-vertical" method="POST" action="<?php echo base_url('index.php/main/checkLogin');?>">
				 <div class="control-group normal_text"><img src="<?php echo base_url()?>assets/img/Juban_Sorsogon.png" width="65px" height="65px"><h4>BILLING MANAGEMENT SYSTEM</h4><h5>Juban Water System</h5></div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on"><i class="icon-user"></i></span><input type="text" name="username" placeholder="Username" />
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on"><i class="icon-lock"></i></span><input type="password" name="password" placeholder="Password" />
                        </div>
                    </div>
                </div>
        
                <div class="form-actions">
                    <span class="pull-right"><input type="submit" class="btn btn-success" value="Login" /></span>
         
                </div>
            </form>
            </div>
        
        <script src="<?php echo base_url()?>assets/js/jquery.min.js"></script>  
        <script src="<?php echo base_url()?>assets/js/jquery.min.js"></script> 
        <script src="<?php echo base_url()?>assets/js/jquery.ui.custom.js"></script> 
        <script src="<?php echo base_url()?>assets/js/bootstrap.min.js"></script> 
        <script src="<?php echo base_url()?>assets/js/bootstrap-colorpicker.js"></script> 
        <script src="<?php echo base_url()?>assets/js/bootstrap-datepicker.js"></script> 
        <script src="<?php echo base_url()?>assets/js/jquery.uniform.js"></script> 
        <script src="<?php echo base_url()?>assets/js/select2.min.js"></script> 
        <script src="<?php echo base_url()?>assets/js/maruti.js"></script> 
        <script src="<?php echo base_url()?>assets/js/maruti.form_common.js"></script>
        <script src="<?php echo base_url()?>assets/js/jquery.peity.min.js"></script> 
        <script src="<?php echo base_url()?>assets/js/maruti.interface.js"></script>


    </body>

</html>
