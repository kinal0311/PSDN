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
            <div class="block-header" style="display:none;">
                <h2>
                    Invoices List
                </h2>				
					
					<?php
					$user_type=$this->session->userdata('user_type');
					$redirectionBase='admin';
					if((string)$user_type==='1')
					{
						$redirectionBase='dealer';
					}
					$hideHomeFromDealer='';
					if((string)$user_type==='1')
					{
						$hideHomeFromDealer='display:none;';
					}
					$dealerNone="";
					if((string)$user_type==='1' || (string)$user_type==='2' )
					{
						$dealerNone="display:none;";
					}
					?>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               LIST OF Invoices
                            </h2>   
                             <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <!-- <ul class="dropdown-menu pull-right">
                                        <li><a href="<?php echo base_url().'admin/create_new_entry'; ?>">Create</a></li>                                        
                                    </ul> -->
                                </li>
                            </ul>                         
                        </div>
                        <div class="body">
						
						
                             <div class="table-responsive">
					<!--- Search---->
								<form action="<?php echo base_url().$redirectionBase; ?>/invoices_list" name="searchfilter"  id="searchfilter" method="get" />				
										<input type="hidden" name="offset"   id="offset" value="<?php echo isset($_GET['offset'])?$_GET['offset']:0; ?>">				
										<div class="row clearfix">

											<div class="col-sm-2" style="<?php echo $dealerNone; ?>">
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
																	$selected='selected';
																}
															?>
															<option <?php echo $selected; ?> value="-1"> ALL </option>
															<?php
															$selectArray=unserialize(USERS_TYPE_LIST);
															foreach($selectArray as $key=>$value)
															{
																$selected="";
																if(isset($_GET['user_type']) && $_GET['user_type'] == $key)
																{
																	$selected='selected';
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

											<div class="col-sm-2" style="<?php echo $dealerNone; ?>">
												<div class="form-group">
													<div class="form-line">
														<select class="form-control show-tick" name="user_id" id="user_id" data-live-search="true" data-size="5" >
															<option value="">-- Select User --</option>
														</select>
													</div>
												</div>
											</div>

											<div class="col-sm-2">
												<div class="form-group">
													<div class="form-line">
														<input type="text" class="form-control" placeholder="Search by Invoice Number" id="search" value="<?php echo isset($_GET['search'])?$_GET['search']:""; ?>" name="search">
													</div>
												</div>
											</div>
											 <div class="col-sm-2">
												<div class="form-group">
													<div class="form-line">
														<?php
														$dateValue="";
														if(isset($_GET['start_date'])?$_GET['start_date']:'')
														{
															$dateValue=$_GET['start_date'];
														}
														?>
														<input type="text" class="datetimepicker form-control" value="<?php echo $dateValue; ?>" name="start_date"  id="start_date" placeholder="Please choose Start Date.." >
													</div>
												</div>
											</div>
											<div class="col-sm-2">
												<div class="form-group">
													<div class="form-line">
														<?php
														$dateValue="";
														if(isset($_GET['end_date'])?$_GET['end_date']:'')
														{
															$dateValue=$_GET['end_date'];
														}
														?>
														<input type="text" class="datetimepicker form-control" value="<?php echo $dateValue; ?>" name="end_date"  id="end_date" placeholder="Please choose End Date.." >
													</div>
												</div>
											</div>
											
											<div class="col-sm-1">
												<div class="form-group">
													<div class="form-line">
														<button class="btn btn-primary waves-effect" type="submit" id="searchfiltersubmit" name="searchfiltersubmit">Search</button>														
													</div>
												</div>
											</div>
											
										</div>
							</form>

			<!---- Search ---->
					
					
					
              <table id="mytable" class="table table-bordred table-striped">
                   
                   <thead>
					   <th>#</th>
					   <th>Inv Num</th>                     
					   <th>User</th>
					   <th>User Type</th>
					   <th>Customer</th>
					   <th>Created On</th>					   
					   <th>Due On</th>	
<?php if(check_permission($user_type,'invoice_download')){ ?>
					   <th>Action</th>                      				
<?php } ?>					   	  
                   </thead>
					<tbody>  
						<?php
				if(count($listofInvoices)>0)
				{
						
						$sno=1;
						if(isset($_GET['offset']) && (int)$_GET['offset']>0)
						{
							$sno=(((int)$_GET['offset']-1)*LIST_PAGE_LIMIT)+1;
						}
						foreach($listofInvoices as $key=>$value)
						{							

							if((string)$value['i_user_type']==='1')
							{
								$value['i_user_type']='Dealer';
							}
							if((string)$value['i_user_type']==='2')
							{
								$value['i_user_type']='Distributor';
							}
							if((string)$value['i_user_type']==='3')
							{
								$value['i_user_type']='Rto';
							}
							if((string)$value['i_user_type']==='0')
							{
								$value['i_user_type']='SUPER ADMIN';
							}
							// if((string)$value['user_status']==='1')
							// {
								$value['user_statusComments']='Active';
							// }else if((string)$value['user_status']==='0')
							// {
								// $value['user_statusComments']='Inactive';
							// }
						?>
						<tr>
						<td><?php echo $sno; ?></td>
						<td><?php echo $value['invoice_number']; ?></td>
						<td><?php echo $value['user_name2']; ?></td>
						<td><?php echo $value['i_user_type']; ?></td>
						<td><?php echo $value['user_name3']; ?></td>
						<td><?php echo date('d-M-Y h:i A', strtotime($value['i_created_date'])); ?></td>
						<td><?php echo date('d-M-Y', strtotime($value['i_created_date'])  + (INVOICE_DUE_PERIOD * 86400)); ?></td>
					
						<?php
							$pdfEncode=base64_encode(base64_encode(base64_encode($value['i_invoice_id'])));
							$href=base_url()."admin/downloadinvoice_customers?id=".$pdfEncode;
							$user_type=$this->session->userdata('user_type');
						?>
  <?php if(check_permission($user_type,'invoice_download')){ ?>						
						<td class="text-left">

							<a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="return ShowPdfGenerateMail('<?php echo $href; ?>','210')">
								<span class="glyphicon glyphicon-download-alt"></span></a>
							
						</td>
 <?php } ?>						
					</tr>
						<?php	
						$sno++;
						}
				}else{
				?>
				<tr style=" text-align: center;">
					<td colspan="6">No Records Found</td>						
				</tr>
				<?php	
				}
				?>
						</tbody>
        
					</table>
					
						<div style="float:right;" id="pageformat"></div>
						
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Validation -->
        
        </div>
    </section>
	
	
	 <script src="<?php echo base_url() ?>public/js/pages/function/dealersalesreport.js"></script>
	<?php $this->load->view('common/admin_login_css_js'); ?> 		
	<script>
	function ShowPdfGenerateMail(url,veh_id) {
	    swal({
	        title: "Download Pdf",
	        text: "Click here.<a target='_blank' href='"+url+"'>Download it</a><br />",
	    //    type: "input",
	         html: true,
	      //  showCancelButton: true,
	      //  closeOnConfirm: false,
	        animation: "slide-from-top",
	        inputPlaceholder: "Enter the email address to get pdf."
	    }, function (inputValue) {
	       
	       	return true;
	    });
	}
	</script>
	<script>	
		$(function() {
				$('#pageformat').pagination({
					items: '<?php echo $totalNoOfVehicles; ?>',
					itemsOnPage: '<?php echo LIST_PAGE_LIMIT; ?>',
					cssStyle: 'light-theme',
					onPageClick:function(no){
						var offsetValue=$('#offset').val();
						if(offsetValue==no)
						{
							
						}else{
							$('#offset').val(no);							
							$('#searchfiltersubmit').trigger('click');
						}
					}
				});
				<?php
				if(isset($_GET['offset']) && (int)$_GET['offset']>0)
				{
				?>
				$('#pageformat').pagination('selectPage', '<?php echo $_GET['offset']; ?>');
				<?php
				}
				?>
		});
	</script>
</body>
</html>