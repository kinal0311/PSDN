 <?php $this->load->view('common/admin_login_header'); ?>
 <!-- Bootstrap Material Datetime Picker Css -->
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

 <!-- Wait Me Css -->
 <link href="<?php echo base_url() ?>public/plugins/waitme/waitMe.css" rel="stylesheet" />

 <!-- Bootstrap Select Css -->
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<style>
.glyphicon { 
  line-height: 2 !important;  
}
.pagination>li>a, .pagination>li>span { border-radius: 50% !important;margin: 0 5px;}
</style>
<body class="<?php echo THEME_BODY_HEADER_COLOR; ?>">
    <?php $this->load->view('common/top_search_bar'); ?>  
	 <?php $this->load->view('common/dashboard_top_bar'); ?> 
  <?php $this->load->view('common/left_side_bar'); ?>


   <section class="content">
        <div class="container-fluid">
            <div class="block-header" style="display: none;">
                <h2</h2>
            </div>
            <!-- Input -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Sales Report
							<?php
								$user_type=$this->session->userdata('user_type');
								$user_id=$this->session->userdata('user_id');
								$redirectionBase='admin';
								if((string)$user_type==='1')
								{
									$redirectionBase='dealer';
								}
								$dealerNone="";
								if((string)$user_type==='1' || (string)$user_type==='2' )
								{
									$dealerNone="display:none;";
								}
							?>
                            </h2>
                        </div>
                        <div class="body">
                           <div class="table-responsive" style="    overflow-x: hidden;    height: 45%;">
					<!--- Search---->
								<form action="<?php echo base_url().$redirectionBase; ?>/view_salesreport" name="searchfilter"  id="form_validation"  method="get" novalidate />				
										<div class="row clearfix">
											
											<div class="col-sm-4" style="<?php echo $dealerNone; ?>">
												<div class="form-group">
													<div class="form-line">
														<select class="form-control show-tick" name="user_type"
														onchange="return select_user_type(event,this);" 
														 id="user_type" data-live-search="true" data-size="5" >
															<option value="">-- User Type --</option>
															<?php
																$selected="";
																if(isset($_GET['user_type']) && (string)$_GET['user_type']===(string)-1)
																{
																	$selected='selected="selected"';
																}
															?>
															<option <?php echo $selected; ?> value="-1"> ALL </option>
															<?php
															$selectArray=unserialize(USERS_TYPE_LIST);
															foreach($selectArray as $key=>$value)
															{
																$selected="";
																if(isset($_GET['user_type']) && (string)$_GET['user_type']===(string)$key)
																{
																	$selected='selected="selected"';
																}
															?>
															<option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
															<?php													
															}														
															?>														
														</select>
													</div>
												</div>
											</div>

											<div class="col-sm-4" style="<?php echo $dealerNone; ?>">
												<div class="form-group">
													<div class="form-line">
														<select class="form-control show-tick" name="user_id" id="user_id" data-live-search="true" data-size="5" >
															<option value="">-- Select User --</option>
														</select>
													</div>
												</div>
											</div>

											<div class="col-sm-4" style="<?php echo $dealerNone; ?>">
												<div class="form-group">
													<div class="form-line">
														<select class="form-control show-tick" name="company_id" id="company_id" data-live-search="true" data-size="5">
															<option value="">-- Select Company --</option>
															<?php
															foreach($company_list as $key=>$value)
															{														
															?>
															<option value="<?php echo $value['c_company_id']; ?>"><?php echo $value['c_company_name']; ?></option>
															<?php													
															}														
															?>														
														</select>
													</div>
												</div>
											</div>
											<div class="col-sm-4"  style="display: none;">
												<div class="form-group">
													<div class="form-line">
														<select class="form-control show-tick" name="make_no" id="make_no" data-live-search="true" data-size="5" onchange="return select_model_list(event,this);">
															<option value="">-- Select Make --</option>
															<?php
															foreach($make_list as $key=>$value)
															{		
															?>
															<option  value="<?php echo $value['v_make_id']; ?>"><?php echo $value['v_make_name']; ?></option>
															<?php													
															}														
															?>														
														</select>
													</div>
												</div>
											</div>
											<div class="col-sm-4"  style="display: none;">
												<div class="form-group">
													<div class="form-line">
														<select class="form-control show-tick" name="model_id" id="model_id" data-live-search="true" data-size="5">
															<option value="">-- Select Model --</option>
																												
														</select>
													</div>
												</div>
											</div>

											
											<div class="col-sm-4"  style="display: none;">
												<div class="form-group">
													<div class="form-line">
														<select class="form-control show-tick" name="rto_no" id="rto_no" data-live-search="true" data-size="5">
															<option value="">-- Select RTO --</option>
															<?php
															foreach($rto_list as $key=>$value)
															{		
															?>
															<option  value="<?php echo $value['rto_no']; ?>"><?php echo $value['rto_number'].'-'.$value['rto_place']; ?></option>
															<?php													
															}														
															?>														
														</select>
													</div>
												</div>
											</div>

<?php
											if((string)$user_type==='1' || (string)$user_type==='2' )
											{
											?>
											<input type="hidden" name="user_type" value="<?php echo $user_type; ?>">
											<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
											<?php
											}
											?>
											 <div class="col-sm-4">
												<div class="form-group">
													<div class="form-line">
														<?php
														$dateValue=date('Y-m-d');
														?>
														<input type="text" class="datetimepicker form-control" value="<?php echo $dateValue; ?>" name="start_date"  id="start_date" placeholder="Please choose Start Date.." >
													</div>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="form-group">
													<div class="form-line">
														
														<input type="text" class="datetimepicker form-control" value="<?php echo $dateValue; ?>" name="end_date"  id="end_date" placeholder="Please choose End Date.." >
													</div>
												</div>
											</div>
											
											<div class="col-sm-4">
												<div class="form-group">
													<div class="form-line">
														<button class="btn btn-primary waves-effect" type="submit" id="searchfiltersubmit" name="searchfiltersubmit">Search</button>													
														<button id="print" style="display:none;" onClick="return printReport()" class="btn btn-primary waves-effect" type="button" >Print</button>			
													</div>
												</div>
											</div>
										</div>
							</form>
							</div>
							<iframe  id="iframe" scrolling="yes" style="display:none;overflow:scroll;width: 100%;
    height: 100%;"   src=""></iframe>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    </section>
	
	<script type="text/javascript">
		var salesReport=1;
	</script>
	 <script src="<?php echo base_url() ?>public/js/pages/function/inventoryreport.js"></script>
	<?php $this->load->view('common/admin_login_css_js'); ?> 		
	
	
</body>
</html>