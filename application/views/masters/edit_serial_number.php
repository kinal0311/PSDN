 <?php $this->load->view('common/admin_login_header'); ?>
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
                            <h2>Edit Serial Numbers</h2>                           
                        </div>
                        <div class="body">
                            <form id="form_validation" method="POST" enctype="multipart/form-data">

                                    <input type="hidden" name="s_serial_id" id="s_serial_id" value="<?php echo $SerialInfo['s_serial_id']; ?>" />
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="s_company_id"  id="s_company_id" data-live-search="true" required>
                                            <option value="">--Select Company Name--</option>
                                            <?php
                                            foreach ($company_list as $key => $value) {
                                                $selected="";
                                                if(isset($SerialInfo['s_company_id']) && (string)$SerialInfo['s_company_id']===(string)$value['c_company_id'])
                                                {
                                                    $selected="selected='selected'";
                                                }
                                            ?>
                                            <option <?php echo $selected; ?> value="<?php echo $value['c_company_id'] ?>"><?php echo $value['c_company_name'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>                                       
                                     </div>
                                </div>
                                
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="s_product_id"  id="s_product_id" data-live-search="true" required>
                                            <option value="">--Select Product Name--</option>
                                            <?php
                                            foreach ($product_list as $key => $value) {
                                                $selected="";
                                                if(isset($SerialInfo['s_product_id']) && (string)$SerialInfo['s_product_id']===(string)$value['p_product_id'])
                                                {
                                                    $selected="selected='selected'";
                                                }
                                            ?>
                                            <option  <?php echo $selected; ?> value="<?php echo $value['p_product_id'] ?>"><?php echo $value['p_product_name'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>                                       
                                     </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="number" class="form-control" name="admin_price" min="0" step="1" value="<?php echo $SerialInfo['admin_price'] ?>" required>
                                        <label class="form-label">Admin Price</label>
                                    </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="s_serial_number"  value="<?php echo isset($SerialInfo['s_serial_number'])?$SerialInfo['s_serial_number']:""; ?>" id="s_serial_number" readonly required>
                                        <label class="form-label">Serial Numbers</label>
                                    </div>
                                    
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="s_imei"  value="<?php echo isset($SerialInfo['s_imei'])?$SerialInfo['s_imei']:""; ?>" id="s_imei" readonly required>
                                        <label class="form-label">IMEI</label>
                                    </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="s_mobile"  value="<?php echo isset($SerialInfo['s_mobile'])?$SerialInfo['s_mobile']:""; ?>" id="s_mobile" required>
                                        <label class="form-label">Sim 1</label>
                                    </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="s_mobile_2"  value="<?php echo isset($SerialInfo['s_mobile_2'])?$SerialInfo['s_mobile_2']:""; ?>" id="s_mobile_2">
                                        <label class="form-label">Sim 2</label>
                                    </div>
                                </div>

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="s_iccid"  value="<?php echo isset($SerialInfo['s_iccid'])?$SerialInfo['s_iccid']:""; ?>" id="s_iccid">
                                        <label class="form-label">ICCID</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="s_state_id" id="s_state_id" data-live-search="true">
                                            <option value="">--State--</option>
                                                <?php
                                                foreach ($stateList as $key => $value) {
                                                    $selected = "";
                                                    if (isset($SerialInfo['s_state_id']) && (string)$SerialInfo['s_state_id'] === (string)$value['id']) {
                                                        $selected = 'selected="selected"';
                                                    }
                                                ?>
                                            <option <?php echo $selected; ?> value="<?php echo $value['id']; ?>"><?php echo $value['s_name'];?></option>
                                            </option><?php } ?>
                                        </select>
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
	
	 <script src="<?php echo base_url() ?>public/js/pages/function/edit_unassigned_vehicle_serial_number.js?t=<?php echo time(); ?>"></script>
	<?php $this->load->view('common/admin_login_css_js'); ?> 		
</body>
</html>