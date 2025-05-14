<?php  
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
                    Unregistered Device Data
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               List of Unregistered Device Data
                            </h2>   
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <!-- <ul class="dropdown-menu pull-right">
                                        <li><a href="<?php echo base_url().'admin/create_rto'; ?>">Create</a></li>                                        
                                    </ul> -->
                                </li>
                            </ul>                         
                        </div>
                        <div class="body">
						
						
                             
					<!--- Search---->
								<form action="<?php echo base_url() ?>device/unregistered_data" name="searchfilter"  id="searchfilter" method="get" />				
										<input type="hidden" name="offset"   id="offset" value="<?php echo isset($_GET['offset'])?$_GET['offset']:0; ?>">				
										<div class="row clearfix">
											<div class="col-sm-4">
												<div class="form-group">
													<div class="form-line">
														<input type="text" class="form-control" placeholder="Search by IMEI" id="search" value="<?php echo isset($_GET['search'])?$_GET['search']:""; ?>" name="search" required>
													</div>
												</div>
											</div>									
										
											<div class="col-sm-3">
												<div class="form-group form-float">
													<div class="form-line">
														<select class="form-control show-tick" name="s_state_id"
															id="s_state_id" data-live-search="true" required>
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
											<div class="col-sm-4">
												<div class="form-group">
													<div class="">
														<button class="btn btn-primary waves-effect" type="submit" id="searchfiltersubmit" name="searchfiltersubmit">Search</button>
													</div>
												</div>
											</div>
											
										</div>
							</form>

			<!---- Search ---->
            <div class="table-responsive">
              <table id="mytable" class="table table-bordred table-striped" style="table-layout: fixed; width: 100% !important;">
                   
                   <thead>
						<th style="width:5%">#</th>
						<th>IMEI</th>
						<th style="width:50%">Data</th>
						<th>Server Reached</th>
                   </thead>
				<tbody>  
					
				<?php
						// print_r($listOfUnregisteredDatas);exit;

				 if(count($listOfUnregisteredDatas)>0)
					{
						// print_r($listOfUnregisteredDatas);exit;
						$sno=1;
						if(isset($_GET['offset']) && (int)$_GET['offset']>0)
						{
							$sno=(((int)$_GET['offset']-1)*LIST_PAGE_LIMIT)+1;
						}
						foreach($listOfUnregisteredDatas as $key => $value)
						{ ?>
							<tr>
								<td><?php echo $sno; ?> </td>
								<td><?php echo $value['imei']; ?></td>
								<td style="word-wrap: break-word"><?php echo $value['data']; ?></td>
								<td><?php echo date('Y-m-d H:i:s', strtotime($value['created_time'])); ?></td>
							</tr>
							<?php $sno++;
						} 
					}else{ ?>
						<tr style=" text-align: center;">
							<td colspan="4">No Records Found</td>
						</tr>
					<?php } ?>
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
				items: '<?php echo $totalNoOfUnregisteredDatas; ?>',
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
			if(isset($_GET['offset']) && (int)$_GET['offset']>0)
			{
			?>
			$('#pageformat').pagination('selectPage', '<?php echo $_GET['offset']; ?>');
			<?php
			}
			?>
		});
	</script>
	
	<script>
		
        $(document).ready(function() {
            // Check if a search parameter is present in the URL
            var searchParam = getParameterByName('search');
            
            // If search parameter is not present, hide the table
            if (!searchParam) {
                $('.table-responsive').hide();
            }
    
            // Function to get URL parameters by name
            function getParameterByName(name) {
                name = name.replace(/[\[\]]/g, "\\$&");
                var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                    results = regex.exec(window.location.href);
                if (!results) return null;
                if (!results[2]) return '';
                return decodeURIComponent(results[2].replace(/\+/g, " "));
            }
        });
    </script>
	<script>
		$(document).ready(function () {
			$('[name=s_country_id]').on('change', function () {
				var value = $(this).val();
				if (value === '') {
					return true;
				}
				// console.log("data", value);
				$.post(SITEURL + "admin/getLaunchStateByCountryById", { 'id': value }, function (data) {
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