<!DOCTYPE html>

<?php include "nav_top_menu_admin.php";?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"><a href="#" class="current"><i class="icon-home"></i>Register Applicant</a></div>
    <?php echo $this->session->flashdata("info");?>
    <h1>Application For Water Service Connection</h1>
  </div>
   
  <div class="container-fluid">

    <div class="row-fluid">
        

    <form class="form-horizontal" method="post" action="<?php echo base_url('index.php/main/addConsumer');?>">
	 <div class="buttons" style="padding-left:10px;padding-top:10px;">
                  <a href="<?php echo base_url();?>forms/application_for_water_service_connection.docx" download="application_for_water_service_connection.docx" class="btn btn-primary"><i class="icon-print"></i> Print Application Form</a>
                 
                  <a href="javascript:window.history.go(-1);" class="btn btn-warning" style="float:right;"><i class="icon-arrow-left"></i>&nbsp;Previous Page</a>
                </div>
                
           
                        <div class="widget-box collapsible">
							
                            <div class="widget-title">
                                <a href="#collapseOne" data-toggle="collapse">
    								<span class="icon"><i class="icon-th"></i></span>
                                    <h5>Application Details</h5>
                                </a>
                            </div>
                            <div class="collapse in" id="collapseOne">
                            <div class="widget-content">
                                <div class="control-group">
                <label class="control-label"><strong>First Name :</strong></label>
                <div class="controls">
                  <input type="text" name="fname" class="span11" placeholder="First name" required/>
                </div>
              </div>
             
			  <div class="control-group">
                <label class="control-label"><strong>Middle Name :</strong></label>
                <div class="controls">
                  <input type="text" name="mname" class="span11" placeholder="Middle name" />
                </div>
              <div class="control-group">
                <label class="control-label"><strong>Last Name :</strong></label>
                <div class="controls">
                  <input type="text" name="lname" class="span11" placeholder="Last name" required/>
                </div>
              </div>
       
			   <div class="control-group">
                <label class="control-label"><strong>Date of Birth :</strong></label>
                <div class="controls">
                  <input type="date" name="bdate" class="span4" placeholder="Date of Birth" required/>
                </div>
              </div>
			    <div class="control-group">
                <label class="control-label"><strong>Address (Barangay) :</strong></label>
                <div class="controls">
                  <select name="address" class="span4">
                              
                    <option>Binanuahan</option>
                    <option>Biriran</option>
                    <option>Catanagan</option>
                    <option>Cogon</option>
                    <option>Embarcadero</option>
                    <option>North Poblacion</option>
                    <option>South Poblacion</option>
                    <option>Taboc</option>
                    <option>Tughan</option>

                  </select>
                </div>
              </div>
              
              <div class="control-group">
                <label class="control-label"><strong>Specific Address :</strong></label>
                <div class="controls">
                  <input type="text" name="specific" class="span11" placeholder="Specific Address" required/>
                </div>
              </div>

               <div class="control-group">
                <label class="control-label"><strong>Phone Number :</strong></label>
                <div class="controls">
                  <div class="input-prepend"> <span class="add-on">(+63)</span>
							<input type="text" name="phonenum" placeholder="Phone Number" class="span12">
						  </div>
                </div>
              </div>

              <div class="control-group">
                        <label class="control-label"><strong>Type of Connection :</strong></label>
                        <div class="controls">
                          <label>
                            <input type="radio" name="radios" value="Residential_Institutional" checked/>
                           Residential/Institutional</label>
                          <label>
                            <input type="radio" name="radios" value="Commercial"/>
                           Commercial</label>
                        </div>
                </div>
				
				<div class="control-group">
					<label class="control-label"><strong>Inspector :</strong></label>
					<div class="controls">
					  <input type="text" class="span11" name="inspector" placeholder="Inspector" required/>
					</div>
				  </div>
				  
				 <div class="control-group">
					<label class="control-label"><strong>Date of Inspection :</strong></label>
					<div class="controls">
					  <input type="date" name="inspect_date" class="span4" placeholder="Date of Inspection" required/>
					</div>
				 </div>
                            </div>
                            </div>
                           <div class="widget-title">
                                <a href="#collapseTwo" data-toggle="collapse">
    								<span class="icon"><i class="icon-th"></i></span>
                                    <h5>Meter Details</h5>
                                </a>
                            </div>
                            <div class="collapse in" id="collapseTwo">
                                <div class="widget-content">
                                   <div class="control-group">
					<label class="control-label"><strong>Meter Serial No. :</strong></label>
					<div class="controls">
					  <input type="text" name="meter_num" class="span11" placeholder="Meter Number" required/>
					</div>
				  </div>
				  
				  <div class="control-group">
					<label class="control-label"><strong>Brand :</strong></label>
					<div class="controls">
					  <input type="text" name="meter_brand" class="span11" placeholder="Meter Brand" required/>
					</div>
				  </div>
				  
				  <div class="control-group">
					<label class="control-label"><strong>Size :</strong></label>
					<div class="controls">
						<div class="input-prepend"> 
							<input type="number" name="meter_size" min="0" class="span11" placeholder="Size" required/>
							<span class="add-on">mm</span>
						  </div>

					</div>
				  </div>
				
				<div class="control-group">
					<label class="control-label"><strong>Date Installed :</strong></label>
					<div class="controls">
					  <input type="date" name="install_date" class="span4" placeholder="Date Installed" required/>
					</div>
				  </div>
				  
				  <div class="control-group">
					<label class="control-label"><strong>Initial Reading :</strong></label>
					<div class="controls">
					  <input type="number" name="initial_reading" min="0" class="span11" placeholder="Initial Reading" required/>
					</div>
				  </div>
                                </div>
                            </div>
                          <div class="widget-title">
                                <a href="#collapseThree" data-toggle="collapse">
    								<span class="icon"><i class="icon-th"></i></span>
                                    <h5>Charges Due</h5>
                                </a>
                            </div>
                            <div class="collapse in" id="collapseThree">
                                <div class="widget-content">
                                    <div class="control-group">
						<label class="control-label"><strong>Installation Fees :</strong></label>
						<div class="controls">
						  <div class="input-prepend"> <span class="add-on">₱</span>
							<input type="number" name="installation_fees" placeholder="Installation Fee" class="span12" min="0" step=".01" value="1075">
						  </div>
						</div>
					  </div>
				   <div class="control-group">
						<label class="control-label"><strong>Meter Fee :</strong></label>
						<div class="controls">
						  <div class="input-prepend"> <span class="add-on">₱</span>
							<input type="number" name="meter_fee" min="0" step=".01" placeholder="Meter Fee" class="span12" value="0" >
						  </div>
						</div>
					  </div>
				  <div class="control-group">
						<label class="control-label"><strong>Others :</strong></label>
						<div class="controls">
						  <div class="input-prepend"> <span class="add-on">₱</span>
							<input type="number" name="others" min="0" step=".01" placeholder="Others" class="span12" value="0">
						  </div>
						</div>
					  </div>
					
				 <div class="control-group">
                        <label class="control-label"><strong>Payment Method :</strong></label>
                        <div class="controls">
                          <label>
                            <input type="radio" name="radios2" value="cash" checked>
                           Cash <em>(10% Discount)</em></label>
                          <label>
                            <input type="radio" name="radios2" value="installment"/>
                           Installment</label>
                        </div>
                    </div>
                                </div>
                            </div>
                            <div class="widget-title">
                                <a href="#collapseFour" data-toggle="collapse">
    								<span class="icon"><i class="icon-th"></i></span>
                                    <h5>Material Loan (<em>Optional</em>)</h5>
                                </a>
                            </div>
                            <div class="collapse in" id="collapseFour">
                                <div class="widget-content">
                                    <div class="control-group">
                        <label class="control-label"><strong>Loan Amount :</strong></label>
                        <div class="controls">
                        	<div class="input-prepend"> <span class="add-on">₱</span>
							 <input type="number" name="loan_amt" class="span12" placeholder="Loan Amount" value="0"/>                         
                         </div>
                          <p><em>*12% Interest per annum</em></p>
						</div>    
                     </div>

                      <div class="control-group">
		                <label class="control-label"><strong>Remarks: </strong></label>
		                <div class="controls">
		                  <textarea class="span5"  name="remarks" value=""></textarea>
		                </div>
		              </div>
                                </div>
                            </div>
						</div>
					
		</div>
			</div>
		  </div>
		  
			<div class="form-actions">
						<center><button type="submit" class="btn btn-success btn-large">Submit</button>
						<a href="<?php echo base_url('index.php/main/viewConsumers');?>" class="btn btn-warning btn-large">Cancel</a>
			</div>
		</form>
	</div>
	
	

</div>
	
<?php include "footer.php";?>

<?php include "form_scripts.php";?>

</body>
</html>
