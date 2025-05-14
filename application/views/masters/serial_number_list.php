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
<?php
$user_type=$this->session->userdata('user_type');
?>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header" style="display:none;">
                <h2>
                    Dealer List
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Device List - Assign to Dealer
                            </h2>    
                            <!--<ul class="header-dropdown m-r--5">-->
                            <!--    <li class="dropdown">-->
                            <!--        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">-->
                            <!--            <i class="material-icons">more_vert</i>-->
                            <!--        </a>-->
                            <!--        <ul class="dropdown-menu pull-right">-->
                            <!--            <li><a href="<?php echo base_url().'admin/assign_serial_number'; ?>">Create</a></li>                                        -->
                            <!--        </ul>-->
                            <!--    </li>-->
                            <!--</ul>                       -->
                        </div>
                        <div class="body">
						
						
                             <div class="table-responsive">
					<!--- Search---->
								<form action="<?php echo base_url() ?>admin/serial_number_list" name="searchfilter"  id="searchfilter" method="get" />				
										<input type="hidden" name="offset"   id="offset" value="<?php echo isset($_GET['offset'])?$_GET['offset']:0; ?>">				
										<div class="row clearfix">
											<div class="col-sm-4">
												<div class="form-group">
													<div class="form-line">
														<input type="text" class="form-control" placeholder="Search By Serial Number, IMEI" id="search" value="<?php echo isset($_GET['search'])?$_GET['search']:""; ?>" name="search">
													</div>
												</div>
											</div>									
											<!-- <div class="col-sm-2">
												<div class="form-group form-float">
													<div class="form-line">
														<select class="form-control show-tick" name="company_id" id="company_id" data-live-search="true">
															<option value="">--Select Company Name--</option>
															<?php
															foreach ($company_list as $key => $value) {
																$selected="";
																if(isset($_GET['company_id']) && (string)$_GET['company_id']===(string)$value['c_company_id'])
																{
																	$selected="selected='selected'";
																}
															?>
															<option  <?php echo $selected; ?> value="<?php echo $value['c_company_id'] ?>"><?php echo $value['c_company_name'] ?></option>
															<?php
															}
															?>							
														</select>
													 </div>
												</div>
											</div> -->

                                    <!-- <div class="col-sm-3">
												<div class="form-group form-float">
													<div class="form-line">
														<select class="form-control show-tick" name="s_product_id"  id="s_product_id" data-live-search="true">
				                                            <option value="">--Select Product Name--</option>
				                                            <?php
				                                            foreach ($product_list as $key => $value) {
																$selected="";
				                                            	if(isset($_GET['s_product_id']) && (string)$_GET['s_product_id']===(string)$value['p_product_id'])
				                                                {
				                                                    $selected="selected='selected'";
				                                                }
				                                            ?>
				                                            <option <?php echo $selected; ?> value="<?php echo $value['p_product_id'] ?>"><?php echo $value['p_product_name'] ?></option>
				                                            <?php
				                                            }
				                                            ?>
														</select>
													 </div>
												</div>
											</div> -->

											<!--<div class="col-sm-2">
												<div class="form-group form-float">
													<div class="form-line">
														<select class="form-control show-tick" name="used_status" id="used_status" data-live-search="true">
															<option value="">-- Used Status --</option>
															<?php
