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
                               List Of Vehicle Models
                            </h2>       
<?php if(check_permission($user_type,'menu_vehicle_model')){ ?>                            
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="<?php echo base_url().'admin/create_vehicle_model'; ?>">Create</a></li>                                        
                                    </ul>
                                </li>
                            </ul> 
<?php } ?>                                               
                        </div>
                        <div class="body">
						
						
                             <div class="table-responsive">
					<!--- Search---->
								<form action="<?php echo base_url() ?>admin/vehicle_model_list" name="searchfilter"  id="searchfilter" method="get" />				
										<input type="hidden" name="offset"   id="offset" value="<?php echo isset($_GET['offset'])?$_GET['offset']:0; ?>">				
										<div class="row clearfix">
											<div class="col-sm-4">
												<div class="form-group">
													<div class="form-line">
														<input type="text" class="form-control" placeholder="Search by model name." id="search" value="<?php echo isset($_GET['search'])?$_GET['search']:""; ?>" name="search">
													</div>
												</div>
											</div>									
											

											<div class="col-sm-4">
												<div class="form-group form-float">
													<div class="form-line">
														<select class="form-control show-tick" name="make_id" id="make_id" data-live-search="true">


															<option value="">-- Select Make --</option>
															<?php
															foreach ($make_list as $key => $value) {

																$selected='';
																if(isset($_GET['make_id']) &&									
																	(string)$_GET['make_id'] ===(string)$value['v_make_id']
																	)
																{
																	$selected='selected="selected"';
																}

															?>
															<option <?php echo $selected; ?> value="<?php echo $value['v_make_id'] ?>"><?php echo $value['v_make_name'] ?></option>
															<?php
															}
															?>												
														</select>
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
					
					
					
              <table id="mytable" class="table table-bordred table-striped">
                   
                   <thead>
					   <th>#</th>
					   <th>Make Name</th>					   				   
					   <th>Model Name List</th>						  				   				   
                   </thead>
					<tbody>  
						<?php
				if(count($listofVehicleModels)>0)
				{
						
						$sno=1;
						if(isset($_GET['offset']) && (int)$_GET['offset']>0)
						{
							$sno=(((int)$_GET['offset']-1)*LIST_PAGE_LIMIT)+1;
						}
						foreach($MakeList as $key=>$value)
						{							
						?>
						<tr>
						<td><?php echo $sno; ?></td>
						<td><?php echo $value['name']; ?></td>
						<td>
							<?php
								$make=array();							
								if(isset($value['list']))
								{
									foreach ($value['list'] as $lkey => $lvalue) {

											$break='';
											
 if(check_permission($user_type,'vehicle_model_edit')){ 
										     	$href=base_url()."admin/edit_vehicle_model/".$lvalue['ve_model_id'];
										   		$make[]='<a href="'.$href.'">'.$lvalue['ve_model_name'].'</a>'.$break;
  }else{ 
										     	$make[]='<b>'.$lvalue['ve_model_name'].$break.'</b>';	
 } 
									}
								}								
								echo implode(",",$make);
							?>
						</td>
						<td></td>
						
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
				$('#pageformat1').pagination({
					items: '<?php echo $totalNoOfModelList; ?>',
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
				$('#pageformat1').pagination('selectPage', '<?php echo $_GET['offset']; ?>');
				<?php
				}
				?>
		});
	</script>
</body>
</html>