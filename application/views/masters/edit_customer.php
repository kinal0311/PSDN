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
                    Edit Customer
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Edit Customer</h2>                          
                        </div>
                        <div class="body">
                            <form id="form_validation" method="POST" enctype="multipart/form-data">
                            <!-- <div class="form-group form-float"> -->
                                        <input type="hidden" class="form-control" tabindex="1" id="c_customer_id" name="c_customer_id" value="<?php echo $customerInfo['c_customer_id']; ?>" >    
                                <!-- </div>  -->

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" tabindex="1" id="c_customer_name" name="c_customer_name" value="<?php echo isset($customerInfo['c_customer_name'])?$customerInfo['c_customer_name']:""; ?>" required>
                                        <label class="form-label">Customer Name *</label>
                                    </div>
                                </div> 

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" tabindex="1"  id="c_phone" name="c_phone" value="<?php echo isset($customerInfo['c_phone'])?$customerInfo['c_phone']:""; ?>" required>
                                        <label class="form-label">Phone *</label>
                                    </div>
                                </div> 
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" tabindex="1"  id="c_email" name="c_email" value="<?php echo isset($customerInfo['c_email'])?$customerInfo['c_email']:""; ?>" >
                                        <label class="form-label">Email</label>
                                    </div>
                                </div> 
                                <!-- <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" tabindex="1"  id="c_password" name="c_password" value="<?php echo md5($customerInfo['c_password']); ?>"  required>
                                        <label class="form-label">Password *</label>
                                    </div>
                                </div>  -->
                                <div class="form-group form-float">
                                    <div class="form-line">
										<input type="hidden" class="form-control" id="old_password" value="<?php echo isset($customerInfo['c_password'])?$customerInfo['c_password']:""; ?>" name="old_password" >
                                        <input type="password" class="form-control" id="password" value="<?php echo isset($customerInfo['c_password'])?$customerInfo['c_password']:""; ?>" name="password" >
                                        <label class="form-label">Password</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea class="form-control" tabindex="1"  id="c_address" name="c_address"> <?php echo isset($customerInfo['c_address'])?$customerInfo['c_address']:""; ?></textarea> 
                                        <label class="form-label">Address</label>
                                    </div>
                                </div>
                                <!-- <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" tabindex="1"  id="c_password" name="c_password" required>
                                        <label class="form-label">Password *</label>
                                    </div>
                                </div>  -->
                                <div class="form-group form-float">
							  <div class="form-line">
								<label class="form-label">Photo </label><br/>
									<div class="body">
                                        <div class="fallback">
                                            <input type="file" id="vehicle_owners" name="vehicle_owners"  accept="image/*"/>
                                        </div>
									</div>
                                    <div class="help-info" style="color:#1F91F3">Allowed image formats : <b>jpg,jpeg,gif,png,bmp</b> (Max 5 MB)</div>	
								</div>
                              <input type="hidden" id="vehicle_owners_photo" name="vehicle_owners_photo" value="<?php echo isset($customerInfo['c_photo'])?$customerInfo['c_photo']:""; ?>">
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

	<script src="<?php echo base_url() ?>public/js/pages/function/edit_customer.js?t=<?php echo time(); ?>"></script>
	<?php $this->load->view('common/admin_login_css_js'); ?> 		
</body>
</html>