/*															$used='';
															$unused='';
															if(isset($_GET['used_status']) && (string)$_GET['used_status']==='1')
															{
																$used='selected="selected"';
															}
															if(isset($_GET['used_status']) && (string)$_GET['used_status']==='2')
															{
																$unused='selected="selected"';
															}
															*/?>
															<option <?php /*echo $used; */?> value="1">Used</option>
															<option <?php /*echo $unused; */?> value="0">Unused</option>
														</select>
													 </div>
												</div>
											</div>-->
											<!-- <div class="col-sm-2">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" name="s_country_id" id="s_country_id"
                                                        data-live-search="true">
														<option value="">--Select Country--</option>
                                                        <?php
                                                            foreach ($countryList as $key => $value) { 
															$selected = "";
                                                                if (isset($_GET['s_country_id']) && (string)$_GET['s_country_id'] === (string)$value['c_id']) {
                                                                    $selected = 'selected="selected"';
                                                                }  ?>
																<option <?php echo $selected; ?>
																	value="<?php echo $value['c_id']; ?>">
																	<?php echo $value['c_name']; ?></option>
																<?php
																}
                                                            ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div> -->
										<div class="col-sm-3">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <select class="form-control show-tick" name="s_state_id"
                                                        id="s_state_id" data-live-search="true">
                                                        <option value="">--Select State--</option>
                                                        <?php
                                                            foreach ($stateList as $key => $value) {
																$selected = "";
                                                                if (isset($_GET['s_state_id']) && (string)$_GET['s_state_id'] === (string)$value['id']) {
																	$selected = 'selected="selected"';
                                                                }
                                                            ?>
                                                        <option <?php echo $selected; ?>
                                                            value="<?php echo $value['id']; ?>">
                                                            <?php echo $value['s_name'];?></option>
                                                        </option>
                                                        <?php
                                                            }
                                                            ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
											<div class="col-sm-1">
												<div class="form-group">
													<div class="form-line">
														<select class="form-control show-tick" name="recs"  id="recs" data-live-search="false" >
															<?php
				                                            foreach ($num_recs as $key => $value) {
				                                            	$selected="";
				                                            	if(isset($_GET['recs']) && (string)$_GET['recs']===(string)$value)
				                                                {
				                                                    $selected="selected='selected'";
				                                                }
				                                            ?>
			                                                	<option <?php echo $selected; ?> value="<?php echo $value ?>"><?php echo $value ?></option>
			                                                <?php
				                                            }
				                                            ?>
														</select>
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
					
					
					<form action="<?php echo base_url() ?>admin/assign_serial_number" name="searchfilter"  id="searchfilter" method="post" />	
<?php if(check_permission($user_type,'assign_to_dealer_assign')){ ?>
						<div class="form-group">
							<div class="form-line" style="display: flex;">
								<button type="submit" id="assign-btn" class="btn btn-primary waves-effect" >Assign</button>
								<p style="padding-top: 5px; padding-left: 25%;"><b>Company : </b>PSDN Technology Pvt Ltd</p>
                                            
							</div>
						</div>
 <?php } ?>						
						<input type="hidden" id="hid_mode" name="hid_mode" value="assigned">
						<!--<input type="hidden" id="hid_company_id" name="hid_company_id" value="<?php echo $_GET['company_id']; ?>">-->
						<input type="hidden" id="hid_company_id" name="hid_company_id" value="2">
		
        <?php $product_id = preg_replace("/&#?[a-z0-9]+;/i","",$_REQUEST["s_product_id"]); ?>
        
        <!--<input type="hidden" id="hid_product_id" name="hid_product_id" value="<?php echo $product_id; ?>">-->
        <input type="hidden" id="hid_product_id" name="hid_product_id" value="1">
		              <table id="mytable" class="table table-bordred table-striped" >
		                   
		                   <thead>
							   <th>#</th>
