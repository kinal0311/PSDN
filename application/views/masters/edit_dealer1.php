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
                    Update Dealer
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Update User Information</h2>                           
                        </div>
                        <div class="body">
						
						 
                            <form id="form_validation" method="POST" enctype="multipart/form-data">

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="user_company_id" id="user_company_id" data-live-search="true" required>
                                            <option value="">-- Company Name --</option>
                                            <?php
                                                foreach($company_list as $key=>$value)
                                                {  
                                                $selected='';
                                                if(isset($userinfo['user_company_id']) && (string)$userinfo['user_company_id']===(string)$value['c_company_id'])
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
                                <?php
                                $SubAdmin = "";
                                $Distributor="";
                                $Dealer="";
								$user_type=$this->session->userdata('user_type'); 
                                if(isset($userinfo['user_type']) && (string)$userinfo['user_type']==4)
                                {
                                    $SubAdmin='Sub Admin';
									$userType = 'Sub Admin';
                                }
                                if(isset($userinfo['user_type']) && (string)$userinfo['user_type']==2)
                                {
                                    $Dealer='checked';
									$userType="Distributor";
                                }
                                if(isset($userinfo['user_type']) && (string)$userinfo['user_type']==1)
                                {
                                    $Distributor='checked';
									$userType="Dealer";
                                }
								
                                ?>

                                <div class="form-group">
                                   <label class="form-label">User Type</label>		
<span for="s_user_type2ss" >: <?php echo $userType; ?></span>
									
									<?php /*
									$user_type=$this->session->userdata('user_type');  
									if($user_type == 0){ ?>                                  
									<input type="radio" name="user_type" disabled='disabled' <?php echo $SubAdmin; ?> id="s_user_type0" value="4" class="radio-col-deep-purple" required>
									<label for="s_user_type0" class="m-l-20">Sub Admin</label>
									<?php }
									if(($user_type == 4) || $user_type == 0){ ?>
									<input type="radio" name="user_type"  disabled='disabled' <?php echo $Distributor; ?> id="s_user_type2" value="2" class="radio-col-deep-purple" required>
									<label for="s_user_type2" >Distributor</label>
									<?php }
									if(($user_type == 2) || $user_type == 0){ ?>                                   
									<input type="radio" name="user_type"  disabled='disabled' <?php echo $Dealer; ?> id="s_user_type1" value="1" class="radio-col-deep-purple" required>
									<label for="s_user_type1">Dealer</label>                                   
									<?php } */ ?>  
									
                                </div>   
                                
                                <?php
                                $style="";
                                if(strlen($Distributor)>0)
                                {
                                    $style="display:none;";
                                }
                                ?>
                                                                  
                                 <div class="form-group form-float" style="display:none;" id="under_by_userd">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="user_distributor_id"  id="user_distributor_id" data-live-search="true" required >
                                         <?php
                                            $option="";
                                            if(strlen($Distributor)>0)
                                            {
                                                $option='<option value="0" selected>----</option>';
                                            }else{
                          $option='<option value="'.$userinfo["dis_id"].'">'.$userinfo["dis_name"].'</option>';
                                            }
                                            echo $option;
                                            ?>
                                        </select>                                       
                                     </div>
                                 </div> 
                                
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="name" value="<?php echo isset($userinfo['user_name'])?$userinfo['user_name']:""; ?>" required>
                                        <label class="form-label">Name</label>
                                    </div>
                                </div>  
								<input type="hidden" name="user_id" id="user_id" value="<?php echo $userinfo['user_id']; ?>" />
								<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="number" class="form-control" name="phone" value="<?php echo isset($userinfo['user_phone'])?$userinfo['user_phone']:""; ?>" required>
                                        <label class="form-label">Phone</label>
                                    </div>
                                </div>								
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="email" class="form-control" value="<?php echo isset($userinfo['user_email'])?$userinfo['user_email']:""; ?>" name="email" required>
                                        <label class="form-label">Email</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="user_own_company" value="<?php echo isset($userinfo['user_own_company'])?$userinfo['user_own_company']:""; ?>">
                                        <label class="form-label">Company Name</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="gstin" value="<?php echo isset($userinfo['gstin'])?$userinfo['gstin']:""; ?>">
                                        <label class="form-label">GSTIN Number</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="invoice_prefix"value="<?php echo isset($userinfo['invoice_prefix'])?$userinfo['invoice_prefix']:""; ?>" >
                                        <label class="form-label">Invoice Prefix</label>
                                    </div>
                                </div>
                                <div class="form-group">
									<?php
									$gender=isset($userinfo['user_gender'])?$userinfo['user_gender']:"M"; 
									$M=$F="";
									if((string)$gender==='M')
									{
										$M='checked="checked"';
									}else{
										$F='checked="checked"';
									}
									?>
								
                                    <input type="radio" name="gender" <?php echo $M; ?> id="male" value="M" class="with-gap">
                                    <label for="male">Male</label>

                                    <input type="radio" name="gender" <?php echo $F; ?> id="female" value="F" class="with-gap">
                                    <label for="female" class="m-l-20">Female</label>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea name="description" cols="30" rows="5" class="form-control no-resize"><?php echo isset($userinfo['user_info'])?$userinfo['user_info']:""; ?></textarea>
                                        <label class="form-label">Address</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
										<input type="hidden" class="form-control" id="old_password" value="<?php echo isset($userinfo['user_password'])?$userinfo['user_password']:""; ?>" name="old_password" >									
                                        <input type="password" class="form-control" id="password" value="<?php echo isset($userinfo['user_password'])?$userinfo['user_password']:""; ?>" name="password" >
                                        <label class="form-label">Password</label>
                                    </div>
                                </div>
								<div class="form-group form-float" style="display: <?php if($user_type == 4){ echo "block"; } else { echo "none"; } ?>">
                                    <div class="form-line">
										<select class="form-control show-tick" name="user_states" data-live-search="true" required>
											<option value="">-- Select States --</option>
											<?php  
											foreach($states_list as $key=>$value)
											{ ?>
											<option value="<?php echo $value['state_id']; ?>"><?php echo $value['state_name']; ?></option>	
											<?php 
											}
											?>
											<?php 
											foreach($states_list as $key=>$value)
											{ 
												$selected='';
												if(isset($userinfo['state_id']) && (string)$userinfo['state_id']===(string)$value['state_id'])
												{
													$selected='selected="selected"';
												}
											?>
											
                                            <option <?php echo $selected; ?> value="<?php echo $value['state_id']; ?>"><?php echo $value['state_name']; ?></option>   
											<?php 
											}
											?>
										</select>
									 </div>
                                </div>
								<div class="form-group form-float" style="display: <?php if($user_type == 2){ echo "block"; } else { echo "none"; } ?>">
                                    <div class="form-line">
										<select class="form-control show-tick" name="user_rto" data-live-search="true" required>
											<option value="">-- Select Rto --</option>
											<?php 
											foreach($rto_list as $key=>$value)
											{ 
												$selected='';
												if(isset($userinfo['users_rtono']) && (string)$userinfo['users_rtono']===(string)$value['rto_no'])
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
							    <br>
								<div class="form-group form-float">
									<label class="form-label">Account Details</label>
                                </div>
								<br>
								<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="number" class="form-control" name="user_acc_number" value="<?php echo isset($userinfo['acc_no'])?$userinfo['acc_no']:""; ?>" >
                                        <label class="form-label">Account Number</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="user_acc_name" value="<?php echo isset($userinfo['acc_name'])?$userinfo['acc_name']:""; ?>">
                                        <label class="form-label">Account Name</label>
                                    </div>
                                </div>
								<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="user_acc_ifsc_code" value="<?php echo isset($userinfo['acc_ifsc_code'])?$userinfo['acc_ifsc_code']:""; ?>">
                                        <label class="form-label">IFSC Code</label>
                                    </div>
                                </div>
								<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="user_acc_branch" value="<?php echo isset($userinfo['acc_branch'])?$userinfo['acc_branch']:""; ?>">
                                        <label class="form-label">Branch</label>
                                    </div>
                                </div>
                               

							<div class="form-group form-float">
                                <label for="upload_profile_photo">Logo</label>                                   
                                <div class="body">
										<div class="fallback">
											<input name="file" type="file" id="upload_profile_photo" name="upload_profile_photo"  accept="image/*" />
										</div>									
								</div>		
                                    <input type="hidden" id="profile_photo" name="profile_photo" value="<?php echo isset($userinfo['user_photo'])?$userinfo['user_photo']:""; ?>" />								
							</div>
							
							<div class="form-group form-float">
                                    <div class="form-line">
										<?php
										$no_image=NO_IMAGE;										
										if(isset($userinfo['user_photo']) &&  strlen($userinfo['user_photo'])>0)
										{
											$no_image=base_url().$userinfo['user_photo'];
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
	
	<script src="<?php echo base_url() ?>public/js/pages/function/edit_dealer.js?t=<?php echo time(); ?>"></script>
	<?php $this->load->view('common/admin_login_css_js'); ?> 		
</body>
</html>