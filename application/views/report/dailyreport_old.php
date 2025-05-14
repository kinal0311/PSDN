<?php $this->load->view('common/admin_login_header'); ?>
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
            <div class="block-header" style="display: none;">
                <h2</h2>
            </div>
            <!-- Input -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Daily Report
							<?php
							
								$user_type=$this->session->userdata('user_type');
								$user_id=$this->session->userdata('user_id');
								$redirectionBase='admin';
								if((string)$user_type==='1')
								{
									$redirectionBase='dealer';
								}
								$dealerNone="";
								if((string)$user_type==='1' || (string)$user_type==='2' )
								{
									$dealerNone="display:none;";
								}
							?>
                            </h2>
                        </div>
                        <div class="body">
                           	<div class="table-responsive">

								<table id="mytable" class="table table-bordred table-striped"
                                    style="width: 2800px !important;">

                                    <thead>
                                        <th>#</th>
                                        <th>Vehicle</th>
                                        <th>Registration</th>
                                        <th>IMEI</th>
                                        <th>Area</th>
                                        <th style="width: 242px;">Date</th>
                                        <th style="width: 242px;">First Ignition On</th>
										<th style="width: 242px;">Last Ignition Off</th>
										<th>Starting Address</th>
                                        <th>Last Address</th>
                                        <th>Distance</th>
                                        <th>Engine Utilisation</th>
                                        <th>Stop Count</th>
                                        <th>Stop Time</th>
                                        <th>Movement Time</th>
                                        <th>Idling Time</th>
                                        <th>Avg Speed</th>
                                        <th>Max Speed</th>
                                        <th>Start Odometer</th>
                                        <th>End Odometer</th>

                                        <?php if (check_permission($user_type, 'cerificate_edit')) { ?>
                                        <th>
                                            <center>Action</center>
                                        </th>
                                        <?php } ?>
                                    </thead>


                                </table>
							</div>
							<iframe  id="iframe" scrolling="yes" style="display:none;overflow:scroll;width: 100%;
    height: 100%;"   src=""></iframe>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

	<script type="text/javascript">
		var salesReport=1;
	</script>
	 <script src="<?php echo base_url() ?>public/js/pages/function/inventoryreport.js"></script>
	<?php $this->load->view('common/admin_login_css_js'); ?> 		


</body>
</html>