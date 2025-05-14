 <?php $this->load->view('common/admin_login_header'); ?>
 <?php
 $user_type=$this->session->userdata('user_type');
 ?>
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
                    <?php echo $pageTitle; ?>
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>UPDATE PROFILE INFORMATION</h2>                           
                        </div>
                        <div class="body">
						
						 
                            <form id="form_validation" method="POST" enctype="multipart/form-data">
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
                                        <input type="text" class="form-control" name="gstin" value="<?php echo isset($userinfo['gstin'])?$userinfo['gstin']:""; ?>" >
                                        <label class="form-label">GSTIN Number</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="invoice_prefix" value="<?php echo isset($userinfo['invoice_prefix'])?$userinfo['invoice_prefix']:""; ?>" >
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
                                        <textarea required name="description" cols="30" rows="5" class="form-control no-resize"><?php echo isset($userinfo['user_info'])?$userinfo['user_info']:""; ?></textarea>
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
								
							<br>
							<div class="form-group form-float">
								<label class="form-label">Documents Uploads</label>
							</div>
							<br>
							<div class="form-group form-float">
								<div class="form-line">
									<label class="form-label" for="upload_gst_certificate">GST certificate</label><br />                                  
									<div class="body">
										<div class="fallback">
											<input name="file" type="file" id="upload_gst_certificate" name="upload_gst_certificate"  accept="image/*" />
										</div>
									</div>
									<?php if(isset($userinfo['gst_certificate']) &&  strlen($userinfo['gst_certificate'])>0) { ?>
										<div class="pull-left" style="color:#1F91F3"><p></p><a href="<?php echo base_url().$userinfo['gst_certificate']; ?>" target="_blank" download>Download</a></div>
									<?php } ?>
									<div class="help-info" style="color:#1F91F3" >Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>								
								</div>		
                                <input type="hidden" id="gst_certificate" name="gst_certificate" value="<?php echo isset($userinfo['gst_certificate'])?$userinfo['gst_certificate']:""; ?>" />								
							</div>
							<br>
							<div class="form-group form-float">
								<div class="form-line">
									<label class="form-label" for="upload_id_proof">ID Proof</label><br />                                  
									<div class="body">
										<div class="fallback">
											<input name="file" type="file" id="upload_id_proof" name="upload_id_proof"  accept="image/*" />
										</div>
									</div>
									<?php if(isset($userinfo['id_proof']) &&  strlen($userinfo['id_proof'])>0) { ?>
										<div class="pull-left" style="color:#1F91F3"><p></p><a href="<?php echo base_url().$userinfo['id_proof']; ?>" target="_blank" download>Download</a></div>
									<?php } ?>
									<div class="help-info" style="color:#1F91F3" >Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>
								</div>		
                                <input type="hidden" id="id_proof" name="id_proof" value="<?php echo isset($userinfo['id_proof'])?$userinfo['id_proof']:""; ?>" />								
							</div>
							<br>
							<div class="form-group form-float">
								<div class="form-line">
									<label class="form-label" for="upload_photo_personal">Dealer / Distributer Photo (Personal)</label><br />                                   
									<div class="body">
										<div class="fallback">
											<input name="file" type="file" id="upload_photo_personal" name="upload_photo_personal"  accept="image/*" />
										</div>
									</div>
									<?php if(isset($userinfo['user_photo']) &&  strlen($userinfo['photo_personal'])>0) { ?>
										<div class="pull-left" style="color:#1F91F3"><p></p><a href="<?php echo base_url().$userinfo['photo_personal']; ?>" target="_blank" download>Download</a></div>
									<?php } ?>
									<div class="help-info" style="color:#1F91F3" >Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>
								</div>		
                                <input type="hidden" id="photo_personal" name="photo_personal" value="<?php echo isset($userinfo['photo_personal'])?$userinfo['photo_personal']:""; ?>" />								
							</div>
							<br>
							<div class="form-group form-float">
								<div class="form-line">
                                <label class="form-label" for="upload_pan_card">PAN card</label><br />                                   
									<div class="body">
										<div class="fallback">
											<input name="file" type="file" id="upload_pan_card" name="upload_pan_card"  accept="image/*" />
										</div>
									</div>
									<?php if(isset($userinfo['pan_card']) &&  strlen($userinfo['pan_card'])>0) { ?>
										<div class="pull-left" style="color:#1F91F3"><p></p><a href="<?php echo base_url().$userinfo['pan_card']; ?>" target="_blank" download>Download</a></div>
									<?php } ?>
									<div class="help-info" style="color:#1F91F3" >Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>
								</div>		
                                <input type="hidden" id="pan_card" name="pan_card" value="<?php echo isset($userinfo['pan_card'])?$userinfo['pan_card']:""; ?>" />								
							</div>
							<br>
							<div class="form-group form-float">
								<div class="form-line">
									<label class="form-label" for="upload_cancelled_cheque_leaf">Cancelled Cheque Leaf</label><br />                                  
									<div class="body">
										<div class="fallback">
											<input name="file" type="file" id="upload_cancelled_cheque_leaf" name="upload_cancelled_cheque_leaf"  accept="image/*" />
										</div>
									</div>
									<?php if(isset($userinfo['cancelled_cheque_leaf']) &&  strlen($userinfo['cancelled_cheque_leaf'])>0) { ?>
										<div class="pull-left" style="color:#1F91F3"><p></p><a href="<?php echo base_url().$userinfo['cancelled_cheque_leaf']; ?>" target="_blank" download>Download</a></div>
									<?php } ?>
									<div class="help-info" style="color:#1F91F3" >Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>
								</div>		
                                <input type="hidden" id="cancelled_cheque_leaf" name="cancelled_cheque_leaf" value="<?php echo isset($userinfo['cancelled_cheque_leaf'])?$userinfo['cancelled_cheque_leaf']:""; ?>" />								
							</div>
							<br>	
							<div class="form-group form-float">
                                <label for="upload_profile_photo">Logo</label>                                   
                                <div class="body">
										<div class="fallback">
											<input name="file" type="file" id="upload_profile_photo" name="upload_profile_photo" />
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
 <?php if(check_permission($user_type,'profile_edit')){ ?>							
							<div class="form-group form-float">
                                <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
							</div>
 <?php } ?>                            
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Validation -->
        
        </div>
    </section>
	
	<!--- Model Dialog ---->

	<script src="<?php echo base_url() ?>public/js/pages/function/edit_profile.js?t=<?php echo time(); ?>"></script>
	<?php $this->load->view('common/admin_login_css_js'); ?> 		


    <script>
    $(document).ready(function(){
        <?php
            $user_type=$this->session->userdata('user_type');
            if((string)$user_type !='0')
            {
        ?>
                $('#form_validation').find('input').prop('disabled',true);
                $('#upload_profile_photo').hide();
                $("[type=submit]").hide();
        <?php
            }
        ?>
    });
    </script>
</body>
</html>