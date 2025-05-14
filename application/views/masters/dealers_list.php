 <?php $this->load->view('common/admin_login_header'); ?>
 <?php
 $user_type=$this->session->userdata('user_type');
 // echo "<pre>";
// print_r($this->session->userdata()); 
// echo "</pre>"; ?>
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
                    Dealer List
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               List Of Users
                            </h2>   
<?php if(check_permission($user_type,'menu_user_create')){ ?>
                            <!--<ul class="header-dropdown m-r--5">-->
                            <!--    <li class="dropdown">-->
                            <!--        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">-->
                            <!--            <i class="material-icons">more_vert</i>-->
                            <!--        </a>-->
                            <!--        <ul class="dropdown-menu pull-right">-->
                            <!--            <li><a href="<?php echo base_url().'admin/create_new_users'; ?>">Create</a></li>                                        -->
                            <!--        </ul>-->
                            <!--    </li>-->
                            <!--</ul>   -->
<?php } ?>                                                  
                        </div>
                        <div class="body">
						
						
                             <div class="table-responsive">
					<!--- Search---->
								<form action="<?php echo base_url() ?>admin/users_list" name="searchfilter"  id="searchfilter" method="get" />				
										<input type="hidden" name="offset"   id="offset" value="<?php echo isset($_GET['offset'])?$_GET['offset']:0; ?>">				
										<div class="row clearfix">
											<div class="col-sm-4">
												<div class="form-group">
													<div class="form-line">
														<input type="text" class="form-control" placeholder="Search by Name, Phone Number" id="search" value="<?php echo isset($_GET['search'])?$_GET['search']:""; ?>" name="search">
													</div>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="form-group form-float">
													<div class="form-line">
														<select class="form-control show-tick" name="user_type" id="user_type" data-live-search="true">
															<option value="">-- User Type --</option>
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
											
											<div class="col-sm-4">
												<div class="form-group">
													<div class="form-lines">
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
					   <th>Name</th>
					   <th>Phone</th>                     
					   <th>Type</th>
					   <th>Status</th>
<?php if(check_permission($user_type,'user_edit')){ ?>
					   <th>Action</th>                      				
<?php } ?>					   	  
                   </thead>
					<tbody>  
						<?php
				if(count($listofdealers)>0)
				{
						$user_type=$this->session->userdata('user_type');	
						$sno=1;
						if(isset($_GET['offset']) && (int)$_GET['offset']>0)
						{
							$sno=(((int)$_GET['offset']-1)*LIST_PAGE_LIMIT)+1;
						}
						foreach($listofdealers as $key=>$value)
						{			
							$enc_user_Id = base64_encode($value['user_id']);				
							if((string)$value['user_type']==='1')
							{
								$value['user_type']='Dealer';
							}
							if((string)$value['user_type']==='4')
							{
								$value['user_type']='Sub Admin';
							}
							if((string)$value['user_type']==='2')
							{
								$value['user_type']='Distributor';
							}
							if((string)$value['user_type']==='3')
							{
								$value['user_type']='Rto';
							}
							if((string)$value['user_type']==='6')
							{
								$value['user_type']='Technician';
							}
							if((string)$value['user_status']==='1')
							{
								$value['user_statusComments']='Active';
							}else if((string)$value['user_status']==='0')
							{
								$value['user_statusComments']='Inactive';
							}
						?>
						<tr>
						<td><?php echo $sno; ?></td>
						<td style="    width: 30%;"><?php echo $value['user_name']; ?></td>
						<td><?php echo $value['user_phone']; ?></td>
						<td><?php echo $value['user_type']; ?></td>
						<td><?php echo $value['user_statusComments']; ?></td>
<?php if(check_permission($user_type,'user_edit')){ ?>						
						<td class="text-left">
							 <a class='btn btn-info btn-xs' href="<?php echo base_url()."admin/edit_users/?q=".$enc_user_Id; ?>">
								<span class="glyphicon glyphicon-edit"></span> Edit
							 </a>
<?php if(check_permission($user_type,'user_deactivate')){ ?>
							 <a onClick='return changeStatus("<?php echo $value["user_id"]; ?>","<?php echo $value["user_status"]; ?>")' href="javascript:void(0);"  class="btn btn-danger btn-xs" >
								<span class="glyphicon glyphicon-retweet" ></span></a>
<?php } ?>							
							
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
	
	
	 <script src="<?php echo base_url() ?>public/js/pages/function/users_list.js"></script>
	<?php $this->load->view('common/admin_login_css_js'); ?> 			
	<script>	
		$(function() {
				$('#pageformat').pagination({
                        items: '<?php echo $totalNoOfDealers; ?>',
                        itemsOnPage: '<?php echo LIST_PAGE_LIMIT; ?>',
                        cssStyle: 'light-theme',
                        onPageClick: function(no) {
                            var offsetValue = $('#offset').val();
                            // console.log(checkBox)
                            if (offsetValue == no) {
            
                            } else {
                                $('#offset').val(no);
                                $('#searchfiltersubmit').trigger('click');
                            }
                        }
                    });
                    <?php
                        if (isset($_GET['offset']) && (int)$_GET['offset'] > 0) {
                        ?>
                    $('#pageformat').pagination('selectPage', '<?php echo $_GET['offset']; ?>');
                    if ('<?php echo intval($totalNoOfDealers); ?>' < 25) {
                        $('#pageformat').pagination('selectPage', '1');
                    } else {
                        // $('#offset').val(no);
                        $('#pageformat').pagination('selectPage', '<?php echo $_GET['offset']; ?>');
                    }
				<?php
				}
				?>
		});
	</script>
</body>
</html>