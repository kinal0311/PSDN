 <?php 
//echo $value['veh_id'];
//echo "<pre>"; print_r($listofvehicles); exit;
//echo "<pre>"; print_r($listofvehicles[0]['veh_id']); exit;
//echo hi(); exit;
//$enc_veh_Id = urlencode($this->encrypt->encode($listofvehicles[0]['veh_id']));

//echo "id".$enc_veh_Id; exit;
 
 $this->load->view('common/admin_login_header'); ?>
 <?php
 $user_type=$this->session->userdata('user_type');



 ?>
 <!-- Bootstrap Material Datetime Picker Css -->
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

 <!-- Wait Me Css -->
 <link href="<?php echo base_url() ?>public/plugins/waitme/waitMe.css" rel="stylesheet" />

 <!-- Bootstrap Select Css -->
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<style>
#success {
    background: green;
}

#error {
    background: red;
}
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
											<div class="col-sm-4">
												<div class="form-group">
													<div class="form-line">
                                                        <input type="text" class="form-control" placeholder="Search By Ser no, IMEI, ICCID, Veh No, Phone" id="search" value="<?php echo isset($_GET['search'])?$_GET['search']:""; ?>" name="search">
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
													<div>
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
					   <th style="width: 242px;">Customer Name</th>	
					   <th>Phone No</th>
<?php if(check_permission($user_type,'cerificate_edit')){ ?>
					   <th><center>Action</center></th>				
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
						<!--<td><?php echo $value['s_serial_number']; ?></td>-->
						<td><?php
								$href=base_url()."admin/device_qrcode?serialNumber=".base64_encode($value['s_serial_number']);
								echo '<a href="'.$href.'">'.$value['s_serial_number'].'</a>'; 
							?>
						</td>
						<!--td><?php echo $value['veh_invoice_no']; ?></td-->
						<!--<td><?php //echo $value['veh_rc_no']; ?></td>-->
						<td><?php 
						if($value['veh_rc_no']==""){
							echo "NEW REGISTRATION";
						}else{
							echo $value['veh_rc_no'];
						}?></td>
						<td><?php echo $value['veh_owner_name']; ?></td>
						<td><?php echo $value['veh_owner_phone']; ?></td>
					    <?php
									$channel=isset($value['veh_channel'])?$value['veh_channel']:0; 
									$M="";
									if((string)$channel==1)
									{
										$M=base_url()."public/images/android.png";
									}else{
                                        $M=base_url()."public/images/laptop.png";
									}
                                    
									?>
                                          
						<?php
							$pdfEncode=base64_encode(base64_encode(base64_encode($value['veh_id'])));
							$href=base_url()."admin/downloadwebpdf?id=".$pdfEncode;
							$user_type=$this->session->userdata('user_type');

							$enc_veh_Id = base64_encode($value['veh_id']);

						?>
						<td class="text-left">
					
<?php if(check_permission($user_type,'cerificate_edit')){ ?>
                           <img src=" <?php echo $M ?>" alt="image" width="24px" />
                                             
						<a class='btn btn-info btn-xs' href="<?php echo base_url()."admin/edit_entry/?q=".$enc_veh_Id; ?>">
						 <span class="glyphicon glyphicon-edit" title="Edit"></span>
						</a>
													
<?php } ?>
<?php if(check_permission($user_type,'cerificate_download')){ ?>
							<a href="javascript:void(0)" class="btn btn-success btn-xs" onClick="return ShowPdfGenerateMail('<?php echo $href; ?>','<?php echo $value["veh_id"]; ?>')">
								<span class="glyphicon glyphicon-download-alt"  title="Download"></span></a>
<?php } ?>
                        <?php if(check_permission($user_type,'cerificate_interchange')){ ?>
                         <a class="btn btn-primary btn-xs" href="<?php echo base_url()."admin/inter_change_device/?q=".$enc_veh_Id; ?>">
                             <span class="glyphicon glyphicon-refresh" title="Inter Change Device"></span></a>
                         <a class="btn btn-warning btn-xs" href="<?php echo base_url()."apicontroller/owner_inter_change/?q=".$enc_veh_Id; ?>">
                            <span class="glyphicon glyphicon-transfer" title="Ownership Interchange"></span></a>
                         <a class='btn btn-danger btn-xs' onClick="showConfirmmesage(<?php echo $value['veh_id']+","+$user_type ?>)">
                            <span class="glyphicon glyphicon-trash" title="Delete Certificate"></span></a>
                        
                         <?php } ?>
<!--<?php //if(check_permission($user_type,'cerificate_remove')){ ?>-->
<!--							 <a class='btn btn-danger btn-xs' href="javascript:void(0)" onClick="showConfirmmesage(<?php echo $value['veh_id']; ?>)">-->
<!--								<span class="glyphicon glyphicon-trash"></span>-->
<!--							 </a>-->
<!--<?php //} ?>	-->
						
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