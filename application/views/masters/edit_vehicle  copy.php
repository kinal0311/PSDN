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
							
							<?php /* echo "<pre>"; print_r($userinfo);echo "</pre>"; */ ?>
							
						 
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
							<input type="hidden" name="veh_owner_id" id="veh_owner_id" value="<?php echo $userinfo['veh_owner_id']; ?>" />
							    <!--<div>-->
           <!--                          <input type="checkbox" id="scales" name="scales">-->
           <!--                          <label for="scales">Brand New Vehicle No Rc Book</label>-->
           <!--                     </div><br>-->
                                <div class="form-group">
                                    <?php
									$isNewVeh=isset($userinfo['veh_is_new_vehicle'])?$userinfo['veh_is_new_vehicle']:0; 
									$M="";
									if((string)$isNewVeh==1)
									{
										$M='checked="checked"';
									}else{
                                        $M='unchecked="unchecked"';
									}
									?>

                                    <input type="checkbox" id="scales" name="scales" <?php echo $M; ?>>
                                    <label for="scales">Brand New Vehicle No Rc Book</label>
                                </div>
                                
								<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="veh_rc_no"  value="<?php echo isset($userinfo['veh_rc_no'])?$userinfo['veh_rc_no']:""; ?>" id="veh_rc_no">
                                        <label class="form-label">Vehicle RC No *</label>
                                    </div>
                                </div>
								<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="veh_chassis_no" value="<?php echo isset($userinfo['veh_chassis_no'])?$userinfo['veh_chassis_no']:""; ?>"  id="veh_chassis_no">
                                        <label class="form-label">Chassis No</label>
                                    </div>
                                </div>	
								<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control"  name="veh_engine_no" value="<?php echo isset($userinfo['veh_engine_no'])?$userinfo['veh_engine_no']:""; ?>" id="veh_engine_no">
                                        <label class="form-label">Engine No</label>
                                    </div>
                                </div>

                                 <div class="form-group form-float">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="veh_make_no"  id="veh_make_no" data-live-search="true">
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
                                        <select class="form-control show-tick" name="veh_model_no"  id="veh_model_no" data-live-search="true">
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
                                        <input type="text" class="form-control" name="veh_owner_name" value="<?php echo isset($userinfo['veh_owner_name'])?$userinfo['veh_owner_name']:""; ?>"  id="veh_owner_name">
                                        <label class="form-label">Owner Name</label>
                                    </div>
                                </div>
								
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea cols="30" rows="5"  name="veh_address"  id="veh_address" class="form-control no-resize"><?php echo isset($userinfo['veh_address'])?$userinfo['veh_address']:""; ?></textarea>
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
                                        <input type="number" class="form-control" name="veh_owner_phone" value="<?php echo isset($userinfo['veh_owner_phone'])?$userinfo['veh_owner_phone']:""; ?>" id="veh_owner_phone" readonly required>
                                        <label class="form-label">Phone No *</label>
                                    </div>
                                </div>

                                 <div class="form-group form-float">
                                     <div class="form-line">
                                        <select class="form-control show-tick" name="veh_company_id"  id="veh_company_id" data-live-search="true">
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
                                          <option value="">--Select Serial Number-- *</option>
                                        <?php
                                        }
                                        ?>
                                           
                                        </select>                                       
                                     </div>
                                </div>

                                <div class="form-group form-float">
                                     <div class="form-line">
                                     <select class="form-control show-tick" name="technician_id" id="technician_id"
                                             data-live-search="true" >
                                             <option value="">--Select Technician--</option>
                                             <?php 
                                            foreach($technician_list as $key=>$value)
                                            { 
                                            	$selected='';
												if(isset($userinfo['veh_technician_id']) && (string)$userinfo['veh_technician_id']===(string)$value['user_id'])
												{
													$selected='selected="selected"';
												}

                                            	?>
                                             <option <?php echo $selected; ?> value="<?php echo $value['user_id']; ?>">
                                                 <?php echo $value['user_name']; ?></option>
                                             <?php 
                                            }
                                            ?>                   
                                         </select>
                                     </div>
                                 </div>
                                 
							
							 <div class="form-group form-float">
                                     <div class="form-line">
                                         <select class="form-control show-tick" name="state" id="state"
                                             data-live-search="true">
                                             <!-- <option value="">--Select State--</option>
                                             <?php  foreach($stateList as $key=>$value){ 
                                            	$selected='';
												if(isset($userinfo['veh_state_id']) && (string)$userinfo['veh_state_id']===(string)$value['id'])
												{
													$selected='selected="selected"';
												}
                                            	?>
                                             <option <?php echo $selected; ?> value="<?php echo $value['id']; ?>">
                                                 <?php echo $value['s_name']; ?></option>
                                             <?php 
                                            }
                                            ?> -->
                                            <?php
                                                if(isset($userinfo['veh_state_id']) && (string)$userinfo['stateName'])
                                                {
                                                ?>
                                                <option value='<?php echo $userinfo["veh_state_id"] ?>'><?php echo $userinfo["stateName"] ?></option>
                                                <?php
                                                }else{
                                                ?>
                                                <option value="">--Select State-- *</option>
                                                <?php
                                                }
                                                ?>
                                         </select>
                                     </div>
                                 </div>

                                 <div class="form-group form-float">
                                     <div class="form-line">
                                         <select class="form-control show-tick" name="veh_rto_no" id="veh_rto_no"
                                             data-live-search="true" required>
                                             <?php
                                        if(isset($userinfo['veh_rto_no']) && (string)$userinfo['rto_number'])
                                        {
                                        ?>
                                             <option value='<?php echo $userinfo["veh_rto_no"] ?>'>
                                                 <?php echo $userinfo["rto_number"] ?></option>
                                             <?php
                                        }else{
                                        ?>
                                             <option value="">--Select RTO-- *</option>
                                             <?php
                                        }
                                        ?>

                                         </select>
                                     </div>
                                 </div>
							 <!-- <div class="form-group form-float">
                                     <div class="form-line">
                                         <select class="form-control show-tick" name="veh_rto_no" id="veh_rto_no"
                                             data-live-search="true" required>
                                             <option value="">--Select RTO--</option>
                                             <?php 
											// foreach($rto_list as $key=>$value)
											// { 
											// 	$selected='';
											// 	if(isset($userinfo['veh_rto_no']) && (string)$userinfo['veh_rto_no']===(string)$value['rto_no'])
											// 	{
											// 		$selected='selected="selected"';
											// 	}
											// ?>

                                             <option <?php //echo $selected; ?> value="<?php //echo $value['rto_no']; ?>">
                                                 <?php //echo $value['rto_number']." - ".$value['rto_place'].""; ?>
                                             </option>
                                             <?php 
											//}
											?>
                                         </select>
                                     </div>
                                 </div> -->
							
							<!--div class="form-group form-float">
                                    <div class="form-line">
										<select class="form-control show-tick" name="veh_speed"  id="veh_speed" data-live-search="true" required>
											<option value="">--Set Speed--</option>
											<?php /* 
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
											} */
											?>
										</select>										
									 </div>
                            </div -->
								 <input type="hidden" class="form-control"name="veh_speed"  id="veh_speed" value="50" required>
                                        <input type="hidden" class="form-control" name="veh_invoice_no"  id="veh_invoice_no" value="INV" required>
								<div class="form-group form-float">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="veh_tac"  id="veh_tac" data-live-search="true">
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
                                         <select class="form-control show-tick" name="veh_cat"  id="veh_cat" data-live-search="true" >
                                                 <option value="" >--Select Vehivle Category--</option>
                                                <?php
												
							$arr = array( 1 => 'TRUCK', 2 => 'LORRY', 3 => 'OFF ROAD', 4 => 'BUS', 5 => 'VAN', 6 => 'CAR', 7 => 'BIKES');
							
												$ival = 1; 
												foreach ($arr as $key => $value) { 
												$catSelected='';
												if(isset($userinfo['veh_cat']) && (string)$userinfo['veh_cat']===(string)$ival ){
													$catSelected='selected="selected"';
												}
												
												?>  
												<option <?php echo $catSelected ; ?> value="<?php echo $key ?>" ><?php echo $value ?></option>
													
												<?php $ival++; } ?>            
                                         </select>                                       
                                </div>
                            </div>
                            
								<!--div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="veh_invoice_no" value="<?php //echo isset($userinfo['veh_invoice_no'])?$userinfo['veh_invoice_no']:""; ?>"  id="veh_invoice_no" required>
                                        <label class="form-label">Invoice No</label>
                                    </div>
                                </div-->
                            <?php
                            $validity_to=date('Y-m-d',strtotime($userinfo['validity_to']));
                            ?>
                             <div class="row clearfix">
                         <div class="col-sm-4">
                             <label class="form-label">No Of Panic Button</label><br />
                             <div class="form-group">
                                 <div class="form-line">
                                     <input type="text" class="form-control" name="panic_button"
                                         id="panic_button" placeholder="Please Enter Panic Button Count"
                                         value="<?php echo $userinfo['veh_panic_button']; ?>">
                                 </div>
                             </div>
                         </div>
                         <div class="col-sm-4">
                             <label class="form-label">Validity To</label><br />
                             <div class="form-group">
                                 <div class="form-line">
                                     <input type="text" class="datetimepicker form-control" name="validity_to"
                                         id="validity_to" placeholder="Please choose date.."
                                         value="<?php echo $validity_to; ?>" required>
                                 </div>
                             </div>
                         </div>
                         <div class="col-sm-4">
                             <label class="form-label">Validity Validation</label><br />

                             <div class="form-group form-float">
                                 <div class="form-line">
                                        <select class="form-control show-tick" name="validity_validation"  id="validity_validation" data-live-search="true">
                                        <option value="">--Select Validity Validation--</option>
                                        
                                        <?php		
                                            $arr = array( 1 => 'OLD: 1Year', 2 => 'NEW: 2Year');
                            
                                            $ival = 1; 
                                            foreach ($arr as $key => $value) { 
                                            $validSelected='';
                                            if(isset($userinfo['veh_validity_validation']) && (string)$userinfo['veh_validity_validation']===(string)$ival){
                                                $validSelected='selected="selected"';
                                            }
                                            
                                            ?>  
                                            <option <?php echo $validSelected ; ?> value="<?php echo $key ?>" ><?php echo $value ?></option>
                                                    
                                        <?php $ival++; } ?>                                                                                      
                                        </select>                                       
                                     </div>
                             </div>
                         </div>
                     </div>
							<!--<div class="form-group form-float">-->
							<!--  <div class="form-line">-->
							<!--	<label class="form-label">Vehicle Owner ID Proof </label><br />-->
							<!--		<div class="body">-->
							<!--				<div class="fallback">-->
							<!--					<input type="file" id="vehicle_owner_id_proof" name="vehicle_owner_id_proof"  accept="image/*"/>-->
							<!--				</div>									-->
							<!--		</div>-->
							<!--		<?php if(isset($userinfo['vehicle_owner_id_proof']) &&  strlen($userinfo['vehicle_owner_id_proof'])>0) { ?>-->
							<!--			<div class="pull-left" style="color:#1F91F3"><p></p><a href="<?php echo AWS_S3_BUCKET_URL.$userinfo['vehicle_owner_id_proof']; ?>" target="_blank" download>Download</a></div>-->
										
							<!--		<?php } ?>-->
       <!--                             <div class="help-info" style="color:#1F91F3">Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>	-->
							<!--	</div><div class="clearfix"></div>-->
       <!--                       <input type="hidden" id="vehicle_owner_id_proof_photo" name="vehicle_owner_id_proof_photo" value="<?php echo isset($userinfo['vehicle_owner_id_proof'])?$userinfo['vehicle_owner_id_proof']:""; ?>"  />								-->
							<!--</div>-->
							<!--<div class="form-group form-float">-->
							<!--  <div class="form-line">-->
							<!--	<label class="form-label">Vehicle Owner Photo </label><br />-->
							<!--		<div class="body">-->
							<!--			<div class="fallback">-->
							<!--				<input type="file" id="vehicle_owners" name="vehicle_owners"  accept="image/*" />-->
							<!--			</div>									-->
							<!--		</div>-->
							<!--		<?php if(isset($userinfo['vehicle_owner_photo']) &&  strlen($userinfo['vehicle_owner_photo'])>0) { ?>-->
							<!--			<div class="pull-left" style="color:#1F91F3"><p></p><a href="<?php echo AWS_S3_BUCKET_URL.$userinfo['vehicle_owner_photo']; ?>" target="_blank" download>Download</a></div>-->
										
							<!--		<?php } ?>-->
       <!--                             <div class="help-info" style="color:#1F91F3">Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>	-->
							<!--	</div><div class="clearfix"></div>-->
       <!--                       <input type="hidden" id="vehicle_owners_photo" name="vehicle_owners_photo" value="<?php echo isset($userinfo['vehicle_owner_photo'])?$userinfo['vehicle_owner_photo']:""; ?>"  />								-->
							<!--</div>-->
							<!--<div class="form-group form-float">-->
							<!--  <div class="form-line">-->
							<!--	<label class="form-label">RC Book Photo </label><br />-->
							<!--		<div class="body">-->
							<!--				<div class="fallback">-->
							<!--					<input type="file" id="rc_book" name="rc_book"  accept="image/*"/>-->
							<!--				</div>									-->
							<!--		</div>	-->
							<!--		<?php if(isset($userinfo['rc_book_photo']) &&  strlen($userinfo['rc_book_photo'])>0) { ?>-->
							<!--			<div class="pull-left" style="color:#1F91F3"><p></p><a href="<?php echo AWS_S3_BUCKET_URL.$userinfo['rc_book_photo']; ?>" target="_blank" download>Download</a></div>-->
										
							<!--		<?php } ?>-->
       <!--                             <div class="help-info" style="color:#1F91F3">Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>	-->
							<!--	</div><div class="clearfix"></div>-->
       <!--                       <input type="hidden" id="rc_book_photo" name="rc_book_photo" value="<?php echo isset($userinfo['rc_book_photo'])?$userinfo['rc_book_photo']:""; ?>"  />								-->
							<!--</div>-->

                            <div class="form-group form-float">
                                     <div class="form-line">
                                         <textarea cols="30" rows="5" name="remarks" id="remarks"
                                             class="form-control no-resize"></textarea>
                                         <label class="form-label">Remarks</label>
                                         <input type="text" class="form-control" name="remarks"
                                         id="remarks"
                                         value="<?php echo $userinfo['veh_remarks']; ?>" >
                                     </div>
                            </div>
                           
							<div class="form-group form-float">
							  <div class="form-line">
								<label class="form-label">Device Photo *</label><br />
									<div class="body">
											<div class="fallback">
												<input type="file" id="upload_governer_photo" name="upload_governer_photo" />
											</div>									
									</div>
									<?php if(isset($userinfo['veh_speed_governer_photo']) &&  strlen($userinfo['veh_speed_governer_photo'])>0) { ?>
										<div class="pull-left" style="color:#1F91F3"><p></p><a href="<?php echo AWS_S3_BUCKET_URL.$userinfo['veh_speed_governer_photo']; ?>" target="_blank" download>Download</a></div>
									<?php } ?>
									
                                      <div class="help-info" style="color:#1F91F3" >Allowed image formats : <b>jpg,jpeg,jfif,gif,png,bmp</b> (Max 5 MB)</div>  		
								</div><div class="clearfix"></div>
                              <input type="hidden" id="veh_speed_governer_photo" name="veh_speed_governer_photo" value="<?php echo isset($userinfo['veh_speed_governer_photo'])?$userinfo['veh_speed_governer_photo']:""; ?>"  />								
							</div>
							
							<!--div class="form-group form-float">
                                    <div class="form-line">
										<?php
										/*$no_image=NO_IMAGE;										
										if(isset($userinfo['veh_speed_governer_photo']) &&  strlen($userinfo['veh_speed_governer_photo'])>0)
										{
											$no_image=base_url().$userinfo['veh_speed_governer_photo'];
										}*/
										?>
                                        <img src="<?php //echo $no_image; ?>" class="img-rounded" alt="Cinque Terre" width="120" height="120">
                                    </div>
                            </div-->
							
							<div class="form-group form-float">
							  <div class="form-line">
							   <label class="form-label">Vehicle Photo *</label><br />
									<div class="body">
											<div class="fallback">
												<input  type="file" id="upload_vehicle_photo" name="upload_vehicle_photo" />
											</div>									
									</div>
									<?php if(isset($userinfo['veh_photo']) &&  strlen($userinfo['veh_photo'])>0) { ?>
									<div class="pull-left" style="color:#1F91F3"><p></p><a href="<?php echo AWS_S3_BUCKET_URL.$userinfo['veh_photo']; ?>" target="_blank" download>Download</a></div>
									
									<?php } ?>									
                                      <div class="help-info" style="color:#1F91F3" >Allowed image formats : <b>jpg,jpeg,jfif,gif,png,bmp</b> (Max 5 MB)</div>  	
								</div><div class="clearfix"></div>
                              <input type="hidden" id="veh_photo" name="veh_photo" value="<?php echo isset($userinfo['veh_photo'])?$userinfo['veh_photo']:""; ?>" />								
							</div>
							
							<!--div class="form-group form-float">
                                    <div class="form-line">
										<?php
										/*$no_image=NO_IMAGE;										
										if(isset($userinfo['veh_photo']) &&  strlen($userinfo['veh_photo'])>0)
										{
											$no_image=base_url().$userinfo['veh_photo'];
										}*/
										?>
                                        <img src="<?php //echo $no_image; ?>" class="img-rounded" alt="Cinque Terre" width="120" height="120">
                                    </div>
                            </div-->
							
							<div class="clearfix"></div>
							<br>
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