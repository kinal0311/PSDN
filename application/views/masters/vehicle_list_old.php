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
                    Vehicle List
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
					?>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               LIST OF GPS CERTIFICATES
                            </h2>   
                             <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="<?php echo base_url().'admin/create_new_entry'; ?>">Create</a></li>                                        
                                    </ul>
                                </li>
                            </ul>                         
                        </div>
                        <div class="body">
						
						
                             <div class="table-responsive">
					<!--- Search---->
								<form action="<?php echo base_url().$redirectionBase; ?>/entry_list" name="searchfilter"  id="searchfilter" method="get" />				
										<input type="hidden" name="offset"   id="offset" value="<?php echo isset($_GET['offset'])?$_GET['offset']:0; ?>">				
										<div class="row clearfix">
											<div class="col-sm-3">
												<div class="form-group">
													<div class="form-line">
														<input type="text" class="form-control" placeholder="Search by ser.no,veh.no,Inv.no,Chassis no,Rcno" id="search" value="<?php echo isset($_GET['search'])?$_GET['search']:""; ?>" name="search">
													</div>
												</div>
											</div>
											 <div class="col-sm-3">
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
											<div class="col-sm-3">
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
					   <th>Serial No</th>
					   <!--th>Invoice No</th-->
					   <th>Rc No</th>                     
					   <th>Customer Name</th>	
					   <th>Phone No</th>	
<?php if(check_permission($user_type,'cerificate_edit')){ ?>
					   <th>Action</th>                      				
<?php } ?>					   	  
                   </thead>
					<tbody>  
						<?php
				if(count($listofvehicles)>0)
				{
						
						$sno=1;
						if(isset($_GET['offset']) && (int)$_GET['offset']>0)
						{
							$sno=(((int)$_GET['offset']-1)*LIST_PAGE_LIMIT)+1;
						}
						foreach($listofvehicles as $key=>$value)
						{							
							
						?>
						<tr>
						<td><?php echo $sno; ?></td>
						<td><?php echo $value['s_serial_number']; ?></td>
						<!--td><?php echo $value['veh_invoice_no']; ?></td-->
						<td><?php echo $value['veh_rc_no']; ?></td>
						<td><?php echo $value['veh_owner_name']; ?></td>
						<td><?php echo $value['veh_owner_phone']; ?></td>
					
						<?php
							$pdfEncode=base64_encode(base64_encode(base64_encode($value['veh_id'])));
							$href=base_url()."admin/downloadwebpdf?id=".$pdfEncode;
							$user_type=$this->session->userdata('user_type');
						?>
						<td class="text-left">
<?php if(check_permission($user_type,'cerificate_edit')){ ?>
							 <a class='btn btn-info btn-xs' href="<?php echo base_url().$redirectionBase."/edit_entry/".$value['veh_id']; ?>">
								<span class="glyphicon glyphicon-edit"></span> Edit
							 </a>							
<?php } ?>
<?php if(check_permission($user_type,'cerificate_download')){ ?>
							<a href="javascript:void(0)" class="btn btn-danger btn-xs" onClick="return ShowPdfGenerateMail('<?php echo $href; ?>','<?php echo $value["veh_id"]; ?>')">
								<span class="glyphicon glyphicon-download-alt"></span></a>
<?php } ?>
<?php if(check_permission($user_type,'cerificate_remove')){ ?>
							 <a class='btn btn-danger btn-xs' href="javascript:void(0)" onClick="showConfirmmesage(<?php echo $value['veh_id']; ?>)">
								<span class="glyphicon glyphicon-trash"></span>
							 </a>
<?php } ?>							 	
						
						</td></tr>
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
	
	
	 <script src="<?php echo base_url() ?>public/js/pages/function/dealer_list.js"></script>
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