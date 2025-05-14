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
 
 <link href="<?php echo base_url() ?>public/plugins/DataTables/media/css/dataTables.bootstrap.css" rel="stylesheet" />

<body class="<?php echo THEME_BODY_HEADER_COLOR; ?>">
    <?php $this->load->view('common/top_search_bar'); ?>  
	 <?php $this->load->view('common/dashboard_top_bar'); ?> 
  <?php $this->load->view('common/left_side_bar'); ?>


    <section class="content">
        <div class="container-fluid">
            <div class="block-header" style="display:none;">
                <h2>
                    Create New Entry
                </h2>
            </div>
            <!-- Basic Validation -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>PROFORMA INVOICE LIST</h2>                           
                        </div>
                        <div class="body">
							
							
							
											<table class="table table-striped table-hover js-exportable dataTable">
											<thead>
												<tr>
													<th>No#</th>
													<th>Create From</th>
													<th>Create To</th>
													<th>Due Date</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
											<?php											
											foreach($getallentries_datas as $row) {
											?>
											<tr>
											<td><?php echo $row->id;?></td>
											<td><?php echo $row->user_own_company;?></td>
											<td><?php echo $row->company_name;?></td>
											<td><?php echo $row->duedate;?></td>
											<td><?php 
											if($row->statuscheck>=1){ 
												echo 'Expired'; 
											} else { 
												$stchk = (-$row->statuscheck);
												echo "Expire in ".$stchk." Days"; 
											}
											?>
											</td>
											<td><a href=""><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;<a href="<?php echo base_url()."invoice/".$row->invoice_file;?>" target="_blank"><span class="glyphicon glyphicon-save"></span></a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="javascript:modalclickfunc(<?php echo $row->id;?>);"><span class="glyphicon glyphicon-envelope"></a></td>
											</tr>
											<?php
											}
											?>
											</tbody>
											</table>
							
							
							
							<input type="hidden" name="hidbaseurl" id="hidbaseurl" value="<?php echo base_url();?>" />
							
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Validation -->
        
        </div>
    </section>
	
	<!--- Model Dialog ---->
	
	<div class="country_info modal " id="State" tabindex="-1"
							role="dialog" aria-labelledby="EmployementTypeLabel">
							<div class="modal-dialog">
							
							<?php echo form_open(base_url('general/addedit_area'), array('id'=>'frmarea','name'=>'frmarea','autocomplete'=>'off')); ?>
								<div class="modal-content" style="margin-top: 10%">
									<div class="modal-header">
										<a type="button" class="close " data-dismiss="modal">
											<span aria-hidden="true">×</span> <span class="sr-only">Close</span>
										</a>
										<h4 class="modal-title" id="EmployementTypeLabel">Send Mail</h4>
									</div>
									<div class="modal-body" style="height: 250px">
											
											<div class="row">
											<div class="alert alert-warning" id="alertmsgdiv1" style="background-color:#277135;">
											<i class="fa fa-check" aria-hidden="true"></i><strong>Success!</strong> Proforma Invoice send mail successfully.
											</div>
											</div>
											
											<div class="row" style="margin-left:10px; margin-right:10px;">
												<div class="form-group form-float">
												<div class="form-line">
													<input type="text" class="form-control" name="txtmodalemail"  id="txtmodalemail" value="">										
													<label class="form-label">Email Id</label>
												</div>
												<span class="help-block errortext" id="txtmodalemailerror"></span>
												</div>
												
												
											</div>
											
											
											
											</div>
										
									<div class="modal-footer clearfix">
										<div class="form-group" style="">
											<button class="btn btn-danger pull-right btn-sm RbtnMargin"
												id="modalresetbtn" type="button">Reset</button>

												<input type="button" name="modalsubmitbtn" id="modalsubmitbtn" class="btn btn-success pull-right btn-sm" value="Send Mail" style="margin-right:5px;">
												
												<input type="hidden" name="modalhidid" id="modalhidid" />
												
										</div>
									</div>
									
									
									
									</form>
								</div>
							</div>
						</div>
	
	 
	<?php  $this->load->view('common/admin_login_css_js'); ?> 
	
	<script src="<?php echo base_url() ?>public/plugins/DataTables/media/js/jquery.dataTables.js"></script>
	<script	src="<?php echo base_url() ?>public/plugins/DataTables/media/js/dataTables.bootstrap.js"></script>
	
    <script	src="<?php echo base_url() ?>public/js/proforma-list.js"></script>	
</body>
</html>