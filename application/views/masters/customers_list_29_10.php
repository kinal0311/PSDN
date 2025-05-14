 <?php  
 
//  echo "no of data per page:".LIST_PAGE_LIMIT."<br>"; //exit;
//   echo "total data:".$totalNoOfCustomers;
//  echo "<pre>"; print_r($listofCustomers); exit;
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
                    Customers List
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               List Of Customers
                            </h2>   
                            <!--<ul class="header-dropdown m-r--5">-->
                            <!--    <li class="dropdown">-->
                            <!--        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">-->
                            <!--            <i class="material-icons">more_vert</i>-->
                            <!--        </a>-->
                            <!--        <ul class="dropdown-menu pull-right">-->
                            <!--            <li><a href="<?php echo base_url().'admin/create_rto'; ?>">Create</a></li>                                        -->
                            <!--        </ul> -->
                            <!--    </li>-->
                            <!--</ul>                         -->
                        </div>
                        <div class="body">
						
						
                             <div class="table-responsive">
					<!--- Search---->
								<form action="<?php echo base_url() ?>admin/customers_list" name="searchfilter"  id="searchfilter" method="get" />				
										<input type="hidden" name="offset"   id="offset" value="<?php echo isset($_GET['offset'])?$_GET['offset']:0; ?>">				
										<div class="row clearfix">
											<div class="col-sm-4">
												<div class="form-group">
													<div class="form-line">
														<input type="text" class="form-control" placeholder="Search by name, phone." id="search" value="<?php echo isset($_GET['search'])?$_GET['search']:""; ?>" name="search">
													</div>
												</div>
											</div>									
											
											<div class="col-sm-4">
												<div class="form-group">
													<div class="form-line">
														<button class="btn btn-primary waves-effect" type="submit" id="searchfiltersubmit" name="searchfiltersubmit">Search</button>
													</div>
												</div>
											</div>
											
										</div>
							</form>

			<!---- Search ---->
					
					
				<div style="display: none;" id="activeBtns">
					<button class="btn btn-primary waves-effect" type="button" onclick="activeInactiveCustomers('ACTIVE')" id="activSubmit" name="activSubmit">Active</button>
					<div>&nbsp;</div>
				</div>
				<div style="display: none;" id="InactiveBtns">
					<button class="btn btn-primary waves-effect" type="button" onclick="activeInactiveCustomers('INACTIVE')" id="inActivSubmit" name="inActivSubmit">Inactive</button>
					<div>&nbsp;</div>
				</div>	
              <table id="mytable" class="table table-bordred table-striped" style="width: 100% !important;">
                   
                   <thead>
					   <th>#</th>
					   <th><input type="checkbox" class="chk-all" id="chkall" value="all" > <label for="chkall"></label></th>
					   <?php if($usertype!=1 ) { ?>
					  <th>Dealer Name</th>
					  <?php } ?>
					  <?php if( $usertype==0 || $usertype==4 ) { ?>
						<th>Distributor Name</th>
					  <?php } ?>
					  <th>Customer Name</th>
					  <th>Phone</th>  
					   <th>Email</th>  
					   <th>Address</th>	  
					   <?php if( $usertype==0 || $usertype==4 ) { ?>
					   <th>
                            <center>Action</center>
                       </th>        
					  <?php } ?>
					   <th>Status</th>	                					  
                   </thead>
				<tbody>  
						<?php
				if(count($listofCustomers)>0)
				{
						
						$sno=1;
						if(isset($_GET['offset']) && (int)$_GET['offset']>0)
						{
							$sno=(((int)$_GET['offset']-1)*LIST_PAGE_LIMIT)+1;
						}
						foreach($listofCustomers as $key=>$value)
						{	

										
						?>
						<tr>
						<td><?php echo $sno; ?> </td>
						<?php //if(check_permission($user_type,'assign_to_distributer_assign')){ ?>
						<td><input type="checkbox" class="chk-ind" id="chk<?php echo $sno; ?>" name="serial_ids[]"  value="<?php echo $value['c_customer_id']; ?>" data-cus_status="<?php echo $value['c_status']; ?>" > <label for="chk<?php echo $sno; ?>"></label></td>
						<?php //} ?>
						<?php if($usertype==0 || $usertype==4) { 
							if($value['distributor_name'] =='') {?><td><?php echo "Admin";?></td><?php } else {?>
						<td><?php echo $value['dealer_name']; // admin dealer name  ?></td>
						<?php 
						  } 
				     	}
						else 
						{
						 if($usertype!=1)
						 { ?> 
						   <td><?php echo $value['user_name']; // distributor dealer name  ?></td>
						<?php  
						 } 
						} ?>
						<?php if( $usertype==0 || $usertype==4 ) {  

							if($value['distributor_name'] =='') {?><td><?php echo "Admin";?></td><?php } else {?>
						<td><?php echo $value['distributor_name']; // admin distributor name ?></td>
							
						<?php  } }?>
						<td><?php echo $value['c_customer_name']; ?></td>
						<td><?php echo $value['c_phone']; ?></td>
						<td><?php echo $value['c_email']; ?></td>
						<td><?php echo $value['c_address']; ?></td>
						<?php if ($user_type == 0 || $user_type == 4 ) { ?>
						<!--<td class="text-left">-->


      <!--                                          <a class='btn btn-Basic btn-xs'-->
      <!--                                              href="<?php //echo base_url() . "admin/edit_entry/?q=" . $enc_veh_Id; ?>">-->
      <!--                                              <span class="glyphicon glyphicon-edit" title="Edit"></span>-->
      <!--                                          </a>-->

      <!--                                      </td>-->
						<td class="text-left">
                            <a class='btn btn-Basic btn-xs' href="<?php echo base_url() . "admin/edit_customer/?id=" . $value['c_customer_id']; ?>">
                                <span class="glyphicon glyphicon-edit" title="Edit"></span>
                            </a>
						</td>
						<?php } ?>
						
						<td><span class="label <?php if($value['c_status'] == "ACTIVE") { echo "label-success"; } else { echo "label-danger"; } ?> text-capitalize" style="padding: .2em .5em;"><?php echo strtolower($value['c_status']); ?></span></td>
						
						<!-- <td class="text-left">
							 <a class='btn btn-info btn-xs' href="<?php echo base_url()."admin/edit_rto_number/".$value['rto_no']; ?>">
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
			$('#pageformat').pagination({
				items: '<?php echo $totalNoOfCustomers; ?>',
				itemsOnPage: '<?php echo LIST_PAGE_LIMIT; ?>',
				cssStyle: 'light-theme',
				onPageClick:function(no){
					var offsetValue=$('#offset').val();
					if(offsetValue==no)
					{
						
					}
					else
					{
						$('#offset').val(no);							
						$('#searchfiltersubmit').trigger('click');
					}
				}
			});
			<?php
                if (isset($_GET['offset']) && (int)$_GET['offset'] > 0) {
                ?>
    			$('#pageformat').pagination('selectPage', '<?php echo $_GET['offset']; ?>');
    			if ('<?php echo intval($totalNoOfCustomers); ?>' < 25) {
    				$('#pageformat').pagination('selectPage', '1');
    			} else {
    				// $('#offset').val(no);
    				$('#pageformat').pagination('selectPage', '<?php echo $_GET['offset']; ?>');
    			}
    		<?php
    		}
    		?>
			
			$(".chk-ind").on('click', function(){
				var count = 0; var count1 = 0;
				$(".chk-ind[data-cus_status='INACTIVE']").each(function(){
					if($(this).is(":checked")) {
						count1++;
					}
				});
				$(".chk-ind[data-cus_status='ACTIVE']").each(function(){
					if($(this).is(":checked")) {
						count++;
					}
				});
				if((count1 > 0) && (count == 0)) {
					$("#activeBtns").show();
					$("#InactiveBtns").hide();
				} else if((count > 0) && (count1 == 0)) {
					$("#InactiveBtns").show();
					$("#activeBtns").hide();
				} else if((count > 0) && (count1 > 0)){
					$("#activeBtns").hide();
					$("#InactiveBtns").hide();
				} else {
					$("#InactiveBtns").hide();
					$("#activeBtns").hide();
				}
			});	
			$("#chkall").on('click', function(){
				$("#activeBtns").hide();
				$("#InactiveBtns").hide();
				var checked=$(this).is(':checked');
				$(".chk-ind").prop('checked', checked);
			});
			
		});
		
		
		function activeInactiveCustomers(from) {
			var myVal = [];
			myVal.push(from);
			$(".chk-ind").each(function(){
				if($(this).is(":checked")){
					myVal.push($(this).val());
				}
			});
			$.post(SITEURL + "admin/update_customer_status", { valJson : myVal }, function (data) {
				$('[type=submit]').removeAttr('disabled');
				data = data.replace(/^\s+|\s+$/g, "");
				data = JSON.parse(data);
				if (data.error) {
					showWithTitleMessage(data.error, '');
				}
				//Success Response
				if (data.success) {
					//msg.success
					swal({
						title: "<bold>"+ data.success.toLowerCase() +"</bold>",
						text: '',
						type: "success",
						html: true
					}, function (isConfirm) {
						
					});
					setTimeout(function(){ window.location.href = SITEURL + "admin/customers_list"; }, 1000);
				}

			});
// 			$.ajax({
// 				type: "POST",
// 				url: SITEURL + "admin/update_customer_status",
// 				data: {
// 					valJson : myVal
// 				},
// 				dataType: 'json',
// 				//use contentType, processData for sure.
// 				// contentType: false,
// 				// processData: false,
// 				// beforeSend: function() {
// 				// },
// 				success: function(msg) {		
// 					console.log("msg",msg);	
// 					if(msg.fail) {						
						
// 					} else { 
// 						//msg.success
// 						swal({
// 							title: "<bold>"+ msg.success.toLowerCase() +"</bold>",
// 							text: '',
// 							type: "success",
// 							html: true
// 						}, function (isConfirm) {
							
// 						});
// 					}
// 					setTimeout(function(){ window.location.href = SITEURL + "admin/customers_list"; }, 1000);
					
// 				},
// 				error: function(jqXHR, textStatus, errorThrown) {
//     // Handle errors
//     console.log("Error: " + textStatus + ", " + errorThrown);
//   }
// 			});
			//return true; */
		}
	</script>
</body>
</html>