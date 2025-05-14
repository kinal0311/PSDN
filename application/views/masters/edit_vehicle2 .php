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
                    Update Vehicle
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>EDIT VEHICLE CERTIFICATE</h2>                           
                        </div>
                        <div class="body">
							
							
							
						 
                            <form id="form_validation" method="POST" enctype="multipart/form-data">
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" style="color:red"  readonly class="datetimepicker form-control" name="veh_create_date" value="<?php echo isset($userinfo['veh_create_date'])?$userinfo['veh_create_date']:""; ?>"  id="veh_create_date" placeholder="Please choose date.." required>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<input type="hidden" name="veh_id" id="veh_id" value="<?php echo $userinfo['veh_id']; ?>" />
								<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="veh_rc_no"  value="<?php echo isset($userinfo['veh_rc_no'])?$userinfo['veh_rc_no']:""; ?>" id="veh_rc_no" required>
                                        <label class="form-label">Vehicle RC No</label>
                                    </div>
                                </div>
								<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="veh_chassis_no" value="<?php echo isset($userinfo['veh_chassis_no'])?$userinfo['veh_chassis_no']:""; ?>"  id="veh_chassis_no" required>
                                        <label class="form-label">Chassis No</label>
                                    </div>
                                </div>	
								<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control"  name="veh_engine_no" value="<?php echo isset($userinfo['veh_engine_no'])?$userinfo['veh_engine_no']:""; ?>" id="veh_engine_no" required>
                                        <label class="form-label">Engine No</label>
                                    </div>
                                </div>

                                 <div class="form-group form-float">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="veh_make_no"  id="veh_make_no" data-live-search="true" required>
                                            <option value="">--Select Make--</option>
                                            <?php 
                                            foreach($make_list as $key=>$value)
                                            { 
                                            	$selected='';
												if(isset($userinfo['veh_make_no']) && (string)$userinfo['veh_make_no']===(string)$value['v_make_id'])
												{
													$selected='selected="selected"';
												}

                                            	?>
                                            <option <?php echo $selected; ?> value="<?php echo $value['v_make_id']; ?>"><?php echo $value['v_make_name']; ?></option>    
                                            <?php 
                                            }
                                            ?>
                                        </select>                                       
                                     </div>
                                </div>


                              <div class="form-group form-float">
                                     <div class="form-line">
                                        <select class="form-control show-tick" name="veh_model_no"  id="veh_model_no" data-live-search="true" required>
                                        <?php
                                        if(isset($userinfo['veh_model_no']) && (string)$userinfo['ve_model_name'])
                                        {
                                        ?>
                                         <option value='<?php echo $userinfo["veh_model_no"] ?>'><?php echo $userinfo["ve_model_name"] ?></option>
                                        <?php
                                        }else{
                                        ?>
                                        <option value="">--Select Model--</option>
                                        <?php
                                        }
                                        ?>
                                            
                                        </select>                                       
                                     </div>
                                </div>



								<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="veh_owner_name" value="<?php echo isset($userinfo['veh_owner_name'])?$userinfo['veh_owner_name']:""; ?>"  id="veh_owner_name" required>
                                        <label class="form-label">Owner Name</label>
                                    </div>
                                </div>
								
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea cols="30" rows="5"  name="veh_address"  id="veh_address"   class="form-control no-resize" required><?php echo isset($userinfo['veh_address'])?$userinfo['veh_address']:""; ?></textarea>
                                        <label class="form-label">Address</label>
                                    </div>
                                </div>
				                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="email" class="form-control" name="veh_owner_email" value="<?php echo isset($userinfo['veh_owner_email'])?$userinfo['veh_owner_email']:""; ?>" id="veh_owner_email" >
                                        <label class="form-label">Email </label>
                                    </div>
                                </div>
								<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="number" class="form-control" name="veh_owner_phone" value="<?php echo isset($userinfo['veh_owner_phone'])?$userinfo['veh_owner_phone']:""; ?>" id="veh_owner_phone" required>
                                        <label class="form-label">Phone No</label>
                                    </div>
                                </div>

                                 <div class="form-group form-float">
                                     <div class="form-line">
                                        <select class="form-control show-tick" name="veh_company_id"  id="veh_company_id" data-live-search="true" required>
                                            <option value="">--Select Company/ Brand Name--</option>
                                            <?php 
                                            foreach($company_list as $key=>$value)
                                            { 

                                            	$selected='';
												if(isset($userinfo['veh_company_id']) && (string)$userinfo['veh_company_id']===(string)$value['c_company_id'])
												{
													$selected='selected="selected"';
												}
                                            ?>
                                            <option <?php echo $selected; ?>  value="<?php echo $value['c_company_id']; ?>"><?php echo $value['c_company_name']; ?></option>    
                                            <?php 
                                            }
                                            ?>
                                        </select>                                       
                                     </div>
                                </div>

 							<div class="form-group form-float">
                                     <div class="form-line">
                                        <select class="form-control show-tick" name="veh_serial_no"  id="veh_serial_no" data-live-search="true" required>
                                        <?php
                                         if(isset($userinfo['veh_serial_no']) && (string)$userinfo['s_serial_number'])
                                        {
                                        ?>
                                         <option value='<?php echo $userinfo["veh_serial_no"] ?>'><?php echo $userinfo["s_serial_number"] ?></option>
                                        <?php
                                        }else{
                                        ?>
                                          <option value="">--Select Serial Number--</option>
                                        <?php
                                        }
                                        ?>
                                           
                                        </select>                                       
                                     </div>
                                </div>

							
							<div class="form-group form-float">
                                    <div class="form-line">
										<select class="form-control show-tick" name="veh_rto_no"  id="veh_rto_no" data-live-search="true" required>
											<option value="">--Select RTO--</option>
											<?php 
											foreach($rto_list as $key=>$value)
											{ 
												$selected='';
												if(isset($userinfo['veh_rto_no']) && (string)$userinfo['veh_rto_no']===(string)$value['rto_no'])
												{
													$selected='selected="selected"';
												}
											?>
											
                                              <option <?php echo $selected; ?> value="<?php echo $value['rto_no']; ?>"><?php echo $value['rto_number']." - ".$value['rto_place'].""; ?></option> 
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
											$speed=array(30,40,50,60,70,80,90,100,110,120,130,140);
											foreach($speed as $key=>$value)
											{ 
												$selected='';
												if(isset($userinfo['veh_speed']) && (string)$userinfo['veh_speed']===(string)$value)
												{
													$selected='selected="selected"';
												}
											?>
											<option <?php echo $selected; ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>	
											<?php 
											}
											?>
										</select>										
									 </div>
                            </div>
							
								<div class="form-group form-float">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="veh_tac"  id="veh_tac" data-live-search="true" required>
                                         <?php
                                         if(isset($userinfo['veh_tac']))
                                        {
                                        ?>
                                         <option value='<?php echo $userinfo["veh_tac"] ?>'><?php echo $userinfo["veh_tac"] ?></option>
                                        <?php
                                        }else{
                                        ?>
                                          <option value="">--Select Tac No--</option>
                                        <?php
                                        }
                                        ?>                                                                                  
                                        </select>                                       
                                     </div>
                         	   </div>
							
					
								<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="veh_invoice_no" value="<?php echo isset($userinfo['veh_invoice_no'])?$userinfo['veh_invoice_no']:""; ?>"  id="veh_invoice_no" required>
                                        <label class="form-label">Invoice No</label>
                                    </div>
                                </div>
                            <?php
                            $validity_to=date('Y-m-d',strtotime($userinfo['validity_to']));
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
								<label class="form-label">Device Photo </label><br />
									<div class="body">
											<div class="fallback">
												<input type="file" id="upload_governer_photo" name="upload_governer_photo" />
											</div>									
									</div>
                                      <div class="help-info" style="color:#1F91F3" >Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>  		
								</div>
                              <input type="hidden" id="veh_speed_governer_photo" name="veh_speed_governer_photo" value="<?php echo isset($userinfo['veh_speed_governer_photo'])?$userinfo['veh_speed_governer_photo']:""; ?>"  />								
							</div>
							
							<div class="form-group form-float">
                                    <div class="form-line">
										<?php
										$no_image=NO_IMAGE;										
										if(isset($userinfo['veh_speed_governer_photo']) &&  strlen($userinfo['veh_speed_governer_photo'])>0)
										{
											$no_image=base_url().$userinfo['veh_speed_governer_photo'];
										}
										?>
                                        <img src="<?php echo $no_image; ?>" class="img-rounded" alt="Cinque Terre" width="120" height="120">
                                    </div>
                            </div>
							
							<div class="form-group form-float">
							  <div class="form-line">
							   <label class="form-label">Vehicle Photo </label><br />
									<div class="body">
											<div class="fallback">
												<input  type="file" id="upload_vehicle_photo" name="upload_vehicle_photo" />
											</div>									
									</div>	
                                      <div class="help-info" style="color:#1F91F3" >Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>  	
								</div>
                              <input type="hidden" id="veh_photo" name="veh_photo" value="<?php echo isset($userinfo['veh_photo'])?$userinfo['veh_photo']:""; ?>" />								
							</div>
							
							<div class="form-group form-float">
                                    <div class="form-line">
										<?php
										$no_image=NO_IMAGE;										
										if(isset($userinfo['veh_photo']) &&  strlen($userinfo['veh_photo'])>0)
										{
											$no_image=base_url().$userinfo['veh_photo'];
										}
										?>
                                        <img src="<?php echo $no_image; ?>" class="img-rounded" alt="Cinque Terre" width="120" height="120">
                                    </div>
                            </div>
							
							<div class="form-group form-float">
                                <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
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
	
	
	 <script src="<?php echo base_url() ?>public/js/pages/function/edit_vehicle.js?t=<?php echo time(); ?>"></script>
	<?php $this->load->view('common/admin_login_css_js'); ?> 		
</body>
</html>