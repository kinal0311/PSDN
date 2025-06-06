﻿ <?php $this->load->view('common/admin_login_header'); ?>
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
                            <h2>Reassign Serial Numbers</h2>                           
                        </div>
                        <div class="body">
                            <form id="form_validation" method="POST" enctype="multipart/form-data">
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
                                
                                    <input type="hidden" name="s_serial_id" id="s_serial_id" value="<?php echo $SerialInfo['s_serial_id']; ?>" />

                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="s_serial_number"  value="<?php echo isset($SerialInfo['s_serial_number'])?$SerialInfo['s_serial_number']:""; ?>" id="s_serial_number" required>
                                        <label class="form-label">Serial Numbers</label>
                                    </div>
                                </div>
                                <?php
                                $Distributor='';
                                $Dealer='';
                                if(isset($SerialInfo['s_user_type']) && (string)$SerialInfo['s_user_type']==='2')
                                {
                                    $Distributor='checked';
                                }else if(isset($SerialInfo['s_user_type']) && (string)$SerialInfo['s_user_type']==='1')
                                {
                                    $Dealer='checked';
                                }
                                ?>
                                <div class="form-group">
                                   <label class="form-label">User Type</label>
                                   <input <?php echo $Distributor; ?> type="radio" name="s_user_type" id="s_user_type2" value="2" class="radio-col-deep-purple" required >
                                    <label for="s_user_type2" class="m-l-20">Distributor</label>

                                    <input <?php echo $Dealer; ?> type="radio" name="s_user_type" id="s_user_type1" value="1" class="radio-col-deep-purple" required>
                                    <label for="s_user_type1">Dealer</label>                                   
                                </div>    

                                 <div class="form-group form-float">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="s_user_id"  id="s_user_id" data-live-search="true" required >
                                            <option value='<?php echo isset($SerialInfo["s_user_id"])?$SerialInfo["s_user_id"]:""; ?>'>
                                                <?php echo isset($SerialInfo['user_name'])?$SerialInfo['user_name']:""; ?>
                                            
                                            </option>
                                        </select>                                       
                                     </div>
                                </div>   

                                <div class="form-group form-float" style="display:<?php echo ($Distributor == 'checked')?'block':'none'; ?>" id="distributor_price_div">
                                    <div class="form-line">
                                        <input type="number" class="form-control" name="distributor_price" id="distributor_price" value="<?php echo $SerialInfo['distributor_price']; ?>" min="<?php echo $SerialInfo['admin_price']; ?>" required>
                                        <label class="form-label">Distributor Price</label>
                                    </div>
                                </div>

                                <div class="form-group form-float" style="display:<?php echo ($Dealer == 'checked')?'block':'none'; ?>" id="dealer_price_div">
                                    <div class="form-line">
                                        <input type="number" class="form-control" name="dealer_price" id="dealer_price"  value="<?php echo $SerialInfo['dealer_price']; ?>" min="<?php echo $SerialInfo['distributor_price']; ?>"required>
                                        <label class="form-label">Dealer Price</label>
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
	
	 <script src="<?php echo base_url() ?>public/js/pages/function/edit_vehicle_serial_number.js?t=<?php echo time(); ?>"></script>
	<?php $this->load->view('common/admin_login_css_js'); ?> 		
</body>
</html>