<?php if(check_permission($user_type,'assign_to_dealer_assign')){ ?>
							   <th><input type="checkbox" class="chk-all" id="chkall" value="all" > <label for="chkall"></label></th>
 <?php } ?>								   
							   <th>Serial Number</th>			
							   <th>IMEI</th>					   				   
							   <th>Mobile</th>			   				   
							   <th>Distributor Name</th>	
							   <th>Dealer Name</th>	
							   <th>Company Name</th>						  
							   <th  style="display: none;">Used Status</th>					   				   
							  <!--  <th>Action</th>				   			 -->	   
							   
		                   </thead>
							<tbody>  
								<?php
						if(count($listofSerialNumbers)>0)
						{
								
								$sno=1;
								if(isset($_GET['offset']) && (int)$_GET['offset']>0)
								{
									$sno=(((int)$_GET['offset']-1)* $limit)+1;
								}
								foreach($listofSerialNumbers as $key=>$value)
								{							
									$userType='';
									if(isset($value['s_user_type']) && (string)$value['s_user_type']==='1')
									{
										$userType='Dealer';
									}else{
										$userType='Distributor';
									}
								?>
								<tr>
								<td><?php echo $sno; ?></td>
<?php if(check_permission($user_type,'assign_to_dealer_assign')){ ?>
								<td> <?php if( isset($value['dealer_name']) == ""){  ?>
  <input type="checkbox" class="chk-ind" id="chk<?php echo $sno; ?>" name="serial_ids[]" value="<?php echo $value['s_serial_id']; ?>" > <label for="chk<?php echo $sno; ?>"></label>
  									<?php } ?>

  </td>
   <?php } ?>
								<td><?php echo $value['s_serial_number']; ?></td>
								<td><?php echo $value['s_imei']; ?></td>
								<td><?php echo $value['s_mobile']; ?></td>

								<td><?php echo $value['distributor_name']; ?></td>
								<td><?php echo $value['dealer_name']; ?></td>
								<td><?php echo $value['c_company_name']; ?></td>
								 <td  style="display: none;"><?php echo ((string)$value['s_used']==='1')?'Used':'Unused'; ?></td>
								<!-- <td class="text-left">
									 <a class='btn btn-info btn-xs' href="<?php echo base_url()."admin/edit_serial_number/".$value['s_serial_id']; ?>">
										<span class="glyphicon glyphicon-edit"></span> Edit
									 </a>							
								</td> -->
								
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
						</form>
					
					<div style="float:right;" id="pageformat"></div>
                   </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Validation -->
        
        </div>
    </section>
	
	<!--- Model Dialog ---->
	
	
	<?php $this->load->view('common/admin_login_css_js'); ?> 			
	<script>	
		$(function() {
			if($('#company_id option').length===2 && ''+user_type!='0')
	{
		$('#company_id option:eq(1)').prop('selected','selected');
		setTimeout(function(){
		$('#company_id').trigger('change');
		},1000)
	}
			
				$('#pageformat').pagination({
					items: '<?php echo $totalNoOfSerialNumbers; ?>',
					itemsOnPage: '<?php echo $limit; ?>',
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
					if (isset($_GET['offset']) && (int)$_GET['offset'] > 0) {
					?>
				$('#pageformat').pagination('selectPage', '<?php echo $_GET['offset']; ?>');
				if ('<?php echo intval($totalNoOfSerialNumbers); ?>' < 25) {
					$('#pageformat').pagination('selectPage', '1');
				} else {
					// $('#offset').val(no);
					$('#pageformat').pagination('selectPage', '<?php echo $_GET['offset']; ?>');
				}
				<?php
				}
				?>
		});

		$('#company_id').on('change',function(){
			var value=$(this).val();

			if(value==='')
			{
				return true;
			}
			$.post(SITEURL+"admin/fetch_list_of_products",{'p_company_id':value},function(data){
				data=JSON.parse(data);
				if(data.list && data.list.length===0)
				{
					showWithTitleMessage('No Product Records Found.','');
				}
				var html='';
				if(data.list && data.list.length)
				{
					$.each(data.list,function(resKey,resValue){
						html+='<option value="'+resValue.p_product_id+'">'+resValue.p_product_name+'</option>';
					});
				}
				$('#s_product_id').html(html);		
				$('#s_product_id').selectpicker('refresh');

			});

		});

		$("#chkall,.chk-ind").on('click', function(){
			if($("#hid_product_id").val() == ''){
				showWithTitleMessage("Please filter by Company & Product and then select the serial numbers to assign",'');
				return false;
			}
		});	
		$("#chkall").on('click', function(){
			if($("#hid_product_id").val() == ''){
				return false;
			}
			var checked=$(this).is(':checked');
			$(".chk-ind").prop('checked', checked);
		});

		$("#assign-btn").on('click', function(){
			if($(".chk-ind:checked").length == 0){
				showWithTitleMessage("Please select atleast one serial number to assign",'');
				return false;
			}
		});

	</script>
	<script>
		$(document).ready(function(){
			$('[name=s_country_id]').on('change', function () {
		var value = $(this).val();
		if (value === '') {
			return true;
		}
		// console.log("data", value);
		$.post(SITEURL + "admin/getStateByCountryById", { 'id': value }, function (data) {
			data = data.replace(/^\s+|\s+$/g, "");
			data = JSON.parse(data);
			if (data.state_list && data.state_list.length === 0) {
				showWithTitleMessage('No Records Found', "Selected Country Doesn't have any State records.");
			}
			var html = '';
			html = '<option value="" selected="selected">--Select State--</option>';
			if (data.state_list && data.state_list.length) {
				$.each(data.state_list, function (resKey, resValue) {
					html += '<option value="' + resValue.id + '">' + resValue.s_name + '</option>';
				});
			}
			$('#s_state_id').html(html);
			$('#s_state_id').selectpicker('refresh');
		});
    	
    	});
		});
		</script>
</body>
</html>