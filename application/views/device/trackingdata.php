<!-- application/views/search_view.php -->

<?php  
 $this->load->view('common/admin_login_header'); ?>
 <?php
 $user_type=$this->session->userdata('user_type');

 ?>
 <!-- Bootstrap Material Datetime Picker Css -->
 <link href="<?php echo base_url() ?>public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

<!-- Include SweetAlert 2 CSS and JS files -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.7/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.7/dist/sweetalert2.all.min.js"></script>

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
                    Registered Device Data
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               List of upcoming Registered data
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
						
						
                             <!--<div class="table-responsive">-->
					             <!--- Search---->
								<form method="post"action="<?php echo base_url() ?>device/search" name="searchfilter"  id="searchfilter" >				
										<input type="hidden" name="offset"   id="offset" value="<?php echo isset($_GET['offset'])?$_GET['offset']:0; ?>">				
										<div class="row clearfix">
											<div class="col-sm-4">
												<div class="form-group">
													<div class="form-line">
														<input type="text" name="imei" class="form-control" placeholder="Search by IMEI" id="search" value="<?php echo isset($_GET['search'])?$_GET['search']:""; ?>" >
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

                         <!-- Display search results here -->
                            <?php if (isset($results) && !empty($results)): ?>
                                <div>
                                    <h2>Search Results</h2>
                                    <ul>
                                        <?php foreach ($results as $result): ?>
                                            <li><?php echo $result->column_name; ?></li>
                                            <!-- Display other columns as needed -->
                                        <?php endforeach; ?>
                                    </ul>
                                    <?php if (count($results) >= 100): ?>
                                        <script>
                                            // Show a SweetAlert if there are 100 or more results
                                            Swal.fire({
                                                title: 'Alert',
                                                text: 'There are 100 or more results.',
                                                icon: 'warning',
                                            });
                                        </script>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
			<!---- Search ---->
					
					<!--<div style="float:right;" id="pageformat"></div>-->
                     <!--</div>-->
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
				items: '<?php echo $totalNoOfregisteredDatas; ?>',
				itemsOnPage: '<?php echo 10; ?>',
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
</body>
</html>

 
