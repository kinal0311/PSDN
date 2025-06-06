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



<body class="<?php echo THEME_BODY_HEADER_COLOR; ?>">

    <?php $this->load->view('common/top_search_bar'); ?>  

	 <?php $this->load->view('common/dashboard_top_bar'); ?> 

  <?php $this->load->view('common/left_side_bar'); ?>





    <section class="content">

        <div class="container-fluid">

            <div class="block-header" style="display:none;">

                <h2>

                    Create New Company

                </h2>

            </div>

            <!-- Basic Validation -->

            <div class="row clearfix">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="card">

                        <div class="header">

                            <h2>Check Device Status</h2>                           

                        </div>

                        <div class="body">

                            <form id="search_form_validation" method="POST" enctype="multipart/form-data">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="imei_no" id="imei_no" minlength="12" required>
                                        <label class="form-label">Enter IMEI Number</label>
                                    </div>
                                </div>
    							<div class="form-group form-float">
                                    <button class="btn btn-primary waves-effect" id="check_imei_btn" type="button">SUBMIT</button>
    							</div>
                            </form>
							<div id="resultOfVechStatus" style="display: none;">
							<br></br>
								<table id="mytable" class="table table-bordred table-striped" >
									<thead>
									<th>Vehicle Registration #</th>			
									<th>Customer ID</th>					   				   
									<th>Vendor Name</th>			   				   
									<th>IMEI</th>	
									<th>SIM Number</th>	
									<th>Ignition Status</th>				   				   
									</thead>
									<tbody> 
									
									</tbody> 
							</table>
							</div>
							
							<div id="resultOfVechStatusEmpty" style="display: none;">
							
							</div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- #END# Basic Validation -->

        

        </div>

    </section>

	

	<!--- Model Dialog --->

	<!-- Default Size -->

            <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">

                <div class="modal-dialog" role="document">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h4 class="modal-title" id="defaultModalLabel">Modal title</h4>

                        </div>

                        <div class="modal-body" id="defaultModalBody">

                           

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">OK</button>

                        </div>

                    </div>

                </div>

            </div>

	<!--- Model Dialog ---->

	

	 <script src="<?php echo base_url() ?>public/js/pages/function/add_vehicle_serial_number.js?t=<?php echo time(); ?>"></script>

	<?php $this->load->view('common/admin_login_css_js'); ?> 		

</body>

</html>