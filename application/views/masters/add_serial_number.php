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

                            <h2>Stock Inward</h2>

                        </div>

                        <div class="body">

                            <form id="form_validation" method="POST" enctype="multipart/form-data">

                                <div class="form-group form-float">

                                    <div class="form-lines">
										<div class="form-label">Company Name: <label>PSDN Technology Pvt Ltd</label></div>
                                    <input value="2" type="hidden" class="form-control show-tick" name="s_company_id"  id="s_company_id" data-live-search="true" required>
                                        <?php /* <select class="form-control show-tick" name="s_company_id"  id="s_company_id" data-live-search="true" required>

                                            <option value="">--Select Company Name--</option>

                                            <?php

                                            foreach ($company_list as $key => $value) {

                                            ?>

                                            <option value="<?php echo $value['c_company_id'] ?>"><?php echo $value['c_company_name'] ?></option>

                                            <?php

                                            }

                                            ?>

                                        </select> */ ?>                                      

                                     </div>

                                </div>



                                <div class="form-group form-float">

                                    <div class="form-line">

                                        <select class="form-control show-tick" name="s_product_id"  id="s_product_id" data-live-search="true" required>

                                            <option value="">--Select Product Name--</option>

                                            <?php

                                            foreach ($product_list as $key => $value) {

                                            ?>

                                            <option value="<?php echo $value['p_product_id'] ?>"><?php echo $value['p_product_name'] ?></option>

                                            <?php

                                            }

                                            ?>

                                        </select>                                       

                                     </div>

                                </div>

                               
                                <div class="form-group form-float">
                                     <div class="form-line">
                                         <select class="form-control show-tick" name="s_country_id" id="s_country_id"
                                             data-live-search="true">
                                             <?php 
											foreach($countryList as $key=>$value)
											{ ?>
                                             <option value="<?php echo $value['c_id']; ?>">
                                                 <?php echo $value['c_name']; ?>
                                             </option>
                                             <?php 
											}
											?>
                                         </select>
                                     </div>
                                 </div>

                                 <div class="form-group form-float">
                                     <div class="form-line">
                                         <select class="form-control show-tick" name="s_state_id" id="s_state_id"
                                             data-live-search="true" required>
                                             <option value="">--Select State-- *</option>
                                         </select>
                                     </div>
                                 </div>

                                 <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="number" class="form-control" name="admin_price" min="0" step="1" required>
                                        <label class="form-label">Admin Price</label>
                                    </div>
                                </div>

                                <div class="form-group">

                                    <input type="radio" name="mode" id="list" value="list" class="with-gap" checked >

                                    <label for="list">Product List</label>



                                    <input type="radio" name="mode" id="upload" value="upload" class="with-gap">

                                    <label for="upload" class="m-l-20">CSV Upload </label>&nbsp;&nbsp;<a href="<?php echo AWS_S3_BUCKET_URL . 'public/sample-serial-number-upload.csv' ?>">Download Sample CSV File</a>

                                </div>

                                

                                <div class="form-group form-float" id="mode_list">

                                    <div class="form-line">

                                        <textarea name="s_serial_number" cols="30" rows="5" id="s_serial_number" class="form-control no-resize" required ></textarea>

                                        <label class="form-label">Serial Numbers</label>

                                    </div>

                                    <small class="form-text text-muted"><b style="color:#1F91F3">Ex.(UIN-IMEI-Mobile)</b> PSDNXXXX-IMEI456123801-9876543210, PSDNXXXX-IMEI456123802-9876543211</small>                                    

                                </div>


                                

                                <div class="form-group form-float" id="mode_upload" style="display: none;">

                                    <div class="form-line">

                                        <input type="file" name="file" id="csv_upload" class="form-control" required />

                                    </div>

                                </div>



    							<div class="form-group form-float">

                                    <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>

    							</div>

                            </form>

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
<script>
    $(document).ready(function () {
        $('[name=s_country_id]').on('change', function () {
		var value = $(this).val();
		if (value === '') {
			return true;
		}
		console.log("data", value);
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
    
    $(function () {
        $.post(SITEURL + "admin/getStateByCountryById", { 'id': "1" }, function (data) {
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
    </script>

</html>