 <?php $this->load->view('common/admin_login_header'); ?>
 <!-- Bootstrap Material Datetime Picker Css -->
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

 <!-- Wait Me Css -->
 <link href="<?php echo base_url() ?>public/plugins/waitme/waitMe.css" rel="stylesheet" />

 <!-- Bootstrap Select Css -->
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

<body class="<?php echo THEME_BODY_HEADER_COLOR; ?>">
    <?php $this->load->view('common/top_search_bar'); ?>  
	 <?php $this->load->view('common/dashboard_top_bar'); ?> 
  <?php $this->load->view('common/left_side_bar'); ?>


    <section class="content">
        <div class="container-fluid">
            <div class="block-header" style="display:none;">
                <h2>
                    Create Renewal Entry
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>CREATE RENEWAL ENTRY</h2>                           
                        </div>
                        <div class="body">
							<?php 		
							
							//print_r($userinfo);
												
							echo "Allotted :&nbsp;".$allotted = isset($userinfo['allotted'])?$userinfo['allotted']:0; 
							echo "<br>";
				            echo "Used :&nbsp;".$used = isset($userinfo['used'])?$userinfo['used']:0;  
				            echo "<br>";
							echo "Available :&nbsp;".$available = $userinfo['allotted'] - $userinfo['used'];
							echo "<br><br>"; 				
							 ?>
						 
                            <form id="form_validation" method="POST" enctype="multipart/form-data">
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" style="color:red" readonly class="form-control" name="veh_create_date"  id="veh_create_date" placeholder="Please choose date.." value="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
								<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="veh_rc_no"  id="veh_rc_no" required>
                                        <label class="form-label">Vehicle RC No</label>
                                    </div>
                                </div>
								<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="veh_chassis_no"  id="veh_chassis_no" required>
                                        <label class="form-label">Chassis No</label>
                                    </div>
                                </div>	
								<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control"  name="veh_engine_no"  id="veh_engine_no" required>
                                        <label class="form-label">Engine No</label>
                                    </div>
                                </div>
								

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="veh_make_no"  id="veh_make_no" data-live-search="true" required>
                                            <option value="">--Select Make--</option>
                                            <?php 
                                            foreach($make_list as $key=>$value)
                                            { ?>
                                            <option value="<?php echo $value['v_make_id']; ?>"><?php echo $value['v_make_name']; ?></option>    
                                            <?php 
                                            }
                                            ?>
                                        </select>                                       
                                     </div>
                                </div>


                              <div class="form-group form-float">
                                     <div class="form-line">
                                        <select class="form-control show-tick" name="veh_model_no"  id="veh_model_no" data-live-search="true" required>
                                            <option value="">--Select Model--</option>
                                           
                                        </select>                                       
                                     </div>
                                </div>


								<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="veh_owner_name"  id="veh_owner_name" required>
                                        <label class="form-label">Owner Name</label>
                                    </div>
                                </div>
								
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea cols="30" rows="5"  name="veh_address"  id="veh_address"  class="form-control no-resize" required></textarea>
                                        <label class="form-label">Address</label>
                                    </div>
                                </div>
				
								<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="number" class="form-control" name="veh_owner_phone"  id="veh_owner_phone" required>
                                        <label class="form-label">Phone No</label>
                                    </div>
                                </div>
								
                                <div class="form-group form-float">
                                     <div class="form-line">
                                        <select class="form-control show-tick" name="veh_company_id"  id="veh_company_id" data-live-search="true" required>
                                            <option value="">--Select Company/ Brand Name--</option>
                                            <?php 
                                            foreach($company_list as $key=>$value)
                                            { ?>
                                            <option  value="<?php echo $value['c_company_id']; ?>"><?php echo $value['c_company_name']; ?></option>    
                                            <?php 
                                            }
                                            ?>
                                        </select>                                       
                                     </div>
                                </div>
                                
							
                                <div class="form-group form-float">
                                     <div class="form-line">
									    <input type="text" class="form-control" name="veh_serial_no"  id="veh_serial_no" required>
										<label class="form-label">Serial No</label>
									   
                                      <?php /* <select class="form-control show-tick" name="veh_serial_no"  id="veh_serial_no" data-live-search="true" required>
                                            <option value="">--Select Serial Number--</option>
                                            <?php 
                                            foreach($serialList as $key=>$value)
                                            { ?>
                                           
                                            <?php 
                                            }
                                            ?>
                                        </select> */ ?>
                                     </div>
                                </div>


                                
								
							<div class="form-group form-float">
                                    <div class="form-line">
										<select class="form-control show-tick" name="veh_rto_no"  id="veh_rto_no" data-live-search="true" required>
											<option value="">--Select RTO--</option>
											<?php 
											foreach($rto_list as $key=>$value)
											{ ?>
											
                                              <option  value="<?php echo $value['rto_no']; ?>"><?php echo $value['rto_number']." - ".$value['rto_place'].""; ?></option> 
											<?php 
											}
											?>
										</select>										
									 </div>
                            </div>
							
							<div class="form-group form-float">
                                    <div class="form-line">
										<select class="form-control show-tick" name="veh_speed"  id="veh_speed" data-live-search="true" required>
											<option value="">--Set Speed--</option>
											<?php 
											$speed=unserialize(VEHICLE_SPEED);
											foreach($speed as $key=>$value)
											{ ?>
											<option value="<?php echo $value; ?>"><?php echo $value; ?></option>	
											<?php 
											}
											?>
										</select>										
									 </div>
                            </div>
							
							
							<div class="form-group form-float">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="veh_tac"  id="veh_tac" data-live-search="true" required>
                                            <option value="">--Select Tac No--</option>                                           
                                        </select>                                       
                                     </div>
                            </div> 
					
							<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="veh_invoice_no"  id="veh_invoice_no" required>
                                        <label class="form-label">Invoice No</label>
                                    </div>
                            </div>
                            <?php

                                $date = date('Y-m-d');
                                $validity_to=date('Y-m-d', strtotime($date. ' + 1 year'));
                            ?>
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                <label class="form-label">Validity To</label><br />
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="datetimepicker form-control" name="validity_to"  id="validity_to" placeholder="Please choose date.." value="<?php echo $validity_to; ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<div class="form-group form-float">
							  <div class="form-line">
								<label class="form-label">Speed Governer Photo </label><br />
									<div class="body">
											<div class="fallback">
												<input type="file" id="upload_governer_photo" name="upload_governer_photo"  accept="image/*" required/>
											</div>									
									</div>	
                                    <div class="help-info">Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>	
								</div>
                              <input type="hidden" id="veh_speed_governer_photo" name="veh_speed_governer_photo" value=""  />								
							</div>
							
							<div class="form-group form-float">
							  <div class="form-line">
							   <label class="form-label">Vehicle Photo </label><br />
									<div class="body">
											<div class="fallback">
												<input  type="file" id="upload_vehicle_photo" name="upload_vehicle_photo"  accept="image/*" required/>
											</div>									
									</div>	
                                    <div class="help-info">Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>  	
								</div>
                              <input type="hidden" id="veh_photo" name="veh_photo" value="" />								
							</div>
							
							<div class="form-group form-float">
                                <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
                                <button class="btn bg-blue-grey waves-effect" type="button" onClick="return resetall();">RESET</button>
							</div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Validation -->
        
        </div>
    </section>
	
	<!--- Model Dialog ---->
	
	
	 <script src="<?php echo base_url() ?>public/js/pages/function/create_renewal_vehicle.js?t=<?php echo 1; ?>"></script>
	<?php $this->load->view('common/admin_login_css_js'); ?> 		
</body>
</html>