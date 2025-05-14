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
                    RTO List
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               List Of RTO
                            </h2>   
<?php if(check_permission($user_type,'menu_rto_create')){ ?>                            
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="<?php echo base_url().'admin/create_rto'; ?>">Create</a></li>                                        
                                    </ul>
                                </li>
                            </ul>    
<?php } ?>                                                 
                        </div>
                        <div class="body">
						
						
                             <div class="table-responsive">
					<!--- Search---->
								<form action="<?php echo base_url() ?>admin/rto_list" name="searchfilter"  id="searchfilter" method="get" />				
										<input type="hidden" name="offset"   id="offset" value="<?php echo isset($_GET['offset'])?$_GET['offset']:0; ?>">				
										<div class="row clearfix">
											<div class="col-sm-4">
												<div class="form-group">
													<div class="form-line">
														<input type="text" class="form-control" placeholder="Search by rto,place name." id="search" value="<?php echo isset($_GET['search'])?$_GET['search']:""; ?>" name="search">
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
					
					
					
              <table id="mytable" class="table table-bordred table-striped" style="width: 50% !important;">
                   
                   <thead>
					   <th>#</th>
					   <th>RTO Number</th>					   				   
					   <th>RTO Place</th>					   				
<?php if(check_permission($user_type,'rto_edit')){ ?>					      
					   <th>Action</th>                      				
<?php } ?>					   	  
                   </thead>
					<tbody>  
						<?php
				if(count($listofRTONumbers)>0)
				{
						
						$sno=1;
						if(isset($_GET['offset']) && (int)$_GET['offset']>0)
						{
							$sno=(((int)$_GET['offset']-1)*LIST_PAGE_LIMIT)+1;
						}
						foreach($listofRTONumbers as $key=>$value)
						{							
						?>
						<tr>
						<td><?php echo $sno; ?></td>
						<td><?php echo $value['rto_number']; ?></td>
						<td><?php echo $value['rto_place']; ?></td>
<?php if(check_permission($user_type,'rto_edit')){ ?>						
						<td class="text-left">
							 <a class='btn btn-info btn-xs' href="<?php echo base_url()."admin/edit_rto_number/".$value['rto_no']; ?>">
								<span class="glyphicon glyphicon-edit"></span> Edit
							 </a>							
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
	
	<!--- Model Dialog ---->
	
	
	<?php $this->load->view('common/admin_login_css_js'); ?> 			
	<script>	
		$(function() {
				$('#pageformat').pagination({
					items: '<?php echo $totalNoOfRTO; ?>',
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