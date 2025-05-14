 <?php $this->load->view('common/admin_login_header'); ?>

     <?php
 $user_type=$this->session->userdata('user_type');
 ?>
 <script type="text/javascript">
     var user_type='<?php echo $user_type; ?>';
 </script>
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
                    Create New Entry
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>CREATE PROFORMA INVOICE</h2>                           
                        </div>
                        <div class="body">
							
							<?php
							
							$user_company_id = $this->session->userdata('user_company_id');
							$user_type 		 = $this->session->userdata('user_type');
							
							//echo $user_company_id."<br>";
							
							
							?>
							<?php if($message!=""){ ?>
							<div class="alert alert-success" role="alert" id="alertmsgdiv">
							  <i class="fa fa-check" aria-hidden="true"></i><strong>Success!</strong> proforma invoice  created successfully. 
							</div>
							<?php
							}
							?>
                            <form method="POST" enctype="multipart/form-data" name="txtfrm" id="txtfrm" autocomplete="off">
                            
							
                                <div class="form-group">
									<?php
									//echo "user Type :: ".$user_type." -> ".$user_company_id."<br>";
									if($user_type=="0" or $user_type=="4"){  
									?>
									
                                    <input type="radio" name="txtcreateto" id="txtcreateto1" value="distributors" class="with-gap" checked onclick="javascript:radiobuttonclick();">
                                    <label for="txtcreateto1">Distributor</label>
									<input type="radio" name="txtcreateto" id="txtcreateto4" value="newperson" class="with-gap" onclick="javascript:radiobuttonclick();">
                                    <label for="txtcreateto4">New Person</label>
									<?php 
									}
									else if($user_type=="2"){
									?>
									<input type="radio" name="txtcreateto" id="txtcreateto2" value="dealers" class="with-gap" checked onclick="javascript:radiobuttonclick();">
                                    <label for="txtcreateto2">Dealers</label>
									<input type="radio" name="txtcreateto" id="txtcreateto4" value="newperson" class="with-gap" onclick="javascript:radiobuttonclick();">
                                    <label for="txtcreateto4">New Person</label>
									<?php	
									}	
									else if($user_type=="1"){
									?>
									<input type="radio" name="txtcreateto" id="txtcreateto3" value="customers" class="with-gap" checked onclick="javascript:radiobuttonclick();">
                                    <label for="txtcreateto3">Customers</label>
									<input type="radio" name="txtcreateto" id="txtcreateto4" value="newperson" class="with-gap" onclick="javascript:radiobuttonclick();">
                                    <label for="txtcreateto4">New Person</label>
									<?php
									}
									?>							

                                </div>
							<?php
							if($user_type=="0"){
							?>
							<div class="form-group form-float">
                                    
                                        <select class="form-control show-tick" name="txtpicompany" id="txtpicompany" data-live-search="true">
                                            <option value="">--Choose Company--</option>
                                            <?php foreach($companydropdowndatas as $row) { ?>
											<option value ="<?php echo $row->c_company_id; ?>"><?php echo $row->c_company_name; ?></option>
											<?php } ?>
                                            
                                        </select>
										<span class="help-block errortext" id="txtpicompanyerror"></span>										
                                     
                            </div>
							<?php
							}
							else{
							?>
							<input type="hidden" name="txtpicompany" id="txtpicompany" value="<?php echo $user_company_id;?>"
							/>
							<span class="help-block errortext" id="txtpicompanyerror"></span>
							<?php	
							}	
							?>
							<div class="form-group form-float" id="dropdowndivs">
                                    
                                        <select class="form-control show-tick" name="txtdropdowndisdeal"  id="txtdropdowndisdeal" data-live-search="true" onchange="javascript:choosecompany();">
										<?php
										if($user_type=="1"){
										?>
										<option value="">--Choose Customers--</option>
										<?php	
										}
										else if($user_type=="0" or $user_type=="4"){  
										?>	
                                        <option value="">--Choose Distributors--</option>
                                        <?php
										}
										else if($user_type=="2"){
										?>
										<option value="">--Choose Dealers--</option>
										<?php
										}
										?>
                                        </select>
										<span class="help-block errortext" id="txtdropdowndisdealerror"></span>										
                                     
                            </div>
							
							
							
							<div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="txtcompanyname"  id="txtcompanyname" readonly value="<?php echo $companyname;?>">										
                                        <label class="form-label">Company Name</label>
                                    </div>
									<span class="help-block errortext" id="txtcompanynameerror"></span>
                            </div>
							<div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea cols="30" rows="5"  name="txtaddress"  id="txtaddress"  class="form-control no-resize" readonly><?php echo $companyaddress;?></textarea>
                                        <label class="form-label">Address</label>
                                    </div>
									<span class="help-block errortext" id="txtaddresserror"></span>
                            </div>
							
							<div class="row clearfix">
                                <div class="col-sm-4">                                
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="txtgsttin"  id="txtgsttin">
											<label class="form-label">GSTIN</label>
                                        </div>
                                    </div>
                                </div>
								<div class="col-sm-4">                                
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="txtmobile"  id="txtmobile">
											<label class="form-label">Mobile</label>
                                        </div>
                                    </div>
                                </div>
								<div class="col-sm-4">                                
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="txtemail"  id="txtemail">
											<label class="form-label">Email</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
							
							<div class="row clearfix">
                                
								<?php
								$validity_to = "2019-06-29";
								?>
								<div class="col-sm-4">                                
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="datetimepicker form-control" name="txtduedate"  id="txtduedate" placeholder="Due Date" value="<?php echo $validity_to; ?>">
                                        </div>
                                    </div>
                                </div>
								<div class="col-sm-4">                                
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="txtdeliverynote"  id="txtdeliverynote">
											<label class="form-label">Delivery Note</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
							
							
							<div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea cols="30" rows="5" name="txttermsofdelivery"  id="txttermsofdelivery"  class="form-control no-resize"></textarea>
                                        <label class="form-label">Terms of Delivery</label>
                                    </div>
                            </div>
                            
                            
							
							
							<div class="row clearfix">
                                <div class="col-sm-12">                                
									<button class="btn btn-primary waves-effect" type="button" id="addmorebtn">Add More</button>
									<table class="table table-striped">
									  <thead>
										<tr>
										  <th scope="col">Product Name</th>
										  <th scope="col">Quantity</th>
										  <th scope="col">Unit Price</th>
										  <th scope="col">Offer Price</th>
										  <th scope="col">Rate</th>
										  <th scope="col">Action</th>
										</tr>
									  </thead>
									  <tbody id="productsbody">
										
									  </tbody>
									  </table>
								
								</div>
							</div>	
							
							<div class="row clearfix">
                                <div class="col-sm-4">
                                <label class="form-label">Send to Email</label><br />
                                    <div class="form-group">
                                        
                                            <input type="radio" name="txtsendmail" id="txtsendmail1" value="yes" class="with-gap" checked>
											<label for="list">Yes</label>
											<input type="radio" name="txtsendmail" id="txtsendmail2" value="no" class="with-gap">
											<label for="list">No</label>
                                        
                                    </div>
                                </div>
                            </div>
							
							<div class="form-group form-float">
                                <button class="btn btn-primary waves-effect" type="button" id="submitbtn">Generate Proforma Invoice</button>
                                
							</div>
							
							
							
							<input type="hidden" name="hidsubmit" id="hidsubmit" value="Submit" />
							<input type="hidden" name="hidusertype" id="hidusertype" value="<?php echo $user_type;?>" />
							<input type="hidden" name="hideditId" id="hideditId" />
							
							<input type="hidden" name="hidbaseurl" id="hidbaseurl" value="<?php echo base_url();?>" />
							<input type="hidden" name="txthidcountrows" id="txthidcountrows" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Validation -->
        
        </div>
    </section>
	
	<!--- Model Dialog ---->
	
	
	 
	<?php  $this->load->view('common/admin_login_css_js'); ?> 
	
	<script	src="<?php echo base_url() ?>public/js/create-proforma.js"></script>
	
    		
</body>
</html>